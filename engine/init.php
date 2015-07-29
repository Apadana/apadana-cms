<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

if (isset($_REQUEST['GLOBALS']) OR isset($_FILES['GLOBALS']))
{
	exit('Request tainting attempted.');
}

date_default_timezone_set('Asia/Tehran');

if (extension_loaded('mbstring') && function_exists('mb_internal_encoding'))
{
    mb_internal_encoding('UTF-8');
}

if (!file_exists(engine_dir.'config.inc.php') && is_dir(root_dir.'install'))
{
	Header('Location: install/');
    exit('Link Redirect:<br /><br />Please click <a href="install/">here.</a>');
}
elseif (!file_exists(engine_dir.'config.inc.php') && !is_dir(root_dir.'install'))
{
    exit('Could not find the "engine/config.inc.php" file!');
}
elseif (is_dir(root_dir.'install'))
{
    exit('<div align="center" style="color:red">Please delete the <b>install</b> folder!</div>');
}

define('start_time', microtime(true));
define('time_now', time());
define('n', "\n");
define('rtl', true);

# includes require files
require_once(engine_dir.'config.inc.php');
require_once(engine_dir.'version.inc.php');
require_once(engine_dir.'functions.php');
require_once(engine_dir.'error.inc.php');
require_once(engine_dir.'hook.function.php');
require_once(engine_dir.'cache.function.php');
require_once(engine_dir.'filter.function.php');
require_once(engine_dir.'jalali-date.function.php');
require_once(engine_dir.'banned.function.php');
require_once(engine_dir.'mod-rewrite.function.php');
require_once(engine_dir.'antiflood.function.php');
require_once(engine_dir.'module.function.php');
require_once(engine_dir.'mbstring.function.php');
require_once(engine_dir.'template.function.php');
require_once(engine_dir.'database.class.php');
require_once(engine_dir.'member.class.php');
require_once(engine_dir.'template.class.php');
require_once(engine_dir.'html.class.php');

error_reporting( debug_system ? E_ALL : 0);

# Determine Magic Quotes Status (< PHP 5.4)
if (version_compare(PHP_VERSION, '5.4', '<'))
{
	if (get_magic_quotes_gpc())
	{
		define('magic_quotes', true);
		strip_slashes_array($_POST);
		strip_slashes_array($_GET);
		strip_slashes_array($_COOKIE);
	}
	if (version_compare(PHP_VERSION, '5.3.0', '<'))
	{
		set_magic_quotes_runtime(0);
	}

	ini_set('magic_quotes_gpc', 0);
	ini_set('magic_quotes_runtime', 0);
	un_register_globals();
}
defined('magic_quotes') or define('magic_quotes', false);

check_xss();

$d = new database;
$result = $d->connect(array(
    'host' => database_host,
    'user' => database_user,
    'password' => database_password,
    'name' => database_name,
    'prefix' => database_prefix,
    'charset' => database_charset,
));

if(! $result)
	exit('Can\'t connect to database!!');

unset($result);

if (!$options = get_cache('options'))
{
	$options = array();
	$rows = $d->get_row("SELECT * FROM `#__options` WHERE `autoload`='1'");
	foreach ($rows as $row)
	{
		$options[$row['option_name']] = $row['option_value'];
	};
	unset($rows, $row);
	set_cache('options', $options);
}

//its better to understand if it is in admin page as soon as possible!!! :-)
if (isset($_GET['admin']) && $_GET['admin'] == $options['admin'] && $options['admin'] != '')
{
	define('admin_page', url.'?admin='.$options['admin']);
}
else
{
	define('admin_page', false);
}

/**
* Change the theme
*
* Allow user to choose the preferd theme
* we should save the name of unchanged theme. maybe we need it later specially in admin panel.
*
* @since 1.1
*/

$options['original_theme'] = $options['theme'];


if( !admin_page && $options['allow-change-theme']){

	if( isset($_GET['theme'])  &&  template_exists($_GET['theme'] ) )
	{
		$options['theme'] = $_GET['theme'];
		set_cookie('theme', $_GET['theme'] );
	}
	elseif ( isset($_COOKIE['theme'])  &&  template_exists($_COOKIE['theme']) ) 
	{
		$options['theme'] = $_COOKIE['theme'];
	}
}

define('template_dir', root_dir.'templates/'.$options['theme'].'/');

antiflood();
check_banned();

if (!is_readable(root_dir.'.htaccess'))
{
	$options['rewrite'] = 0;
}
	
mod_rewrite();

defined('mod_rewrite') or define('mod_rewrite', false);

# safe $_REQUEST
$_REQUEST = array_merge($_GET, $_POST);

if (!$modules = get_cache('modules'))
{
    $modules = $d->get_row("SELECT * FROM `#__modules`", 'assoc', 'module_name');
	set_cache('modules', $modules);
}

