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

function module_files_info()
{
	return array(
		'name' => 'files',
		'version' => '1.0.1',
		'creationDate' => '2012-07-21 18:00:03',
		'description' => 'ماژول فایل‌ها برای آپادانا.',
		'author' => 'iman moodi',
		'authorEmail' => 'imanmoodi@yahoo.com',
		'authorUrl' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL',
	);
}

function module_files_install()
{
	global $d;

	member::check_admin_page_access('modules') or exit('Warning access!');
	$d->query("
		CREATE TABLE IF NOT EXISTS `#__files` (
		  `file_id` int(255) NOT NULL AUTO_INCREMENT,
		  `file_slug` varchar(200) NOT NULL,
		  `file_url` varchar(300) NOT NULL,
		  `file_date` int(10) NOT NULL DEFAULT '0',
		  `file_author` int(255) NOT NULL DEFAULT '0',
		  `file_access` int(1) NOT NULL DEFAULT '1',
		  `file_count_downloads` int(255) NOT NULL DEFAULT '0',
		  `file_members` text NOT NULL,
		  PRIMARY KEY (`file_id`),
		  KEY `file_slug` (`file_slug`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	");
	$d->insert('modules', array(
		'module_name' => 'files',
		'module_version' => '1.0.1',
		'module_status' => 0,
	));
	echo message('ماژول فایل‌ها با موفقیت نصب شد.', 'success');
}

function module_files_uninstall()
{
	global $d;

	member::check_admin_page_access('modules') or exit('Warning access!');
	$d->query("DROP TABLE `#__files`");
	$d->delete('modules', "module_name='files'", 1);
	$d->delete('admin', "admin_rights='files'", 1);
	echo message('ماژول فایل‌ها با موفقیت حذف شد.', 'success');
	remove_cache('module-files', true);
}

function module_files_active()
{
	global $d;

	member::check_admin_page_access('modules') or exit('Warning access!');
	$d->update('modules', array('module_status'=>2), "module_name='files'", 1);

	$d->insert('admin', array(
		'admin_rights' => 'files',
		'admin_image' => 'engine/images/admin/files.png',
		'admin_title' => 'فایل های دانلودی',
		'admin_link' => '?admin={admin}&amp;module=files',
		'admin_page' => 2,
	));
	echo message('ماژول فایل‌ها فعال شد.', 'success');
}

function module_files_inactive()
{
	global $d;

	member::check_admin_page_access('modules') or exit('Warning access!');
	$d->update('modules', array('module_status'=>0), "module_name='files'", 1);
	$d->delete('admin', "admin_rights='files'", 1);
	echo message('ماژول فایل‌ها غیرفعال شد.', 'success');
}

function module_files_admin()
{
	member::check_admin_page_access('files') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	require_once(root_dir.'modules/files/functions.admin.php');

	$_GET['do'] = get_param($_GET, 'do');

	switch($_GET['do'])
	{
		case 'new':
		_new();
		break;

		case 'get-members':
		_get_members();
		break;
		
		case 'get-info':
		_get_info();
		break;
		
		case 'get-data':
		_get_data();
		break;
		
		case 'edit':
		_edit();
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