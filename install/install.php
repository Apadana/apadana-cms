<?php
/**
 * @In the name of God!
 * @author:Apadana Development Team
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or header('location: index.php');
date_default_timezone_set('Asia/Tehran');

if (extension_loaded('mbstring') && function_exists('mb_internal_encoding'))
{
    mb_internal_encoding('UTF-8');
}

# Determine Magic Quotes Status (< PHP 5.4)
if (version_compare(PHP_VERSION, '5.4', '<'))
{
	if (@get_magic_quotes_gpc())
	{
		strip_slashes_array($_POST);
		strip_slashes_array($_GET);
		strip_slashes_array($_COOKIE);
	}
	@set_magic_quotes_runtime(0);
	@ini_set('magic_quotes_gpc', 0);
	@ini_set('magic_quotes_runtime', 0);
}

$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'? true : false;
$_GET['action'] = !isset($_GET['action']) || empty($_GET['action'])? null : $_GET['action'];

switch ($_GET['action'])
{
	case 'check':

		$itpl = new template( 'check.tpl' , install_dir.'template/install/',false );

		$assign = array();

		$assign['{phpversion}'] = (phpversion() >= '5.3'? '<b><font color="green">بله</font></b>' : '<b><font color="red">خیر</font></b>') ;
		$assign['{zlib}'] = (extension_loaded('zlib')? '<b><font color="green">بله</font></b>' : '<b><font color="red">خیر</font></b>');
		$assign['{gd}'] = (extension_loaded('gd')? '<b><font color="green">بله</font></b>' : '<b><font color="red">خیر</font></b>');
		$assign['{mysql}'] = (function_exists('mysqli_connect') || function_exists('mysql_connect')? '<b><font color="green">بله</font></b>' : '<b><font color="red">خیر</font></b>');
		$assign['{safemode}'] = (ini_get('safe_mode') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>');
		$assign['{display_errors}'] = (ini_get('display_errors') == '1'? '<b><font color="red">فعال</font></b>' : '<b><font color="green">غیرفعال</font></b>');
		$assign['{file_uploads}'] = (ini_get('file_uploads') == '1'? '<b><font color="green">فعال</font></b>' : '<b><font color="red">غیرفعال</font></b>');
		$assign['{magic_quotes_gpc}'] = (ini_get('magic_quotes_gpc') == '1'? '<b><font color="red">فعال</font></b>' : '<b><font color="green">غیرفعال</font></b>');
		$assign['{magic_quotes_runtime}'] = (ini_get('magic_quotes_runtime') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>');
		$assign['{register_globals}'] = (ini_get('register_globals') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>');
		$assign['{output_buffering}'] = (ini_get('output_buffering') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>');
		$assign['{session}'] = (ini_get('session.auto_start') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>');
		
		$assign['{config}'] = (file_exists('../engine/config.inc.php')?(is_writable('../engine/config.inc.php')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>'):(is_writable('../engine')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>'));
		$assign['{cache}'] = (is_writable('../engine/cache')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>');
		$assign['{backup}'] = (is_writable('../engine/admin/backups')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>');
		$assign['{upload}'] = (is_writable('../uploads')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>');
		$assign['{hta}'] = (is_writable('../.htaccess')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>');
		$assign['{robots}'] = (is_writable('../robots.txt')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>');
	
		$itpl->assign($assign);

	break;
	
	case 'config':

		$itpl = new template( 'config.tpl' , install_dir.'template/install/',false );

		$itpl->assign(array(
			'{prefix}' => strtolower(generate_password(5, null)).'_',
			'{domain}' => str_replace('www.', '', $_SERVER['HTTP_HOST']),
			'{path}' => my_path(),
			'{url}' => 'http://'.trim($_SERVER['HTTP_HOST'].my_path(), '/').'/',
		));
		
	break;
	
	case 'config-check':
	$_POST['config'] = array_map('trim', $_POST['config']);
	$result = 'error';
	
	if(function_exists('mysqli_connect')){
		$connect = @mysqli_connect($_POST['config']['host'], $_POST['config']['user'], $_POST['config']['password']);
		define('mysqli', true);
	}
	else{
		$connect = @mysql_connect($_POST['config']['host'], $_POST['config']['user'], $_POST['config']['password'],true);
		define('mysqli', false);
	}

	if ($connect)
	{

		if ( (mysqli && @mysqli_select_db( $connect , $_POST['config']['name'])) || @mysql_select_db($_POST['config']['name'], $connect))
		{
			if (!preg_check("/^[0-9a-z_]{2,10}$/i", $_POST['config']['prefix']))
			{
				$result = 'prefix';
			}
			else
			{
				$result = 'success';
			}
		}
		else
		{
			$result = 'select';
		}
	}
	else
	{
		$result = 'connect';
	}
	
	exit('{"result":"'.$result.'"}');
	break;
	
	case 'create-config':

	$itpl = new template( 'create_config.tpl' , install_dir.'template/install/',false );

	@$_POST['config'] = array_map('trim', $_POST['config']);
	
	if(function_exists('mysqli_connect')){
		$connect = @mysqli_connect($_POST['config']['host'], $_POST['config']['user'], $_POST['config']['password']);
		define('mysqli', true);
	}
	else{
		$connect = @mysqli_connect($_POST['config']['host'], $_POST['config']['user'], $_POST['config']['password'],true);
		define('mysqli', false);
	}

	if ($connect)
	{
		if ( (mysqli && @mysqli_select_db( $connect , $_POST['config']['name'])) || @mysql_select_db($_POST['config']['name'], $connect))
		{
			if (preg_check("/^[0-9a-z_]{2,10}$/i", $_POST['config']['prefix']))
			{
				$connect = 'success';
			}
		}
	}

	if ($connect == 'success')
	{
		$_POST['config'] = array_map(create_function('$a', 'return str_replace("\'", "\\\'", $a);'), $_POST['config']);
		
		$php  = '<?php'."\r\n";
		$php .= '/**'."\r\n";
		$php .= ' * @In the name of God!'."\r\n";
		$php .= ' * @author:Apadana Development Team'."\r\n";
		$php .= ' * @email: info@apadanacms.ir'."\r\n";
		$php .= ' * @link: http://www.apadanacms.ir'."\r\n";
		$php .= ' * @license: http://www.gnu.org/licenses/'."\r\n";
		$php .= ' * @copyright: Copyright © 2012-'.date('Y').' ApadanaCms.ir. All rights reserved.'."\r\n";
		$php .= ' * @Apadana CMS is a Free Software'."\r\n";
		$php .= ' */'."\r\n\r\n";
		$php .= 'defined(\'security\') or exit(\'Nice TRY!\');'."\r\n\r\n";
		$php .= 'define(\'database_host\', \''.$_POST['config']['host'].'\');'."\r\n";
		$php .= 'define(\'database_user\', \''.$_POST['config']['user'].'\');'."\r\n";
		$php .= 'define(\'database_password\', \''.$_POST['config']['password'].'\');'."\r\n";
		$php .= 'define(\'database_name\', \''.$_POST['config']['name'].'\');'."\r\n";
		$php .= 'define(\'database_prefix\', \''.$_POST['config']['prefix'].'\');'."\r\n";
		$php .= 'define(\'database_charset\', \'utf8\');'."\r\n";
		$php .= 'define(\'database_save_queries\', false);'."\r\n\r\n";
		$php .= 'define(\'debug_system\', false);'."\r\n";
		$php .= 'define(\'show_debug_backtrace\', false);'."\r\n";
		$php .= 'define(\'charset\', \'utf-8\');'."\r\n";
		$php .= 'define(\'sitekey\', \''.generate_password(40).'\');'."\r\n";
		$php .= 'define(\'domain\', \''.$_POST['config']['domain'].'\');'."\r\n";
		$php .= 'define(\'path\', \''.$_POST['config']['path'].'\');'."\r\n";
		$php .= 'define(\'url\', \''.rtrim($_POST['config']['url'], '/').'/\');'."\r\n\r\n";
		$php .= '?>';

		if (file_put_contents('../engine/config.inc.php', $php))
		{
			$ht  = 'DirectoryIndex index.php index.html index.htm'."\n\n";
			$ht .= 'ErrorDocument 400 '.$_POST['config']['path'].'index.php?a=error&b=400'."\n";
			$ht .= 'ErrorDocument 403 '.$_POST['config']['path'].'index.php?a=error&b=403'."\n";
			$ht .= 'ErrorDocument 404 '.$_POST['config']['path'].'index.php?a=error&b=404'."\n";
			$ht .= 'ErrorDocument 500 '.$_POST['config']['path'].'index.php?a=error&b=500'."\n";
			$ht .= 'ErrorDocument 503 '.$_POST['config']['path'].'index.php?a=error&b=503'."\n\n";
			$ht .= '<IfModule mod_rewrite.c>'."\n";
			$ht .= '	RewriteEngine On'."\n";
			$ht .= '	RewriteBase '.$_POST['config']['path'].''."\n";
			$ht .= '	RewriteRule ^index\.php$ - [L]'."\n";
			$ht .= '	RewriteCond %{REQUEST_FILENAME} !-f'."\n";
			$ht .= '	RewriteCond %{REQUEST_FILENAME} !-d'."\n";
			$ht .= '	RewriteRule . '.$_POST['config']['path'].'index.php [L]'."\n";
			$ht .= '</IfModule>'."\n\n";
			$ht .= '# Restrict access to files'."\n";
			$ht .= '<FilesMatch "\.(inc|sql|back|cache|tpl|log|ihtml|class|module|bin|ini|conf|h|spd)$">'."\n";
			$ht .= '	deny from all'."\n";
			$ht .= '</FilesMatch>';
			file_put_contents('../.htaccess', $ht);

			require_once('../engine/config.inc.php');

			$itpl->assign(array(
				'{database_host}' => database_host,
				'{database_user}' => database_user,
				'{database_password}' => database_password == ''? 'بدون پسورد' : database_password,
				'{database_name}' => database_name,
				'{domain}' => domain,
				'{path}' => path == '/'? 'روت سایت' : path,
				'{url}' => url
				));
			$itpl->assign(array(
				'[success]' => null,
				'[/success]' => null
				));
			$itpl->block('#\\[write\\](.*?)\\[/write\\]#s', '');
			$itpl->block('#\\[connect\\](.*?)\\[/connect\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[write]' => null,
				'[/write]' => null
				));
			$itpl->block('#\\[success\\](.*?)\\[/success\\]#s', '');
			$itpl->block('#\\[connect\\](.*?)\\[/connect\\]#s', '');
		}
	}
	else
	{
		$itpl->assign(array(
			'[connect]' => null,
			'[/connect]' => null
			));
		$itpl->block('#\\[write\\](.*?)\\[/write\\]#s', '');
		$itpl->block('#\\[success\\](.*?)\\[/success\\]#s', '');
	}

	break;
	
	case 'db-insert':

	$itpl = new template( 'db_insert.tpl' , install_dir.'template/install/',false );

	require_once('../engine/config.inc.php');

	@set_time_limit(1200);
	require_once('../engine/database.class.php');
	$d = new database;
	$result = $d->connect(array(
		'host' => database_host,
		'user' => database_user,
		'password' => database_password,
		'name' => database_name,
		'prefix' => database_prefix,
		'charset' => database_charset,
	));
	if($result){
		require_once('sql/install.php');

		$itpl->assign(array(
			'[success]' => null,
			'[/success]' => null
			));
		$itpl->block('#\\[write\\](.*?)\\[/write\\]#s', '');
	}	
	else
	{
		$itpl->assign(array(
			'[write]' => null,
			'[/write]' => null
			));
		$itpl->block('#\\[success\\](.*?)\\[/success\\]#s', '');
	}

	break;

	case 'admin':

		$itpl = new template( 'admin.tpl' , install_dir.'template/install/',false );

	break;
	
	case 'admin-check':
	$_POST['admin'] = array_map('trim', $_POST['admin']);
	$_POST['admin']['email'] = strip_tags($_POST['admin']['email']);
	$_POST['admin']['pass1'] = ($_POST['admin']['pass1']);
	$_POST['admin']['pass2'] = ($_POST['admin']['pass2']);
	$result = 'error';
	
	if (!preg_check("/^[0-9a-z-_]{4,40}$/i", $_POST['admin']['name']))
	{
		$result = 'name';
	}
	else
	{
		if (!validate_email($_POST['admin']['email']))
		{
			$result = 'email';
		}
		else
		{
			if (strlen($_POST['admin']['pass1'])<=5 || $_POST['admin']['pass1']!=$_POST['admin']['pass2'])
			{
				$result = 'pass';
			}
			else
			{
				$result = 'success';
			}
		}
	}
	
	exit('{"result":"'.$result.'"}');
	break;
	
	case 'admin-insert':

	$itpl = new template( 'admin-insert.tpl' , install_dir.'template/install/',false );

	require_once('../engine/config.inc.php');
	require_once('../engine/database.class.php');
	$d = new database;
	$d->connect(array(
		'host' => database_host,
		'user' => database_user,
		'password' => database_password,
		'name' => database_name,
		'prefix' => database_prefix,
		'charset' => database_charset,
	));
	
	@$_POST['admin'] = array_map('trim', $_POST['admin']);
	@$_POST['admin']['email'] = strip_tags($_POST['admin']['email']);
	@$_POST['admin']['pass1'] = ($_POST['admin']['pass1']);
	@$_POST['admin']['pass2'] = ($_POST['admin']['pass2']);
	
	if (@preg_check("/^[0-9a-z-_]{4,25}$/i", $_POST['admin']['name']))
	{
		if (@strlen($_POST['admin']['pass1']) > 5 && $_POST['admin']['pass1'] == $_POST['admin']['pass2'] && validate_email($_POST['admin']['email']))
		{
			$_POST['admin']['pass1'] = str_replace('\\', null, $_POST['admin']['pass1']);
			$d->update('members', array(
				'member_name' => strtolower($_POST['admin']['name']),
				'member_alias' => $_POST['admin']['name'],
				'member_password' => md5(sha1($d->escape_string($_POST['admin']['pass1']))),
				'member_ip' => get_ip(),
				'member_lastip' => get_ip(),
				'member_email' => strtolower($_POST['admin']['email']),
				'member_date' => time(),
				'member_lastvisit' => time(),
			), "member_id=1", 1);

			$d->update('shoutbox', array(
				'shout_member' => $_POST['admin']['name'],
			));

			$result = 'ok';
		}
	}
	
	if (isset($result))
	{
		$MailHeader  = 'MIME-Version: 1.0' . "\r\n";
		$MailHeader .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$MailHeader .= 'From: admin@apadanacms.ir'."\r\n"; // Sender's Email Address
		$MailHeader .= 'Return-Path: admin@apadanacms.ir <admin@apadanacms.ir> /n'; // Indicates Return-path
		$MailHeader .= 'Reply-To: admin@apadanacms.ir <admin@apadanacms.ir> /n'; // Reply-to Address
		$MailHeader .= 'X-Mailer: PHP/' . phpversion(); // For X-Mailer
		$Body = "<div style='direction: rtl;text-align: right;font-family: Tahoma;font-size: 10pt'><b>سایت شما با موفقیت ساخته شد!<br>از انتخاب آپادانا سپاس گذاریم.</b>";
		@mail($_POST['admin']['email'], "یک سایت جدید با آپادانا!", $Body, $MailHeader);

		file_put_contents('apadana.lock', 'Copyright © 2012-'.date('Y').' ApadanaCms.ir. All rights reserved.');

		$itpl->assign(array(
			'[done]' => null,
			'[/done]' => null,
			'{url}' => url
			));
		$itpl->block('#\\[error\\](.*?)\\[/error\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[error]' => null,
			'[/error]' => null
			));
		$itpl->block('#\\[done\\](.*?)\\[/done\\]#s', '');
	}
	
	break;
	
	default:
	
		$itpl = new template( 'default.tpl' , install_dir.'template/install/',false );

	break;
}

function my_path()
{
    if(!isset($_SERVER['REQUEST_URI']))
	{
        $url = $_SERVER['PHP_SELF'];
	}
    else
	{
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('?', $url);
        $replace = str_replace(root_dir, '', install_dir);
		$url = str_replace(array(($replace.'index.php'),$replace), '', $url[0]);
	}
    $url = str_replace(array('http://', 'https://'), null, $url);   
    $url = trim($url, '/');
	return ($url != ''? '/'.$url : null).'/';
}
function preg_check($expression, $value)
{
	if (is_string($value))
	{
		return preg_match($expression, $value);
	}
	else
	{
		return false;
	}
}
function get_hook($h)
{
	return false;
}