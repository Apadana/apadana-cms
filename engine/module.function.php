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

function module_exists($name)
{
	if (!is_dir(root_dir.'modules') || !is_readable(root_dir.'modules'))
	{
		return -1;
	}
	elseif (!is_alphabet($name))
	{
		return -2;
	}
	elseif (is_dir(root_dir.'modules/'.$name) && file_exists(root_dir.'modules/'.$name.'/config.php') && is_readable(root_dir.'modules/'.$name.'/config.php'))
	{
		return true;
	}
	return false;
}

function is_module($name, $active = true)
{
	if (module_exists($name) === true)
	{
		global $modules;
		if (!isset($modules[$name]) || ($active && $modules[$name]['module_status'] <= 0))
		{
			return false; 
		}

		return true; 
	}
	return false; 
}

?>