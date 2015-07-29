<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCMS.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

function add_option($name, $value, $autoload = 1)
{
	global $d, $options;

	if (!is_string($name) || empty($name))
	{
		return false;
	}

	if ($d->num_rows("SELECT `option_name` FROM `#__options` WHERE `option_name`='".$d->escape_string($name)."'", true) > 0)
	{
		return false;
	}

	($hook = get_hook('add_option'))? eval($hook) : null;

	$d->insert('options', array(
		'option_name' => $name,
		'option_value' => $value,
		'autoload' => $autoload
	));

	if ($d->affected_rows())
	{
		$options[$name] = $value;

		remove_cache('options');

		($hook = get_hook('add_option.'.$name))? eval($hook) : null;
		($hook = get_hook('added_option'))? eval($hook) : null;

		return true;
	}

	return false;
}

function update_option($name, $value, $autoload = null)
{
	global $d, $options;

	if (!is_string($name) || empty($name))
	{
		return false;
	}

	if ($d->num_rows("SELECT `option_name` FROM `#__options` WHERE `option_name`='".$d->escape_string($name)."'", true) <= 0)
	{
		return add_option($name, $value, $autoload);
	}

	($hook = get_hook('update_option'))? eval($hook) : null;

	$data = array(
		'option_value' => $value
	);

	if (is_numeric($autoload) && $autoload == 0)
	{
		$data['autoload'] = 0;
	}
	elseif (is_numeric($autoload) && $autoload == 1)
	{
		$data['autoload'] = 1;
	}

	$d->update('options', $data, "`option_name`='".$d->escape_string($name)."'", 1);

	if ($d->affected_rows())
	{
		$options[$name] = $value;

		remove_cache('options');

		($hook = get_hook('update_option.'.$name))? eval($hook) : null;
		($hook = get_hook('updated_option'))? eval($hook) : null;

		return true;
	}

	return false;
}

function delete_option($name)
{
	global $d, $options;

	if (!is_string($name) || empty($name))
	{
		return false;
	}

	if ($d->num_rows("SELECT `option_name` FROM `#__options` WHERE `option_name`='".$d->escape_string($name)."'", true) <= 0)
	{
		return true;
	}

	($hook = get_hook('delete_option'))? eval($hook) : null;

	$d->delete('options', "`option_name`='".$d->escape_string($name)."'", 1);

	if ($d->affected_rows())
	{
		unset($options[$name]);
		remove_cache('options');

		($hook = get_hook('delete_option.'.$name))? eval($hook) : null;
		($hook = get_hook('deleted_option'))? eval($hook) : null;

		return true;
	}

	return false;
}

function get_option($name, $use_cache = true)
{
	global $d, $options;

	if (is_string($name) && !empty($name))
	{
		if ($use_cache === true && isset($options[$name]))
		{
			return $options[$name];
		}

		$d->query("SELECT `option_value` FROM `#__options` WHERE `option_name`='".$d->escape_string($name)."' LIMIT 1");

		if ($d->num_rows() <= 0)
		{
			return false;
		}

		$row = $d->fetch();
		$d->free_result();

		if (isset($row['option_value']))
		{
			return $options[$name] = $row['option_value'];
		}
	}
	else if (is_array($name) && count($name))
	{
		if ($use_cache === true)
		{
			$ok = true;

			foreach ($name as $option)
			{
				if (!isset($options[$option]))
				{
					$ok = false;
					break;
				}
			}

			if ($ok === true)
			{
				return true;
			}
		}

		$names = null;
		$comma = null;

		foreach ($name as $option)
		{
			$names .= $comma."'".$d->escape_string($option)."'";
			$comma = ', ';
		}

		$d->query("SELECT `option_name`, `option_value` FROM `#__options` WHERE `option_name` IN(".$names.")");

		if ($d->num_rows() <= 0)
		{
			return false;
		}

		while ($row = $d->fetch())
		{
			$options[$row['option_name']] = $row['option_value'];
		}

		$d->free_result();
		unset($row, $names);

		return true;
	}

	return false;
}
