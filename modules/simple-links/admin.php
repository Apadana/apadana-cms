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

function module_simple_links_info()
{
	return array(
		'name' => 'simple_links',
		'version' => '1.0',
		'creationDate' => '2012-07-21 18:00:03',
		'description' => 'ماژول پیوندهای آپادانا.',
		'author' => 'iman moodi',
		'authorEmail' => 'imanmoodi@yahoo.com',
		'authorUrl' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL',
	);
}

function module_simple_links_install()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->query("
		CREATE TABLE IF NOT EXISTS `#__simple_links` (
		  `link_id` int(255) NOT NULL AUTO_INCREMENT,
		  `link_title` varchar(100) NOT NULL,
		  `link_description` varchar(400) NOT NULL,
		  `link_href` varchar(600) NOT NULL,
		  `link_target` varchar(10) NOT NULL,
		  `link_direct_link` int(1) NOT NULL DEFAULT '1',
		  `link_color` varchar(100) NOT NULL DEFAULT '',
		  `link_bold` int(1) NOT NULL DEFAULT '0',
		  `link_strikethrough` int(1) NOT NULL DEFAULT '0',
		  `link_active` int(1) NOT NULL DEFAULT '1',
		  `link_language` varchar(100) NOT NULL DEFAULT 'persian',
		  PRIMARY KEY (`link_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	");
	$d->insert('modules', array(
		'module_name' => 'simple-links',
		'module_version' => '1.0',
		'module_status' => 0,
	));
	echo message('ماژول پیوندها با موفقیت نصب شد.', 'success');
}

function module_simple_links_uninstall()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->query("DROP TABLE `#__simple_links`");
	$d->delete('modules', "module_name='simple-links'", 1);
	$d->delete('admin', "admin_rights='simple-links'", 1);
	echo message('ماژول پیوندها با موفقیت حذف شد.', 'success');
	remove_cache('simple-links');
}

function module_simple_links_active()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->update('modules', array('module_status'=>1), "module_name='simple-links'", 1);
	$d->insert('admin', array(
		'admin_rights' => 'simple-links',
		'admin_image' => 'engine/images/admin/posts.png',
		'admin_title' => 'پیوندها',
		'admin_link' => '?admin={admin}&amp;module=simple-links',
		'admin_page' => 2,
	));
	echo message('ماژول پیوندها فعال شد.', 'success');
}

function module_simple_links_inactive()
{
	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	global $d;
	$d->update('modules', array('module_status'=>0), "module_name='simple-links'", 1);
	$d->delete('admin', "admin_rights='simple-links'", 1);
	echo message('ماژول پیوندها غیرفعال شد.', 'success');
}

function module_simple_links_admin()
{
	member::check_admin_page_access('simple-links') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	global $tpl, $d, $options, $cache;
	require(root_dir.'modules/simple-links/functions.admin.php');
	$_GET['do'] = get_param($_GET, 'do');

	switch($_GET['do'])
	{
		case 'list';
		_list();
		break;

		case 'active';
		_active();
		break;
		
		case 'delete';
		_delete();
		break;
		
		case 'new';
		_new();
		break;
		
		case 'edit';
		_edit();
		break;
		
		default:
		_default();
		break;
	}
}

?>