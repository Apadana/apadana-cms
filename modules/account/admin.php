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

function module_account_info()
{
	return array(
		'name' => 'account',
		'version' => '1.0',
		'creationDate' => '2012-07-21 18:00:03',
		'description' => 'ماژول سامانه کاربری آپادانا',
		'author' => 'iman moodi',
		'authorEmail' => 'imanmoodi@yahoo.com',
		'authorUrl' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL',
	);
}

function module_account_admin()
{
	global $tpl, $d, $options, $cache;

	member::check_admin_page_access('account') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	require_once(root_dir.'modules/account/functions.admin.php');

	$_GET['do'] = get_param($_GET, 'do');

	switch($_GET['do'])
	{
		case 'edit';
		_edit();
		break;

		case 'status';
		_status();
		break;
		
		case 'options';
		_options();
		break;
		
		default:
		_default();
		break;
	}
}

?>