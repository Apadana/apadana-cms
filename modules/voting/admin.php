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

function module_voting_info()
{
	return array(
		'name' => 'voting',
		'version' => '1.0',
		'creationDate' => '2012-07-21 18:00:03',
		'description' => 'ماژول نظرسنجی حرفه ای برای آپادانا.',
		'author' => 'iman moodi',
		'authorEmail' => 'imanmoodi@yahoo.com',
		'authorUrl' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL',
	);
}

function module_voting_install()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->query("
		CREATE TABLE `#__voting` (
		  `vote_id` int(255) NOT NULL AUTO_INCREMENT,
		  `vote_title` varchar(255) NOT NULL DEFAULT '',
		  `vote_case` text NOT NULL,
		  `vote_ip` text,
		  `vote_members` text,
		  `vote_date` int(10) NOT NULL,
		  `vote_result` text,
		  `vote_button` varchar(200) NOT NULL DEFAULT 'Submit',
		  `vote_status` int(1) NOT NULL DEFAULT '0',
		  `vote_language` varchar(100) DEFAULT NULL,
		  PRIMARY KEY (`vote_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	");
	$d->insert('modules', array(
		'module_name' => 'voting',
		'module_version' => '1.0',
		'module_status' => 0,
	));
	echo message('ماژول نظرسنجی با موفقیت نصب شد.', 'success');
}

function module_voting_uninstall()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->query("DROP TABLE `#__voting`");
	$d->delete('modules', "module_name='voting'", 1);
	$d->delete('admin', "admin_rights='voting'", 1);
	echo message('ماژول نظرسنجی با موفقیت حذف شد.', 'success');
	remove_cache('module-voting', true);
}

function module_voting_active()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->update('modules', array('module_status'=>1), "module_name='voting'", 1);

	$d->insert('admin', array(
		'admin_rights' => 'voting',
		'admin_image' => 'engine/images/admin/obmen.png',
		'admin_title' => 'نظرسنجی',
		'admin_link' => '?admin={admin}&amp;module=voting',
		'admin_page' => '2',
	));
	echo message('ماژول نظرسنجی فعال شد.', 'success');
}

function module_voting_inactive()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->update('modules', array('module_status'=>0), "module_name='voting'", 1);
	$d->delete('admin', "admin_rights='voting'", 1);
	echo message('ماژول نظرسنجی غیرفعال شد.', 'success');
}

function module_voting_admin()
{
	global $tpl, $d, $options, $cache;

	member::check_admin_page_access('voting') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	require_once(root_dir.'modules/voting/functions.admin.php');
	$_GET['do'] = get_param($_GET, 'do');

	switch ($_GET['do'])
	{
		case 'list';
		_list();
		break;

		case 'status';
		_status();
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