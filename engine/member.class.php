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

class member
{
    static private $memberSave;
    static private $infoSave;
    static private $info;
	
	static function is($type = 'member')
	{
		if (!isset($_COOKIE['account']) || empty($_COOKIE['account']))
		{
			return false;
		}

		if (isset(self::$memberSave) AND isset(self::$infoSave) AND is_array(self::$infoSave) AND count(self::$infoSave) AND $type == 'info')
		{
			return self::$infoSave;
		}
		elseif (isset(self::$memberSave))
		{
			return self::$memberSave;
		}

		global $d;
		
		$member = base64_decode($_COOKIE['account']);
		$member = explode('::', $member, 2);

		if (!isset($member[0]) || !isset($member[1]) || !isnum($member[0]) || !is_alphabet($member[1]) || apadana_strlen($member[1]) != 32)
		{
			return false;
		}

		$member[0] = (int) $member[0];
		$member[1] = nohtml($member[1]);

		if (!empty($member[0]) && !empty($member[1]))
		{
			$query = sprintf("SELECT * FROM #__members WHERE member_id='%d' AND member_status='1' LIMIT 1", $member[0]);
			$query = $d->query($query);
			$result = $d->fetch($query);
			$loginKey = md5(self::loginKey($result['member_password']));

			if (!empty($result['member_password']) && $loginKey == $member[1] && $loginKey == md5($result['member_key']))
			{
				if ($result['member_lastvisit']+1200 < time() || $result['member_lastip'] != get_ip())
				{
					$d->update('members', array(
						'member_lastip' => get_ip(),
						'member_lastvisit' => time(),
					), "`member_id`='".$result['member_id']."'", 1);
				}

				self::$info['id'][$result['member_id']] = $result;
				self::$info['name'][$result['member_name']] = &self::$info['id'][$result['member_id']];
				
				self::$infoSave = $result;
				self::$memberSave = true;

				unset($result);

				if ($type == 'info')
				{
					return self::$infoSave;
				}
				else
				{
					return self::$memberSave;
				}
			}
		}
		un_set_cookie('account');

		return self::$memberSave = false;
	}

	static function check_admin_page_access($right)
	{
		$check = group_admin && in_array($right, explode(',', group_rights))? true : false;

		if (!$check && !group_super_admin)
		{
			return false;
		}
		elseif ($check || (!$check && group_super_admin))
		{
			return true;
		}
	}

	static function avatar($avatar = null)
	{
		global $options;

		$avatar = !empty($avatar) && file_exists(root_dir.$avatar) && is_readable(root_dir.$avatar)? $avatar : false;

		if (empty($avatar))
		{
			$avatar = file_exists(root_dir.'templates/'.$options['theme'].'/images/no-avatar.png')? 'templates/'.$options['theme'].'/images/no-avatar.png' : 'engine/images/no-avatar.png';
		}

		return url . $avatar;
	}

	static function group_title($member_name, $member_group)
	{
		global $member_groups;

		if (isset($member_groups[$member_group]) && is_array($member_groups[$member_group]) && count($member_groups[$member_group]))
		{
			$member_name = str_replace('{group-name}', $member_groups[$member_group]['group_name'], $member_name);
			$title = str_replace('{name}', $member_name, $member_groups[$member_group]['group_title']);
		}
		else
		{
			$member_name = str_replace('{group-name}', 'ناشناخته!', $member_name);
			$title = $member_name;
		}

		return $title;
	}

	static function group_icon($group)
	{
		global $member_groups, $options;

		if (isset($member_groups[$group]) && is_array($member_groups[$group]) && count($member_groups[$group]))
		{
			$icon = str_replace('{theme}', $options['theme'], $member_groups[$group]['group_icon']);
		}
		else
		{
			$icon = 'engine/images/groups/5.png';
		}

		return url . $icon;
	}

	static function info($id = false, $username = false)
	{
		global $d;

		$id = intval($id);
		$username = alphabet($username);

		if ($id && isset(self::$info['id'][$id]))
		{
			return self::$info['id'][$id];
		}
		elseif ($username && isset(self::$info['name'][$username]))
		{
			return self::$info['name'][$username];
		}

		if ($id)
		{
			$where = "`member_id`='".$id."'";
		}
		else
		{
			$where = "`member_name`='".$username."'";
		}

		$q = $d->query("SELECT * FROM `#__members` WHERE $where LIMIT 1");
		if ($d->num_rows($q) >= 1)
		{
			$u = $d->fetch($q);
			self::$info['id'][$u['member_id']] = $u;
			self::$info['name'][$u['member_name']] = &self::$info['id'][$u['member_id']];
			$d->free_result($q);
			return $u;
		}
		else
		{
			return false;
		}
	}

	static function exists($username)
	{
		global $d;

		if (is_alphabet($username) && $d->num_rows("SELECT `member_id` FROM `#__members` WHERE `member_name`='".$username."'", true) >= 1)
		{
			return true;
		}
		return false;
	}

	static function loginKey($password)
	{
		return md5($_SERVER['HTTP_USER_AGENT'] . sitekey . $password . $_SERVER['SERVER_SOFTWARE']);
	}

	static function token($login_key)
	{
		global $d;

		return sha1(substr($login_key, 8, 28).sitekey);
	}

	static function password($password)
	{
		global $d;

		$password = str_replace('\\', null, $password);
		return md5(sha1($d->escape_string($password)));
    }
}

?>