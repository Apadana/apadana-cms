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

member::check_admin_page_access('pages') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $options, $page, $d, $tpl;
	require_once(engine_dir.'pagination.class.php');
	require_once(engine_dir.'editor.function.php');

	set_title('صفحات اضافی');

	$order = get_param($_GET, 'order', 'DESC');
	$order = $order=='DESC'? 'DESC' : 'ASC';
	
	$total = get_param($_GET, 'total', 20);
	$total = $total<=0? 20 : $total;

	$_page = get_param($_GET, 'page', 1);
	$_page = $_page<=0? 1 : $_page;

	$total_posts = $d->num_rows("SELECT `page_id` FROM `#__pages`", true);

	$pagination = new pagination($total_posts, $total, $_page);

	$itpl = new template('modules/pages/html/admin/index.tpl');

	$theme = template_info($options['theme']);
	$theme = $theme['pages'];
	$theme = explode(',', $theme);
	$theme2 = array();
	foreach($theme as $t) if (!empty($t) && is_string($t)) $theme2[] = $t;
	if (is_array($theme2) && count($theme2))
	{
		$itpl->add_for('templates', array('{name}' => 'بدون قالب'));
		foreach($theme2 as $t)
		{
			if (!empty($t) && is_string($t))
			{
				$itpl->add_for('templates', array('{name}' => $t));
			}
		}
		$itpl->assign(array(
			'[templates]' => null,
			'[/templates]' => null,
		));
		$itpl->block('#\\[not-templates\\](.*?)\\[/not-templates\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-templates]' => null,
			'[/not-templates]' => null,
		));
		$itpl->block('#\\[templates\\](.*?)\\[/templates\\]#s', '');
	}
	unset($theme, $theme2, $t);

	if (is_ajax())
	{
		$d->query("
			SELECT pages.*, members.member_name, members.member_alias
			FROM #__pages AS pages	
			LEFT JOIN #__members AS members ON members.member_id=pages.page_author
			GROUP BY pages.page_id
			ORDER BY pages.page_id {$order}
			LIMIT $pagination->start, $pagination->end
		");
		if ($d->num_rows() >= 1)
		{
			while($data = $d->fetch()) 
			{
				$itpl->add_for('list', array(
					'{odd-even}' => odd_even(),
					'{id}' => $data['page_id'],
					'{page-url}' => url('pages/'.($options['rewrite'] == 1? $data['page_slug'] : $data['page_id'])),
					'{title}' => $data['page_title'],
					'{author}' => empty($data['member_alias'])? $data['member_alias'] : $data['member_name'],
					'{past-time}' => get_past_time($data['page_time']),
					'{approve}' => $data['page_approve'],
					'{comment-count}' => $data['page_comment_count'],
					'replace' => array(
						'#\\[approve\\](.*?)\\[/approve\\]#s' => $data['page_approve']==1? '\\1' : '',
						'#\\[not-approve\\](.*?)\\[/not-approve\\]#s' => $data['page_approve']!=1? '\\1' : ''
					)
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
	}
	
	if ($order == 'DESC')
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
	
	$itpl->assign(array(
		'{total}' => $total,
		'{textarea}' => wysiwyg_textarea('pages[text]', null),
		'{textarea-edit}' => wysiwyg_textarea('pages[text-edit]', null),
	));
	
	if (is_ajax())
	{
		define('no_template', true);
		$itpl->display();
	}
	else
	{
		$tpl->assign('{content}', $itpl->get_var());
	}
}

function _new()
{
	global $d;

	$json = array();
	$json['type'] = 'error';
	$json['message'] = array();

	$pages = get_param($_POST, 'pages', null, 1);
	$pages['title'] = isset($pages['title'])? htmlencode($pages['title']) : null;
	$pages['slug'] = isset($pages['slug']) && $pages['slug'] != ''? slug($pages['slug']) : slug($pages['title']);
	$pages['text'] = isset($pages['text'])? trim($pages['text']) : null;
	$pages['theme'] = isset($pages['theme']) && is_alphabet($pages['theme'])? $pages['theme'] : null;
	$pages['view'] = isset($pages['view'])? intval($pages['view']) : 1;
	$pages['comment'] = isset($pages['comment']) && $pages['comment'] == 1? 1 : 0;
	$pages['approve'] = isset($pages['approve']) && $pages['approve'] == 1? 1 : 0;
	
	if (empty($pages['title']))
	{
		$json['message'][] = 'عنوان صفحه را ننوشته اید!';
	}
	
	if ($pages['slug'] == '')
	{
		$json['message'][] = 'نام مستعار صفحه را ننوشته اید!';
	}
	else
	{
		if (apadana_strlen($pages['slug']) > 200)
		{
			$json['message'][] = 'نام مستعار بیش از انداره طولانی است!';
		}
		elseif ($d->num_rows("SELECT `page_id` FROM `#__pages` WHERE `page_slug`='".$d->escape_string($pages['slug'])."'", true) >= 1)
		{
			$json['message'][] = 'نام مستعار تکراری است یک نام مستعار دیگر انتخاب کنید!';
		}
	}

	if (empty($pages['text']))
	{
		$json['message'][] = 'متن صفحه را ننوشته اید!';
	}
	
	if (count($json['message']))
	{
		$json['message'] = implode('<br/>', $json['message']);
	}
	else
	{
		$pages['text'] = template_off($pages['text']);
		$pages['text'] = template_off($pages['text']);
		// $pages['text'] = str_replace('[', '&#x5B;', $pages['text']);
		// $pages['text'] = str_replace('{', '&#x7B;', $pages['text']);
	
		$d->insert('pages', array(
			'page_title' => $pages['title'],
			'page_slug' => $pages['slug'],
			'page_text' => $pages['text'],
			'page_theme' => $pages['theme'],
			'page_view' => $pages['view'],
			'page_comment' => $pages['comment'],
			'page_approve' => $pages['approve'],
			'page_author' => member_id,
			'page_time' => time(),
		));

		if ($d->affected_rows())
		{
			remove_cache('module-pages-block', true);
			$json['type'] = 'success';
			$json['message'] = 'صفحه با موفقیت ذخیره شد.';
		}
		else
		{
			$json['message'] = 'در ذخیره اطلاعات خطایی رخ داده مجدد تلاش کنید!';
		}
	}

	$json['slug'] = urldecode($pages['slug']);
	exit(json_encode($json));
}

function _get_data()
{
	global $d;
	
	$_GET['id'] = get_param($_GET, 'id', 0);
	$d->query("SELECT * FROM #__pages WHERE page_id='".$_GET['id']."' LIMIT 1");

	if ($d->num_rows() <= 0)
	{
		exit('{"error":"not found"}');
	}
	
	$row = $d->fetch();

	if ($row['page_author'] != member_id && group_super_admin != 1)
	{
		exit('{"error":"access"}');
	}

	$row['page_slug'] = urldecode($row['page_slug']);
	exit(json_encode($row));
}

function _edit()
{
	global $d;
	
	$_GET['id'] = get_param($_GET, 'id', 0);
	$d->query("SELECT * FROM #__pages WHERE page_id='".$_GET['id']."' LIMIT 1");

	if ($d->num_rows() <= 0)
	{
		exit('{"type":"error", "message":"این صفحه یافت نشد!"}');
	}
	
	$row = $d->fetch();

	if ($row['page_author'] != member_id && group_super_admin != 1)
	{
		exit('{"type":"error", "message":"شما دسترسی لازم برای ویرایش این صفحه را ندارید!"}');
	}

	$json = array();
	$json['type'] = 'error';
	$json['message'] = array();
	
	$pages = get_param($_POST, 'pages', null, 1);
	
	if (is_array($pages) && count($pages))
	{
		$pages['title'] = isset($pages['title'])? htmlencode($pages['title']) : null;
		$pages['slug'] = isset($pages['slug']) && $pages['slug'] != ''? slug($pages['slug']) : slug($pages['title']);
		$pages['text-edit'] = isset($pages['text-edit'])? trim($pages['text-edit']) : null;
		$pages['theme'] = isset($pages['theme']) && is_alphabet($pages['theme'])? $pages['theme'] : null;
		$pages['view'] = isset($pages['view'])? intval($pages['view']) : 1;
		$pages['comment'] = isset($pages['comment']) && $pages['comment'] == 1? 1 : 0;
		$pages['approve'] = isset($pages['approve']) && $pages['approve'] == 1? 1 : 0;
		
		if (empty($pages['title']))
		{
			$json['message'][] = 'عنوان صفحه را ننوشته اید!';
		}
		
		if ($pages['slug'] == '')
		{
			$json['message'][] = 'نام مستعار صفحه را ننوشته اید!';
		}
		else
		{
			if (apadana_strlen($pages['slug']) > 200)
			{
				$json['message'][] = 'نام مستعار بیش از انداره طولانی است!';
			}
			elseif ($row['page_slug'] != $pages['slug'] && $d->num_rows("SELECT `page_id` FROM `#__pages` WHERE `page_slug`='".$d->escape_string($pages['slug'])."'", true) >= 1)
			{
				$json['message'][] = 'نام مستعار تکراری است یک نام مستعار دیگر انتخاب کنید!';
			}
		}

		if (empty($pages['text-edit']))
		{
			$json['message'][] = 'متن صفحه را ننوشته اید!';
		}
		
		if (count($json['message']))
		{
			$json['message'] = implode('<br/>', $json['message']);
		}
		else
		{
			$pages['text-edit'] = template_off($pages['text-edit']);
			$pages['text-edit'] = template_off($pages['text-edit']);
			// $pages['text-edit'] = str_replace('[', '&#x5B;', $pages['text-edit']);
			// $pages['text-edit'] = str_replace('{', '&#x7B;', $pages['text-edit']);

			$d->update('pages', array(
				'page_title' => $pages['title'],
				'page_slug' => $pages['slug'],
				'page_text' => $pages['text-edit'],
				'page_theme' => $pages['theme'],
				'page_view' => $pages['view'],
				'page_comment' => $pages['comment'],
				'page_approve' => $pages['approve'],
			), "page_id='".$_GET['id']."'", 1);
			
			if ($d->affected_rows())
			{
				$json['type'] = 'success';
				$json['message'] = 'صفحه با موفقیت ویرایش شد.';
				remove_cache('module-pages-block', true);
			}
			else
			{
				$json['message'] = 'در ذخیره اطلاعات خطایی رخ داده مجدد تلاش کنید!';
			}
		}
	}
	
	$json['slug'] = urldecode($pages['slug']);
	exit(json_encode($json));
}

function _approve()
{
	global $d;
	$_GET['id'] = get_param($_GET, 'id', 0);
	
	$d->query("SELECT `page_author`, `page_approve` FROM #__pages WHERE page_id='".$_GET['id']."' LIMIT 1");
	$row = $d->fetch();

	if ((!is_array($row) || !count($row)) || ($row['page_author'] != member_id && group_super_admin != 1))
	{
		exit('شما دسترسی لازم برای تغییر وضعیت این صفحه را ندارید!');
	}

	$approve = $row['page_approve'] == 0? 1 : 0;
	
	$d->update('pages', array(
		'page_approve' => $approve,
	), "`page_id`='{$_GET['id']}'", 1);

	if ($d->affected_rows())
	{
		remove_cache('module-pages-block', true);
		exit($approve == 1? 'active' : 'inactive');
	}
	else
	{
		exit('در ذخیره خطایی رخ داده!');
	}
}

function _delete()
{
	global $d;
	
	$_GET['id'] = get_param($_GET, 'id', 0);
	$d->query("SELECT page_title, page_author FROM #__pages WHERE page_id='".$_GET['id']."' LIMIT 1");
	$row = $d->fetch();

	if (!is_array($row) && !count($row))
	{
		redirect(admin_page.'&module=pages');
	}
	
	if ($row['page_author'] == member_id || group_super_admin == 1)
	{
		$d->delete('pages', "page_id='".$_GET['id']."'", 1);
		remove_cache('module-pages-block', true);
		echo message('صفحه <b>'.$row['page_title'].'</b> با موفقیت حذف شد.', 'success');
	}
	else
	{
		echo message('شما دسترسی لازم برای حذف این صفحه را ندارید!', 'error');
	}

	_index();
}

?>