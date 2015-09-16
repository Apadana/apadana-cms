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

function module_shoutbox_info()
{
	return array(
		'name' => 'جعبه پیام',
		'version' => '1.0.1',
		'creation-date' => '2012-07-21 18:00:03',
		'description' => 'ماژول جعبه پیام پیام برای آپادانا.',
		'author' => 'iman moodi',
		'author-email' => 'imanmoodi@yahoo.com',
		'author-url' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL',
	);
}

function module_shoutbox_install()
{
	member::check_admin_page_access('modules') or exit('Warning access!');
	global $d;
	$d->query("
		CREATE TABLE IF NOT EXISTS `#__shoutbox` (
		  `shout_id` int(255) NOT NULL AUTO_INCREMENT,
		  `shout_time` int(10) NOT NULL,
		  `shout_member` varchar(40) NOT NULL,
		  `shout_message` varchar(300) NOT NULL,
		  PRIMARY KEY (`shout_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	");
	$d->insert('modules', array(
		'module_name' => 'shoutbox',
		'module_version' => '1.0.1',
		'module_status' => 0,
	));
	echo message('ماژول shoutbox با موفقیت نصب شد.', 'success');
}

function module_shoutbox_uninstall()
{
	member::check_admin_page_access('modules') or exit('Warning access!');
	global $d;
	$d->query("DROP TABLE `#__shoutbox`");
	$d->delete('modules', "module_name='shoutbox'", 1);
	echo message('ماژول shoutbox با موفقیت حذف شد.', 'success');
}

function module_shoutbox_active()
{
	member::check_admin_page_access('modules') or exit('Warning access!');
	global $d;
	$d->update('modules', array('module_status'=>1), "module_name='shoutbox'", 1);
	echo message('ماژول shoutbox فعال شد.', 'success');
}

function module_shoutbox_inactive()
{
	member::check_admin_page_access('modules') or exit('Warning access!');
	global $d;
	$d->update('modules', array('module_status'=>0), "module_name='shoutbox'", 1);
	echo message('ماژول shoutbox غیرفعال شد.', 'success');
}

?>