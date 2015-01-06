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

member::check_admin_page_access('robots') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $tpl;
	set_title('مدیریت ربات ها');

	if (!file_exists(root_dir.'robots.txt'))
	{
		@file_put_contents(root_dir.'robots.txt', _default());
	}
	
	$itpl = new template('engine/admin/template/robots.tpl');

	$itpl->assign(array(
		'{robots}' => @file_get_contents(root_dir.'robots.txt'),
		'{time}' => jdate('l j F Y ساعت g:i A', filemtime(root_dir.'robots.txt')),
	));
	
	if (is_writable(root_dir.'robots.txt'))
	{
		$itpl->block('#\\[not-writable\\](.*?)\\[/not-writable\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-writable]' => null,
			'[/not-writable]' => null,
		));
	}
	
	set_content(FALSE, $itpl->get_var());
	unset($itpl);
}

function _edit()
{
	$robots = get_param($_POST, 'robots');

	if (isset($_GET['reset']))
	{
		$robots = _default();
	}
	if (!empty($robots))
	{
		if (is_writable(root_dir.'robots.txt'))
		{
			if (file_put_contents(root_dir.'robots.txt', $robots))
			{
				echo message(isset($_GET['reset'])? 'بازگشت محتوای فایل روبات به حالت اولیه با موفقیت انجام شد.' : 'فایل روبات با موفقیت ویرایش شد!', 'success');
				echo message('آخرین ویرایش در '.jdate('l j F Y ساعت g:i A', filemtime(root_dir.'robots.txt')).' بوده است.', 'info');
				if (isset($_GET['reset']))
				{
					$robots = trim($robots);
					$robots = str_replace(array("\n", "\r", "\t", "\r\n"), '\r\n', $robots);
					$robots = str_replace('\r\n\r\n', '\r\n', $robots);
					echo '<script>apadana.value("robots-textarea", "'.$robots.'")</script>';
				}
			}
			else
			{
				echo message('در ویرایش فایل خطایی رخ داده!', 'error');
			}
		}
		else
		{
			echo message('فایل robots.txt در روت سایت قابل نوشتن نیست.', 'error');
		}
	}
	else
	{
		echo message('محتوای فایل را وارد نکرده اید!', 'error');
	}
	exit;
}

function _default()
{
	$robots_content  = 'User-agent: *'."\r\n";
	$robots_content .= 'Disallow: /engine/'."\r\n";
	$robots_content .= 'Disallow: /modules/'."\r\n";
	$robots_content .= 'Disallow: /templates/'."\r\n";
	$robots_content .= 'Sitemap: '.url.'sitemap.xml';
	return $robots_content;
}

$_GET['do'] = get_param($_GET, 'do');

switch($_GET['do'])
{
	case 'edit':
	_edit();
	break;

	default:
	_index();
	break;
}

?>