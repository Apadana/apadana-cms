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

function counter_data()
{
	global $d, $cache;

	if (!isset($cache['block-counter']) || !is_array($cache['block-counter']) || !count($cache['block-counter']))
	{
		$t = strtotime(date('Y-m-d'));
		$t2 = strtotime(date('Y-m-d').' -1 days');
		$t3 = strtotime(date('Y-m-d').' +1 days');

		$result  = "SELECT (SELECT COUNT(*) FROM `#__members`) membersCount,".n;
		$result .= "(SELECT COUNT(`member_id`) FROM `#__members` WHERE `member_date` BETWEEN '$t2' AND '$t') membersYesterday,".n;
		$result .= "(SELECT COUNT(`member_id`) FROM `#__members` WHERE `member_date` BETWEEN '$t' AND '$t3') membersToday,".n;
		$result .= "(SELECT COUNT(`member_id`) FROM `#__members` WHERE `member_date` BETWEEN '".strtotime(date('Y-m'))."' AND '".strtotime(date('Y-m').' +1 month')."') membersMonth,".n;
		$result .= "(SELECT MAX(`member_id`) FROM `#__members`) memberNewID,".n;
		$result .= "(SELECT `member_name` FROM `#__members` WHERE `member_id`=memberNewID) memberNewName,".n;
		$result .= "(SELECT COUNT(`post_id`) FROM `#__posts` WHERE `post_approve`='1' AND post_date <= '".time_now."') postsCount,".n;
		$result .= "(SELECT COUNT(*) FROM `#__comments` WHERE `comment_approve`='1') commentsCount,".n;
		$result .= is_module('simple-links')? "(SELECT COUNT(`link_id`) FROM `#__simple_links` WHERE `link_active`='1') linksCount,".n : null;
		$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Total') totalCount,".n;
		$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Year-".jdate('Y', time_now, 0)."') yearCount,".n;
		$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Month-".jdate('Y-m', time_now, 0)."') monthCount,".n;
		$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Day-".jdate('Y-m-d', $t2, 0)."') yesterdayCount,".n;
		$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Day-".jdate('Y-m-d', time_now, 0)."') todayCount;".n;

		$result = $d->query($result);
		$cache['block-counter'] = $d->fetch($result);
	}

	return $cache['block-counter'];
}

function block_counter($op = null, $id = null, $position= null)
{
	if ($op=='remove-cache') // admin
	{
		return true;
	}

	$result = &counter_data();

	$html  = '<ul id="apadana-block-counter">'.n;
	$html .= '<li id="counter-members-today">کاربران عضو شده امروز: <b>'.intval($result['membersToday']).'</b></li>'.n;
	$html .= '<li id="counter-members-yesterday">کاربران عضو شده دیروز: <b>'.intval($result['membersYesterday']).'</b></li>'.n;
	$html .= '<li id="counter-members-month">کاربران عضو شده ماه: <b>'.intval($result['membersMonth']).'</b></li>'.n;
	$html .= '<li id="counter-members-count">تعداد کل کاربران: <b>'.intval($result['membersCount']).'</b></li>'.n;	
	$html .= '<li id="counter-posts-count">تعداد کل پست ها: <b>'.intval($result['postsCount']).'</b></li>'.n;
	$html .= '<li id="counter-comments-count">تعداد کل نظرات: <b>'.intval($result['commentsCount']).'</b></li>'.n;
	$html .= isset($result['linksCount'])? '<li id="counter-links-count">تعداد تبادل لینک: <b>'.intval($result['linksCount']).'</b></li>'.n : null;
	$html .= '<li id="counter-yesterday-count">بازدید دیروز: <b>'.intval($result['yesterdayCount']).'</b></li>'.n;
	$html .= '<li id="counter-today-count">بازدید امروز: <b>'.intval($result['todayCount']).'</b></li>'.n;
	$html .= '<li id="counter-month-count">بازدید ماه: <b>'.intval($result['monthCount']).'</b></li>'.n;
	$html .= '<li id="counter-year-count">بازدید سال: <b>'.intval($result['yearCount']).'</b></li>'.n;
	$html .= '<li id="counter-total-count">بازدید کل: <b>'.intval($result['totalCount']).'</b></li>'.n;
	$html .= '</ul>';
	return $html;
}

function module_counter_run()
{
	global $d, $options, $member;
	require_once(root_dir.'modules/counter/functions.php');
	$_GET['b'] = get_param($_GET, 'b');
	switch($_GET['b'])
	{
		case 'browser':
		if (isset($_GET['c']))
			_browser_version();
		else
			_browser();
		break;

		case 'os':
		if (isset($_GET['c']))
			_os_version();
		else
			_os();
		break;

		case 'robots':
		_robots();
		break;
		
		case 'month':
		_month();
		break;

		case 'day':
		_day();
		break;

		case 'year':
		default:
		_year();
		break;
	}
}

