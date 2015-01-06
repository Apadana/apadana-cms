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

function module_search_run()
{
	$b = get_param($_GET, 'b');

	if ($b == 'opensearch')
	{
		global $options;
		Header('Content-type: application/xml; charset='.charset);
		$itpl = new template('modules/search/html/opensearch.xml');
		$itpl->assign(array(
			'{site-mail}' => $options['mail'],
			'{year}' => date('Y'),
		));

		($hook = get_hook('search_opensearch'))? eval($hook) : null;

		$itpl->display();
		exit;
	}

	global $page, $d, $options, $modules, $member,$tpl;

	$message = null;
	$search = array();
	$search['story'] = get_param($_GET, 'story');
	$search['story'] = nohtml($search['story']);
	
	$search['type'] = (int) get_param($_GET, 'type');
	$search['type'] = $search['type']>2 || $search['type']<0? 0 : $search['type'];
	
	$search['author'] = get_param($_GET, 'author');
	$search['author'] = alphabet($search['author']);
	
	$search['author-full'] = (int) get_param($_GET, 'author-full', 0);
	$search['author-full'] = $search['author-full']<=0? 0 : 1;
	
	$search['sortby'] = get_param($_GET, 'sortby');
	$search['sortby'] = alphabet($search['sortby']);
	$search['sortby'] = $search['sortby']=='date' || $search['sortby']=='title' || $search['sortby']=='author'? $search['sortby'] : null;

	$search['sort-type'] = get_param($_GET, 'sort-type');
	$search['sort-type'] = $search['sort-type']=='desc'? 'DESC' : 'ASC';
	
	$search['result-in-page'] = (int) get_param($_GET, 'result-in-page', 0);
	$search['result-in-page'] = $search['result-in-page']<=0? 15 : $search['result-in-page'];

	$search['view-type'] = (int) get_param($_GET, 'view-type', 1);
	$search['view-type'] = $search['view-type']<=0? 0 : 1;
	
	$search['modules'] = get_param($_GET, 'modules');
	$search['modules'] = alphabet($search['modules']);
	$search['modules'] = !is_array($search['modules'])? array() : $search['modules'];

	$search['all-modules'] = (int) get_param($_GET, 'all-modules', 0);
	$search['all-modules'] = !isset($_GET['b']) || $search['all-modules']>=1? 1 : 0;
	
	($hook = get_hook('search_start'))? eval($hook) : null;

	if ($b == 'result')
	{
		$search['key'] = md5($search['story'].$search['type'].$search['author'].$search['author-full'].($search['all-modules']==1? null : implode(',', $search['modules'])));
		$d->delete('search', "search_time < '".(time()-4*60*60)."'");
		if (!empty($search['story']))
		{
			if ($d->num_rows("SELECT search_id FROM `#__search` WHERE `search_key`='".$d->escape_string($search['key'])."'", true) <= 0)
			{
				foreach ($modules as $mod)
				{
					if (is_module($mod['module_name']) && function_exists('module_'.str_replace('-', '_', $mod['module_name']).'_search'))
					{
						if ($search['all-modules']==0 && !in_array($mod['module_name'], $search['modules'])) continue;
						$func = 'module_'.str_replace('-', '_', $mod['module_name']).'_search';
						$func($search);
					}
				}
				unset($mod, $func);
			};

			require_once(engine_dir.'pagination.class.php');
			$pages = get_param($_GET, 'page', 1);
			$total = $d->num_rows("SELECT * FROM `#__search` WHERE `search_key`='".$d->escape_string($search['key'])."'", true);
			$pagination = new pagination($total, $search['result-in-page'], $pages);
			$sortby = $search['sortby']=='date'? 'search_date' : ($search['sortby']=='title'? 'search_title' : ($search['sortby']=='author'? 'search_author' : ('search_id')));
			$result = $d->get_row("SELECT * FROM `#__search` WHERE `search_key`='".$d->escape_string($search['key'])."' ORDER BY ".$sortby." ".$search['sort-type']." LIMIT ".$pagination->Start.', '.$pagination->End);
		}
		else
		{
			$message = message('لطفا کلمه ی کلیدی را برای جستجو مشخص کنید!', 'error');
		}
	}

	set_title('search');
	set_title('جستجو');
	set_canonical(url('search'));

	$file = get_tpl(root_dir.'modules/search/html/||form.tpl', template_dir.'||search.tpl');
	$itpl = new template($file[1], $file[0]);
	
	$mods = array(
		'posts' => 'پست ها',
		'pages' => 'صفحات',
	);
	
	($hook = get_hook('search_tpl_start'))? eval($hook) : null;

	foreach ($modules as $mod)
	{
		if (is_module($mod['module_name']) && function_exists('module_'.str_replace('-', '_', $mod['module_name']).'_search'))
		{
			$itpl->add_for('modules', array(
				'{title}' => isset($mods[$mod['module_name']])? $mods[$mod['module_name']] : $mod['module_name'],
				'{name}' => $mod['module_name'],
				'replace' => array(
					'#\\[selected\\](.*?)\\[/selected\\]#s' => $search['all-modules']==1 || in_array($mod['module_name'], $search['modules'])? '\\1' : ''
				),
			));
		}
	}
	unset($mod, $func);

	if (!empty($message))
	{
		$itpl->assign(array(
			'{message}' => $message,
			'[message]' => null,
			'[/message]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[message\\](.*?)\\[/message\\]#s', '');
	}
	
	if ($search['all-modules'] == 1)
	{
		$itpl->assign(array(
			'[all-modules]' => null,
			'[/all-modules]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[all-modules\\](.*?)\\[/all-modules\\]#s', '');
	}
	
	if ($search['view-type'] == 0)
	{
		$itpl->assign(array(
			'[view-title]' => null,
			'[/view-title]' => null,
		));
		$itpl->block('#\\[view-content\\](.*?)\\[/view-content\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[view-content]' => null,
			'[/view-content]' => null,
		));
		$itpl->block('#\\[view-title\\](.*?)\\[/view-title\\]#s', '');
	}
	
	if ($search['author-full'] == 1)
	{
		$itpl->assign(array(
			'[author-full]' => null,
			'[/author-full]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[author-full\\](.*?)\\[/author-full\\]#s', '');
	}
	
	if ($search['sortby'] == '')
	{
		$itpl->assign(array(
			'[sortby-id]' => null,
			'[/sortby-id]' => null,
		));
		$itpl->block('#\\[sortby-(date|title|author)\\](.*?)\\[/sortby-\\1\\]#s', '');
	}
	elseif ($search['sortby'] == 'date')
	{
		$itpl->assign(array(
			'[sortby-date]' => null,
			'[/sortby-date]' => null,
		));
		$itpl->block('#\\[sortby-(id|title|author)\\](.*?)\\[/sortby-\\1\\]#s', '');
	}
	elseif ($search['sortby'] == 'title')
	{
		$itpl->assign(array(
			'[sortby-title]' => null,
			'[/sortby-title]' => null,
		));
		$itpl->block('#\\[sortby-(id|date|author)\\](.*?)\\[/sortby-\\1\\]#s', '');
	}
	elseif ($search['sortby'] == 'author')
	{
		$itpl->assign(array(
			'[sortby-author]' => null,
			'[/sortby-author]' => null,
		));
		$itpl->block('#\\[sortby-(id|date|title)\\](.*?)\\[/sortby-\\1\\]#s', '');
	}
	
	if ($search['sort-type'] == 'DESC')
	{
		$itpl->assign(array(
			'[sort-desc]' => null,
			'[/sort-desc]' => null,
		));
		$itpl->block('#\\[sort-asc\\](.*?)\\[/sort-asc\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[sort-asc]' => null,
			'[/sort-asc]' => null,
		));
		$itpl->block('#\\[sort-desc\\](.*?)\\[/sort-desc\\]#s', '');
	}

	if ($search['type'] == 0)
	{
		$itpl->assign(array(
			'[type-title]' => null,
			'[/type-title]' => null,
		));
		$itpl->block('#\\[type-(content|content&title)\\](.*?)\\[/type-\\1\\]#s', '');
	}
	elseif ($search['type'] == 1)
	{
		$itpl->assign(array(
			'[type-content]' => null,
			'[/type-content]' => null,
		));
		$itpl->block('#\\[type-(title|content&title)\\](.*?)\\[/type-\\1\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[type-content&title]' => null,
			'[/type-content&title]' => null,
		));
		$itpl->block('#\\[type-(title|content)\\](.*?)\\[/type-\\1\\]#s', '');
	}

	if ($options['rewrite'] != 1)
	{
		$itpl->assign(array(
			'[not-rewrite]' => null,
			'[/not-rewrite]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[not-rewrite\\](.*?)\\[/not-rewrite\\]#s', '');
	}
	
	$itpl->assign(array(
		'{story}' => $search['story'],
		'{author}' => $search['author'],
		'{result-in-page}' => $search['result-in-page'],
	));

	($hook = get_hook('search_tpl_end'))? eval($hook) : null;

	if (!isset($file[2])) set_content('جستجو', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	
	if (isset($result) && is_array($result) && count($result))
	{
		foreach ($result as $row)
		{
			$row['search_content'] = $search['view-type']==0? $row['search_title'] : $row['search_content'];
			$row['search_author'] = !empty($row['search_author'])? 'نویسنده: '.$row['search_author'] : null;
			$row['search_date'] = !empty($row['search_date'])? '، تاریخ: '.jdate('j/m/Y g:i a', $row['search_date']) : null;
			$html = $row['search_content'].'<br><font color="green" size="1">'.$row['search_author'].$row['search_date'].'</font>';

			($hook = get_hook('module_search_run_result'))? eval($hook) : null;

			set_content('<a href="'.$row['search_url'].'" target="_blank">'.$row['search_title'].'</a>', $html);
		}
		if (is_array($search['modules']))
		{
			$mo = null;
			foreach ($search['modules'] as $m)
			{
				$mo = 'modules[]='.$m;
			}
			$search['modules'] = $mo;
		}
		$pagination->build('?a=search&b=result&story='.$search['story'].'&type='.$search['type'].'&author='.$search['author'].'&author-full='.$search['author-full'].'&sortby='.$search['sortby'].'&sort-type='.strtolower($search['sort-type']).'&result-in-page='.$search['result-in-page'].'&view-type='.$search['view-type'].'&'.$search['modules'].'&all-modules='.$search['all-modules'].'&page={page}');
	}
	elseif ($b=='result' && isset($result))
	{
		set_content('جستجو', message('متاسفانه جستجو نتیجه ای نداشت!', 'error'));
	}
	
	($hook = get_hook('search_end'))? eval($hook) : null;

	unset($result, $pagination, $search, $itpl);
}

function module_search_sitemap($sitemap)
{
	$sitemap->addItem(url('search'), 0, 'never', '0.8');
}

function block_search($op = null, $id = null, $position= null)
{
	global $options;

	if ($op=='remove-cache') return true;

	$op['size'] = !isset($op['size']) || intval($op['size'])<=0? 150 : intval($op['size']);
	$html  = '<center>';
	$html .= '<form action="'.url('search/result').'" method="get" id="apadana-block-search">';
	$html .= '<input type="text" name="story" value="" style="width:'.$op['size'].'px" id="search-story" />&nbsp;';
	$html .= ($options['rewrite']!=1? '<input type="hidden" name="a" value="search" /><input type="hidden" name="b" value="result" />' : null).'<input type="hidden" name="all-modules" value="1" />';
	$html .= '<input type="submit" value="جستجو" id="search-submit" />';
	$html .= '</form>';
	$html .= '</center>';

	($hook = get_hook('block_search'))? eval($hook) : null;

	return $html;
}

?>