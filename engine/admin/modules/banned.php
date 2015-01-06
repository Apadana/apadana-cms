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

member::check_admin_page_access('banned') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $tpl, $options, $page;
	set_title('مسدود سازی آی پی');
	$itpl = new template('engine/admin/template/banned.tpl');
	$itpl->assign(array(
		'[not-show-list]' => null,
		'[/not-show-list]' => null,
	));
	$itpl->block('#\\[show-list\\](.*?)\\[/show-list\\]#s', '');
	$tpl->assign('{content}', $itpl->get_var());
	unset($itpl);
}

function _list()
{
	global $d;
	$itpl = new template('engine/admin/template/banned.tpl');

	$d->query("SELECT * FROM #__banned ORDER BY ban_id DESC");
	while ($data = $d->fetch()) 
	{
		$ip = explode('.', $data['ban_ip']);
		$itpl->add_for('list', array(
			'{odd-even}' => odd_even(),
			'{id}' => $data['ban_id'],
			'{ip}' => $data['ban_ip'],
			'{ip-1}' => $ip[0],
			'{ip-2}' => $ip[1],
			'{ip-3}' => $ip[2],
			'{ip-4}' => $ip[3],
			'{reason}' => strip_tags($data['ban_reason']),
			'{date}' => jdate('l j F Y ساعت g:i A', $data['ban_date']),
		));
	}

	if (isset($itpl->foreach['list']))
	{
		$itpl->assign(array(
			'[list]' => null,
			'[/list]' => null,
		));
		$itpl->block('#\\[not-list\\](.*?)\\[/not-list\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-list]' => null,
			'[/not-list]' => null,
		));
		$itpl->block('#\\[list\\](.*?)\\[/list\\]#s', '');
	}

	$itpl->assign(array(
		'[show-list]' => null,
		'[/show-list]' => null,
	));
	$itpl->block('#\\[not-show-list\\](.*?)\\[/not-show-list\\]#s', '');
	$itpl->display();
	define('no_template', true);
}

