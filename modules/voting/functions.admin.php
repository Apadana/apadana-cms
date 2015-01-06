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

function _default()
{
	global $tpl, $d;

	member::check_admin_page_access('voting') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	set_title('نظرسنجی ها');
	
	$itpl = new template('modules/voting/html/admin/votes.tpl');
	$itpl->assign(array(
		'[not-show-list]' => null,
		'[/not-show-list]' => null,
	));
	$itpl->block('#\\[show-list\\](.*?)\\[/show-list\\]#s', '');

	$tpl->assign('{content}', $itpl->get_var());
}

function _list()
{
	global $tpl, $d;

	member::check_admin_page_access('voting') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$itpl = new template('modules/voting/html/admin/votes.tpl');

	$d->query("SELECT * FROM #__voting ORDER BY vote_id DESC");
	if ($d->num_rows() >= 1)
	{
		while($row = $d->fetch())
		{
			$row['vote_case'] = trim($row['vote_case'], '|');
			$row['vote_case'] = explode('|', $row['vote_case']);
			$row['vote_result'] = explode(',', $row['vote_result']);
			$totalItemp = count($row['vote_case']);
			$totalCount = array_sum($row['vote_result']);
		
			$itpl->add_for('list', array(
				'{odd-even}' => odd_even(),
				'{id}' => $row['vote_id'],
				'{date}' => jdate('l j F Y ساعت g:i A', $row['vote_date']),
				'{title}' => $row['vote_title'],
				'{button}' => $row['vote_button'],
				'{status}' => $row['vote_status'],
				'{case}' => trim(implode('|', $row['vote_case']), '|'),
				'{result}' => trim(implode(',', $row['vote_result']), ','),
				'{total}' => $totalCount,
				'replace' => array(
					'#\\[status\\](.*?)\\[/status\\]#s' => $row['vote_status']? '\\1' : '',
					'#\\[not-status\\](.*?)\\[/not-status\\]#s' => !$row['vote_status']? '\\1' : '',
				),
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
	global $tpl, $d;

	member::check_admin_page_access('voting') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$msg = array();
	$vote = get_param($_POST, 'vote', null, 1);

	if (isset($vote) && is_array($vote) && count($vote))
	{
		$vote['title'] = isset($vote['title'])? htmlencode($vote['title']) : null;
		$vote['status'] = isset($vote['status']) && $vote['status'] == 1? 1 : 0;
		$vote['case'] = isset($vote['case'])? array_map('nohtml', $vote['case']) : array();
		$vote['button'] = isset($vote['button'])? htmlencode($vote['button']) : null;
		$vote['result'] = get_param($vote, 'result', 0);
		
		$case = $result = null;
		
		$count = 0;
		for ($i = 1; $i <= 20; $i++)
		{
			$vote['case'][$i] = trim($vote['case'][$i]);
			if (!isset($vote['case'][$i]) || empty($vote['case'][$i])) continue;
			$vote['case'][$i] = str_replace('|', ' ', $vote['case'][$i]);
			$case .= $vote['case'][$i].'|';
			$result .= '0,';
			$count++;
		}

		$vote['case'] = trim($case, '|');
		$vote['result'] = trim($result, ',');
		
		if (empty($vote['title']))
		{
			$msg[] = 'عنوان نظرسنجی را ننوشته اید!';
		}
		
		if ($count < 2)
		{
			$msg[] = 'حداقل 2 گزینه را باید پر کنید!';
		}
		
		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
		}
		else
		{
			$d->insert('voting', array(
				'vote_title' => $vote['title'],
				'vote_case' => $vote['case'],
				'vote_result' => $vote['result'],
				'vote_button' => $vote['button'],
				'vote_status' => $vote['status'],
				'vote_date' => time(),
			));	
			
			if ($d->affected_rows())
			{
				echo '<script>apadana.hideID("form-new-voting")</script>';
				echo message('نظرسنجی با موفقیت ثبت شد!', 'success');
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
		//dump($vote);
	}
	exit;
}

function _edit()
{
	global $page, $tpl, $d;

	member::check_admin_page_access('voting') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	
	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT vote_id FROM #__voting WHERE `vote_id`='$id' LIMIT 1");
	$data = $d->fetch();
	
	if (!is_array($data) || !count($data))
	{
		echo message('این نظرسنجی وجود ندارد!', 'error');
		exit;
	}
	
	$msg = null;
	$vote = get_param($_POST, 'vote', null, 2);
	if (isset($vote) && is_array($vote) && count($vote))
	{
		$vote['title'] = isset($vote['title'])? htmlencode($vote['title']) : null;
		$vote['status'] = isset($vote['status']) && $vote['status'] == 1? 1 : 0;
		$vote['case'] = isset($vote['case'])? array_map('nohtml', $vote['case']) : array();
		$vote['button'] = isset($vote['button'])? htmlencode($vote['button']) : null;
		$vote['result'] = get_param($vote, 'result', 0);
	
		$case = $result = null;

		$count = 0;
		for ($i = 1;$i <= 20; $i++)
		{
			$vote['case'][$i] = trim($vote['case'][$i]);
			if (!isset($vote['case'][$i]) || empty($vote['case'][$i])) continue;
			$vote['case'][$i] = str_replace('|', ' ', $vote['case'][$i]);
			$case .= $vote['case'][$i].'|';
			$result .= (isset($vote['result'][$i])? $vote['result'][$i] : 0).',';
			$count++;
		}

		$vote['case'] = trim($case, '|');
		$vote['result'] = trim($result, ',');
		
		if (empty($vote['title']))
		{
			$msg[] = 'عنوان نظرسنجی را ننوشته اید!';
		}
		
		if ($count < 2)
		{
			$msg[] = 'حداقل 2 گزینه را باید پر کنید!';
		}
		
		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
		}
		else
		{
			$d->update('voting', array(
				'vote_title' => $vote['title'],
				'vote_case' => $vote['case'],
				'vote_result' => $vote['result'],
				'vote_button' => $vote['button'],
				'vote_status' => $vote['status'],
			), "`vote_id`='{$id}'", 1);	
			
			if ($d->affected_rows())
			{
				echo message('نظرسنجی با موفقیت ویرایش شد!', 'success');
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
		//dump($vote);
	}
	exit;
}

function _status()
{
	global $d;

	member::check_admin_page_access('voting') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_GET, 'id', 0);
	$d->query("SELECT `vote_status` FROM `#__voting` WHERE `vote_id`='".$id."' LIMIT 1");
	$status = $d->fetch();
	$status = $status['vote_status'] == 0? 1 : 0;
	
	$d->update('voting', array(
		'vote_status' => $status,
	), "`vote_id`='{$id}'", 1);

	if ($d->affected_rows())
	{
		exit($status == 1? 'active' : 'inactive');
	}
	else
	{
		exit('خطا');
	}
}

function _delete()
{
	global $d;

	member::check_admin_page_access('voting') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_GET, 'id', 0);
	
	$d->delete('voting', "`vote_id`='{$id}'", 1);

	if ($d->affected_rows())
	{
		echo message('نظرسنجی با موفقیت حذف شد.', 'success');
	}
	else
	{
		echo message('در حذف نظرسنجی خطایی رخ داده مجدد تلاش کنید!', 'error');
	}
	_list();
}

?>