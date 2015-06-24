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

function check_banned()
{
	global $options, $d;

	$ip = get_ip();
	$options['last-banned'] = intval($options['last-banned']) <= 0? md5(md5(sitekey)) : intval($options['last-banned']);

	if (isset($_COOKIE['check-ip']))
	{
		$check_ip = md5(sitekey . md5($_SERVER['HTTP_USER_AGENT']) . md5($ip) . $options['last-banned']);

		if ($_COOKIE['check-ip'] == $check_ip)
		{
			$check_ip = 'OK';
		}
		else
		{
			$check_ip = $_COOKIE['check-ip'] = FALSE;
			unset($_COOKIE['check-ip']);
			un_set_cookie('check-ip');
		}
	}

	if (!isset($check_ip) || $check_ip != 'OK')
	{
		$ip_class = explode('.', $ip);
		$ip_class[0] = isset($ip_class[0])? intval($ip_class[0]) : 0;
		$ip_class[1] = isset($ip_class[1])? intval($ip_class[1]) : 0;
		$ip_class[2] = isset($ip_class[2])? intval($ip_class[2]) : 0;
		$ip_class[3] = isset($ip_class[3])? intval($ip_class[3]) : 0;

		$ip_1 = $ip_class[0].'.'.$ip_class[1].'.'.$ip_class[2].'.*';
		$ip_2 = $ip_class[0].'.'.$ip_class[1].'.*.'.$ip_class[3];
		$ip_3 = $ip_class[0].'.'.$ip_class[1].'.*.*';

		$d->query("SELECT * FROM `#__banned` WHERE `ban_ip`='".$d->escape_string($ip)."' OR `ban_ip`='".$d->escape_string($ip_1)."' OR `ban_ip`='".$d->escape_string($ip_2)."' OR `ban_ip`='".$d->escape_string($ip_3)."'");
		if ($d->num_rows() >= 1)
		{
			$result = $d->fetch();
			if (is_array($result) && count($result) && !empty($result['ban_ip']))
			{
				if ($result['ban_ip'] == $ip || $result['ban_ip'] == $ip_1 || $result['ban_ip'] == $ip_2 || $result['ban_ip'] == $ip_3)
				{
					header('Content-type: text/html; charset='.charset);

					$tpl = get_tpl(engine_dir.'templates/||banned.tpl', template_dir.'||banned.tpl');
					$tpl = new template($tpl[1], $tpl[0]);

					$tpl->assign(array(
						'{reason}' => nl2br($result['ban_reason']),
						'{date}' => jdate('l j F Y ساعت g:i A', $result['ban_date']),
						'{site-title}' => $options['title'],
						'{site-slogan}' => $options['slogan'],
						'{site-url}' => url
					));
					$tpl->display();
					exit();
				}			
			}
		}

		set_cookie('check-ip', md5(sitekey . md5($_SERVER['HTTP_USER_AGENT']) . md5($ip) . $options['last-banned']), time()+(60*60*5));
		unset($ip, $ip_class, $ip_1, $ip_2, $ip_3, $q, $result);
	}
}