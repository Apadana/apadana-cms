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

function _default()
{
	global $page, $tpl, $d, $member, $member_groups;

	member::check_admin_page_access('account') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	require_once(engine_dir.'pagination.class.php');
	set_title('کاربران');

	$sort_arr = array('member_id', 'member_name', 'member_gender', 'member_visits', 'member_lastvisit');

	$order = get_param($_GET, 'order', 'DESC');
	$order = $order=='DESC'? 'DESC' : 'ASC';
	
	$sort = get_param($_GET, 'sort', 'member_id');
	$sort = !in_array($sort, $sort_arr)? 'member_id' : $sort;

	$total = get_param($_GET, 'total', 20);
	$total = $total<=0? 20 : $total;

	$_page = get_param($_GET, 'page', 1);
	$_page = $_page<=0? 1 : $_page;

	$search = get_param($_GET, 'search');
	$search = alphabet(urldecode($search));

	$total_m = $d->numRows("SELECT `member_id` FROM `#__members` ".(empty($search)? null : "WHERE member_name LIKE '%".$search."%'"), true);

	$pagination = new pagination($total_m, $total, $_page);

	$itpl = new template('modules/account/html/admin/index.tpl');

	$d->query("SELECT * FROM #__members ".(empty($search)? null : "WHERE member_name LIKE '%".$search."%'")." ORDER BY $sort $order LIMIT $pagination->Start, $pagination->End");
	if ($d->numRows() >= 1)
	{
		while($m = $d->fetch()) 
		{
			$itpl->add_for('members', array(
				'{odd-even}' => odd_even(),
				'{url}' => url('account/profile/'.$m['member_name']),
				'{id}' => $m['member_id'],
				'{token}' => member::token($member['member_key']),
				'{name}' => $m['member_name'],
				'{name-show}' => member::group_title($m['member_name'], $m['member_group']),
				'{visits}' => $m['member_visits'],
				'{lastvisit}' => $m['member_lastvisit'],
				'{lastvisit-show}' => jdate('j/m/Y g:i a', $m['member_lastvisit']),
				'{status}' => $m['member_status'],
				'{email}' => $m['member_email'],
				'{ip}' => $m['member_ip'],
				'{lastip}' => $m['member_lastip'],
				'{web}' => $m['member_web'],
				'{alias}' => $m['member_alias'],
				'{signature}' => $m['member_signature'],
				'{group}' => $m['member_group'],
				'{newsletter}' => $m['member_newsletter'],
				'{nationality}' => $m['member_nationality'],
				'{location}' => $m['member_location'],
				'{gender}' => $m['member_gender'],
				'{gender-male}' => $m['member_gender']=='male'? true : false,
				'replace' => array(
					'#\\[status\\](.*?)\\[/status\\]#s' => $m['member_status']? '\\1' : '',
					'#\\[not-status\\](.*?)\\[/not-status\\]#s' => !$m['member_status']? '\\1' : '',
					'#\\[gender-male\\](.*?)\\[/gender-male\\]#s' => $m['member_gender']=='male'? '\\1' : '',
					'#\\[not-gender-male\\](.*?)\\[/not-gender-male\\]#s' => $m['member_gender']!='male'? '\\1' : '',
				),
			));
		}

		$itpl->assign(array(
			'[members]' => null,
			'[/members]' => null,
		));
		$itpl->block('#\\[not-members\\](.*?)\\[/not-members\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-members]' => null,
			'[/not-members]' => null,
		));
		$itpl->block('#\\[members\\](.*?)\\[/members\\]#s', '');
	}

	$p = $pagination->build('{page}', true);
	if (is_array($p) && count($p)) 
	{	
		foreach($p as $link) 
		{
			if (!isset($link['page'])) continue;

			$itpl->add_for('pages', array(
				'{number}' => $link['number'],
				'replace' => array(
					'#\\[selected\\](.*?)\\[/selected\\]#s' => $link['number']==$_page? '\\1' : '',
				),
			));
		}

		$itpl->assign(array(
			'[pages]' => null,
			'[/pages]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[pages\\](.*?)\\[/pages\\]#s', '');
	}
	
	foreach($member_groups as $g)
	{
		$itpl->add_for('groups', array(
			'{id}' => $g['group_id'],
			'{name}' => $g['group_name'],
		));
	}
	
	if ($order=='DESC')
	{
		$itpl->assign(array(
			'[desc]' => null,
			'[/desc]' => null,
		));
		$itpl->block('#\\[asc\\](.*?)\\[/asc\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[asc]' => null,
			'[/asc]' => null,
		));
		$itpl->block('#\\[desc\\](.*?)\\[/desc\\]#s', '');
	}

	if ($sort=='member_id')
	{
		$itpl->block('#\\[member-(name|gender|visits|lastvisit)\\](.*?)\\[/member-\\1\\]#s', '\\2');
		$itpl->block('#\\[member-id\\](.*?)\\[/member-id\\]#s', '');
	}
	elseif ($sort=='member_name')
	{
		$itpl->block('#\\[member-(id|gender|visits|lastvisit)\\](.*?)\\[/member-\\1\\]#s', '\\2');
		$itpl->block('#\\[member-name\\](.*?)\\[/member-name\\]#s', '');
	}
	elseif ($sort=='member_gender')
	{
		$itpl->block('#\\[member-(id|name|visits|lastvisit)\\](.*?)\\[/member-\\1\\]#s', '\\2');
		$itpl->block('#\\[member-gender\\](.*?)\\[/member-gender\\]#s', '');
	}
	elseif ($sort=='member_visits')
	{
		$itpl->block('#\\[member-(id|name|gender|lastvisit)\\](.*?)\\[/member-\\1\\]#s', '\\2');
		$itpl->block('#\\[member-visits\\](.*?)\\[/member-visits\\]#s', '');
	}
	elseif ($sort=='member_lastvisit')
	{
		$itpl->block('#\\[member-(id|name|gender|visits)\\](.*?)\\[/member-\\1\\]#s', '\\2');
		$itpl->block('#\\[member-lastvisit\\](.*?)\\[/member-lastvisit\\]#s', '');
	}

	$itpl->assign(array(
		'{total}' => $total,
		'{search}' => $search,
		'{page}' => $_page,
		'{order}' => $order,
		'{sort}' => $sort,
	));

	if (is_ajax())
	{
		$itpl->display();
		define('no_template', true);
	}
	else
	{
		$tpl->assign('{content}', $itpl->get_var());
	}
	unset($itpl);
}

