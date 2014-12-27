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

set_title('مدیریت سایت');
$itpl = new template('engine/admin/template/index.tpl');

$i=1;
foreach ($admin as $row)
{
    if ($row['admin_page'] != 1 || !member::check_admin_page_access($row['admin_rights'])) continue;
	$array = array(
		'{link}' => url.str_replace('{admin}', $options['admin'], $row['admin_link']),
		'{image}' => url.$row['admin_image'],
		'{title}' => $row['admin_title'],
	);

	$i++;
    if ($i>=5)
	{
		$array['[tr]'] = null;	
		$array['[/tr]'] = null;	
		$i=1;
	}
	else
	{
		$array['replace'] = array(
			'#\\[tr\\](.*?)\\[/tr\\]#s' => ''
		);	
	}
	$itpl->add_for('index', $array);
}

$i=1;
foreach ($admin as $row)
{
    if ($row['admin_page'] != 2 || !member::check_admin_page_access($row['admin_rights'])) continue;
	$array = array(
		'{link}' => url.str_replace('{admin}', $options['admin'], $row['admin_link']),
		'{image}' => url.$row['admin_image'],
		'{title}' => $row['admin_title'],
	);

	$i++;
    if ($i>=5)
	{
		$array['[tr]'] = null;	
		$array['[/tr]'] = null;	
		$i=1;
	}
	else
	{
		$array['replace'] = array(
			'#\\[tr\\](.*?)\\[/tr\\]#s' => ''
		);	
	}
	$itpl->add_for('content', $array);
}

$i=1;
foreach ($admin as $row)
{
    if ($row['admin_page'] != 3 || !member::check_admin_page_access($row['admin_rights'])) continue;
	$array = array(
		'{link}' => url.str_replace('{admin}', $options['admin'], $row['admin_link']),
		'{image}' => url.$row['admin_image'],
		'{title}' => $row['admin_title'],
	);

	$i++;
    if ($i>=5)
	{
		$array['[tr]'] = null;	
		$array['[/tr]'] = null;	
		$i=1;
	}
	else
	{
		$array['replace'] = array(
			'#\\[tr\\](.*?)\\[/tr\\]#s' => ''
		);	
	}
	$itpl->add_for('options', $array);
}