function _new()
{
	global $d;

	$banned = isset($_POST['banned'])? $_POST['banned'] : null;

	if (isset($banned) && is_array($banned) && count($banned))
	{
		$banned['reason'] = htmlencode($banned['reason']);
		$banned['ip'][1] = intval($banned['ip'][1]);
		$banned['ip'][2] = intval($banned['ip'][2]);
		$banned['ip'][3] = trim($banned['ip'][3])=='*'? '*' : intval($banned['ip'][3]);
		$banned['ip'][4] = trim($banned['ip'][4])=='*'? '*' : intval($banned['ip'][4]);
	
		if (substr($banned['ip'][2], 0, 2) == 00) $banned['ip'][2] = str_replace('00', null, $banned['ip'][2]);
		if (substr($banned['ip'][3], 0, 2) == 00) $banned['ip'][3] = str_replace('00', null, $banned['ip'][3]);
		if (substr($banned['ip'][4], 0, 2) == 00) $banned['ip'][4] = str_replace('00', null, $banned['ip'][4]);
	
		$ip = $banned['ip'][1].'.'.$banned['ip'][2].'.'.$banned['ip'][3].'.'.$banned['ip'][4];
		$msg = null;

		if (empty($banned['ip'][1]) OR empty($banned['ip'][2]) OR empty($banned['ip'][3]) OR empty($banned['ip'][4]))
		{
			$msg .= 'آدرس IP ازحدود طبیعی خارج است!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && !is_numeric($banned['ip'][1]) && !empty($banned['ip'][1]) OR !is_numeric($banned['ip'][2]) && !empty($banned['ip'][2]) OR !is_numeric($banned['ip'][3]) && !empty($banned['ip'][3]) && $banned['ip'][3] != '*' OR !is_numeric($banned['ip'][4]) && !empty($banned['ip'][4]) && $banned['ip'][4] != '*')
		{
			$msg .= 'آدرس IP باید عددی باشد!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && $banned['ip'][1] > 255 OR $banned['ip'][2] > 255 OR $banned['ip'][3] > 255 && $banned['ip'][3] != '*' OR $banned['ip'][4] > 255 && $banned['ip'][4] != '*')
		{
			$msg .= 'آدرس IP ازحدود طبیعی خارج است!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && substr($banned['ip'][1], 0, 1) == 0)
		{
			$msg .= 'آدرس IP نمیتواند با 0 شروع شود!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && $ip == '127.0.0.1')
		{
			$msg .= 'شما نمیتوانید Localhost را مسدود کنید!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && $ip == get_ip())
		{
			$msg .= 'شما نمیتونید IP خودتان را مسدود کنید!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (!isset($banned['reason']) || empty($banned['reason']))
		{
			$msg .= 'دلیل مسدود سازی را ننوشته اید!<br>';
		}
		
		if (!empty($msg))
		{
			echo message($msg, 'error');
		}
		else
		{
			$d->insert('banned', array(
				'ban_ip' => $ip,
				'ban_reason' => $banned['reason'],
				'ban_date' => time(),
			));	
			
			if ($d->affected_rows())
			{
				$d->update('options', array('option_value'=>time()), "option_name='last-banned'", 1);
				remove_cache('options');
				echo message('آی پی <u dir="ltr">'.$ip.'</u> با موفقیت مسدود شد!', 'success');
				echo '<script>apadana.hideID("form-new-banned")</script>';
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}
	define('no_template', true);
}

function _edit()
{
	global $d;
	$id = isset($_GET['id'])? intval($_GET['id']) : 0;

	$d->query("SELECT * FROM #__banned WHERE `ban_id`='$id' LIMIT 1");
	$data = $d->fetch();
	
	if (!is_array($data) || !count($data))
	{
		echo message('این آی پی وجود ندارد!', 'error');
		exit;
	}

	$banned = isset($_POST['banned'])? $_POST['banned'] : null;

	if (isset($banned) && is_array($banned) && count($banned))
	{
		$banned['reason'] = htmlencode($banned['reason']);
		$banned['ip'][1] = intval($banned['ip'][1]);
		$banned['ip'][2] = intval($banned['ip'][2]);
		$banned['ip'][3] = trim($banned['ip'][3])=='*'? '*' : intval($banned['ip'][3]);
		$banned['ip'][4] = trim($banned['ip'][4])=='*'? '*' : intval($banned['ip'][4]);
	
		if (substr($banned['ip'][2], 0, 2) == 00) $banned['ip'][2] = str_replace('00', null, $banned['ip'][2]);
		if (substr($banned['ip'][3], 0, 2) == 00) $banned['ip'][3] = str_replace('00', null, $banned['ip'][3]);
		if (substr($banned['ip'][4], 0, 2) == 00) $banned['ip'][4] = str_replace('00', null, $banned['ip'][4]);
	
		$ip = $banned['ip'][1].'.'.$banned['ip'][2].'.'.$banned['ip'][3].'.'.$banned['ip'][4];
		$msg = null;

		if (empty($banned['ip'][1]) OR empty($banned['ip'][2]) OR empty($banned['ip'][3]) OR empty($banned['ip'][4]))
		{
			$msg .= 'آدرس IP ازحدود طبیعی خارج است!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && !is_numeric($banned['ip'][1]) && !empty($banned['ip'][1]) OR !is_numeric($banned['ip'][2]) && !empty($banned['ip'][2]) OR !is_numeric($banned['ip'][3]) && !empty($banned['ip'][3]) && $banned['ip'][3] != '*' OR !is_numeric($banned['ip'][4]) && !empty($banned['ip'][4]) && $banned['ip'][4] != '*')
		{
			$msg .= 'آدرس IP باید عددی باشد!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && $banned['ip'][1] > 255 OR $banned['ip'][2] > 255 OR $banned['ip'][3] > 255 && $banned['ip'][3] != '*' OR $banned['ip'][4] > 255 && $banned['ip'][4] != '*')
		{
			$msg .= 'آدرس IP ازحدود طبیعی خارج است!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && substr($banned['ip'][1], 0, 1) == 0)
		{
			$msg .= 'آدرس IP نمیتواند با 0 شروع شود!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && $ip == '127.0.0.1')
		{
			$msg .= 'شما نمیتوانید Localhost را مسدود کنید!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (empty($msg) && $ip == get_ip())
		{
			$msg .= 'شما نمیتونید IP خودتان را مسدود کنید!<br>';
			$msg .= 'آدرس IP وارد شده: <u dir="ltr">'.$ip.'</u><br>';
		}
		if (!isset($banned['reason']) || empty($banned['reason']))
		{
			$msg .= 'دلیل مسدود سازی را ننوشته اید!<br>';
		}

		if (!empty($msg))
		{
			echo message($msg, 'error');
		}
		else
		{
			$d->update('banned', array(
				'ban_ip' => $ip,
				'ban_reason' => $banned['reason'],
				'ban_date' => time(),
			), "`ban_id`='{$id}'", 1);	
			
			if ($d->affected_rows())
			{
				$d->update('options', array('option_value'=>time()), "option_name='last-banned'", 1);
				remove_cache('options');
				echo message('آی پی <u dir="ltr">'.$ip.'</u> با موفقیت ویرایش شد!', 'success');
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}

	define('no_template', true);
}

function _delete()
{
	global $d;
	$id = isset($_GET['id'])? intval($_GET['id']) : 0;
	
	$d->delete('banned', "`ban_id`='{$id}'", 1);

	if ($d->affected_rows())
	{
		$d->update('options', array('option_value'=>time()), "option_name='last-banned'", 1);
		remove_cache('options');
		echo message('آی پی با موفقیت حذف شد.', 'success');
	}
	else
	{
		echo message('در حذف آی پی خطایی رخ داده مجدد تلاش کنید!', 'error');
	}
	_list();
}

$_GET['do'] = isset($_GET['do'])? $_GET['do'] : null;

switch($_GET['do'])
{
	case 'list':
	_list();
	break;

	case 'new':
	_new();
	break;

	case 'edit':
	_edit();
	break;

	case 'delete':
	_delete();
	break;

	default:
	_index();
	break;
}

?>