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

function antiflood()
{
	global $options, $d;

	if ($options['antiflood'] != 1)
	{
		return false;
	}

	$ip = get_ip();
	$d->delete('antiflood', "`flood_time` < '".(time_now-2)."'");
	$d->insert('antiflood', array(
		'flood_ip' => $ip,
		'flood_time' => time_now
	));

	$numrow = $d->num_rows("SELECT `flood_time` FROM `#__antiflood` WHERE `flood_ip`='".$d->escape_string($ip)."'", true);
	if ($numrow > 7)
	{
		Header('HTTP/1.0 403 Forbidden');
		exit('<title>AntiFlood</title><br><br><center><b>Sorry, too many page loads in so little time!</b></center>');
	}

	unset($ip, $numrow);
}

?>