if (member::check_admin_page_access('counter'))
{
	set_head('<script type="text/javascript" src="'.url.'engine/openChart/js/swfobject.js"></script>');
	set_head('<style type="text/css">.break{margin-right:6px;color:#3E6D8E;background-color:#E0EAF1;border-bottom:1px solid #3E6D8E;border-right:1px solid #7F9FB6;padding:3px 4px 3px 4px;margin:2px 2px 2px 0;text-decoration:none;font-size:90%;line-height:2.4;white-space:nowrap;cursor:default}</style>');

	$start = date('Y-m-d',strtotime(date('Y-m-d').' -9 days'));
	$time = array();
	for ($i=0; $i<=9; $i++)
	{
		$time[$i] = $start.' +'.$i.' days';
		$time[$i] = jdate('Y-m-d', strtotime($time[$i]));
	}

	$result = $d->query("
		SELECT (SELECT `counter_value` FROM #__counter WHERE counter_name='Day-".$time[0]."' AND counter_version='') value0,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[1]."' AND counter_version='') value1,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[2]."' AND counter_version='') value2,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[3]."' AND counter_version='') value3,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[4]."' AND counter_version='') value4,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[5]."' AND counter_version='') value5,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[6]."' AND counter_version='') value6,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[7]."' AND counter_version='') value7,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[8]."' AND counter_version='') value8,
		(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[9]."' AND counter_version='') value9;
	");

	$countData = $d->fetch($result);
	$d->freeResult($result);

	$chartValue[0] = intval($countData['value0']);
	$chartValue[1] = intval($countData['value1']);
	$chartValue[2] = intval($countData['value2']);
	$chartValue[3] = intval($countData['value3']);
	$chartValue[4] = intval($countData['value4']);
	$chartValue[5] = intval($countData['value5']);
	$chartValue[6] = intval($countData['value6']);
	$chartValue[7] = intval($countData['value7']);
	$chartValue[8] = intval($countData['value8']);
	$chartValue[9] = intval($countData['value9']);
	unset($countData);

	foreach ($chartValue as $i => $c)
	{
		$itpl->assign(array(
			'{chart-value-'.$i.'}' => $c,
			'{chart-dete-'.$i.'}' => $time[$i]
		));
	}
	
	$itpl->assign('{chart-url}', urlencode(admin_page.'&module=counter&chart=4'));
	
	$itpl->assign(array(
		'[counter]' => null,
		'[/counter]' => null,
	));
	$itpl->block('#\\[not-counter\\](.*?)\\[/not-counter\\]#s', '');
}
else
{
	$itpl->assign(array(
		'[not-counter]' => null,
		'[/not-counter]' => null,
	));
	$itpl->block('#\\[counter\\](.*?)\\[/counter\\]#s', '');
}

$t = strtotime(date('Y-m-d'));
$t2 = strtotime(date('Y-m-d').' -1 days');
$t3 = strtotime(date('Y-m-d').' +1 days');
$t4 = strtotime(date('Y-m').' +1 month');

$result  = "SELECT (SELECT COUNT(*) FROM `#__members`) membersCount,".n;
$result .= "(SELECT COUNT(`member_id`) FROM `#__members` WHERE `member_date` BETWEEN '$t2' AND '$t') membersYesterday,".n;
$result .= "(SELECT COUNT(`member_id`) FROM `#__members` WHERE `member_date` BETWEEN '$t' AND '$t3') membersToday,".n;
$result .= "(SELECT COUNT(`member_id`) FROM `#__members` WHERE `member_date` BETWEEN '".strtotime(date('Y-m'))."' AND '$t4') membersMonth,".n;
$result .= "(SELECT MAX(`member_id`) FROM `#__members`) memberNewID,".n;
$result .= "(SELECT `member_name` FROM `#__members` WHERE `member_id`=memberNewID) memberNewName,".n;
$result .= "(SELECT COUNT(`post_id`) FROM `#__posts` WHERE post_approve='1' AND post_date <= '".time_now."') postsCount,".n;
$result .= "(SELECT COUNT(*) FROM `#__comments`) commentsCount,".n;
$result .= "(SELECT COUNT(`comment_id`) FROM `#__comments` WHERE comment_approve='0') commentsCount2,".n;
$result .= is_module('simple-links')? "(SELECT COUNT(`link_id`) FROM `#__simple_links` WHERE `link_active`='1') linksCount,".n : null;
$result .= "(SELECT COUNT(`member_id`) FROM `#__members` WHERE `member_newsletter`='1') newsletterCount,".n;
$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Total') totalCount,".n;
$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Year-".jdate('Y')."') yearCount,".n;
$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Month-".jdate('Y-m')."') monthCount,".n;
$result .= "(SELECT `counter_value` FROM `#__counter` WHERE `counter_name`='Day-".jdate('Y-m-d')."') dayCount;".n;

$result = $d->query($result);
$countData = $d->fetch($result);
$d->freeResult($result);

$array = array();
$array['{membersCount}'] = (int) $countData['membersCount'];
$array['{membersYesterday}'] = (int) $countData['membersYesterday'];
$array['{membersToday}'] = (int) $countData['membersToday'];
$array['{membersMonth}'] = (int) $countData['membersMonth'];
$array['{memberNewName}'] = $countData['memberNewName'];
$array['{postsCount}'] = (int) $countData['postsCount'];
$array['{commentsCount}'] = (int) $countData['commentsCount'];
$array['{commentsCount2}'] = (int) $countData['commentsCount2'];
$array['{linksCount}'] = isset($countData['linksCount'])? $countData['linksCount'] : 0;
$array['{newsletterCount}'] = $countData['newsletterCount'];
$array['{totalCount}'] = (int) $countData['totalCount'];
$array['{yearCount}'] = (int) $countData['yearCount'];
$array['{monthCount}'] = (int) $countData['monthCount'];
$array['{dayCount}'] = (int) $countData['dayCount'];
$array['{version}'] = $options['version'];
$array['{error-reporting}'] = error_reporting? '<font color=red><b>فعال</b></font>' : '<font color=green><b>غیرفعال</b></font>';
$array['{offline}'] = $options['offline']==0? '<font color=green><b>فعال</b></font>' : '<font color=red><b>غیرفعال</b></font>';
$array['{rewrite}'] = $options['rewrite']==1? '<font color=green><b>فعال</b></font>' : '<font color=red><b>غیرفعال</b></font>';
$array['{default-module}'] = $options['default-module'];
$array['{theme}'] = $options['theme'];
$array['{http-referer}'] = $options['http-referer']==1? 'فعال' : 'غیرفعال';
$array['{admin}'] = $options['admin'];

$itpl->assign($array);

$tpl->assign('{content}', $itpl->get_var());
unset($itpl, $html);

?>