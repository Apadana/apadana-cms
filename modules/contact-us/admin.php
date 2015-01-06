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

function module_contact_us_info()
{
	return array(
		'name' => 'contact-us',
		'version' => '1.0',
		'creationDate' => '2013-04-12 03:25:00',
		'description' => 'ماژول تماس با ما برای آپادانا.',
		'author' => 'iman moodi',
		'authorEmail' => 'imanmoodi@yahoo.com',
		'authorUrl' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL',
	);
}

function module_contact_us_install()
{
	member::check_admin_page_access('modules') or exit('Warning access!');
	global $d;
	$d->insert('modules', array(
		'module_name' => 'contact-us',
		'module_version' => 1.0,
		'module_status' => 0,
	));
	echo message('ماژول تماس با ما با موفقیت نصب شد.', 'success');
}

function module_contact_us_uninstall()
{
	member::check_admin_page_access('modules') or exit('Warning access!');
	global $d;
	$d->delete('modules', "module_name='contact-us'", 1);
	echo message('ماژول تماس با ما با موفقیت حذف شد.', 'success');
}

function module_contact_us_active()
{
	member::check_admin_page_access('modules') or exit('Warning access!');
	global $d;
	$d->update('modules', array('module_status'=>2), "module_name='contact-us'", 1);
	echo message('ماژول تماس با ما فعال شد.', 'success');
}

function module_contact_us_inactive()
{
	member::check_admin_page_access('modules') or exit('Warning access!');
	global $d;
	$d->update('modules', array('module_status'=>0), "module_name='contact-us'", 1);
	echo message('ماژول تماس با ما غیرفعال شد.', 'success');
}

?>