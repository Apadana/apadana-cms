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

function mod_rewrite()
{
	global $options;

	$alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
	$mod_rewrite = isset($_GET['mod_rewrite'])? nohtml($_GET['mod_rewrite']) : null;

	if ($mod_rewrite != '' && $options['rewrite'] == 1)
	{
		$options['file-rewrite'] = $options['file-rewrite'] == '/'? null : $options['file-rewrite'];
		$strlen = apadana_strlen($options['file-rewrite']);
		$mod_rewrite = trim($mod_rewrite, '/');
		if (!empty($options['file-rewrite']) && apadana_substr($mod_rewrite, -$strlen) != $options['file-rewrite'])
		{
			define('mod_rewrite', 'error');
		}
		else
		{
			$strlen = apadana_strlen($mod_rewrite)-intval($strlen);
			$mod_rewrite = apadana_substr($mod_rewrite, 0, $strlen);
			$mod_rewrite = trim($mod_rewrite, $options['separator-rewrite']);
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

function url($url)
{
	global $options;

	$alphabet = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'aa', 'ab', 'ac', 'ad', 'ae', 'af', 'ag', 'ah', 'ai', 'aj', 'ak', 'al', 'am', 'an', 'ao', 'ap', 'aq', 'ar', 'as', 'at', 'au', 'av', 'aw', 'ax', 'ay', 'az');
	$url = nohtml($url);
	$return = null;
	if (!empty($url))
	{
		$url = trim($url, '/');
		$url = explode('/', $url);
		if (count($url))
		{
			for ($i=0; $i<=count($url)-1; $i++)
			{
				if (empty($url[$i]) || $i>52) continue;
				$return .= $options['rewrite']==1? $options['separator-rewrite'].$url[$i] : '&amp;'.$alphabet[$i].'='.$url[$i];
			}
		}
		$return = apadana_substr($return, $options['rewrite']==1? apadana_strlen($options['separator-rewrite']) : 5);
		return url.($options['rewrite']==1? $return.($options['file-rewrite']==''? '/' : $options['file-rewrite']) : '?'.$return);
	}
	return false;
}

?>