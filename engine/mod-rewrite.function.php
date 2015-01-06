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

function mod_rewrite()
{
	global $options;

	$alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');

	$mod_rewrite = isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : false;
	$mod_rewrite = explode('?', trim(apadana_substr($mod_rewrite, apadana_strlen(path)) , '/'));
	$mod_rewrite = trim($mod_rewrite[0], '/');

	if ($options['separator-rewrite'] != '/')
	{
		$mod_rewrite = trim($mod_rewrite, $options['separator-rewrite']);
	}

	if ($mod_rewrite == 'sitemap.xml')
	{
		$mod_rewrite = 'sitemap'.$options['file-rewrite'];
	}

	if ($mod_rewrite != '' && $options['rewrite'] == 1)
	{
		$options['file-rewrite'] = $options['file-rewrite'] == '/'? null : $options['file-rewrite'];
		$strlen = apadana_strlen($options['file-rewrite']);
		if (!empty($options['file-rewrite']) && apadana_substr($mod_rewrite, -$strlen) != $options['file-rewrite'])
		{
			define('mod_rewrite', 'error');
		}
		else
		{
			$strlen = apadana_strlen($mod_rewrite)-intval($strlen);
			$mod_rewrite = apadana_substr($mod_rewrite, 0, $strlen);
			$mod_rewrite = explode($options['separator-rewrite'], $mod_rewrite);
			if (count($mod_rewrite))
			{
				for ($i = 0; $i <= count($mod_rewrite)-1; $i++)
				{
					if (empty($mod_rewrite[$i]) || $i>52) continue;
					$_GET[$alphabet[$i]] = urldecode($mod_rewrite[$i]);
				}
			}
			define('mod_rewrite', true);
		}
	}
	else
	{
		if ($mod_rewrite != '')
		{
			define('mod_rewrite', 'error');
		}
		else
		{
			define('mod_rewrite', false);
		}
	}
}

function url($link)
{
	global $options;

	$alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
	$link = nohtml($link);
	$url = null;
	if (!empty($link))
	{
		$link = trim($link, '/');
		$link = explode('/', $link);
		if (count($link))
		{
			for ($i = 0; $i <= count($link)-1; $i++)
			{
				if (empty($link[$i]) || $i > 52) continue;
				$url .= $options['rewrite'] == 1? $options['separator-rewrite'].$link[$i] : '&amp;'.$alphabet[$i].'='.$link[$i];
			}
		}
		$url = apadana_substr($url, $options['rewrite'] == 1? apadana_strlen($options['separator-rewrite']) : 5);
		$url = ($options['rewrite'] == 1? $url.($options['file-rewrite'] == ''? '/' : $options['file-rewrite']) : '?'.$url);

		($hook = get_hook('url'))? eval($hook) : null;

		return url.$url;
	}
	return false;
}

?>