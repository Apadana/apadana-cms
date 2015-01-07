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

function module_contact_us_run()
{
	global $page, $d, $options, $member_groups, $member,$tpl;
	$member = member::is('info');

	require_once(engine_dir.'mail.function.php');
	require_once(engine_dir.'captcha.function.php');

	$username = get_param($_GET, 'b');

	if (is_alphabet($username))
	{
		$receiver = member::info(false, $username);
		
		if (!member && !is_array($receiver) || !count($receiver) || $receiver['member_status'] != 1)
		{
			redirect(url('contact-us'));
		}
	}
	
	if (!isset($receiver))
	{
		$q = null;
		foreach($member_groups as $m)
		{
			if ($m['group_admin'] != 1) continue;
			$q .= " OR `member_group`='".intval($m['group_id'])."'";
		}
		$q = ltrim($q, ' OR');
		
		$d->query("SELECT `member_name`, `member_alias`, `member_email` FROM `#__members` WHERE {$q} ORDER BY `member_id` DESC");
		while($m = $d->fetch())
		{
			$members[$m['member_name']] = array(
				'alias' => empty($m['member_alias'])? $m['member_name'] : $m['member_alias'],
				'email' => $m['member_email']
			);
		};
		unset($m, $q);
	}

	$contact = get_param($_POST, 'contact-us');

	($hook = get_hook('contact_us_start'))? eval($hook) : null;

	if (isset($contact) && is_array($contact) && count($contact))
	{
		$message = null;
		$contact['name'] = htmlencode($contact['name']);
		$contact['receiver'] = isset($contact['receiver'])? trim($contact['receiver']) : null;
		$contact['email'] = nohtml($contact['email']);
		$contact['website'] = $contact['website']=='http://'? null : nohtml($contact['website']);
		$contact['message'] = htmlencode($contact['message']);
	
		if (empty($contact['name']))
		{
			$message .= 'نام خود را ننوشته اید!<br />';
		}
	
		if (!validate_email($contact['email']))
		{
			$message .= 'ایمیل وارد شده معتبر نیست!<br />';
		}
		
		if (!empty($contact['website']) && !validate_url($contact['website']))
		{
			$message .= 'وبسایت وارد شده معتبر نیست!<br />';
		}
		
		if (!isset($receiver) && (!is_alphabet($contact['receiver']) || !isset($members[$contact['receiver']])))
		{
			$message .= 'گیرنده پیام معتبر نیست!<br />';
		}
		
		if (empty($contact['message']))
		{
			$message .= 'متن پیام را ننوشته اید!<br />';
		}
		
		if (!validate_captcha('contact-us', $contact['captcha']))
		{
			$message .= 'کد امنیتی صحیح نمی باشد!<br />';
		}
		
		($hook = get_hook('contact_us_validate'))? eval($hook) : null;

		if (empty($message))
		{
			if (isset($receiver))
			{
				$toname = empty($receiver['member_alias'])? $receiver['member_name'] : $receiver['member_alias'];
			}
			else
			{
				$toname = $members[$contact['receiver']]['alias'];
			}
			
			$toemail = isset($receiver)? $receiver['member_email'] : $members[$contact['receiver']]['email'];
			$fromname = $contact['name'];
			$fromemail = $contact['email'];
			$subject = 'تماس از سایت '.$options['title'];
			$Body  = 'ارسال کننده: <b>'.$contact['name'].'</b><br />';
			$Body .= 'ایمیل: <b>'.$contact['email'].'</b><br />';
			$Body .= 'وبسایت: <b dir="ltr">'.$contact['website'].'</b><br />';
			$Body .= 'متن پیام: <br />'.nl2br($contact['message']).'<br />';
			$Body .= '<hr/><b>این پیام از طریق بخش تماس با ما ارسال شده است</b>';

			($hook = get_hook('contact_us_send'))? eval($hook) : null;

			if (send_mail($toname, $toemail, $fromname, $fromemail, $subject, $Body))
			{
				echo message('پیام شما با موفقیت ارسال شد.', 'success');
				echo '<script>$("#form-contact-us").slideUp("slow");</script>';
			}
			else
			{
				echo message('متاسفانه در ارسال پیام خطایی رخ داده!', 'error');
			}
		}
		else
		{
			echo message($message, 'error');
		}
		exit;
	}
	
	set_title('تماس با ما');
	set_canonical(url('contact-us'));

	$file = get_tpl(root_dir.'modules/contact-us/html/||form.tpl', template_dir.'||contact-us.tpl');
	$itpl = new template($file[1], $file[0]);

	if (!isset($receiver) && isset($members) && is_array($members) && count($members))
	{
		foreach($members as $m=>$a)
		{
			$itpl->add_for('receiver', array(
				'{name}' => $m,
				'{alias}' => $a['alias'],
			));
		}
	}

	if (isset($receiver))
	{
		$itpl->block('#\\[receiver-admin\\](.*?)\\[/receiver-admin\\]#s', '');
		$itpl->assign(array(
			'{receiver}' => member::group_title($receiver['member_name'], $receiver['member_group']),
			'[receiver-user]' => null,
			'[/receiver-user]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[receiver-user\\](.*?)\\[/receiver-user\\]#s', '');
		$itpl->assign(array(
			'[receiver-admin]' => null,
			'[/receiver-admin]' => null,
		));
	}

	$itpl->assign(array(
		'{url}' => url('contact-us'.(!empty($username)? '/'.$username : null)),
		'{name}' => member? (empty($member['member_alias'])? $member['member_name'] : $member['member_alias']) : null,
		'{email}' => member? $member['member_email'] : null,
		'{website}' => member? (empty($member['member_web'])? 'http://' : $member['member_web']) : 'http://',
		'{captcha}' => create_captcha('contact-us')
	));
	
	($hook = get_hook('contact_us_end'))? eval($hook) : null;

	if (!isset($file[2])) set_content('تماس با ما', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($itpl, $members, $m, $a);
}

function module_contact_us_sitemap($sitemap)
{
	$sitemap->addItem(url('contact-us'), 0, 'never', '0.6');
}

?>