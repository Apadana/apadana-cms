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

function http_referer()
{
	global $options, $d;
	if ($options['http-referer'] == 1)
	{
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$referer = nohtml($_SERVER['HTTP_REFERER']);
			$referer = !validate_url($referer)? null : $referer;
			$parse = parse_url($referer);
		}

		if (isset($parse['host']) && !empty($referer) && strtolower($parse['host']) != domain && strtolower($parse['host']) != strtolower($_SERVER['HTTP_HOST']))
		{
			($hook = get_hook('http_referer'))? eval($hook) : null;

			$d->insert('referer', array(
				'ref_url' => $referer,
				'ref_domain' => $parse['host'],
				'ref_time' => time(),
				'ref_ip' => get_ip()
			));
		}
		unset($referer, $parse);
	}
}
