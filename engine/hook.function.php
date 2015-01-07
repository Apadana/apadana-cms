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

function add_hook($id, $php)
{
	global $hooks;

	if (!isset($hooks[$id]) || !is_array($hooks[$id]))
	{
		$hooks[$id] = array();
	}

	$hooks[$id][] = $php;
}

function get_hook($id)
{
	global $hooks;

	return (!defined('disable_hooks') || disable_hooks !== true) && isset($hooks[$id])? implode("\n", $hooks[$id]) : false;
}

?>