<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2013 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

member::check_admin_page_access('cache') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $tpl, $options, $page;
	set_title('مدیریت کش');
	$itpl = new template('engine/admin/template/cache.tpl');
	
	$handle = opendir(engine_dir.'cache');
	while ($file = readdir($handle))
	{
		if (get_extension($file) == 'cache')
		{
			$cachedFile = engine_dir.'cache/'.$file;
			$itpl->add_for('cache', array(
				'{odd-even}' => odd_even(),
				'{name}' => apadana_substr($file, 0, -6),
				'{time}' => get_past_time(filemtime($cachedFile))
			));
			$is_cache = true;
		}
	}
	closedir($handle);
	unset($handle, $file);
	
	if (isset($is_cache))
	{
		$itpl->assign(array(
			'[cache]' => null,
			'[/cache]' => null,
		));
		$itpl->block('#\\[not-cache\\](.*?)\\[/not-cache\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-cache]' => null,
			'[/not-cache]' => null,
		));
		$itpl->block('#\\[cache\\](.*?)\\[/cache\\]#s', '');
	}

	if (is_ajax())
	{
		$itpl->display();
		define('no_template', true);
	}
	else
	{
		set_content(false, $itpl->get_var());
	}
	unset($itpl);
}

function _delete()
{
	$name = get_param($_GET, 'name');
	$name = alphabet($name);
	if (!empty($name))
	{
		remove_cache($name);
		echo message('کش <b>'.$name.'</b> حذف شد!', 'success');
	}
	else
	{
		$handle = opendir(engine_dir.'cache');
		while ($file = readdir($handle))
		{
			if (get_extension($file) == 'cache')
			{
				$cachedFile = engine_dir.'cache/'.$file;
				@unlink($cachedFile);
			}
		}
		closedir($handle);
		unset($handle, $file);
		echo message('فایل های کش حذف شدند!', 'success');
	}
	_index();
}

$_GET['do'] = get_param($_GET, 'do');

switch($_GET['do'])
{
	case 'delete':
	_delete();
	break;

	default:
	_index();
	break;
}

?>