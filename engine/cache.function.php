<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92) & Mohammad Sadegh Dehghan Niri (MSDN)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

function set_cache($name, $content, $serialize = true)
{
	static $test;
	
	if (isset($test) && !$test)
	{
		return false;
	}

	if (!is_dir(engine_dir.'cache'))
	{
		@mkdir(engine_dir.'cache', 0777);
		@apadana_chmod(engine_dir.'cache', 0777);
	}

	if (is_dir(engine_dir.'cache') && is_readable(engine_dir.'cache') && is_writable(engine_dir.'cache'))
	{
		$test = true;
	}
	else
	{
		return $test = false;
	}
	
	if (!is_alphabet($name))
	{
		return false;
	}

	$cache = engine_dir.'cache/'.$name.'.cache';

	if (file_put_contents($cache, $serialize? serialize($content) : $content))
	{
		@apadana_chmod($cache, 0666);
		return true;
	}
	else
	{
		return false;
	}
}

function get_cache($name, $life = true)
{
	if (!is_alphabet($name))
	{
		return false;
	}

	$cache = engine_dir.'cache/'.$name.'.cache';

	if (file_exists($cache) && is_readable($cache))
	{
		//Fixed in 1.0.5 : We can't use == for here!!!
		if ($life === true)
		{
			return maybe_unserialize(file_get_contents($cache));
		}
		else
		{
			switch ($life)
			{
				case 'long':
				$life = 31536000; // One year
				break;

				case 'short':
				$life = 10800; // 3 Hours
				break;
			}
			
			$life = intval($life);
			$life = $life <= 100? 10800 : $life;
			$fileLife = filemtime($cache);

			if ($fileLife+$life > time())
			{
				return maybe_unserialize(file_get_contents($cache));
			}
		}
		remove_cache($name);
	}
	return false;
}

function remove_cache($name, $search = false)
{
	if (!is_alphabet($name))
	{
		return false;
	}
	if ($search)
	{
		$cache = engine_dir.'cache/';
		$files = glob($cache.'*.cache');
		if (is_array($files) && count($files))
		{
			foreach ($files as $file)
			{
				$file = basename($file);
				if (strpos($file, $name) !== false && file_exists($cache.$file) && is_writable($cache.$file) && get_extension($file) == 'cache')
				{
					unlink($cache.$file);
					$unlink = true;
				}
			}
			$files = null;
		}
		if (isset($unlink)) return true;
	}
	else
	{
		$cache = engine_dir.'cache/'.$name.'.cache';
		if (file_exists($cache) && is_writable($cache))
		{
			return unlink($cache);
		}
	}
	return false;
}

?>