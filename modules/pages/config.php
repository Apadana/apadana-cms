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

function module_pages_run()
{
	global $page, $d, $tpl, $options;
	
	if ($options['rewrite'] == 1)
	{
		$slug = isset($_GET['b'])? urlencode($_GET['b']) : null;
	}
	else
	{
		$slug = isset($_GET['b'])? intval($_GET['b']) : 0;
	}

	if ($slug == '' || ($options['rewrite'] != 1 && is_numeric($slug) && $slug <= 0))
	{
		set_title('صفحات');
		set_meta('description', 'صفحات سایت', 'add');
		set_canonical(url('pages'));
		set_content('لیست صفحات اضافی', block_pages(array(
			'total' => 500,
			'order' => 'rand'
		), 'list'));

		($hook = get_hook('module_pages_index'))? eval($hook) : null;
	}
	else
	{
		$d->query("
			SELECT pages.*, members.member_name, members.member_alias
			FROM #__pages AS pages	
			LEFT JOIN #__members AS members ON members.member_id=pages.page_author
			WHERE pages.page_approve = '1' AND pages.page_".($options['rewrite'] == 1? 'slug' : 'id')." = '".$d->escapeString($slug)."'
			GROUP BY pages.page_id
			ORDER BY pages.page_time DESC, pages.page_id DESC
			LIMIT 1
		");
		$row = $d->fetch();

		($hook = get_hook('module_pages_start'))? eval($hook) : null;

		if (!isset($row) || !is_array($row) || !count($row))
		{
			module_error_run('404');
		}
		else
		{
			$row['page_theme'] = is_alphabet($row['page_theme'])? $row['page_theme'] : null;

			set_theme($row['page_theme']);
			set_title($row['page_title']);
			set_meta('description', $row['page_title'], 'add');
			set_canonical(url('pages/'.($options['rewrite'] == 1? $row['page_slug'] : $row['page_id'])));

			if ($row['page_view'] == 2 && !member) $row['page_text'] = message('فقط کاربران عضو سایت می توانند این صفحه را ببینند!', 'error');
			elseif ($row['page_view'] == 3 && member) $row['page_text'] = message('فقط کاربران مهمان می توانند این صفحه را ببینند!', 'error');
			elseif ($row['page_view'] == 4 && !group_admin) $row['page_text'] = message('فقط مدیران می توانند این صفحه را ببینند!', 'error');
			elseif ($row['page_view'] == 5 && !group_super_admin) $row['page_text'] = message('فقط مدیران کل سایت می توانند این صفحه را ببینند!', 'error');
			$row['page_text'] = replace_links($row['page_text']);
			
			($hook = get_hook('module_pages'))? eval($hook) : null;

			if (!file_exists(template_dir.'page.tpl') || !is_readable(template_dir.'page.tpl'))
			{
				set_content($row['page_title'], $row['page_text']);
			}
			else
			{
				$itpl = new template('page.tpl', template_dir);
				$itpl->assign(array(
					'{id}' => $row['page_id'],
					'{url}' => url('pages/'.($options['rewrite'] == 1? $row['page_slug'] : $row['page_id'])),
					'{title}' => $row['page_title'],
					'{author}' => empty($row['member_alias'])? $row['member_name'] : $row['member_alias'],
					'{author-profile}' => url('account/profile/'.$row['member_name']),
					'{text}' => $row['page_text'],
					'{theme}' => $row['page_theme']
				));
				$itpl->block('|{date format=[\'"](.+?)[\'"]}|es', 'jdate("\\1", "'.$row['page_time'].'")');

				($hook = get_hook('module_pages_tpl'))? eval($hook) : null;

				$tpl->assign('{content}', $itpl->get_var());
				unset($itpl);
			}
			
			if ($row['page_comment'] == 1)
			{
				require_once(engine_dir.'comments.class.php');
				$comments = new comments('pages', $row['page_id'], url('pages/'.($options['rewrite'] == 1? $row['page_slug'] : $row['page_id'])));
				$comments->build();
			}
		}

		($hook = get_hook('module_pages_end'))? eval($hook) : null;
	}
}

function module_pages_search($search)
{
	global $d, $options;
	
	$where  = $search['type']==0 || $search['type']==2? " OR p.page_title LIKE '%".$d->escapeString($search['story'])."%'" : null;
	$where .= $search['type']==1 || $search['type']==2? " OR p.page_text LIKE '%".$d->escapeString($search['story'])."%'" : null;
	$where .= !empty($search['author']) && $search['author-full']==0? " OR m.member_name LIKE '%".$d->escapeString($search['author'])."%'" : null;
	$where .= !empty($search['author']) && $search['author-full']==1? " OR m.member_name='".$d->escapeString($search['author'])."'" : null;
	$where  = trim($where, ' OR ');
	
	$query  = "SELECT p.*,m.member_name\n";
	$query .= "FROM #__pages AS p\n";
	$query .= "LEFT JOIN #__members AS m ON p.page_author = m.member_id\n";
	$query .= "WHERE p.page_approve='1' AND (".$where.")\n";
	$query .= "GROUP BY p.page_id\n";
	$query .= "ORDER BY p.page_id DESC";

	$query = $d->query($query);
	while($p = $d->fetch($query)) 
	{
		if ($p['page_view']==2 && !member) $p['page_text'] = message('فقط کاربران عضو سایت می توانند این صفحه را ببینند!', 'error');
		elseif ($p['page_view']==3 && member) $p['page_text'] = message('فقط کاربران مهمان می توانند این صفحه را ببینند!', 'error');
		elseif ($p['page_view']==4 && !group_admin) $p['page_text'] = message('فقط مدیران می توانند این صفحه را ببینند!', 'error');
		elseif ($p['page_view']==5 && !group_super_admin) $p['page_text'] = message('فقط مدیران کل سایت می توانند این صفحه را ببینند!', 'error');
	
		$d->insert('search', array(
			'search_key' => $search['key'],
			'search_title' => str_replace($search['story'], '<span style="background:#FFCC33;color:#000000">'.$search['story'].'</span>', $p['page_title']),
			'search_author' => $p['member_name'],
			'search_content' => str_replace($search['story'], '<span style="background:#FFCC33;color:#000000">'.$search['story'].'</span>', nl2br(trim(strip_tags(apadana_substr($p['page_text'], 0, 700).'...')))),
			'search_date' => $p['page_time'],
			'search_module' => 'pages',
			'search_url' => url('pages/'.($options['rewrite'] == 1? $p['page_slug'] : $p['page_id'])),
			'search_time' => time(),
			'search_keywords' => $search['story']
		));
	}
}

function module_pages_sitemap(&$sitemap)
{
	global $d, $options;
	$query = "SELECT * FROM `#__pages` WHERE `page_approve` = '1' ORDER BY `page_time` DESC, `page_id` DESC";	
	$pages = $d->get_row($query, 'assoc');
	if (is_array($pages) && count($pages))
	{
		foreach($pages as $p)
		{
			$sitemap->addItem(url('pages/'.($options['rewrite'] == 1? $p['page_slug'] : $p['page_id'])), $p['page_time'], 'monthly', '0.7');
		}
	}
	unset($pages, $query, $p);
}

function module_pages_feed(&$feeds)
{
	global $d, $options;
	$query = "SELECT * FROM `#__pages` WHERE `page_approve` = '1' ORDER BY `page_time` DESC, `page_id` DESC LIMIT ".intval($options['feed-limit']);	
	$pages = $d->get_row($query, 'assoc');
	if (is_array($pages) && count($pages))
	{
		foreach($pages as $p)
		{
			$url = url('pages/'.($options['rewrite'] == 1? $p['page_slug'] : $p['page_id']));
			$feeds->addItem(new FeedItem($url, $p['page_title'], $url, $p['page_text'], date3339($p['page_time'])));
		}
	}
	unset($pages, $query, $p);
}

function block_pages($op = null, $id = null, $position = null)
{
	if ($op=='remove-cache') // admin
	{
		remove_cache('module-pages-block-'.$id);
		return true;
	}

	($hook = get_hook('block_pages_start'))? eval($hook) : null;

	if (!$rows = get_cache('module-pages-block-'.$id))
	{
		$op['total'] = !isset($op['total']) || $op['total']<=0? 10 : intval($op['total']);
		$op['order'] = !isset($op['order'])? 'DESC' : strtoupper($op['order']);
		$op['order'] = $op['order'] != 'DESC' && $op['order'] != 'ASC' && $op['order'] != 'RAND'? 'DESC' : $op['order'];

		global $d;
		$query = "SELECT page_title, page_slug, page_id FROM #__pages WHERE page_approve='1' ORDER BY ".($op['order'] == 'RAND'? 'RAND()' : "page_time {$op['order']}, page_id {$op['order']}")." LIMIT {$op['total']}";
		$rows = $d->get_row($query);

		if ($op['order'] != 'RAND')
		{
			set_cache('module-pages-block-'.$id, $rows);
		}
	}

	if (is_array($rows) && count($rows))
	{
		global $options;
		
		$html = '<ul id="apadana-block-pages">'.n;
		foreach($rows as $row)
		{
			$html .= '<li><a href="'.url('pages/'.($options['rewrite'] == 1? $row['page_slug'] : $row['page_id'])).'">'.$row['page_title'].'</a></li>'.n;
		}
		$html .= '</ul>';
	}
	else
	{
		$html = 'هیچ صفحه ای وجود ندارد!';
	}

	($hook = get_hook('block_pages_end'))? eval($hook) : null;

	return $html;
}

?>