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

function _inbox()
{
	if (!member) 
		redirect(url('account/login'));

	global $d, $options, $tpl, $page;
	require_once(engine_dir.'pagination.class.php');
	$query = sprintf("SELECT * FROM `#__private_messages` WHERE `msg_receiver`='%s'", member_name);
	$total = $d->numRows($query, true);
	$get_pages = get_param($_GET, 'b', 1);
	
	$pagination = new pagination($total, 20, $get_pages);
	
	if ($get_pages > $pagination->Pages && $pagination->Pages != 0)
	{
		redirect(url('private-messages'));
	}
	
	$query = sprintf("SELECT * FROM #__private_messages WHERE `msg_receiver`='%s' ORDER BY msg_date DESC, msg_id DESC LIMIT %d, %d", member_name, $pagination->Start, $pagination->End);
	$private_messages = $d->get_row($query, 'assoc', 'msg_id');

	set_title('پیام خصوصی');

	if ($get_pages > 1)
	{
		set_title('صفحه '.number2persian($get_pages));
	}

	_menu();

	$file = get_tpl(root_dir.'modules/private-messages/html/||inbox.tpl', template_dir.'||private-messages/inbox.tpl');
	$itpl = new template($file[1], $file[0]);

	if (is_array($private_messages) && count($private_messages))
	{
		foreach($private_messages as $msg)
		{
			$msg['msg_subject'] = $msg['msg_read']==0? '<font color=red>'.$msg['msg_subject'].'</font>' : $msg['msg_subject'];
			$itpl->add_for('list', array(
				'{odd-even}' => odd_even(),
				'{subject}' => $msg['msg_subject'],
				'{sender}' => $msg['msg_sender'],
				'{receiver}' => $msg['msg_receiver'],
				'{id}' => $msg['msg_id'],
				'{past-time}' => get_past_time($msg['msg_date']),
				'{date}' => jdate('j/m/Y g:i a', $msg['msg_date']),
			));
		}
		$itpl->assign(array(
			'[list]' => null,
			'[/list]' => null,
		));
		$itpl->block('#\\[not-list\\](.*?)\\[/not-list\\]#s', '');
	}
	else
	{
		$itpl->block('#\\[list\\](.*?)\\[/list\\]#s', '');
		$itpl->assign(array(
			'[not-list]' => null,
			'[/not-list]' => null,
		));
	}

	if (!isset($file[2])) set_content('پیام های دریافتی', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	$pagination->build(url('private-messages/{page}'));
	unset($itpl, $pagination, $private_messages, $msg, $total, $query);
}

function _outbox()
{
	if (!member) 
		redirect(url('account/login'));

	global $d, $options, $tpl, $page;
	require_once(engine_dir.'pagination.class.php');
	$query = sprintf("SELECT * FROM `#__private_messages` WHERE `msg_sender`='%s'", member_name);
	$total = $d->numRows($query, true);
	$get_pages = get_param($_GET, 'c', 1);
	
	$pagination = new pagination($total, 20, $get_pages);
	
	if ($get_pages > $pagination->Pages && $pagination->Pages != 0)
	{
		redirect(url('private-messages/outbox'));
	}
	
	$query = sprintf("SELECT * FROM #__private_messages WHERE `msg_sender`='%s' ORDER BY msg_date DESC, msg_id DESC LIMIT %d, %d", member_name, $pagination->Start, $pagination->End);
	$private_messages = $d->get_row($query, 'assoc', 'msg_id');

	set_title('پیام های ارسالی');

	if ($get_pages > 1)
	{
		set_title('صفحه '.number2persian($get_pages));
	}

	_menu();

	$file = get_tpl(root_dir.'modules/private-messages/html/||outbox.tpl', template_dir.'||private-messages/outbox.tpl');
	$itpl = new template($file[1], $file[0]);

	if (is_array($private_messages) && count($private_messages))
	{
		foreach($private_messages as $msg)
		{
			$msg['msg_subject'] = $msg['msg_read']==0? '<font color=red>'.$msg['msg_subject'].'</font>' : $msg['msg_subject'];
			$itpl->add_for('list', array(
				'{odd-even}' => odd_even(),
				'{subject}' => $msg['msg_subject'],
				'{sender}' => $msg['msg_sender'],
				'{receiver}' => $msg['msg_receiver'],
				'{id}' => $msg['msg_id'],
				'{past-time}' => get_past_time($msg['msg_date']),
				'{date}' => jdate('j/m/Y g:i a', $msg['msg_date']),
			));
		}
		$itpl->assign(array(
			'[list]' => null,
			'[/list]' => null,
		));
		$itpl->block('#\\[not-list\\](.*?)\\[/not-list\\]#s', '');
	}
	else
	{
		$itpl->block('#\\[list\\](.*?)\\[/list\\]#s', '');
		$itpl->assign(array(
			'[not-list]' => null,
			'[/not-list]' => null,
		));
	}

	if (!isset($file[2])) set_content('پیام های ارسالی', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	$pagination->build(url('private-messages/outbox/{page}'));
	unset($itpl, $pagination, $private_messages, $msg, $total, $query);
}

function _read()
{
	if (!member) 
		redirect(url('account/login'));

	require_once(engine_dir.'bbcode.class.php');
	global $d, $tpl, $page;
	$id = get_param($_GET, 'c', 0);

	$query = sprintf("SELECT * FROM #__private_messages WHERE `msg_id`='%d' AND (`msg_receiver`='%s' OR `msg_sender`='%s') LIMIT 1", $id, member_name, member_name);
	$private_messages = $d->fetch($query, 'assoc', true);

	if (!is_array($private_messages) || !count($private_messages)) 
		redirect(url('private-messages'));

	if ($private_messages['msg_read'] == 0 && $private_messages['msg_receiver'] == member_name)
	{
		$where = sprintf("`msg_id`='%d' AND `msg_receiver`='%s'", $id, member_name);
		$d->update('private_messages', array(
			'msg_read' => 1
		), $where, 1);
	}
	
	$bbcode = new bbcode;
	$private_messages['msg_text'] = $bbcode->parse($private_messages['msg_text']);
	$private_messages['msg_subject'] = htmlencode($private_messages['msg_subject']);
	
	set_title('خواندن پیام');
	
	_menu();

	$file = get_tpl(root_dir.'modules/private-messages/html/||read.tpl', template_dir.'||private-messages/read.tpl');
	$itpl = new template($file[1], $file[0]);

	$itpl->assign(array(
		'{subject}' => $private_messages['msg_subject'],
		'{sender}' => $private_messages['msg_sender'],
		'{receiver}' => $private_messages['msg_receiver'],
		'{text}' => $private_messages['msg_text'],
		'{id}' => $private_messages['msg_id'],
		'{past-time}' => get_past_time($private_messages['msg_date']),
		'{date}' => jdate('l j F Y ساعت g:i A', $private_messages['msg_date']),
	));

	if ($private_messages['msg_sender'] != member_name)
	{
		$itpl->assign(array(
			'[reply]' => null,
			'[/reply]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[reply\\](.*?)\\[/reply\\]#s', '');
	}
	
	if (!isset($file[2])) set_content('خواندن پیام', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($itpl, $private_messages, $bbcode, $query);
}

function _new()
{
	if (!member) 
		redirect(url('account/login'));

	global $d, $tpl, $options;
	$msg = $success = false;
	$new = get_param($_POST, 'new', null, 1);

	if (is_array($new) && count($new) && isset($new['subject']) && isset($new['receiver']) && isset($new['text']))
	{
		$new['subject'] = htmlencode($new['subject']);
		$new['receiver'] = trim($new['receiver']);
		$new['text'] = htmlencode($new['text']);

		if (empty($new['subject']))
		{
			$msg .= 'عنوان پیام را فراموش کرده اید!<br />';
		}

		if (empty($new['receiver']))
		{
			$msg .= 'گیرنده پیام را مشخص نکرده اید!<br />';
		}
		else
		{
			if (!is_alphabet($new['receiver']) || !member::exists($new['receiver']) || $new['receiver'] == member_name)
			{
				$msg .= 'گیرنده پیام معتبر نمی باشد!<br />';
			}
		}

		if (empty($new['text']))
		{
			$msg .= 'متن پیام را ننوشته اید!';
		}

		if (empty($msg))
		{
			$new['text'] = str_replace('{', '&#x7B;', $new['text']);

			$arr = array(
				'msg_sender' => member_name,
				'msg_receiver' => $new['receiver'],
				'msg_subject' => $new['subject'],
				'msg_text' => $new['text'],
				'msg_date' => time(),
			);
			$id = $d->insert('private_messages', $arr);
			if ($d->affectedRows())
			{
				$u = member::info(false, $new['receiver']);
				if ($u['member_newsletter'] == 1)
				{
					require_once(engine_dir.'mail.function.php');
					send_mail($u['member_name'], $u['member_email'], $options['title'], $options['mail'], 'پیام خصوصی جدید در سایت '.$options['title'], 'شما یک پیام خصوصی جدید دریافت کرده اید.<br/>برای مشاهده آن <a href="'.url('private-messages/read/'.$id).'" target="_blank">اینجا</a> کلیک کنید.');
				}
				$msg = message('پیام با موفقیت ارسال شد.', 'success');
				refresh(url('private-messages/outbox'), 4);
				$success = true;
			}
			else
			{
				$msg = message('در ذخیره پیام خطایی رخ داده مجدد تلاش کنید.', 'error');
			}
		}
		else
		{
			$msg = message($msg, 'error');
		}
	}

	require_once(engine_dir.'editor.function.php');

	set_title('ارسال پیام جدید');
	_menu();

	$subject = null;
	$receiver = null;
	$request = get_param($_GET, 'c');

	if (!$success)
	{
		if (isnum($request))
		{
			$query = sprintf("SELECT * FROM #__private_messages WHERE `msg_id`='%d' AND (`msg_receiver`='%s' OR `msg_sender`='%s') LIMIT 1", $request, member_name, member_name);
			$private_messages = $d->fetch($query, 'assoc', true);
			
			if (is_array($private_messages) && count($private_messages))
			{
				$receiver = $private_messages['msg_sender'];

				if (apadana_substr($private_messages['msg_subject'], 0, 5) != 'پاسخ:')
				{
					$subject = 'پاسخ: ' . $private_messages['msg_subject'];
				}
				else
				{
					$subject = $private_messages['msg_subject'];
				}
			}
		}
		elseif (is_alphabet($request))
		{
			$receiver = $request;
		}

		$file = get_tpl(root_dir.'modules/private-messages/html/||new.tpl', template_dir.'||private-messages/new.tpl');
		$itpl = new template($file[1], $file[0]);
		$itpl->assign(array(
			'{subject}' => isset($new['subject'])? $new['subject'] : $subject,
			'{receiver}' => isset($new['receiver'])? htmlspecialchars($new['receiver']) : $receiver,
			'{textarea}' => wysiwyg_textarea('new[text]', isset($new['text'])? $new['text'] : null, 'BBcode'),
		));
		
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
		
		$html = $itpl->get_var();
	}
	else
	{
		$html = $msg;
	}

	if (!isset($file[2])) set_content('ارسال پیام جدید', $html); else $tpl->assign('{content}', $html);	
	unset($html, $itpl, $private_messages, $query);
}

function _remove()
{
	if (!member) 
		redirect(url('account/login'));

	global $d, $tpl, $page;
	$id = get_param($_GET, 'c', 0);

	$query = sprintf("SELECT * FROM #__private_messages WHERE `msg_id`='%d' AND (`msg_receiver`='%s' OR `msg_sender`='%s') LIMIT 1", $id, member_name, member_name);
	$private_messages = $d->fetch($query, 'assoc', true);

	if (!is_array($private_messages) || !count($private_messages)) 
		redirect(url('private-messages'));

	if ($private_messages['msg_receiver'] == member_name || ($private_messages['msg_sender'] == member_name && $private_messages['msg_read'] == 0))
	{
		$where = sprintf("`msg_id`='%d' AND (`msg_receiver`='%s' OR `msg_sender`='%s')", $id, member_name, member_name);
		$d->delete('private_messages', $where, 1);
		redirect(url('private-messages'));
	}

	set_title('حذف پیام');

	_menu();
	set_content('حذف پیام', message('متاسفیم کاربر گیرنده این پیام را خوانده است و فقط او می توانید این پیام را حذف کند.', 'error'));
	unset($private_messages, $query);
}

function _menu()
{
	$num = private_messages();
	$file = get_tpl(root_dir.'modules/private-messages/html/||menu.tpl', template_dir.'||private-messages/menu.tpl');
	$itpl = new template($file[1], $file[0]);

	if ($num['newpms'] >= 1)
	{
		$itpl->assign(array(
			'{unread-messages}' => (int) $num['newpms'],
			'[unread-messages]' => null,
			'[/unread-messages]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[unread-messages\\](.*?)\\[/unread-messages\\]#s', '');
	}

	if (!isset($file[2])) set_content('دسترسی سریع', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($itpl, $num);
}

?>