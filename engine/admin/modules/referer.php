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

member::check_admin_page_access('referer') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function referer_index()
{
	global $tpl, $options, $page, $d;
	require_once(engine_dir.'pagination.class.php');
	set_title('لینک دهندگان');

	$order = get_param($_GET, 'order', 'DESC');
	$order = $order=='DESC'? 'DESC' : 'ASC';
	
	$total = get_param($_GET, 'total', 100);
	$total = $total <= 0? 20 : $total;

	$_page = get_param($_GET, 'page', 1);
	$_page = $_page <= 0? 1 : $_page;

	$total_ref = $d->num_rows("SELECT `ref_id` FROM `#__referer`", true);

	$pagination = new pagination($total_ref, $total, $_page);

	$itpl = new template('engine/admin/template/referer.tpl');
	
	$d->query("SELECT * FROM #__referer ORDER BY ref_id {$order} LIMIT $pagination->Start, $pagination->End");
	if ($d->num_rows() >= 1)
	{
		while ($data = $d->fetch()) 
		{
			$itpl->add_for('referers', array(
				'{odd-even}' => odd_even(),
				'{id}' => $data['ref_id'],
				'{url}' => urldecode($data['ref_url']),
				'{redirect}' => redirect_link($data['ref_url']),
				'{domain}' => $data['ref_domain'],
				'{domain-redirect}' => redirect_link((substr($data['ref_url'], 0, 7) == 'http://'? 'http://' : 'https://') .$data['ref_domain']),
				'{ip}' => $data['ref_ip'],
				'{time}' => jdate('l j F Y ساعت g:i A', $data['ref_time']),
				'{past-time}' => get_past_time($data['ref_time']),
			));
		}
		
		$itpl->assign(array(
			'[referers]' => null,
			'[/referers]' => null,
		));
		$itpl->block('#\\[not-referers\\](.*?)\\[/not-referers\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-referers]' => null,
			'[/not-referers]' => null,
		));
		$itpl->block('#\\[referers\\](.*?)\\[/referers\\]#s', '');
	}

	$p = $pagination->build('{page}', true);
	if (is_array($p) && count($p)) 
	{	
		foreach($p as $link) 
		{
			if (!isset($link['page'])) continue;

			$itpl->add_for('pages', array(
				'{number}' => $link['number'],
				'replace' => array(
					'#\\[selected\\](.*?)\\[/selected\\]#s' => $link['number']==$_page? '\\1' : '',
				),
			));
		}

		$itpl->assign(array(
			'[pages]' => null,
			'[/pages]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[pages\\](.*?)\\[/pages\\]#s', '');
	}
	
	if ($order=='DESC')
	{
		$itpl->assign(array(
			'[desc]' => null,
			'[/desc]' => null,
		));
		$itpl->block('#\\[asc\\](.*?)\\[/asc\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[asc]' => null,
			'[/asc]' => null,
		));
		$itpl->block('#\\[desc\\](.*?)\\[/desc\\]#s', '');
	}
	
	if ($options['http-referer']==1)
	{
		$itpl->block('#\\[http-referer\\](.*?)\\[/http-referer\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[http-referer]' => null,
			'[/http-referer]' => null,
		));
	}
	
	$itpl->assign(array(
		'{total}' => $total,
		'{http-referer}' => $options['http-referer'],
	));
	
	if (is_ajax())
	{
		define('no_template', true);
		$itpl->display();
	}
	else
	{
		set_content(FALSE, $itpl->get_var());
	}
	unset($itpl);
}

function referer_delete()
{
	global $d;
	
	$d->delete('referer');

	if ($d->affected_rows())
	{
		echo message('لینک دهندگان با موفقیت حذف شدند.', 'success');
	}
	else
	{
		echo message('در حذف لینک دهندگان خطایی رخ داده مجدد تلاش کنید!', 'error');
	}
	referer_index();
}

$_GET['do'] = get_param($_GET, 'do');
switch($_GET['do'])
{
	case 'delete':
	referer_delete();
	break;

	default:
	referer_index();
	break;
}

?>