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

member::check_admin_page_access('newsletter') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $d, $tpl;

	require_once(engine_dir.'editor.function.php');

	set_title('خبرنامه');
	$itpl = new template('newsletter.tpl', engine_dir.'admin/template/');
	$itpl->assign(array(
		'{textarea}' => wysiwyg_textarea('newsletter[text]', null),
		'{members-0}' => $d->numRows("SELECT `member_id` FROM `#__members` WHERE `member_newsletter`='0'", true),
		'{members-1}' => $d->numRows("SELECT `member_id` FROM `#__members` WHERE `member_newsletter`='1'", true),
	));
	set_content(null, $itpl->get_var());
}

function _send()
{
	global $options, $page, $d;

	require_once(engine_dir.'mail.function.php');

	$msg = null;
	$newsletter = get_param($_POST, 'newsletter', null, 1);
	$newsletter['title'] = htmlencode($newsletter['title']);
	$newsletter['all'] = !isset($newsletter['all']) || intval($newsletter['all'])<=0? 0 : 1;

	if (empty($newsletter['title']))
	{
		$msg .= 'عنوان خبرنامه را ننوشته اید!<br>';
	}
	
	if (empty($newsletter['text']))
	{
		$msg .= 'متن خبرنامه را ننوشته اید!<br>';
	}
	
	if (!empty($msg))
	{
		echo message($msg, 'error');
	}
	else
	{
		$send = $error = 0;
		$d->query("SELECT `member_email`, `member_name` FROM #__members ".($newsletter['all']==0? "WHERE `member_newsletter`='1'" : null)." ORDER BY `member_id` DESC");
		while ($data = $d->fetch()) 
		{
			$toname = $data['member_name'];
			$toemail = $data['member_email'];
			$fromname = $options['title'];
			$fromemail = $options['mail'];
			$subject = 'خبرنامه سایت '.$options['title'];
			$Body  = '<h2>'.$newsletter['title'].'</h2><br>'.$newsletter['text'];
			$Body .= '<font size="1"><br>این پیام از طریق بخش خبرنامه اختصاصی اعضا ارسال شده است.';
			$Body .= '<br>در صورتی که مایل به دریافت آن نیستید می توانید از تنظیمات پروفایل خود آن را غیرفعال کنید.</font>';

			if (send_mail($toname, $toemail, $fromname, $fromemail, $subject, $Body))
			{
				$send++;
			}
			else
			{
				$error++;
			}
		}
		echo message($send.' خبرنامه با موفقیت ارسال شد و '.$error.' مورد در ارسال با خطا مواجه شد.', 'info');
		echo '<script>apadana.hideID("newsletter-form")</script>';
	}
	exit;
}

$_GET['do'] = get_param($_GET, 'do');

switch($_GET['do'])
{
	case 'send':
	_send();
	break;

	default:
	_index();
	break;
}

?>