function _edit()
{
	global $page, $tpl, $d, $member, $member_groups;

	member::check_admin_page_access('account') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$message = false;
	$postMember = get_param($_POST, 'member', null, 1);

	if (isset($postMember) && is_array($postMember) && count($postMember) && isset($postMember['email']) && isset($postMember['newsletter']))
	{
		$member_info = member::info(intval($postMember['id']));

		if (!is_array($member_info) || !count($member_info))
			redirect(admin_page.'&module=account');
		
		$postMember['email'] = apadana_strtolower(nohtml($postMember['email']));
		$postMember['id'] = intval($postMember['id']);
		$postMember['group'] = intval($postMember['group']);
		$postMember['token'] = trim($postMember['token']);
		$postMember['pass1'] = trim($postMember['pass1']);
		$postMember['pass2'] = trim($postMember['pass2']);
		$options_account = account_options();
		if ($postMember['token'] != member::token($member['member_key']))
		{
			$message .= 'کد مجوز معتبر نیست!<br />';
		}

		if (!empty($postMember['pass1']) && !empty($postMember['pass2']))
		{
			if (empty($postMember['pass1']) || empty($postMember['pass2']))
			{
				$message .= 'اطلاعات را کامل نکرده اید!<br />';
			}

			if ($postMember['pass1'] != $postMember['pass2'])
			{
				$message .= 'پسورد های وارد شده مشابه نیستند!<br />';
			}

			if (apadana_strlen($postMember['pass1']) < $options_account['minPassword'])
			{
				$message .= 'حداقل طول پسورد باید '.$options_account['minPassword'].' حرف باشد!<br />';
			}

			if (stripos($postMember['pass1'], member_name) !== false)
			{
				$message .= 'نباید از نام کاربری خود در پسورد استفاده کنید!<br />';
			}
		
			if ($postMember['id'] == 1 && member_id != 1)
			{
				$message .= 'شرمنده رفیق، نمی تونی پسورد مدیرکل سایت رو تغییر بدی!<br />';
			}
		}

		if (!validate_email($postMember['email']))
		{
			$message .= 'ایمیل وارد شده معتبر نیست!';
		}
		else
		{
			if ($options_account['email'] == 1 && $member_info['member_email'] != $postMember['email'] && $d->numRows("SELECT `member_id` FROM `#__members` WHERE `member_email`='".$d->escapeString($postMember['email'])."'", true) >= 1)
			{
				$message .= 'این ایمیل قبلا ثبت شده، یک ایمیل دیگر انتخاب کنید!<br />';
			}
		}
		
		if (!isset($member_groups[$postMember['group']]) || !is_array($member_groups[$postMember['group']]) || !count($member_groups[$postMember['group']]))
		{
			$message .= 'گروه انتخاب شده برای کاربر معتبر نیست!<br>';
		}

		if ($postMember['id'] == 1 && $postMember['group'] != 1)
		{
			$message .= 'این کاربر باید مدیرکل سایت باشد!<br>';
		}
		else
		{
			if ($postMember['group'] == 5)
			{
				$message .= 'یک کاربر نمی تواند جزو گروه مهمان ها باشد!<br>';
			}

			if ($postMember['group'] != $member_info['member_group'] && $postMember['id'] == member_id && $postMember['id'] != 1)
			{
				$message .= 'شرمنده رفیق، نمی تونی گروه کاربری خودتو تغییر بدی!<br>';
			}
				
			if ($postMember['group'] == 1 && member_group != 1)
			{
				$message .= 'شما اجازه دادن دسترسی مدیرکل به کاربران را ندارید!<br>';
			}
		}

		if ($postMember['web'] != '' && !validate_url($postMember['web']))
		{
			$message .= 'آدرس وبسایت وارد شده معتبر نمی باشد!<br>';
		}

		require(engine_dir.'countries.inc.php');
		if (!isset($postMember['nationality']) || !in_array($postMember['nationality'], $countries))
			$postMember['nationality'] = 'Iran (Islamic Republic of)';
		unset($countries);
		
		$postMember['gender'] = isset($postMember['gender']) && $postMember['gender']=='male'? 'male' : 'female';

		if (!empty($message))
		{
			echo message($message, 'error');
		}
		else
		{
			$arr = array(
				'member_group' => intval($postMember['group']),
				'member_alias' => htmlencode($postMember['alias']),
				'member_signature' => htmlencode($postMember['signature']),
				'member_email' => nohtml($postMember['email']),
				'member_web' => nohtml($postMember['web']),
				'member_nationality' => nohtml($postMember['nationality']),
				'member_location' => htmlencode($postMember['location']),
				'member_gender' => nohtml($postMember['gender']),
				'member_newsletter' => intval($postMember['newsletter']),
			);

			if (!empty($postMember['pass1']) && !empty($postMember['pass2']))
			{
				$arr['member_password'] = member::password($postMember['pass1']);
				
				if (member_id == $postMember['id'])
				{
					$arr['member_key'] = member::loginKey($arr['member_password']);
					set_cookie('account', base64_encode(member_id.'::'.md5($arr['member_key'])) );
				}
			}
			
			$d->update('members', $arr, "`member_id`='".intval($postMember['id'])."'", 1);

			if ($d->affectedRows())
			{
				echo message('پروفایل کاربر با موفقیت ویرایش شد.', 'success');

				if ($member_groups[$postMember['group']]['group_superAdmin'] == 1)
				{
					echo message('این کاربر دسترسی مدیرکل را خواهد داشت!', 'info');
				}
				elseif ($member_groups[$postMember['group']]['group_admin'] == 1)
				{
					echo message('این کاربر به بخش مدیریت دسترسی خواهد داشت!', 'info');
				}
			}
			else
			{
				echo message('در ذخیره اطلاعات خطایی رخ داده، مجدد تلاش کنید!', 'error');
			}
		}
	}
	exit;
}

