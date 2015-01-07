<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92) & Mohammad Sadegh Dehghan Niri (MSDN)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
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


	$itpl->assign(array(
		'[not-counter]' => null,
		'[/not-counter]' => null,
	));
	$itpl->block('#\\[counter\\](.*?)\\[/counter\\]#s', '');

if(isset($options['first_install']) && $options['first_install'] == 1)
{
	$itpl->assign(array(
		'[intro]' => null,
		'[/intro]' => null
	));
	unset($options['first_install']);
	remove_cache('options');

	$d->delete('options' , " `option_name` = 'first_install' " , 1);
}
else
{
	$itpl->block('#\\[intro\\](.*?)\\[/intro\\]#s', '');
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
$result .= "(SELECT option_value FROM `#__options` WHERE `option_name`='admin-notes') admin_note;".n;

$result = $d->query($result);
$countData = $d->fetch($result);
$d->free_result($result);

$array = array();
$array['{membersCount}'] = (int) $countData['membersCount'];
$array['{membersYesterday}'] = (int) $countData['membersYesterday'];
$array['{membersToday}'] = (int) $countData['membersToday'];
$array['{membersMonth}'] = (int) $countData['membersMonth'];
$array['{memberNewName}'] = $countData['memberNewName'];
$array['{postsCount}'] = (int) $countData['postsCount'];
$array['{commentsCount}'] = (int) $countData['commentsCount'];
$array['{commentsCount2}'] = (int) $countData['commentsCount2'];
$array['{version}'] = $options['version'];
$array['{error-reporting}'] = debug_system ? '<font color=red><b>فعال</b></font>' : '<font color=green><b>غیرفعال</b></font>';
$array['{offline}'] = $options['offline']==0? '<font color=green><b>فعال</b></font>' : '<font color=red><b>غیرفعال</b></font>';
$array['{rewrite}'] = $options['rewrite']==1? '<font color=green><b>فعال</b></font>' : '<font color=red><b>غیرفعال</b></font>';
$array['{default-module}'] = $options['default-module'];
$array['{theme}'] = $options['theme'];
$array['{http-referer}'] = $options['http-referer']==1? 'فعال' : 'غیرفعال';
$array['{admin}'] = $options['admin'];
$array['{admin-note}'] = $countData['admin_note'];

$itpl->assign($array);

if(! $server = get_cache('admin-server-info',86400)){
	$server = array();
	$server['{server-os}'] = (@php_uname( "s" ) . " " . @php_uname( "r" )) == '' ? 'تعریف نشده' : php_uname( "s" ) . " " . php_uname( "r" );
	$server['{server-php}'] = @phpversion() == '' ? 'تعریف نشده' : phpversion();
	$server['{server-mysql}'] = $d->version() == '' ? 'تعریف نشده' : $d->version();
	$server['{server-mysqli}'] = @extension_loaded('mysqli') ? '<font color=green><b>فعال</b></font>' : '<font color=red><b>غیرفعال</b></font>';
	
	$d->query( "SHOW TABLE STATUS" );
	$d_size = 0;
	while ( $data = $d->fetch() ) {
		if( strpos( $data['Name'], database_prefix ) !== false ) $d_size += $data['Data_length'] + $data['Index_length'];
	}
	$server['{server-mysql-space}'] = $d_size == 0 ? 'تعریف نشده' : file_size($d_size);
	$server['{server-cache-space}'] = file_size(get_size(engine_dir.'cache'));
	$max_upload = ((int) @ini_get('upload_max_filesize')) * 1024 * 1024;
	$server['{server-upload-limit}'] = $max_upload == 0 ? 'تعریف نشده' : file_size($max_upload);
	$max_memory = ((int) @ini_get('memory_limit')) * 1024 * 1024;
	$server['{server-memory-limit}'] = $max_memory == 0 ? 'تعریف نشده' : file_size($max_memory);
	$server['{server-free}'] = disk_free_space('.') ? file_size(disk_free_space('.')) : 'تعریف نشده';

	if( function_exists( 'apache_get_modules' ) ) {
		if( array_search( 'mod_rewrite', apache_get_modules() ) ) {
			$server['{server-rewrite}']= '<font color=green><b>فعال</b></font>';
		} else {
			$server['{server-rewrite}']= '<font color=red><b>غیرفعال</b></font>';
		}
	} else {
		$server['{server-rewrite}']= 'تعریف نشده';
	}

	$server['{server-zlib}'] = @extension_loaded('zlib') ? '<font color=green><b>فعال</b></font>' : '<font color=red><b>غیرفعال</b></font>';
	$server['{server-gd}'] = @extension_loaded('gd') ? '<font color=green><b>فعال</b></font>' : '<font color=red><b>غیرفعال</b></font>';

	set_cache('admin-server-info',$server);
}

$itpl->assign($server);

$tpl->assign('{content}', $itpl->get_var());
unset($itpl, $html);

?>