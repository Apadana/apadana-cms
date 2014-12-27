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

function module_private_messages_info()
{
	return array(
		'name' => 'private-messages',
		'version' => '1.0.1',
		'creationDate' => '2013-04-12 03:25:00',
		'description' => 'ماژول پیام خصوصی آپادانا',
		'author' => 'iman moodi',
		'authorEmail' => 'imanmoodi@yahoo.com',
		'authorUrl' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL',
	);
}

function module_private_messages_install()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->query("
		CREATE TABLE IF NOT EXISTS `#__private_messages` (
		  `msg_id` int(255) NOT NULL AUTO_INCREMENT,
		  `msg_sender` varchar(40) NOT NULL,
		  `msg_receiver` varchar(40) NOT NULL,
		  `msg_subject` varchar(200) NOT NULL,
		  `msg_text` text NOT NULL,
		  `msg_date` int(10) NOT NULL,
		  `msg_read` int(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`msg_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	");
	$d->insert('modules', array(
		'module_name' => 'private-messages',
		'module_version' => '1.0.1',
		'module_status' => 0,
	));
	echo message('ماژول پیام خصوصی با موفقیت نصب شد.', 'success');
}

function module_private_messages_uninstall()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->query("DROP TABLE `#__private_messages`");
	$d->delete('modules', "module_name='private-messages'", 1);
	echo message('ماژول پیام خصوصی با موفقیت حذف شد.', 'success');
	remove_cache('module-private-messages', true);
}

function module_private_messages_active()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->update('modules', array('module_status'=>1), "module_name='private-messages'", 1);
	echo message('ماژول پیام خصوصی فعال شد.', 'success');
}

function module_private_messages_inactive()
{
	global $d;

	member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$d->update('modules', array('module_status'=>0), "module_name='private-messages'", 1);
	echo message('ماژول پیام خصوصی غیرفعال شد.', 'success');
}

?>