function module_counter_update()
{
	global $d;
	require_once(root_dir.'modules/counter/lib/counter.class.php');
	$counter = counter::analyse_user_agent();

	if ($counter['robot'] !== FALSE)
	{
		$counter['browser'] = 'Bot';
		$counter['browser'] = 'robot.png';
		$counter['browser_version'] = FALSE;
	}

	$counter = $d->escapeString($counter);

	if (!empty($counter['browser_version']) && $d->numRows("SELECT `counter_name` FROM `#__counter` WHERE `counter_name`='Browser-".$counter['browser']."' AND `counter_version`='".$counter['browser_version']."'", true) <= 0)
	{
		$d->insert('counter', array('counter_name'=>'Browser-'.$counter['browser'], 'counter_value'=>'0', 'counter_version'=>$counter['browser_version']));
	}

	if (!empty($counter['os_version']) && $d->numRows("SELECT `counter_name` FROM `#__counter` WHERE `counter_name`='OS-".$counter['os']."' AND `counter_version`='".$counter['os_version']."'", true) <= 0)
	{
		$d->insert('counter', array('counter_name'=>'OS-'.$counter['os'], 'counter_value'=>'0', 'counter_version'=>$counter['os_version']));
	}

	if ($d->numRows("SELECT `counter_name` FROM `#__counter` WHERE `counter_name`='Year-".jdate('Y')."'", true) <= 0)
	{
	   $d->insert('counter', array('counter_name'=>'Year-'.jdate('Y'), 'counter_value'=>'0'));
	}

	if ($d->numRows("SELECT `counter_name` FROM `#__counter` WHERE `counter_name`='Month-".jdate('Y-m')."'", true) <= 0)
	{
	   $d->insert('counter', array('counter_name'=>'Month-'.jdate('Y-m'), 'counter_value'=>'0'));
	}

	if ($d->numRows("SELECT `counter_name` FROM `#__counter` WHERE `counter_name`='Day-".jdate('Y-m-d')."'", true) <= 0)
	{
	   $d->insert('counter', array('counter_name'=>'Day-'.jdate('Y-m-d'), 'counter_value'=>'0'));
	}

	/* Save on the databases the obtained values */

 	$query  = 'UPDATE `#__counter` SET `counter_value`=`counter_value`+1 WHERE ';
	$query .= "(counter_name='Browser-".$counter['browser']."' AND counter_version='')";
	$query .= !empty($counter['browser_version'])? " OR (counter_name='Browser-".$counter['browser']."' AND counter_version='".$counter['browser_version']."')" : null;
	$query .= " OR (counter_name='OS-".$counter['os']."' AND counter_version='')";
	$query .= !empty($counter['os_version'])? " OR (counter_name='OS-".$counter['os']."' AND counter_version='".$counter['os_version']."')" : null;
	$query .= !empty($counter['robot'])? " OR (counter_name='Robot-".$counter['robot']."' AND counter_version='') OR (counter_name='Browser-Bot' AND counter_version='')" : null;
	if (empty($counter['robot'])) // no robot
	{
		$query .= " OR (counter_name='Total')";
		$query .= " OR (counter_name='Year-".jdate('Y')."')";
		$query .= " OR (counter_name='Month-".jdate('Y-m')."')";
		$query .= " OR (counter_name='Day-".jdate('Y-m-d')."')";
	}
 	$d->query($query);
	unset($total, $query, $counter);
	
	/* session */
	
 	$d->delete('session', "`session_time` <= '".(time()-3600)."'");	
	$ip = get_ip();
	if (!empty($ip))
	{
		$page = nohtml($_SERVER['REQUEST_URI']);
		$page = ltrim($page, '/');
		$dirname = trim(path, '/');

		if ($dirname != '')
		{
			$strlen = apadana_strlen($dirname) + 1;
			if (apadana_substr($page, 0, $strlen) == $dirname.'/')
			{
				$page = apadana_substr($page, $strlen);
			}
			else
			{
				$strlen = apadana_strlen($dirname);
				if (apadana_substr($page, 0, $strlen) == $dirname)
				{
					$page = apadana_substr($page, $strlen);
				}
			}
		}	

		if (member) 
		{
			$member = member_name;
			$guest = 0;
		}
		else 
		{
			$member = $ip;
			$guest = 1;
		}
		
		$query = "SELECT `session_ip` FROM `#__session` WHERE `session_member`='".$d->escapeString($member)."' OR `session_ip`='".$d->escapeString($ip)."'";
		if ($d->numRows($query, true) <= 0) 
		{
			$d->insert('session', array(
				'session_member' => $member,
				'session_ip' => $ip,
				'session_guest' => $guest,
				'session_page' => $page,
				'session_time' => time(),
			));
		}
		else
		{
			$d->update('session', array(
				'session_member' => $member,
				'session_ip' => $ip,
				'session_guest' => $guest,
				'session_page' => $page,
				'session_time' => time(),
			), "`session_member`='".$d->escapeString($member)."' OR `session_ip`='".$d->escapeString($ip)."'", 1);	
		}
	}
	unset($ip, $member, $guest, $page, $dirname, $query, $strlen);
}

function module_counter_sitemap(&$sitemap)
{
	$sitemap->addItem(url('counter/browser'), 0, 'daily', '0.1');
	$sitemap->addItem(url('counter/os'), 0, 'daily', '0.1');
	$sitemap->addItem(url('counter/robots'), 0, 'daily', '0.1');
	$sitemap->addItem(url('counter/year'), 0, 'daily', '0.1');
}

?>