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

function _register()
{
	if (member)
		redirect(url('account'));

	require_once(engine_dir.'captcha.function.php');

	global $d, $tpl, $page;

	$message = $success = false;
	$options_account = account_options();
	$register = get_param($_POST, 'register');

	($hook = get_hook('account_register_start'))? eval($hook) : null;

	if ($options_account['register'] == 1 && isset($register) && is_array($register) && count($register) && isset($register['name']) && isset($register['email']) && isset($register['password']) && isset($register['password-repeat']))
	{
		$register['name'] = strtolower($register['name']);
		$register['email'] = nohtml($register['email']);
		$register['password'] = ($register['password']);
		$register['password-repeat'] = ($register['password-repeat']);
		
		if (!validate_captcha('register', @$register['captcha']))
		{
			$message .= 'کد امنیتی را صحیح وارد نکرده اید!<br />';
		}

		if (!validate_email($register['email']))
		{
			$message .= 'ایمیل وارد شده معتبر نیست!<br />';
		}
		else
		{
			if ($options_account['email'] == 1 && $d->num_rows("SELECT `member_id` FROM `#__members` WHERE `member_email`='".$d->escape_string($register['email'])."'", true) >= 1)
			{
				$message .= 'این ایمیل قبلا ثبت شده است، یک ایمیل دیگر انتخاب کنید!<br />';
			}
		}

		if ($register['password'] != $register['password-repeat'])
		{
			$message .= 'پسوردهای وارد شده مشابه نیستند!<br />';
		}

		if (apadana_strlen($register['password']) < $options_account['minPassword'])
		{
			$message .= 'حداقل طول پسورد باید '.$options_account['minPassword'].' حرف باشد!<br />';
		}

		if (stripos($register['password'], $register['name']) !== false)
		{
			$message .= 'نباید از نام کاربری خود در پسورد استفاده کنید!<br />';
		}
		
		if (!is_alphabet($register['name']))
		{
			$message .= 'نام کاربری فقط می تواند شامل حروف و اعداد انگلیسی باشد!<br />';
		}
		else
		{
			if (apadana_strlen($register['name']) < $options_account['minUsername'])
			{
				$message .= 'حداقل طول نام کاربری باید '.$options_account['minUsername'].' حرف باشد!<br />';
			}
			elseif (apadana_strlen($register['name']) > 40)
			{
				$message .= 'نام کاربری نباید از 40 حرف بیشتر باشد!<br />';
			}
			else
			{
				if (member::exists($register['name']))
				{
					$message .= 'این نام کاربری قبلا ثبت شده است!';
				}
			}
		}

		if (!empty($message))
		{
			$message = message($message, 'error');
		}
		else
		{
			$register['password'] = member::password($register['password']);
			$loginKey = member::loginKey($register['password']);

			$id = $d->insert('members', array(
				'member_name' => $register['name'],
				'member_alias' => $register['name'],
				'member_email' => $register['email'],
				'member_password' => $register['password'],
				'member_date' => time(),
				'member_lastvisit' => time(),
				'member_ip' => get_ip(),
				'member_lastip' => get_ip(),
				'member_group' => 4,
				'member_key' => $loginKey,
			));

			if ($d->affected_rows())
			{
				remove_captcha('register');
				require_once(engine_dir.'mail.function.php');
				global $options;
				$success = true;
				$message = message('عملیات عضویت شما با موفقیت پایان یافته است، شما در حساب کاربری خود قرار دارید!', 'success');
				set_cookie('account', base64_encode($id.'::'.md5($loginKey)) );
				refresh(url('account'), 5);
				$Body  = 'عضویت شما با موفقیت انجام شد.<br />';				
				$Body .= 'نام کاربری شما: '.$register['name'].'<br />';				
				$Body .= 'ایمیل: '.$register['email'];				
				send_mail($register['name'], $register['email'], $options['title'], $options['mail'], 'عضویت در سایت '.$options['title'], $Body);

				($hook = get_hook('account_register_save'))? eval($hook) : null;
			}
			else
			{
				$message = message('در ذخیره اطلاعات خطایی رخ داده، مجدد تلاش کنید!', 'error');
			}
		}
	}
	else
	{
		$message  = 'نام کاربری فقط می تواند شامل حروف و اعداد انگلیسی باشد.<br />';
		$message .= 'حداقل طول نام کاربری باید '.$options_account['minUsername'].' حرف باشد.<br />';
		$message .= 'حداقل طول پسورد باید '.$options_account['minPassword'].' حرف باشد.<br />';
		$message .= 'عضویت در سایت به معنای پذیرش <a href="'.url('rules').'"><u>قوانین</u></a> سایت خواهد بود.';
		$message  = message($message, 'info');
	}
	
	// end save

	set_title('عضویت در سایت');
	set_meta('description', 'عضویت در سایت', 'add');
	set_canonical(url('account/register'));

	if ($options_account['register'] == 1)
	{
		$html = $message;
		if (!$success)
		{
			$file = get_tpl(root_dir.'modules/account/html/||register.tpl', template_dir.'||account/register.tpl');
			$itpl = new template($file[1], $file[0]);
			$itpl->assign(array(
				'{name}' => isset($register['name'])? htmlspecialchars($register['name']) : null,
				'{email}' => isset($register['email'])? htmlspecialchars($register['email']) : null,
				'{captcha}' => create_captcha('register'),
			));
			$html .= $itpl->get_var();
			unset($itpl);
		};
	}
	else
	{
		$html = message('متاسفیم، تا اطلاع ثانوی امکان ثبت عضو جدید وجود ندارد.', 'info');
	}

	($hook = get_hook('account_register_end'))? eval($hook) : null;

	if (!isset($file[2])) set_content('عضویت در سایت', $html); else $tpl->assign('{content}', $html);	
	unset($success, $message, $html, $register);
}