function _status()
{
	global $page, $tpl, $d;

	member::check_admin_page_access('account') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_GET, 'id', 0);
	$status = get_param($_GET, 'status', 0);

	if ($status == 1 && ($id == 1 || $id == member_id))
		exit('نمی توانید این کاربر را اخراج کنید!');

	$d->query("SELECT * FROM #__members WHERE `member_id`='$id' LIMIT 1");
	$data = $d->fetch();

	if (!is_array($data) || !count($data))
		exit('این کاربر وجود ندارد!');

	$d->update('members', array(
		'member_status' => $status==1? 0 : 1,
	), "`member_id`='{$id}'", 1);	

	if ($d->affectedRows())
	{
		exit($status==1? 'no' : 'ok');
	}
	else
	{
		exit('در ذخیره خطایی رخ داده مجدد تلاش کنید!');
	}
	die;
}

######################## OPTIONS FUNCTION ########################

function _options()
{
	global $d, $page;

	member::check_admin_page_access('account') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	
	$op = get_param($_POST, 'options', 1);
	if (isset($op) && is_array($op) && count($op))
	{
		$op['register'] = !isset($op['register']) || $op['register']==0? 0 : 1;
		$op['members'] = !isset($op['members']) || $op['members']==0? 0 : 1;
		$op['members-total'] = $op['members-total']<=0? 1 : $op['members-total'];
		$op['avatar'] = !isset($op['avatar']) || $op['avatar']==0? 0 : 1;
		$op['avatarsize'] = $op['avatarsize']<=10? 15 : $op['avatarsize'];

		$op['maxavatardims-1'] = $op['maxavatardims-1']<=80? 80 : $op['maxavatardims-1'];
		$op['maxavatardims-2'] = $op['maxavatardims-2']<=80? 80 : $op['maxavatardims-2'];
		$op['maxavatardims'] = $op['maxavatardims-1'].'x'.$op['maxavatardims-2'];
		unset($op['maxavatardims-1'], $op['maxavatardims-2']);
		
		$op['minUsername'] = $op['minUsername']<=3? 3 : $op['minUsername'];
		$op['minPassword'] = $op['minPassword']<=4? 4 : $op['minPassword'];
		$op['email'] = !isset($op['email']) || $op['email']==0? 0 : 1;

		$d->update('options', array(
			'option_value' => serialize($op)
		), "`option_name`='account'", 1);	
		
		if ($d->affectedRows())
		{
			remove_cache('options-account');
			echo message('تنظیمات با موفقیت ذخیره شد!', 'success');
		}
		else
		{
			echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
		}
		die;
	}

	set_title('تنظیمات سامانه کاربری');

	$op = account_options();
	$op['maxavatardims'] = explode('x',  $op['maxavatardims']);
	$itpl = new template('modules/account/html/admin/options.tpl');

	if ($op['register'])
	{
		$itpl->assign(array(
			'[register-checked]' => null,
			'[/register-checked]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[register-checked\\](.*?)\\[/register-checked\\]#s', '');
	}
	
	if ($op['members'])
	{
		$itpl->assign(array(
			'[members-checked]' => null,
			'[/members-checked]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[members-checked\\](.*?)\\[/members-checked\\]#s', '');
	}
	
	if ($op['avatar'])
	{
		$itpl->assign(array(
			'[avatar-checked]' => null,
			'[/avatar-checked]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[avatar-checked\\](.*?)\\[/avatar-checked\\]#s', '');
	}

	if ($op['email'])
	{
		$itpl->assign(array(
			'[email-checked]' => null,
			'[/email-checked]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[email-checked\\](.*?)\\[/email-checked\\]#s', '');
	}
	
	$itpl->assign(array(
		'{members-total}' => $op['members-total'],
		'{avatarsize}' => $op['avatarsize'],
		'{maxavatardims-1}' => $op['maxavatardims'][0],
		'{maxavatardims-2}' => $op['maxavatardims'][1],
		'{minUsername}' => $op['minUsername'],
		'{minPassword}' => $op['minPassword'],
	));

	set_content(false, $itpl->get_var());
}

?>