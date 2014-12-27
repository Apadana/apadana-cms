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
	global $page, $tpl, $d;
	member::check_admin_page_access('simple-links') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	set_title('پیوندها');
	set_head('<script type="text/javascript" src="'.url.'engine/javascript/jscolor/jscolor.js"></script>');

	$itpl = new template('modules/simple-links/html/admin/index.tpl');
	$itpl->assign(array(
		'[not-show-list]' => null,
		'[/not-show-list]' => null,
	));
	$itpl->block('#\\[show-list\\](.*?)\\[/show-list\\]#s', '');

	$tpl->assign('{content}', $itpl->get_var());
}

function _list()
{
	global $page, $tpl, $d;
	member::check_admin_page_access('simple-links') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$itpl = new template('modules/simple-links/html/admin/index.tpl');

	$d->query("SELECT * FROM #__simple_links ORDER BY link_id DESC");
	if ($d->numRows() >= 1)
	{
		while($row = $d->fetch())
		{
			$itpl->add_for('list', array(
				'{odd-even}' => odd_even(),
				'{id}' => $row['link_id'],
				'{title}' => $row['link_title'],
				'{description}' => $row['link_description'],
				'{href}' => $row['link_href'],
				'{redirect}' => redirect_link($row['link_href']),
				'{target}' => $row['link_target'],
				'{direct-link}' => $row['link_direct_link'],
				'{color}' => $row['link_color'],
				'{bold}' => $row['link_bold'],
				'{strikethrough}' => $row['link_strikethrough'],
				'{active}' => $row['link_active'],
				'{language}' => $row['link_language'],
				'replace' => array(
					'#\\[active\\](.*?)\\[/active\\]#s' => $row['link_active']? '\\1' : '',
					'#\\[not-active\\](.*?)\\[/not-active\\]#s' => !$row['link_active']? '\\1' : '',
					'#\\[direct-link\\](.*?)\\[/direct-link\\]#s' => $row['link_direct_link']? '\\1' : '',
					'#\\[not-direct-link\\](.*?)\\[/not-direct-link\\]#s' => !$row['link_direct_link']? '\\1' : '',
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
	global $page, $tpl, $d;
	member::check_admin_page_access('simple-links') or exit('Warning access!');

	$link = get_param($_POST, 'link', null, 1);
	if (isset($link) && is_array($link) && count($link))
	{
		$msg = null;
		$link['title'] = htmlencode($link['title']);
		$link['description'] = htmlencode($link['description']);
		$link['href'] = nohtml($link['href']);
		$link['color'] = nohtml($link['color']);
		$link['target'] = !isset($link['target']) || intval($link['target'])<=0? 0 : 1;
		$link['direct-link'] = !isset($link['direct-link']) || intval($link['direct-link'])<=0? 0 : 1;
		$link['bold'] = !isset($link['bold']) || intval($link['bold'])<=0? 0 : 1;
		$link['strikethrough'] = !isset($link['strikethrough']) || intval($link['strikethrough'])<=0? 0 : 1;
		$link['active'] = !isset($link['active']) || intval($link['active'])<=0? 0 : 1;

		if (!isset($link['title']) || empty($link['title']))
		{
			$msg .= 'عنوان پیوند را ننوشته اید!<br>';
		}
		
		if (!isset($link['href']) || !validate_url($link['href']))
		{
			$msg .= 'آدرس پیوند معتبر نیست!<br>';
		}
		
		if (!empty($msg))
		{
			echo message($msg, 'error');
		}
		else
		{
			$d->insert('simple_links', array(
				'link_title' => $link['title'],
				'link_description' => $link['description'],
				'link_href' => $link['href'],
				'link_direct_link' => $link['direct-link'],
				'link_target' => $link['target']==1? '_blank' : '_self',
				'link_color' => isset($link['no-color'])? null : '#'.$link['color'],
				'link_bold' => $link['bold'],
				'link_strikethrough' => $link['strikethrough'],
				'link_active' => $link['active'],
			));	
			
			if ($d->affectedRows())
			{
				remove_cache('simple-links');
				echo '<script>apadana.hideID("form-new-sLink")</script>';
				echo message('پیوند با موفقیت ثبت شد!', 'success');
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}
	exit;
}

function _edit()
{
	global $page, $tpl, $d;
	member::check_admin_page_access('simple-links') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	
	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT link_id FROM #__simple_links WHERE `link_id`='$id' LIMIT 1");
	$data = $d->fetch();
	
	if (!is_array($data) || !count($data))
	{
		echo message('این پیوند وجود ندارد!', 'error');
		exit;
	}
	
	$link = get_param($_POST, 'link', null, 1);
	if (isset($link) && is_array($link) && count($link))
	{
		$msg = null;
		$link['title'] = htmlencode($link['title']);
		$link['description'] = htmlencode($link['description']);
		$link['href'] = nohtml($link['href']);
		$link['color'] = nohtml($link['color']);
		$link['target'] = !isset($link['target']) || intval($link['target'])<=0? 0 : 1;
		$link['direct-link'] = !isset($link['direct-link']) || intval($link['direct-link'])<=0? 0 : 1;
		$link['bold'] = !isset($link['bold']) || intval($link['bold'])<=0? 0 : 1;
		$link['strikethrough'] = !isset($link['strikethrough']) || intval($link['strikethrough'])<=0? 0 : 1;
		$link['active'] = !isset($link['active']) || intval($link['active'])<=0? 0 : 1;

		if (!isset($link['title']) || empty($link['title']))
		{
			$msg .= 'عنوان پیوند را ننوشته اید!<br>';
		}
		
		if (!isset($link['href']) || !validate_url($link['href']))
		{
			$msg .= 'آدرس پیوند معتبر نیست!<br>';
		}
		
		if (!empty($msg))
		{
			echo message($msg, 'error');
		}
		else
		{
			$d->update('simple_links', array(
				'link_title' => $link['title'],
				'link_description' => $link['description'],
				'link_href' => $link['href'],
				'link_direct_link' => $link['direct-link'],
				'link_target' => $link['target']==1? '_blank' : '_self',
				'link_color' => isset($link['no-color'])? null : '#'.$link['color'],
				'link_bold' => $link['bold'],
				'link_strikethrough' => $link['strikethrough'],
				'link_active' => $link['active'],
			), "link_id='".$id."'", 1);	
			
			if ($d->affectedRows())
			{
				remove_cache('simple-links');
				echo message('پیوند با موفقیت ویرایش شد!', 'success');
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}
	exit;
}

function _active()
{
	member::check_admin_page_access('simple-links') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	global $d;
	$id = get_param($_GET, 'id', 0);
	$d->query("SELECT link_active FROM #__simple_links WHERE `link_id`='$id' LIMIT 1");
	$active = $d->fetch();
	$active = $active['link_active']==0? 1 : 0;
	
	$d->update('simple_links', array(
		'link_active' => $active,
	), "`link_id`='{$id}'", 1);

	if ($d->affectedRows())
	{
		remove_cache('simple-links');
		exit($active==1? 'active' : 'inactive');
	}
	else
	{
		exit('خطا');
	}
}

function _delete()
{
	member::check_admin_page_access('simple-links') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	global $d;
	$id = get_param($_GET, 'id', 0);
	
	$d->delete('simple_links', "`link_id`='{$id}'", 1);

	if ($d->affectedRows())
	{
		remove_cache('simple-links');
		echo message('پیوند با موفقیت حذف شد.', 'success');
	}
	else
	{
		echo message('در حذف پیوند خطایی رخ داده مجدد تلاش کنید!', 'error');
	}
	_list();
}

?>