function _login()
{
	if (member)
		redirect(url('account'));

	global $d, $tpl, $page;

	$msg = false;
	$login = get_param($_POST, 'login');

	($hook = get_hook('account_login_start'))? eval($hook) : null;

	if (isset($login) && is_array($login) && count($login) && isset($login['username']) && isset($login['password']) && isset($login['submit']))
	{
		$login['username'] = trim($login['username']);
		$login['password'] = $login['password'];

		if (is_alphabet($login['username']) && !empty($login['password']))
		{
			$result = $d->query("SELECT * FROM #__members WHERE member_name='{$login['username']}' LIMIT 1");
			$result = $d->fetch($result);
			if (is_array($result) && count($result) && !empty($result['member_password']))
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
					if ($result['member_status'] == 1)
					{
						$loginKey = member::loginKey($result['member_password']);
						set_cookie('account', base64_encode($result['member_id'].'::'.md5($loginKey)));
						$d->update('members', array(
							'member_key' => $loginKey,
							'member_lastvisit' => time(),
							'member_visits' => $result['member_visits']+1,
							'member_lastip' => get_ip(),
						), "`member_name`='{$login['username']}'", 1);

						($hook = get_hook('account_login_success'))? eval($hook) : null;

						redirect(url('account'));
					}
					else
					{
						$msg = message('شما از سایت اخراج شده اید!', 'error');
					}
				}
				else
				{
					$msg = message('پسورد و یا نام کاربری را اشتباه وارد کرده اید!', 'error');
				}
			}
			else
			{
				$msg = message('پسورد و یا نام کاربری را اشتباه وارد کرده اید!', 'error');
			}
		}
		else
		{
			$msg = message('اطلاعات وارد شده معتبر نمی باشد!!', 'error');
		}
	}

	set_title('ورود به حساب کاربری');
	set_meta('description', 'ورود به حساب کاربری', 'add');
	set_canonical(url('account/login'));

	$file = get_tpl(root_dir.'modules/account/html/||login.tpl', template_dir.'||account/login.tpl');
	$itpl = new template($file[1], $file[0]);
	if (!empty($msg))
	{
		$itpl->assign(array(
			'{message}' => $msg,
			'[message]' => null,
			'[/message]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[message\\](.*?)\\[/message\\]#s', '');
	}

	($hook = get_hook('account_login_end'))? eval($hook) : null;

	if (!isset($file[2])) set_content('ورود به حساب کاربری', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($msg, $html, $result);
}

function _logout()
{
	if (!member)
		redirect(url('account/login'));

	un_set_cookie('account');

	global $d;

	$d->update('members', array(
		'member_key' => null,
		'member_lastip' => get_ip(),
		'member_lastvisit' => time(),
	), "`member_id`='".member_id."'", 1);

	($hook = get_hook('account_logout'))? eval($hook) : null;

	redirect(url('account/login'));
}

function _members()
{
	global $d, $tpl, $page;
	$get_pages = get_param($_GET, 'c', 1);

	$options_account = account_options();

	($hook = get_hook('account_members'))? eval($hook) : null;

	set_title('لیست کاربران');
	set_meta('description', 'لیست کاربران', 'add');
	set_canonical(url('account/members'));

	if ($options_account['members'] == 1)
	{	
		($hook = get_hook('account_members_start'))? eval($hook) : null;

		require_once(engine_dir.'pagination.class.php');
		$total = $d->num_rows("SELECT * FROM `#__members`", true);
		$pagination = new pagination($total, intval($options_account['members-total']), $get_pages);

		if ($get_pages > $pagination->Pages)
		{
			redirect(url('account/members'));
		}
		
		$query = sprintf("SELECT * FROM #__members ORDER BY member_id DESC LIMIT %d, %d", $pagination->Start, $pagination->End);
		$members = $d->get_row($query);

		if ($get_pages > 1 && $get_pages <= $pagination->Pages)
		{
			set_title('صفحه '.translate_number($get_pages,'fa'));
			set_canonical(url('account/members/'.$get_pages));
		}

		$list = null;
		$file = get_tpl(root_dir.'modules/account/html/||members.tpl', template_dir.'||account/members.tpl');
		$itpl = new template($file[1], $file[0]);

		foreach($members as $m)
		{
			$name = member::group_title($m['member_name'], $m['member_group']);
			$name = '<a href="'.url('account/profile/'.$m['member_name']).'" title="مشاهده پروفایل '.$m['member_name'].'">'.$name.'</a>';

			$itpl->add_for('list', array(
				'{odd-even}' => odd_even(),
				'{name}' => $name,
				'{name2}' => $m['member_name'],
				'{visits}' => $m['member_visits'],
				'{past-time}' => get_past_time($m['member_lastvisit']),
				'{lastvisit}' => jdate('j/m/Y g:i a', $m['member_lastvisit']),
			));
		}

		($hook = get_hook('account_members_end'))? eval($hook) : null;

		if (!isset($file[2])) set_content('لیست کاربران', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
		$pagination->build(url('account/members/{page}'));
	}
	else
	{
		set_content('لیست کاربران', message('نمایش لیست کاربران سایت غیرفعال می باشد.', 'info'));
	}
	
	unset($members, $m, $get_pages, $total, $pagination, $query, $options_account, $name, $itpl);
}

function _profile()
{
	global $d, $member_groups,$tpl;
	set_title('پروفایل');
	$username = get_param($_GET, 'c');
	$member = is_alphabet($username)? member::info(false, $username) : false;

	if (!$member)
	{
		set_title('خطا');
		set_canonical(url('account'));
		set_content('کاربر یافت نشد!', message('کاربری با نام کاربری '.$username.' در سیستم یافت نشد!', 'error'));
	}
	else
	{
		($hook = get_hook('account_profile_start'))? eval($hook) : null;

		set_title($username);
		set_canonical(url('account/profile/'.$member['member_name']));

		$file = get_tpl(root_dir.'modules/account/html/||profile.tpl', template_dir.'||account/profile.tpl');
		$itpl = new template($file[1], $file[0]);
		$itpl->assign(array(
			'{avatar}' => member::avatar($member['member_avatar']),
			'{name}' => $member['member_name'],
			'{group}' => member::group_title('{group-name}', $member['member_group']),
			'{group-name}' => isset($member_groups[$member['member_group']]['group_name'])? $member_groups[$member['member_group']]['group_name'] : null,
			'{group-icon}' => member::group_icon($member['member_group']),
			'{alias}' => empty($member['member_alias'])? 'بدون نام مستعار' : $member['member_alias'],
			'{nationality}' => $member['member_nationality'],
			'{location}' => $member['member_location'],
			'{gender}' => $member['member_gender']=='male'? '<img src="'.url.'engine/images/icons/gender.png" alt="gender male" />&nbsp;مرد' : '<img src="'.url.'engine/images/icons/gender-female.png" alt="gender female" />&nbsp;زن',
			'{date}' => jdate('l j F Y ساعت g:i A', $member['member_date']),
			'{lastvisit}' => '<span title="'.jdate('l j F Y ساعت g:i A', $member['member_lastvisit']).'">'.get_past_time($member['member_lastvisit']).'</span>',
			'{visits}' => $member['member_visits'],
			'{web}' => validate_url($member['member_web'])? $member['member_web'] : false,
			'{signature}' => !empty($member['member_signature'])? nl2br($member['member_signature']) : false,
		));
		
		if (validate_url($member['member_web']))
		{
			$itpl->assign(array(
				'[web]' => null,
				'[/web]' => null,
			));
			$itpl->block('#\\[not-web\\](.*?)\\[/not-web\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[not-web]' => null,
				'[/not-web]' => null,
			));
			$itpl->block('#\\[web\\](.*?)\\[/web\\]#s', '');
		}
		
		if ($member['member_status'] == 1)
		{
			$itpl->assign(array(
				'[active]' => null,
				'[/active]' => null,
			));
			$itpl->block('#\\[banned\\](.*?)\\[/banned\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[banned]' => null,
				'[/banned]' => null,
			));
			$itpl->block('#\\[active\\](.*?)\\[/active\\]#s', '');
		}
		
		if (!empty($member['member_location']))
		{
			$itpl->assign(array(
				'[location]' => null,
				'[/location]' => null,
			));
			$itpl->block('#\\[not-location\\](.*?)\\[/not-location\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[not-location]' => null,
				'[/not-location]' => null,
			));
			$itpl->block('#\\[location\\](.*?)\\[/location\\]#s', '');
		}
		
		if (!empty($member['member_signature']))
		{
			$itpl->assign(array(
				'[signature]' => null,
				'[/signature]' => null,
			));
			$itpl->block('#\\[not-signature\\](.*?)\\[/not-signature\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[not-signature]' => null,
				'[/not-signature]' => null,
			));
			$itpl->block('#\\[signature\\](.*?)\\[/signature\\]#s', '');
		}

		($hook = get_hook('account_profile_end'))? eval($hook) : null;

		if (!isset($file[2])) set_content('پروفایل', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
		unset($itpl);
	}
}

