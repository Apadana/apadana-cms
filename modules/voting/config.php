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

function module_voting_run()
{
	require_once(root_dir.'modules/voting/functions.php');

	$_GET['b'] = get_param($_GET, 'b');

	switch ($_GET['b'])
	{
		case 'save':
		_save();
		break;
		
		case 'result':
		_result();
		break;
		
		default:
		_default();
		break;
	}
}

function module_voting_sitemap(&$sitemap)
{
	$sitemap->addItem(url('voting'), 0, 'monthly', '0.6');
}

function block_voting($op = null, $id = null, $position = null)
{
	if ($op == 'remove-cache') // admin
	{
		return true;
	}
	
	$op['id'] = !isset($op['id']) || intval($op['id']) <= 0? 0 : intval($op['id']);

	global $d;
	$d->query("SELECT * FROM #__voting WHERE `vote_status`='1'".($op['id']? " AND vote_id = '".$op['id']."'" : null)." ORDER BY vote_id DESC LIMIT 1");
	$row = $d->fetch();
	
	if (is_array($row) || !count($row))
	{
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

		unset($ip, $members);
		$html = voting_show($row, $form, false, null, rand(111111111, 999999999));

		($hook = get_hook('block_voting'))? eval($hook) : null;
	}
	else
	{
		$html = 'رای گیری یافت نشد!';
	}

	return $html;
}

function voting_show($row, $form = false, $showPercent = false, $message = null, $key = null)
{
	global $tpl, $page, $d, $options;

	$row['vote_case'] = trim($row['vote_case'], '|');
	$row['vote_case'] = explode('|', $row['vote_case']);
	$row['vote_result'] = explode(',', $row['vote_result']);
	$totalItemp = count($row['vote_case']);
	$totalCount = array_sum($row['vote_result']);

	$itpl = new template('modules/voting/html/block.tpl');

	($hook = get_hook('voting_show_start'))? eval($hook) : null;

	$progress = 1;
	for ($i=1; $i<=$totalItemp; $i++)
	{
		$count = isset($row['vote_result'][$i-1]) && isset($row['vote_result'][$i-1])>0? intval($row['vote_result'][$i-1]) : 0;
		$percent = $count * 100;

		if ($totalCount != 0)
			$percent /= $totalCount;
		else
			$percent = 0;

		$percent = floor($percent);
		$percent = $percent>100? 100 : $percent;
	
		$itpl->add_for('vote', array(
			'{name}' => $row['vote_case'][$i-1],
			'{progress}' => $progress,
			'{percent}' => $percent,
			'{count}' => $count,
			'replace' => array(
				'#\\[show-percent\\](.*?)\\[/show-percent\\]#s' => $showPercent? '\\1' : '',
				'#\\[not-show-percent\\](.*?)\\[/not-show-percent\\]#s' => !$showPercent? '\\1' : ''
			),
		));
		
		$progress++;
		if ($progress > 5) $progress = 1;
	};
	
	$itpl->assign(array(
		'{key}' => $key,
		'{id}' => $row['vote_id'],
		'{total}' => $totalCount,
		'{title}' => $row['vote_title'],
		'{button}' => $row['vote_button'],
		'{message}' => $message,
	));

	if ($form)
	{
		$itpl->assign(array(
			'[form]' => null,
			'[/form]' => null,
		));
		$itpl->block('#\\[not-form\\](.*?)\\[/not-form\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-form]' => null,
			'[/not-form]' => null,
		));
		$itpl->block('#\\[form\\](.*?)\\[/form\\]#s', '');
	}

	($hook = get_hook('voting_show_end'))? eval($hook) : null;

	return $itpl->get_var();
}

?>