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

function check_banned()
{
	global $options;

	$IP = get_ip();
	$options['last-banned'] = intval($options['last-banned']) <= 0? md5(md5(sitekey)) : intval($options['last-banned']);

	if (isset($_COOKIE['check-ip']))
	{
		$checkIP = md5(sitekey . md5($_SERVER['HTTP_USER_AGENT']) . md5($IP) . $options['last-banned']);
		if ($_COOKIE['check-ip'] == $checkIP)
		{
			$checkIP = 'OK';
		}
		else
		{
			$checkIP = $_COOKIE['check-ip'] = FALSE;
			unset($_COOKIE['check-ip']);
			un_set_cookie('check-ip');
		}
	}
	if (!isset($checkIP) || $checkIP != 'OK')
	{
		global $d;
		$ip_class = explode('.', $IP);
		$ip_class[0] = intval(@$ip_class[0]);
		$ip_class[1] = intval(@$ip_class[1]);
		$ip_class[2] = intval(@$ip_class[2]);
		$ip_class[3] = intval(@$ip_class[3]);

		$IP_1 = $ip_class[0].'.'.$ip_class[1].'.'.$ip_class[2].'.*';
		$IP_2 = $ip_class[0].'.'.$ip_class[1].'.*.'.$ip_class[3];
		$IP_3 = $ip_class[0].'.'.$ip_class[1].'.*.*';

		$d->query("SELECT * FROM `#__banned` WHERE `ban_ip`='".$d->escapeString($IP)."' OR `ban_ip`='".$d->escapeString($IP_1)."' OR `ban_ip`='".$d->escapeString($IP_2)."' OR `ban_ip`='".$d->escapeString($IP_3)."'");
		if ($d->numRows() >= 1)
		{
			$result = $d->fetch();
			if (is_array($result) && count($result) && !empty($result['ban_ip']))
			{
				if ($result['ban_ip']==$IP || $result['ban_ip']==$IP_1 || $result['ban_ip']==$IP_2 || $result['ban_ip']==$IP_3)
				{
					@Header('Content-type: text/html; charset='.charset);

					$tpl = get_tpl(engine_dir.'templates/||banned.tpl', template_dir.'||banned.tpl');
					$tpl = new template($tpl[1], $tpl[0]);

					$tpl->assign(array(
						'{reason}' => nl2br($result['ban_reason']),
						'{date}' => jdate('l j F Y ساعت g:i A', $result['ban_date']),
						'{site-title}' => $options['title'],
						'{site-slogan}' => $options['slogan'],
						'{site-url}' => url,
					));
					$tpl->display();
					exit();
				}			
			}
		}

		set_cookie('check-ip', md5(sitekey . md5($_SERVER['HTTP_USER_AGENT']) . md5($IP) . $options['last-banned']), time()+(60*60*5));
		unset($IP, $ip_class, $IP_1, $IP_2, $IP_3, $q, $result);
	}
}

?>