function _index()
{
	global $d, $member_groups, $member,$tpl;

	if (!member)
		redirect(url('account/login'));

	($hook = get_hook('account_index_start'))? eval($hook) : null;

	set_title('سامانه کاربری');
	_menu();

	$unread_messages = is_module('private-messages')? private_messages() : array();
	$unread_messages = isset($unread_messages['newpms'])? (int) $unread_messages['newpms'] : 0;

	$file = get_tpl(root_dir.'modules/account/html/||index.tpl', template_dir.'||account/index.tpl');
	$itpl = new template($file[1], $file[0]);
	
	$itpl->assign(array(
		'{avatar}' => member::avatar($member['member_avatar']),
		'{name}' => $member['member_name'],
		'{group}' => member::group_title('{group-name}', $member['member_group']),
		'{group-name}' => isset($member_groups[$member['member_group']]['group_name'])? $member_groups[$member['member_group']]['group_name'] : null,
		'{group-icon}' => member::group_icon($member['member_group']),
		'{unread-messages}' => $unread_messages <= 0? false : $unread_messages,
		'{alias}' => empty($member['member_alias'])? 'بدون نام مستعار' : $member['member_alias'],
		'{nationality}' => $member['member_nationality'],
		'{location}' => $member['member_location'],
		'{gender}' => $member['member_gender']=='male'? '<img src="'.url.'engine/images/icons/gender.png" alt="gender male" />&nbsp;مرد' : '<img src="'.url.'engine/images/icons/gender-female.png" alt="gender female" />&nbsp;زن',
		'{date}' => jdate('l j F Y ساعت g:i A', $member['member_date']),
		'{lastvisit}' => jdate('l j F Y ساعت g:i A', $member['member_lastvisit']),
		'{visits}' => $member['member_visits'],
		'{web}' => validate_url($member['member_web'])? $member['member_web'] : false,
		'{signature}' => !empty($member['member_signature'])? nl2br($member['member_signature']) : false,
	));
	
	if ($unread_messages > 0)
	{
		$itpl->assign(array(
			'[unread-messages]' => null,
			'[/unread-messages]' => null,
		));
		$itpl->block('#\\[not-unread-messages\\](.*?)\\[/not-unread-messages\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-unread-messages]' => null,
			'[/not-unread-messages]' => null,
		));
		$itpl->block('#\\[unread-messages\\](.*?)\\[/unread-messages\\]#s', '');
	}
	
	if (validate_url($member['member_web']))
	{
		$itpl->assign(array(
			'[web]' => null,
			'[/web]' => null,
		));
		$itpl->block('#\\[not-web\\](.*?)\\[/not-web\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-web]' => null,
			'[/not-web]' => null,
		));
		$itpl->block('#\\[web\\](.*?)\\[/web\\]#s', '');
	}

	if (!empty($member['member_location']))
	{
		$itpl->assign(array(
			'[location]' => null,
			'[/location]' => null,
		));
		$itpl->block('#\\[not-location\\](.*?)\\[/not-location\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-location]' => null,
			'[/not-location]' => null,
		));
		$itpl->block('#\\[location\\](.*?)\\[/location\\]#s', '');
	}

	if (!empty($member['member_signature']))
	{
		$itpl->assign(array(
			'[signature]' => null,
			'[/signature]' => null,
		));
		$itpl->block('#\\[not-signature\\](.*?)\\[/not-signature\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-signature]' => null,
			'[/not-signature]' => null,
		));
		$itpl->block('#\\[signature\\](.*?)\\[/signature\\]#s', '');
	}
	
	($hook = get_hook('account_index_end'))? eval($hook) : null;

	if (!isset($file[2])) set_content('اطلاعات حساب کاربری شما', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($itpl);
}

