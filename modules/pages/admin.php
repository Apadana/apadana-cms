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

function module_pages_info()
{
	return array(
		'name' => 'صفحات اضافی',
		'version' => '1.0.1',
		'creation-date' => '2012-07-21 18:00:03',
		'description' => 'ماژول صفحات اضافی برای آپادانا.',
		'author' => 'iman moodi',
		'author-email' => 'imanmoodi@yahoo.com',
		'author-url' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL',
	);
}

function module_pages_install()
{
	global $d;

	member::check_admin_page_access('modules') or exit('Warning access!');
	$d->query("
		CREATE TABLE `#__pages` (
		  `page_id` int(255) NOT NULL AUTO_INCREMENT,
		  `page_title` varchar(300) NOT NULL,
		  `page_slug` varchar(200) NOT NULL,
		  `page_time` int(10) NOT NULL DEFAULT '0',
		  `page_author` int(255) NOT NULL DEFAULT '0',
		  `page_text` longtext NOT NULL,
		  `page_theme` varchar(200) NOT NULL,
		  `page_view` int(1) NOT NULL DEFAULT '1',
		  `page_comment` int(1) NOT NULL DEFAULT '1',
		  `page_comment_count` int(255) NOT NULL DEFAULT '0',
		  `page_approve` int(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`page_id`),
		  KEY `page_slug` (`page_slug`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	");
	$d->insert('modules', array(
		'module_name' => 'pages',
		'module_version' => '1.0.1',
		'module_status' => 0,
	));
	echo message('ماژول صفحات اضافی با موفقیت نصب شد.', 'success');
}

function module_pages_uninstall()
{
	global $d;

	member::check_admin_page_access('modules') or exit('Warning access!');
	$d->query("DROP TABLE `#__pages`");
	$d->delete('comments', "comment_type='pages'");
	$d->delete('modules', "module_name='pages'", 1);
	$d->delete('admin', "admin_rights='pages'", 1);
	echo message('ماژول صفحات اضافی با موفقیت حذف شد.', 'success');
	remove_cache('module-pages', true);
}

function module_pages_active()
{
	global $d;

	member::check_admin_page_access('modules') or exit('Warning access!');
	$d->update('modules', array('module_status'=>1), "module_name='pages'", 1);

	$d->insert('admin', array(
		'admin_rights' => 'pages',
		'admin_image' => 'engine/images/admin/pages.png',
		'admin_title' => 'صفحات اضافی',
		'admin_link' => '?admin={admin}&amp;module=pages',
		'admin_page' => 2,
	));
	echo message('ماژول صفحات اضافی فعال شد.', 'success');
}

function module_pages_inactive()
{
	global $d;

	member::check_admin_page_access('modules') or exit('Warning access!');
	$d->update('modules', array('module_status'=>0), "module_name='pages'", 1);
	$d->delete('admin', "admin_rights='pages'", 1);
	echo message('ماژول صفحات اضافی غیرفعال شد.', 'success');
}

function module_pages_admin_comments($action, $data = array())
{
	global $d, $options;

	member::check_admin_page_access('comments') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	switch ($action)
	{
		case 'name';
		return array(
			'pages' => 'صفحات اضافی'
		);
		break;
		
		case 'url';
		$query = "SELECT `page_slug`, `page_id` FROM `#__pages` WHERE `page_approve`='1' AND `page_id`='".intval($data['link'])."' LIMIT 1";	
		$post = $d->get_row($query);
		return url('pages/'.($options['rewrite'] == 1? $post[0]['page_slug'] : $post[0]['page_id']));
		break;
		
		case 'approve';
		$total = $d->num_rows("SELECT `comment_id` FROM `#__comments` WHERE `comment_type`='pages' AND `comment_approve`='1' AND `comment_link`='".intval($data['link'])."'", true);
		$d->query("UPDATE `#__pages` SET `page_comment_count`='".intval($total)."' WHERE `page_id`='".intval($data['link'])."' LIMIT 1");
		break;
		
		case 'delete';
		$total = $d->num_rows("SELECT `comment_id` FROM `#__comments` WHERE `comment_type`='pages' AND `comment_approve`='1' AND `comment_link`='".intval($data['link'])."'", true);
		$d->query("UPDATE `#__pages` SET `page_comment_count`='".intval($total)."' WHERE `page_id`='".intval($data['link'])."' LIMIT 1");
		break;
	}
	return false;
}

function module_pages_admin()
{
	member::check_admin_page_access('pages') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	require_once(root_dir.'modules/pages/functions.admin.php');

	$_GET['do'] = get_param($_GET, 'do');

	switch($_GET['do'])
	{
		case 'new':
		_new();
		break;

		case 'get-data':
		_get_data();
		break;
		
		case 'edit':
		_edit();
		break;
		
		case 'approve':
		_approve();
		break;

		case 'delete':
		_delete();
		break;
		
		default:
		_index();
		break;
	}
}

?>