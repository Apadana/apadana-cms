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

if (member == 1 && group_admin == 1)
{
	redirect(admin_page);
}
	
function _index()
{
	global $options, $page, $d, $tpl;
	
	if (is_ajax())
	{
		exit('<font color=red>دوره ی مدیریتی شما پایان یافته لطفا مجدد وارد سیستم شوید!<font>');
	}
	
	$tpl->load('login.tpl');
	$tpl->assign(array(
		'{redirect}' => nohtml(current_url()),
	));
}

function _save()
{
	global $d;

	$r = 'no';
	$url = admin_page;
	$login = get_param($_POST, 'login');

	if (isset($login) && is_array($login) && count($login) && isset($login['username']) && isset($login['password']))
	{
		$login['username'] = apadana_strtolower(trim($login['username']));
		$login['password'] = $login['password'];

		$result = $d->query("SELECT * FROM #__members WHERE member_status='1' AND member_group!='4' AND member_name='".$d->escape_string($login['username'])."' LIMIT 1");
		$result = $d->fetch($result);
		if (is_alphabet($login['username']) && is_array($result) && count($result) && !empty($result['member_password']))
		{
			if ($result['member_password'] != member::password($login['password'])) // old password
			{
				$login['password'] = str_replace('\\', null, $login['password']);
				$login['password'] = md5('pars-'.sha1($d->escape_string($login['password'])).'-nuke');
			}
			else
			{
				$login['password'] = member::password($login['password']);
			}

			if ($result['member_password'] == $login['password'])
			{
				$loginKey = member::loginKey($result['member_password']);
				set_cookie('account', base64_encode($result['member_id'].'::'.md5($loginKey)));

				$d->update('members', array(
					'member_key' => $loginKey,
					'member_lastvisit' => time(),
					'member_visits' => $result['member_visits']+1,
					'member_lastip' => get_ip(),
				), "`member_name`='{$login['username']}'", 1);
				
				$r = 'yes';
				
				if (isset($login['redirect']) && validate_url($login['redirect']) && stristr($login['redirect'], domain))
				{
					$url = nohtml($login['redirect']);
				}
			}
		}
	}

	exit('{"result":"'.$r.'","url":"'.$url.'"}');
}

if (isset($_POST['login']) && is_array($_POST['login']))
	_save();
else
	_index();

?>