if (!$member_groups = get_cache('member-groups'))
{
    $member_groups = $d->get_row("SELECT * FROM `#__member_groups` ORDER BY `group_id` ASC", 'assoc', 'group_id');
	set_cache('member-groups', $member_groups);
}

$member = member::is('info');
if ($member && is_array($member) && count($member) && isset($member['member_id']) && isset($member['member_group']) && intval($member['member_id'])>0 && intval($member['member_group'])>0)
{
    define('member', 1);
    define('member_id', intval($member['member_id']));
    define('member_name', $member['member_name']);
    define('member_group', intval($member['member_group']));
}
else
{
    define('member', 0);
    define('member_id', 0);
    define('member_name', 'GUEST');
    define('member_group', 5);
	$member = 0;
}

if (is_array($member_groups[member_group]) && count($member_groups[member_group]))
{
    define('group_admin', intval($member_groups[member_group]['group_admin']) == 1? 1 : 0);
    define('group_super_admin', intval($member_groups[member_group]['group_superAdmin']) == 1? 1 : 0);
    define('group_rights', nohtml($member_groups[member_group]['group_rights']));
}
else
{
    define('group_admin', 0);
    define('group_super_admin', 0);
    define('group_rights', null);
}

$_GET['a'] = isset($_GET['a'])? $_GET['a'] : null;

$page = array();
$page['theme'] = null;
$page['title'] = empty($options['title'])? 'Apadana Cms v'.version : $options['title'];
$page['canonical'] = null;
$page['head'] = array();
$page['foot'] = array();
$page['links'] = array();
$page['meta'] = array();
/**
* The Default page vars That Should be included
*
* You can remove them by $page variabe
*
* @see set_script()
* @see set_link()
* @see set_meta()
*
* @since 1.1
*/
$page['scripts'] = array(
	'jquery' => '<script type="text/javascript" src="'.url.'engine/javascript/jquery.js"></script>',
	'core' => '<script type="text/javascript" src="'.url.'engine/javascript/core.js"></script>'
	);

if( ! admin_page ){
$page['links'] = array(
	'home' => '<link rel="start" href="'.url.'" title="Home" />',
	'sitemap' => '<link rel="sitemap" href="'.url.($options['rewrite'] == 1? 'sitemap.xml' : '?a=sitemap').'" />',
	'search' => '<link rel="search" type="application/opensearchdescription+xml" href="'.url('search/opensearch').'" title="'.$options['title'].'" />',
	'rss' => '<link rel="alternate" type="application/rss+xml" href="'.url('feed/posts/rss').'" title="'.$options['title'].'" />'
	);

$page['meta'] = array(
	'author' => $options['title'] ,
	'description' => $options['meta-desc'] ,
	'keywords' =>  $options['meta-keys'] ,
	'distribution' => 'global',
	'robots' => 'index, follow',
	'revisit-after' => '1 days',
	'rating' => 'general'
	);
}
$cache = array();
$hooks = array();

if ( admin_page )
{
	$tpl = new template('body.tpl', root_dir.'engine/admin/template/');
}
else
{
	if ($options['offline'] == 1 && group_admin != 1)
	{
		if (!$options['offline-message'] = get_cache('options-offline-message'))
		{
			$options['offline-message'] = $d->get_row("SELECT * FROM `#__options` WHERE `option_name`='offline-message'");
			$options['offline-message'] = $options['offline-message'][0]['option_value'];
			set_cache('options-offline-message', $options['offline-message'], 0);
		}
	
		if (is_readable(engine_dir.'templates/offline.tpl'))
		{
			@header('Content-type: text/html; charset='.charset);
			$tpl = new template('offline.tpl', template_dir);
			$tpl->assign(array(
				'{message}' => $options['offline-message'],
			));
			$tpl->display();
			gzip_out();
		}
		else
		{
			warning('سایت غیرفعال است', $options['offline-message']);
		}
	}
	
	if (!template_exists($options['theme']))
	{
		warning('خطا در تم سایت', 'متاسفانه تم سایت ناقص می باشد!');
	}

	$tpl = new template('body.tpl', root_dir.'templates/'.$options['theme'].'/');
}

$tpl->assign(array(
	'{copyright}' => copyright(),
));

foreach ($modules as $___mod)
{
	if ($___mod['module_status'] == 0 || ($___mod['module_status'] == 2 && !admin_page && $_GET['a'] != $___mod['module_name']))
	{
		continue;
	}

	if (file_exists(root_dir.'modules/'.$___mod['module_name'].'/config.php'))
	{
		require_once(root_dir.'modules/'.$___mod['module_name'].'/config.php');
	}
}
unset($___mod);

if (file_exists(engine_dir.'custom-file.php') && is_readable(engine_dir.'custom-file.php'))
{
	require_once(engine_dir.'custom-file.php');
}

if (file_exists(root_dir.'templates/'.$options['theme'].'/functions.php'))
{
	require_once(root_dir.'templates/'.$options['theme'].'/functions.php');
}

($hook = get_hook('init'))? eval($hook) : null;
