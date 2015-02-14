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

@ob_start();
@ob_implicit_flush(0);
@session_start();

@error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);

define('security', true);
define('root_dir', dirname(__FILE__).'/');
define('engine_dir', root_dir.'/engine/');
require_once(engine_dir.'init.php');

if (isset($_GET['admin']) && $_GET['admin'] == $options['admin'] && $options['admin'] != '')
{
	define('home', false);
	require_once(engine_dir.'admin/init.php');
}
else
{
	check_url();

	if (mod_rewrite !== 'error' && $_GET['a'] == '')
	{
		define('home', true);
		$_GET['a'] = $options['default-module'];
	}
	else
	{
		define('home', false);
	}
	if (mod_rewrite !== 'error' && is_module($_GET['a']) && function_exists('module_'.str_replace('-', '_', $_GET['a']).'_run'))
	{
		call_user_func('module_'.str_replace('-', '_', $_GET['a']).'_run');
	}
	else
	{
		module_error_run('404');
	}
}

if (!defined('no_headers') || no_headers === false)
{
    header('Content-type: text/html; charset='.charset);
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
}

if (!defined('no_template') || no_template !== true)
{
	if (admin_page === false && !is_ajax())
	{
		require_once(engine_dir.'http-referer.function.php');
		http_referer();
		update_session();
	}

	if (!defined('no_blocks') || no_blocks !== true)
	{
		require_once(engine_dir.'block.function.php');
		blocks();
	}

	if (isset($tpl->foreach['pagination']))
	{
		$tpl->assign(array(
			'[pagination]' => null,
			'[/pagination]' => null,
		));
	}
	else
	{
		$tpl->block('#\\[pagination\\](.*?)\\[/pagination\\]#s', '');
	}

	$tpl->assign(array(
		'{head}' => head(),
		'{foot}' => is_array($page['foot']) && count($page['foot'])? implode(n, $page['foot']).n : null,
		'{num-queries}' => translate_number($d->num_queries),
		'{creation-time}' => translate_number(apadana_substr(microtime(true)-start_time, 0, debug_system? 7 : 4)),
		'{memory-get-usage}' => translate_number(apadana_memory_get_usage())
	));

	if ($options['allow-change-theme']) {
		$tpl->assign('{templates-list}' , get_templates(true));
	}else{
		$tpl->assign('{templates-list}' , 'تغیر قالب غیر فعال است');
	}

	if (home === true)
	{
		set_theme('home');
	}

	if (is_alphabet($page['theme']) && !admin_page && file_exists($tpl->base_dir.$page['theme'].'.tpl'))
	{
		$tpl->load($page['theme'].'.tpl');
	}

	($hook = get_hook('index'))? eval($hook) : null;

	$tpl->tags['{content}'] = '<div id="apadana-ajax-content">'.(isset($tpl->tags['{content}'])? $tpl->tags['{content}'] : null).'</div>';
	$tpl->display();
}

gzip_out();

?>