function _profile_edit()
{
	if (!member)
		redirect(url('account/login'));

	global $d, $tpl, $page, $member;

	$message = false;
	$profileEdit = get_param($_POST, 'profileEdit', null, 1);

	($hook = get_hook('account_profile_edit_start'))? eval($hook) : null;

	if (isset($profileEdit) && is_array($profileEdit) && count($profileEdit) && isset($profileEdit['email']) && isset($profileEdit['newsletter']) && isset($profileEdit['submit']))
	{
		$profileEdit['email'] = strtolower(nohtml($profileEdit['email']));
		$options_account = account_options();

		if (!validate_email($profileEdit['email']))
		{
			$message .= 'ایمیل وارد شده معتبر نیست!';
		}
		else
		{
			if ($options_account['email'] == 1 && $member['member_email'] != $profileEdit['email'] && $d->num_rows("SELECT `member_id` FROM `#__members` WHERE `member_email`='".$d->escape_string($profileEdit['email'])."'", true) >= 1)
			{
				$message .= 'این ایمیل قبلا ثبت شده، یک ایمیل دیگر انتخاب کنید!';
			}
		}
		
		if (!isset($profileEdit['web']) || !validate_url($profileEdit['web']))
		{
			$profileEdit['web'] = null;
		}

		require(engine_dir.'countries.inc.php');
		if (!isset($profileEdit['nationality']) || !in_array($profileEdit['nationality'], $countries))
			$profileEdit['nationality'] = 'Iran (Islamic Republic of)';
		unset($countries);
		
		$profileEdit['gender'] = isset($profileEdit['gender']) && $profileEdit['gender'] == 'male'? 'male' : 'female';

		if (!empty($message))
		{
			$message = message($message, 'error');
		}
		else
		{
			$d->update('members', array(
				'member_alias' => htmlencode($profileEdit['alias']),
				'member_signature' => htmlencode($profileEdit['signature']),
				'member_email' => nohtml($profileEdit['email']),
				'member_web' => nohtml($profileEdit['web']),
				'member_nationality' => nohtml($profileEdit['nationality']),
				'member_location' => isset($profileEdit['location'])? htmlencode($profileEdit['location']) : null,
				'member_gender' => $profileEdit['gender'],
				'member_newsletter' => $profileEdit['newsletter'] == 1? 1 : 0
			), "`member_id`='".member_id."'", 1);

			if ($d->affected_rows())
			{
				$message = message('پروفایل شما با موفقیت ویرایش شد.', 'success');
				redirect(url('account/profile-edit'));
			}
			else
			{
				$message = message('در ذخیره اطلاعات خطایی رخ داده، مجدد تلاش کنید!', 'error');
			}
		}
	}

	// end save

	set_title('ویرایش پروفایل');
	_menu();

	require(engine_dir.'countries.inc.php');
	$array = array();
	foreach ($countries as $country)
		$array[$country] = $country;
	
	$file = get_tpl(root_dir.'modules/account/html/||profile-edit.tpl', template_dir.'||account/profile-edit.tpl');
	$itpl = new template($file[1], $file[0]);
	$itpl->assign(array(
		'{name}' => $member['member_name'],
		'{alias}' => $member['member_alias'],
		'{location}' => $member['member_location'],
		'{nationality}' => html::select('profileEdit[nationality]', $array, $member['member_nationality'], 'dir="ltr"'),
		'{gender}' => html::radio('profileEdit[gender]', array('male' => 'مرد', 'female' => 'زن'), $member['member_gender']=='male'? 'male' : 'female'),
		'{web}' => validate_url($member['member_web'])? $member['member_web'] : 'http://',
		'{newsletter}' => html::radio('profileEdit[newsletter]', array(1 => 'بله', 0 => 'خیر'), intval($member['member_newsletter'])),
		'{email}' => $member['member_email'],
		'{signature}' => $member['member_signature'],
	));
	if (!empty($message))
	{
		$itpl->assign(array(
			'{message}' => $message,
			'[message]' => null,
			'[/message]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[message\\](.*?)\\[/message\\]#s', '');
	}

	($hook = get_hook('account_profile_edit_end'))? eval($hook) : null;

	if (!isset($file[2])) set_content('ویرایش پروفایل', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var(),'add');	
	unset($itpl, $countries, $array);
}

function _change_password()
{
	if (!member)
		redirect(url('account/login'));

	global $d, $tpl, $page, $member;

	$message = false;
	$changePassword = get_param($_POST, 'changePassword');

	($hook = get_hook('account_change_password_start'))? eval($hook) : null;

	if (isset($changePassword) && is_array($changePassword) && count($changePassword) && isset($changePassword['pass']) && isset($changePassword['pass1']) && isset($changePassword['pass2']))
	{
		$changePassword['pass'] = ($changePassword['pass']);
		$changePassword['pass1'] = ($changePassword['pass1']);
		$changePassword['pass2'] = ($changePassword['pass2']);
		$options_account = account_options();

		if (empty($changePassword['pass']) || empty($changePassword['pass1']) || empty($changePassword['pass2']))
		{
			$message .= 'اطلاعات را کامل نکرده اید!<br />';
		}

		if ($member['member_password'] != member::password($changePassword['pass'])) // old password
		{
			$changePassword['pass'] = str_replace('\\', null, $changePassword['pass']);
			$changePassword['pass'] = md5('pars-'.sha1($d->escape_string($changePassword['pass'])).'-nuke');
		}
		else
		{
			$changePassword['pass'] = member::password($changePassword['pass']);
		}
		if ($changePassword['pass'] != $member['member_password'])
		{
			$message .= 'پسورد فعلی را صحیح وارد نکرده اید!<br />';
		}

		if ($changePassword['pass1'] != $changePassword['pass2'])
		{
			$message .= 'پسورد های وارد شده مشابه نیستند!<br />';
		}

		if (apadana_strlen($changePassword['pass1']) < $options_account['minPassword'])
		{
			$message .= 'حداقل طول پسورد باید '.$options_account['minPassword'].' حرف باشد!<br />';
		}

		if (stripos($changePassword['pass1'], member_name) !== false)
		{
			$message .= 'نباید از نام کاربری خود در پسورد استفاده کنید!';
		}

		if (!empty($message))
		{
			$message = message($message, 'error');
		}
		else
		{
			$changePassword['pass1'] = member::password($changePassword['pass1']);
			$loginKey = member::loginKey($changePassword['pass1']);
			set_cookie('account', base64_encode(member_id.'::'.md5($loginKey)) );

			$d->update('members', array(
				'member_password' => $changePassword['pass1'],
				'member_key' => $loginKey,
				'member_lastvisit' => time(),
				'member_lastip' => get_ip(),
			), "`member_id`='".member_id."'", 1);

			if ($d->affected_rows())
			{
				$message = message('پسورد شما با موفقیت تغییر کرد.', 'success');
			}
			else
			{
				$message = message('در ذخیره اطلاعات خطایی رخ داده، مجدد تلاش کنید!', 'error');
			}
		}
	}

	// end save

	set_title('تغییر پسورد');
	_menu();
	$file = get_tpl(root_dir.'modules/account/html/||change-password.tpl', template_dir.'||account/change-password.tpl');
	$itpl = new template($file[1], $file[0]);
	if (!empty($message))
	{
		$itpl->assign(array(
			'{message}' => $message,
			'[message]' => null,
			'[/message]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[message\\](.*?)\\[/message\\]#s', '');
	}

	($hook = get_hook('account_change_password_end'))? eval($hook) : null;

	if (!isset($file[2])) set_content('تغییر پسورد', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var(),'add');	
	unset($itpl, $message, $changePassword);
}

function _change_avatar()
{
	if (!member)
		redirect(url('account/login'));

	global $d, $tpl, $page, $member;

	$options_account = account_options();
	$message = false;
	$changeAvatar = get_param($_POST, 'changeAvatar');

	($hook = get_hook('account_change_avatar_start'))? eval($hook) : null;

	if ($options_account['avatar'] == 1 && isset($changeAvatar) && is_array($changeAvatar) && count($changeAvatar) && isset($changeAvatar['submit']))
	{		
		if (isset($_FILES['fileAvatar']) && is_array($_FILES['fileAvatar']) && count($_FILES['fileAvatar']))
		{
			$upload_avatar = _upload_avatar($_FILES['fileAvatar'], member_id);

			if (isset($upload_avatar['error']))
			{
				$message .= message($upload_avatar['error'], "error");
			}
	   
			if (isset($upload_avatar['avatar']) && !empty($upload_avatar['avatar'])) 
			{				
				$d->update('members', array(
					'member_avatar' => $upload_avatar['avatar'],
					'member_lastvisit' => time(),
					'member_lastip' => get_ip(),
				), "`member_id`='".member_id."'", 1);
				
				$member['member_avatar'] = $upload_avatar['avatar'];
				redirect(url('account/change-avatar'));
			}
		}
	}

	// end save

	set_title('تغییر آوارتار');
	_menu();
	$file = get_tpl(root_dir.'modules/account/html/||change-avatar.tpl', template_dir.'||account/change-avatar.tpl');
	$itpl = new template($file[1], $file[0]);
	$itpl->assign('{avatar}', member::avatar($member['member_avatar']) . '?no-cache='.time());
	if (!empty($message))
	{
		$itpl->assign(array(
			'{message}' => $message,
			'[message]' => null,
			'[/message]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[message\\](.*?)\\[/message\\]#s', '');
	}
	if ($options_account['avatar'] == 0)
	{
		$itpl->assign(array(
			'{disabled}' => message('امکان آپلود آوارتار غیرفعال می باشد!', 'info'),
			'[disabled]' => null,
			'[/disabled]' => null,
		));
		$itpl->block('#\\[not-disabled\\](.*?)\\[/not-disabled\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-disabled]' => null,
			'[/not-disabled]' => null,
		));
		$itpl->block('#\\[disabled\\](.*?)\\[/disabled\\]#s', '');
	}

	($hook = get_hook('account_change_avatar_end'))? eval($hook) : null;

	if (!isset($file[2])) set_content('تغییر آوارتار', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var(),'add');	
	unset($itpl, $message, $changeAvatar);
}

function _upload_avatar($avatar=array(), $uid=0)
{
	if (!member) 
		redirect(url('account/login'));

	require_once(engine_dir.'upload.class.php');
	$options_account = account_options();

	($hook = get_hook('account_upload_avatar_start'))? eval($hook) : null;

	if (!$avatar['name'] || !$avatar['tmp_name'])
	{
		if (!isset($_FILES['member_avatar']))
		{
			$ret['error'] = 'خطا!';
			return $ret;
		}
		$avatar = $_FILES['member_avatar'];
	}

	if (!is_uploaded_file($avatar['tmp_name']))
	{
		$ret['error'] = 'نوع فایل آپلود شده نامعتبر است. لطفاً یک نوع فایل معتبر انتخاب کنید و دوباره امتحان کنید.';
		return $ret;
	}

	// Check we have a valid extension
	$ext = strtolower(get_extension($avatar['name']));
	if (!preg_match("#^(gif|jpg|jpeg|jpe|bmp|png)$#i", $ext))
	{
		$ret['error'] = 'پسوند آوارتار انتخابی شما مجاز نمی باشد!';
		return $ret;
	}

	$avatarpath = root_dir.'uploads/avatars/';

	if (!is_dir($avatarpath))
	{
		@mkdir($avatarpath, 0777);
		apadana_chmod($avatarpath, 0777);
	}

	$filename = 'avatar_'.$uid.'.'.$ext;
	$file = upload::file($avatar, $avatarpath, $filename);
	if (isset($file['error']))
	{
		@unlink($avatarpath.$filename);
		$ret['error'] = 'نوع فایل آپلود شده نامعتبر است. لطفاً یک نوع فایل معتبر انتخاب کنید و دوباره امتحان کنید.';
		return $ret;
	}

	// Lets just double check that it exists
	if (!file_exists($avatarpath.$filename))
	{
		$ret['error'] = 'نوع فایل آپلود شده نامعتبر است. لطفاً یک نوع فایل معتبر انتخاب کنید و دوباره امتحان کنید.';
		@unlink($avatarpath.$filename);
		return $ret;
	}

	// Check if this is a valid image or not
	$img_dimensions = @getimagesize($avatarpath.$filename);
	if (!is_array($img_dimensions))
	{
		@unlink($avatarpath.$filename);
		$ret['error'] = 'نوع فایل آپلود شده نامعتبر است. لطفاً یک نوع فایل معتبر انتخاب کنید و دوباره امتحان کنید.';
		return $ret;
	}

	if (!verify_image($avatarpath.$filename))
	{
		$ret['error'] = 'فایل آوارتار انتخابی شما دارای کدهای مشکوکی می باشد؟';
		@unlink($avatarpath.$filename);
		return $ret;
	}
	
	if (!empty($options_account['maxavatardims'])) 
	{
		list($maxwidth, $maxheight) = @explode('x', $options_account['maxavatardims']);
		if (($maxwidth && $img_dimensions[0] > $maxwidth) || ($maxheight && $img_dimensions[1] > $maxheight))
		{
			require_once(engine_dir.'thumbnail.class.php');
			$thumbnail = thumbnail::generate($avatarpath.$filename, $avatarpath, $filename, $maxheight, $maxwidth);
			if (!$thumbnail['filename'])
			{
				$ret['error'] = 'حداکثر اندازه آوارتار '.$maxwidth.' در '.$maxheight.' پیکسل می باشد.';
				@unlink($avatarpath.$filename);
				return $ret;
			}
			else
			{
				// Reset filesize
				$avatar['size'] = filesize($avatarpath.$filename);
				// Reset dimensions
				$img_dimensions = @getimagesize($avatarpath.$filename);
			}
		}
	}

	// Next check the file size
	if ($avatar['size'] > ($options_account['avatarsize']*1024) && $options_account['avatarsize'] > 0)
	{
		@unlink($avatarpath.$filename);
		$ret['error'] = 'حد کثر حجم مجاز برای آوارتار '.$options_account['avatarsize'].' کیلوبایت می باشد.';
		return $ret;
	}

	// Check a list of known MIME types to establish what kind of avatar we're uploading
	switch(strtolower($avatar['type']))
	{
		case 'image/gif':
		$img_type =  1;
		break;
		
		case 'image/jpeg':
		case 'image/x-jpg':
		case 'image/x-jpeg':
		case 'image/pjpeg':
		case 'image/jpg':
		$img_type = 2;
		break;
		
		case 'image/png':
		case 'image/x-png':
		$img_type = 3;
		break;
		
		default:
		$img_type = 0;
	}

	// Check if the uploaded file type matches the correct image type (returned by getimagesize)
	if ($img_dimensions[2] != $img_type || $img_type == 0)
	{
		$ret['error'] = 'نوع فایل آپلود شده نامعتبر است. لطفاً یک نوع فایل معتبر انتخاب کنید و دوباره امتحان کنید.';
		@unlink($avatarpath.$filename);
		return $ret;
	}
	// Everything is okay so lets delete old avatars for this user
	_remove_avatars($uid, $filename);

	$ret = array(
		'avatar' => 'uploads/avatars/'.$filename,
		'width' => intval($img_dimensions[0]),
		'height' => intval($img_dimensions[1])
	);

	($hook = get_hook('account_upload_avatar_end'))? eval($hook) : null;

	return $ret;
}

function _remove_avatars($uid, $exclude = null)
{
	if (!member) 
		redirect(url('account/login'));

	$avatarpath = root_dir.'uploads/avatars/';

	$dir = opendir($avatarpath);
	if ($dir)
	{
		while($file = @readdir($dir))
		{
			if (preg_match('#avatar_'.$uid.'\.#', $file) && is_file($avatarpath.'/'.$file) && $file != $exclude)
			{
				@unlink($avatarpath.'/'.$file);
			}
		}
		@closedir($dir);
	}
}

function _remove_avatar()
{
	if (!member)
		redirect(url('account/login'));

	global $d;

	_remove_avatars(member_id, '');

	($hook = get_hook('account_remove_avatar'))? eval($hook) : null;

	$d->update('members', array(
		'member_avatar' => null,
		'member_lastvisit' => time(),
		'member_lastip' => get_ip(),
	), "`member_id`='".member_id."'", 1);

	redirect(url('account/change-avatar'));
}

function _forget()
{
	if (member)
		redirect(url('account/login'));

	require_once(engine_dir.'captcha.function.php');

	global $d, $page, $member,$tpl;
	$success = false;
	$message = null;
	$options_account = account_options();
	$forget = get_param($_POST, 'forget');

	($hook = get_hook('account_forget_start'))? eval($hook) : null;

	if (is_array($forget) && count($forget))
	{
		if (!isset($_GET['c']) && !isset($_GET['d']) && isset($forget['name']) && isset($forget['email']) && isset($forget['captcha']))
		{
			$forget['email'] = nohtml($forget['email']);

			if (!is_alphabet($forget['name']) || empty($forget['email']))
				$message .= 'اطلاعات وارد شده کامل نیست!<br />';

			if (!validate_captcha('forget', $forget['captcha']))
				$message .= 'کد امنیتی را صحیح وارد نکرده اید!<br />';

			if (!validate_email($forget['email']))
				$message .= 'ایمیل وارد شده معتبر نیست!';

			if (!empty($message))
			{
				$message = message($message, 'error');
			}
			else
			{
				$q = $d->query("SELECT * FROM `#__members` WHERE member_status='1' AND `member_name`='".$d->escape_string($forget['name'])."' AND `member_email`='".$d->escape_string($forget['email'])."' LIMIT 1");
				if ($d->num_rows($q) >= 1)
				{
					$u = $d->fetch($q);
					$k = md5(generate_password(20, null).'FORGET'.$u['member_password']);
					$k = apadana_substr($k, 0, 18);

					$d->update('members', array(
						'member_key' => 'FORGET-'.$k.'-FORGET',
					), "`member_id`='".$u['member_id']."'", 1);

					if ($d->affected_rows())
					{
						require_once(engine_dir.'mail.function.php');

						global $options;
						$Body = url('account/forget/'.$u['member_name'].'/'.$k);
						$Body = 'شما درخواست بازیابی پسورد حساب کاربری خود را داده اید؟<br />برای بازیابی پسورد خود روی لینک زیر کلیک کنید.<br />اگر شما این درخواست را ارسال نکرده اید این پیام را حذف کنید و هیچ اتفاقی رخ نخواهد داد.<br /><a href="'.$Body.'" traget="_blank" dir="ltr">'.$Body.'</a>';

						if (send_mail($u['member_name'], $u['member_email'], $options['title'], $options['mail'], 'بازیابی پسورد در سایت '.$options['title'], $Body))
						{
							remove_captcha('forget');
							$message  = message('پیامی به ایمیل شما ارسال شد.', 'success');
							$message .= message('در صورتی که در پوشه Inbox پیامی نبود پوشه Spam را هم برسی کنید.', 'info');
							$success = true;
						}
						else
						{
							$message = message('در ارسال ایمیل خطایی رخ داده مجدد تلاش کنید!', 'error');
						}
					}
					else
						$message = message('در انجام عملیات خطایی رخ داده مجدد تلاش کنید!', 'error');

					$d->free_result($q);
				}
				else
				{
					$message = message('کاربری با مشخصات داده شده در سایت یافت نشد!', 'error');
				}
			}
		}
		elseif (isset($_GET['c']) && isset($_GET['d']) && isset($forget['pass1']) && isset($forget['pass2']))
		{
			if (is_alphabet($_GET['c']) && is_alphabet($_GET['d']) && apadana_strlen($_GET['d']) == 18)
			{
				$q = $d->query("SELECT * FROM `#__members` WHERE member_status='1' AND `member_name`='".$d->escape_string($_GET['c'])."' AND `member_key`='FORGET-".$d->escape_string($_GET['d'])."-FORGET' LIMIT 1");
				if ($d->num_rows($q) >= 1)
				{
					$u = $d->fetch($q);
					
					if ($u['member_name'] != $_GET['c'] || $u['member_key'] != 'FORGET-'.$_GET['d'].'-FORGET')
					{
						exit('Nice TRY!!');
					}
					
					$forget['pass1'] = ($forget['pass1']);
					$forget['pass2'] = ($forget['pass2']);

					if (empty($forget['pass1']) || empty($forget['pass2']))
					{
						$message .= 'اطلاعات را کامل نکرده اید!<br />';
					}

					if ($forget['pass1'] != $forget['pass2'])
					{
						$message .= 'پسورد های وارد شده مشابه نیستند!<br />';
					}

					if (apadana_strlen($forget['pass1']) < $options_account['minPassword'])
					{
						$message .= 'حداقل طول پسورد باید '.$options_account['minPassword'].' حرف باشد!';
					}

					if (stripos($forget['pass1'], $u['member_name']) !== false)
					{
						$message .= 'نباید از نام کاربری خود در پسورد استفاده کنید!';
					}

					if (!empty($message))
					{
						$message = message($message, 'error');
					}
					else
					{
						$forget['pass1'] = member::password($forget['pass1']);

						$d->update('members', array(
							'member_password' => $forget['pass1'],
							'member_key' => null,
							'member_lastvisit' => time(),
							'member_lastip' => get_ip(),
						), "`member_id`='".$u['member_id']."'", 1);

						if ($d->affected_rows())
						{
							$message = message('پسورد شما با موفقیت تغییر کرد.', 'success');
							$success = true;
						}
						else
							$message = message('در ذخیره اطلاعات خطایی رخ داده، مجدد تلاش کنید!', 'error');
					}

					$d->free_result($q);
				}
				else
				{
					$message = message('کاربری با مشخصات داده شده در سایت یافت نشد!', 'error');
				}
			}
			else
			{
				$message = message('درخواست شما معتبر نمی باشد!!', 'error');
			}
		}
	}
	
	// end save

	set_title('بازیابی پسورد');
	set_meta('description', 'بازیابی پسورد', 'add');
	set_canonical(url('account/forget'));

	if (!isset($_GET['c']) && !isset($_GET['d']))
	{
		$file = get_tpl(root_dir.'modules/account/html/||forget.tpl', template_dir.'||account/forget.tpl');
		$itpl = new template($file[1], $file[0]);
		$itpl->assign(array(
			'{captcha}' => create_captcha('forget'),
			'[form-1]' => null,
			'[/form-1]' => null,
		));
		
		if (!empty($message))
		{
			$itpl->assign(array(
				'{message}' => $message,
				'[message]' => null,
				'[/message]' => null,
			));
		}
		else
		{
			$itpl->block('#\\[message\\](.*?)\\[/message\\]#s', '');
		}
		
		if ($success)
		{
			$itpl->block('#\\[form-1\\](.*?)\\[/form-1\\]#s', '');
		}
		
		$itpl->block('#\\[form-2\\](.*?)\\[/form-2\\]#s', '');
		$html = $itpl->get_var();
		unset($itpl);
	}
	else
	{
		$q = "SELECT * FROM `#__members` WHERE member_status='1' AND `member_name`='".$d->escape_string($_GET['c'])."' AND `member_key`='FORGET-".$d->escape_string($_GET['d'])."-FORGET' LIMIT 1";
		if (is_alphabet($_GET['c']) && is_alphabet($_GET['d']) && apadana_strlen($_GET['d']) == 18 && ($d->num_rows($q, true) >= 1 || $success===true))
		{
			$file = get_tpl(root_dir.'modules/account/html/||forget.tpl', template_dir.'||account/forget.tpl');
			$itpl = new template($file[1], $file[0]);
			
			if (!empty($message))
			{
				$itpl->assign(array(
					'{message}' => $message,
					'[message]' => null,
					'[/message]' => null,
				));
			}
			else
			{
				$itpl->block('#\\[message\\](.*?)\\[/message\\]#s', '');
			}
			
			if (!$success)
			{
				$itpl->assign(array(
					'{name}' => $_GET['c'],
					'{forget}' => $_GET['d'],
					'[form-2]' => null,
					'[/form-2]' => null,
				));
			}
			else
			{
				$itpl->block('#\\[form-2\\](.*?)\\[/form-2\\]#s', '');
			}
			
			$itpl->block('#\\[form-1\\](.*?)\\[/form-1\\]#s', '');
			$html = $itpl->get_var();
			unset($itpl);
		}
		else
		{
			$html = message('درخواست داده شده معتبر نمی باشد!', 'error');
		}
	}

	($hook = get_hook('account_forget_end'))? eval($hook) : null;

	if (!isset($file[2]))
	{
		set_content('بازیابی پسورد', $html);
	}
	else
	{
		$tpl->assign('{content}', $html);
	}
	unset($html, $message, $forget);
}

function _menu()
{
	global $options, $tpl;
	$file = get_tpl(root_dir.'modules/account/html/||menu.tpl', template_dir.'||account/menu.tpl');
	$itpl = new template($file[1], $file[0]);
	$itpl->assign(array(
		'{image-home}' => file_exists(root_dir.'themes/'.$options['theme'].'/images/account/home.png')? 'themes/'.$options['theme'].'/images/account/home.png' : 'modules/account/images/home.png',
		'{image-profile-edit}' => file_exists(root_dir.'themes/'.$options['theme'].'/images/account/profile-edit.png')? 'themes/'.$options['theme'].'/images/account/profile-edit.png' : 'modules/account/images/profile-edit.png',
		'{image-pm}' => file_exists(root_dir.'themes/'.$options['theme'].'/images/account/pm.png')? 'themes/'.$options['theme'].'/images/account/pm.png' : 'modules/account/images/pm.png',
		'{image-change-password}' => file_exists(root_dir.'themes/'.$options['theme'].'/images/account/change-password.png')? 'themes/'.$options['theme'].'/images/account/change-password.png' : 'modules/account/images/change-password.png',
		'{image-change-avatar}' => file_exists(root_dir.'themes/'.$options['theme'].'/images/account/change-avatar.png')? 'themes/'.$options['theme'].'/images/account/change-avatar.png' : 'modules/account/images/change-avatar.png',
		'{image-logout}' => file_exists(root_dir.'themes/'.$options['theme'].'/images/account/logout.png')? 'themes/'.$options['theme'].'/images/account/logout.png' : 'modules/account/images/logout.png',
	));

	($hook = get_hook('account_menu'))? eval($hook) : null;

	if (!isset($file[2])) set_content('دسترسی سریع', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($itpl);
}

?>