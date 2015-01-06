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

function module_sitemap_run()
{
	global $modules, $options;

	$mod_rewrite = current_url();
	if ($options['rewrite'] == 1 && $mod_rewrite != url . 'sitemap.xml')
	{
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.url . 'sitemap.xml');
	}

	require_once(engine_dir.'sitemap/sitemap.class.php');
	$sitemap = new sitemap();
	$sitemap->baseDir = url;
	$sitemap->cache = true;
	$sitemap->cacheLife = 14400;
	$sitemap->cacheFile = engine_dir.'cache/sitemap.cache';

	if(!$sitemap->is_cache())
	{
		$sitemap->addItem(url, time(), 'always', '1.0');

		($hook = get_hook('sitemap'))? eval($hook) : null;

		foreach($modules as $mod)
		{
			if(is_module($mod['module_name']) && function_exists('module_'.str_replace('-', '_', $mod['module_name']).'_sitemap'))
			{
				$func = 'module_'.str_replace('-', '_', $mod['module_name']).'_sitemap';
				$func($sitemap);
			}
		}
		unset($mod, $func);
	}

	$sitemap->display();
}

?>