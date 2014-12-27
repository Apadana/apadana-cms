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

group_admin==1 or exit('Warning access!');

$_GET['module'] = alphabet($_GET['module']);

if(isset($_GET['module']) && is_module($_GET['module']) && file_exists(root_dir.'modules/'.$_GET['module'].'/admin.php'))
{
	require_once(root_dir.'modules/'.$_GET['module'].'/admin.php');
	
	if(is_module($_GET['module']) && function_exists('module_'.str_replace('-', '_', $_GET['module']).'_admin'))
	{
		call_user_func('module_'.str_replace('-', '_', $_GET['module']).'_admin');
	}
	else
	{
		require(engine_dir.'admin/modules/404.php');
	}
}
else
{
	require(engine_dir.'admin/modules/404.php');
}

?>