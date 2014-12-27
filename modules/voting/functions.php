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
	global $d;

	$d->query("SELECT * FROM #__voting WHERE `vote_status`='1' ORDER BY vote_id DESC");
	if ($d->numRows() >= 1)
	{
		$html = '<ul id="apadana-voting-list">'.n;
		while($row = $d->fetch())
		{
			$html .= '<li><a href="'.url('voting/result/'.$row['vote_id']).'" title="ساخته شده در '.jdate('l j F Y ساعت g:i A', $row['vote_date']).'">'.$row['vote_title'].'</a></li>'.n;
		}
		$html .= '</ul>';
	}
	else
	{
		$html = message('هیچ نظرسنجی در سیستم یافت نشد!', 'error');
	}

	($hook = get_hook('voting_default'))? eval($hook) : null;

	set_title('نظرسنجی ها');
	set_meta('description', 'نظرسنجی ها', 'add');
	set_canonical(url('voting'));
	set_content('نظرسنجی ها', $html);
}

function _result()
{
	$id = get_param($_GET, 'c', 0);
	$vote = get_param($_GET, 'vote', 0);
	$key = get_param($_GET, 'key', 0);

	global $d;
	$d->query("SELECT * FROM #__voting WHERE `vote_status`='1' AND vote_id = '".$id."' ORDER BY vote_id DESC LIMIT 1");

	if ($d->numRows() >= 1)
	{
		$row = $d->fetch();
		$msg = null;
		
		($hook = get_hook('voting_result_start'))? eval($hook) : null;

		$ip = explode(',', $row['vote_ip']);
		$members = explode(',', $row['vote_members']);
		if (isset($_COOKIE['vote-'.$row['vote_id']]) || isset($_SESSION['vote-'.$row['vote_id']]) || in_array(get_ip(), $ip) || (member && in_array(member_name, $members)))
		{
			$form = false;
		}
		else
		{
			$form = true;
		}
			
		if (!$form && $vote)
		{
			$msg = '<font color="red">قبلا رای داده اید!!</font><br>';
			$vote = 0;
		}

		$html = voting_show($row, $vote? true : false, true, $msg, $key);
		
		if (is_ajax())
		{
			exit($html);
		}
		else
		{
			set_title('نظرسنجی ها');
			set_meta('description', 'نتایج نظرسنجی - '.$row['vote_title'], 'add');
			set_canonical(url('voting/result/'.$row['vote_id']));
			set_content('نتایج نظرسنجی', $html);
		}

		($hook = get_hook('voting_result_end'))? eval($hook) : null;
	}
	else
	{
		module_error_run('404');
	}
}

function _save()
{
	$message = null;
	$showPercent = false;
	$key = get_param($_GET, 'key', 0);
	$id = get_param($_POST, 'id', 0);
	$vote = get_param($_POST, 'vote');

	global $d;
	$d->query("SELECT * FROM #__voting WHERE `vote_status`='1' AND vote_id = '".$id."' ORDER BY vote_id DESC LIMIT 1");

	if ($d->numRows() >= 1)
	{
		$row = $d->fetch();
		$ip = explode(',', $row['vote_ip']);
		$members = explode(',', $row['vote_members']);

		($hook = get_hook('voting_save_start'))? eval($hook) : null;

		if (isset($_COOKIE['vote-'.$row['vote_id']]) || isset($_SESSION['vote-'.$row['vote_id']]) || in_array(get_ip(), $ip) || (member && in_array(member_name, $members)))
		{
			$form = false;
		}
		else
		{
			$form = true;
		}
		
		if ($form)
		{
			if (empty($vote))
			{
				$message = '<font color="red">گزینه انتخابی معتبر نیست!</font><br/>';
			}
			else
			{
				$row['vote_case'] = trim($row['vote_case'], '|');
				$case = explode('|', $row['vote_case']);
				$result = explode(',', $row['vote_result']);
				$totalItemp = count($case);
				$index = array_search($vote, $case);
				for ($i=0; $i<=$totalItemp; $i++)
				{
					$result[$i] = !isset($result[$i]) || intval($result[$i])<=0? 0 : intval($result[$i]);
					if ($i==$index)
					{
						$result[$i]++;
						$result_add = true;
					}
				}
				
				if (!isset($result_add))
				{
					$message = '<font color="red">گزینه ارسالی معتبر نیست!</font><br/>';
				}
				else
				{
					$row['vote_result'] = implode(',', $result);

					if (!in_array(get_ip(), $ip))
					{
						array_push($ip, get_ip());
					}
					$row['vote_ip'] = implode(',', $ip);
					
					if (member && !in_array(member_name, $members))
					{
						array_push($members, member_name);
					}
					$row['vote_members'] = implode(',', $members);

					$d->update('voting', array(
						'vote_result' => $row['vote_result'],
						'vote_ip' => trim($row['vote_ip'], ','),
						'vote_members' => trim($row['vote_members'], ','),
					), "vote_id='".$id."'", 1);

					set_cookie('vote-'.$row['vote_id'], md5($row['vote_id']));
					$_SESSION['vote-'.$row['vote_id']] = true;
					$form = false;
					$showPercent = true;

					($hook = get_hook('voting_save_success'))? eval($hook) : null;
				}
			}
		}
		
		($hook = get_hook('voting_save_end'))? eval($hook) : null;

		unset($ip, $members);
		$html = voting_show($row, $form, $showPercent, $message, $key);
	}
	else
	{
		$html = 'رای گیری یافت نشد!';
	}

	exit($html);
}

?>