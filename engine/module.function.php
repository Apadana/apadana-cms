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

 /**
 * @since 1.1 
 */
function module_info($module)
{
	static $ap_module_info;

	$module = trim($module);

	if (isset($ap_module_info[$module]) && is_array($ap_module_info[$module]) && count($ap_module_info[$module]))
	{
		return $ap_module_info[$module];
	}

	if (!module_exists($module))
	{
		return false;
	}

	if (file_exists(root_dir.'modules/'.$module.'/admin.php'))
	{
		require_once(root_dir.'modules/'.$module.'/admin.php');

		if (function_exists('module_'.str_replace('-', '_', $module).'_info'))
		{
			$data = call_user_func('module_'.str_replace('-', '_', $module).'_info');
		}
	}

	$array = array();
	$array['name'] = !isset($data['name']) || empty($data['name'])? $module : $data['name'];
	$array['version'] = !isset($data['version']) || empty($data['version'])? '1.0' : nohtml($data['version']);
	$array['compatibility'] = !isset($data['compatibility']) || empty($data['compatibility'])? '*' : trim($data['compatibility']);
	$array['creation-date'] = !isset($data['creation-date']) || empty($data['creation-date'])? date('Y-m-d H:i:s', file_exists(root_dir.'modules/'.$module.'/config.php')? fileatime(root_dir.'modules/'.$module.'/config.php') : time()) : nohtml($data['creation-date']);
	$array['description'] = !isset($data['description']) || empty($data['description'])? null : $data['description'];
	$array['author'] = !isset($data['author']) || empty($data['author'])? null : $data['author'];
	$array['author-email'] = !isset($data['author-email']) || !validate_email($data['author-email'])? null : $data['author-email'];
	$array['author-url'] = !isset($data['author-url']) || !validate_url($data['author-url'])? null : $data['author-url'];

	$array['license'] = !isset($data['license']) || empty($data['license'])? 'GNU/GPL' : $data['license'];
	$array['depends-on'] = !isset($data['depends-on']) || !is_array($data['depends-on'])? array() : $data['depends-on'];

	return $ap_module_info[$module] = $array;
}