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

function send_mail($toname, $toemail, $fromname, $fromemail, $subject, $message, $type = 'apadana', $cc = null, $bcc = null)
{
	global $options;

	require_once(engine_dir.'PHPMailer/class.phpmailer.php');
	require_once(engine_dir.'PHPMailer/class.smtp.php');

	$mail = new PHPMailer();

	($hook = get_hook('send_mail_start'))? eval($hook) : null;

	if (empty($options['smtp-host']) || empty($options['smtp-username']) || empty($options['smtp-password']))
	{
		$mail->IsMAIL();
	}
	else
	{
		$mail->IsSMTP();
		$mail->Host = $options['smtp-host'];
		$mail->Port = $options['smtp-port'];
		$mail->SMTPAuth = !empty($options['smtp-username']) && !empty($options['smtp-password'])? true : false;
		$mail->Username = $options['smtp-username'];
		$mail->Password = $options['smtp-password'];
		#$mail->SMTPDebug  = 2; 
	}

	if (!$toname)
	{
		$toname = $toemail;
	}
	
	if (!$fromname)
	{
		$fromname = $fromemail;
	}
	
	$mail->CharSet = charset;
	$mail->From = $fromemail;
	$mail->FromName = $fromname;
	$mail->AddAddress($toemail, $toname);
	$mail->AddReplyTo($fromemail, $fromname);

	if ($cc)
	{
		$cc = explode(',', $cc);
		$cc = array_map('trim', $cc);
		foreach ($cc as $ccaddress)
		{
			$mail->AddCC($ccaddress);
		}
	}
	if ($bcc)
	{
		$bcc = explode(',', $bcc);
		$bcc = array_map('trim', $bcc);
		foreach ($bcc as $bccaddress)
		{
			$mail->AddBCC($bccaddress);
		}
	}
	if ($type == 'plain')
	{
		$mail->IsHTML(false);
	}
	else
	{
		$mail->IsHTML(true);
	}
	if ($type == 'apadana')
	{
		$tpl = get_tpl(engine_dir.'templates/||mail.tpl', template_dir.'||mail.tpl');
		$tpl = new template($tpl[1], $tpl[0]);

		$tpl->assign(array(
			'{subject}' => $subject,
			'{to-name}' => $toname,
			'{to-email}' => $toemail,
			'{from-name}' => $fromname,
			'{from-email}' => $fromemail,
			'{body}' => $message,
		));

		$message = $tpl->get_var();
		unset($tpl);
	}
	
	$mail->Subject = $subject;
	$mail->Body = $message;
	
	($hook = get_hook('send_mail_end'))? eval($hook) : null;

	if (!$mail->Send())
	{
		#echo $mail->ErrorInfo;
		$mail->ClearAllRecipients();
		$mail->ClearReplyTos();
		return false;
	}
	else
	{
		$mail->ClearAllRecipients(); 
		$mail->ClearReplyTos();
		return true;
	}
}
