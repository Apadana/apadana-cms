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

function get_posts($do)
{
	global $cache, $d; 

	$default = array(
		'tags' => 1,
		'fields' => 1,
		'approve' => 1,
		'date' => time_now,
		'where' => null,
		'limit' => array(0, 10)
	);

	$do = array_merge($default, $do);

	$where = array();

	if ($do['approve'] == 1)
	{
		$where[] = "p.post_approve = '1'";
	}
	
	if ($do['date'] > 0)
	{
		$where[] = "p.post_date <= '".intval($do['date'])."'";
	}

	$where = implode(' AND ', $where);

	$do['where'] = $where != '' || $do['where'] != ''? ('WHERE ' . $where . ($do['where'] != ''? ' ' . $do['where'] : null)) : null;
	
	$query  = "SELECT p.*, m.member_name, m.member_alias\n";
	$query .= "FROM #__posts AS p\n";
	$query .= "LEFT JOIN #__members AS m ON m.member_id=p.post_author\n";
	$query .= ($do['where'] != ''? $do['where']."\n" : null);
	$query .= "GROUP BY p.post_id\n";
	$query .= "ORDER BY p.post_fixed DESC, p.post_date DESC, p.post_id DESC\n";
	$query .= 'LIMIT '.(intval($do['limit'][0]) . (isset($do['limit'][1])? ', ' . intval($do['limit'][1]) : null));
	
	$query = $d->query($query);
	
	unset($where, $default);
	
	if ($d->num_rows($query) <= 0)
	{
		return false;
	}

	$postsID = array();
	$postsTagsID = array();
	$postsTemp = array();
	while ($row = $d->fetch($query))
	{
		$postsTemp[] = $row;
		
		$postsID[] = $row['post_id'];

		$row['post_tags'] = explode(',', $row['post_tags']);
		foreach ($row['post_tags'] as $tag)
		{
			if (!isnum($tag)) continue;
			$postsTagsID[$tag] = $tag;
		}
	}

	$d->free_result($query);
	unset($row, $query);

	$postsID = implode(',', $postsID);
	$postsTagsID = implode(',', $postsTagsID);
	
	$postsTags = array();
	if (!empty($postsTagsID) && $do['tags'])
	{
		$tagsQuery = $d->query("SELECT `term_id`, `term_name`, `term_slug` FROM `#__terms` WHERE `term_type`='p-tag' AND `term_id` IN ($postsTagsID)");
		if ($d->num_rows($tagsQuery) > 0)
		{
			while ($row = $d->fetch($tagsQuery))
			{
				$postsTags[$row['term_id']] = $row;
			}
		}
		$d->free_result($tagsQuery);
	}
	unset($row, $tagsQuery, $postsTagsID);

	$fields = posts_options();
	$fields = $fields['fields'];

	$postsFields = array();
	if (is_array($fields) && count($fields) && $do['fields'])
	{
		$fieldsQuery = $d->query("SELECT `field_link`, `field_name`, `field_value` FROM `#__fields` WHERE `field_type`='p' AND `field_link` IN ($postsID)");
		if ($d->num_rows($fieldsQuery) > 0)
		{
			while ($row = $d->fetch($fieldsQuery))
			{
				$postsFields[$row['field_link']][$row['field_name']] = $row['field_value'];
			}
		}
		$d->free_result($fieldsQuery);
	}
	
	unset($fields, $do);

	posts_categories();
	
	$posts = array();
	foreach ($postsTemp as $p)
	{
		$categories = array();
		$cats = explode(',', $p['post_categories']);
		$catsCount = count($cats);
		for ($i = 0; $i < $catsCount; $i++)
		{
			if (!isnum($cats[$i]) || !isset($cache['posts_categories'][$cats[$i]]) || !is_array($cache['posts_categories'][$cats[$i]])) continue;

			$cats[$i] = $cache['posts_categories'][$cats[$i]];
			$categories[$cats[$i]['term_id']] = array(
				'id' => $cats[$i]['term_id'],
				'name' => $cats[$i]['term_name'],
				'description' => $cats[$i]['term_description'],
				'slug' => $cats[$i]['term_slug'],
				'parent' => $cats[$i]['term_parent']
			);
		}
		unset($cats, $catsCount);

		$tags = array();
		$_tags = explode(',', $p['post_tags']);
		$tagsCount = count($_tags);
		for ($i = 0; $i < $tagsCount; $i++)
		{
			if (!isnum($_tags[$i]) || !isset($postsTags[$_tags[$i]]) || !is_array($postsTags[$_tags[$i]])) continue;

			$_tags[$i] = $postsTags[$_tags[$i]];
			$tags[$_tags[$i]['term_id']] = array(
				'id' => $_tags[$i]['term_id'],
				'name' => $_tags[$i]['term_name'],
				'slug' => $_tags[$i]['term_slug'],
			);
		}
		unset($_tags, $tagsCount);

		$fields = array();
		if (isset($postsFields[$p['post_id']]) && is_array($postsFields[$p['post_id']]) && count($postsFields[$p['post_id']]))
		{
			foreach ($postsFields[$p['post_id']] as $fname => $fvalue)
			{
				if (!is_alphabet($fname)) continue;
				
				$fields[$fname] = $fvalue;
			}
			unset($fname, $fvalue);
		}
		
		$posts[] = array(
			'post_id' => $p['post_id'],
			'post_author' => $p['post_author'],
			'post_author_neme' => $p['member_name'],
			'post_author_alias' => $p['member_alias'],
			'post_date' => $p['post_date'],
			'post_title' => $p['post_title'],
			'post_name' => $p['post_name'],
			'post_image' => $p['post_image'],
			'post_text' => $p['post_text'],
			'post_more' => $p['post_more'],
			'post_hits' => $p['post_hits'],
			'post_view' => $p['post_view'],
			'post_fields' => $fields,
			'post_categories_id' => $p['post_categories'],
			'post_categories' => $categories,
			'post_tags_id' => $p['post_tags'],
			'post_tags' => $tags,
			'post_comment' => $p['post_comment'],
			'post_comment_count' => $p['post_comment_count'],
			'post_language' => $p['post_language'],
			'post_fixed' => $p['post_fixed'],
			'post_approve' => $p['post_approve']
		);
	}

	unset($p, $fields, $categories, $tags, $postsTags, $postsFields, $postsID, $postsTagsID, $postsTemp);
	
	return $posts;
}

function posts_options()
{
	if (!$options = get_cache('options-posts'))
	{
		global $d;

		$d->query("SELECT `option_value` FROM `#__options` WHERE `option_name`='posts' LIMIT 1");
		$result = $d->fetch();
		$d->free_result();
		$options = maybe_unserialize($result['option_value']);
		set_cache('options-posts', $options);
	}
	return $options;
}

function posts_categories()
{
	global $cache, $d; 

	if (!$cache['posts_categories'] = get_cache('module-posts-categories'))
	{
		$cache['posts_categories'] = $d->get_row("SELECT * FROM `#__terms` WHERE `term_type`='p-cat'", 'assoc', 'term_id');
		set_cache('module-posts-categories', $cache['posts_categories']);
	}
	
	return $cache['posts_categories'];
}

function block_categories($op = null, $id = null, $position= null)
{
	global $options;	

	if ($op == 'remove-cache') return true;
	$categories = posts_categories();

	if (isset($categories) && is_array($categories) && count($categories))
	{
		$html = '<ul id="apadana-block-posts-categories">'.n;
		foreach ($categories as $cat)
		{
			if ($cat['term_parent'] != 0) continue;
			$html .= '<li class="cat-item cat-item-'.$cat['term_id'].'"><a href="'.url('posts/category/'.($options['rewrite'] == 1? $cat['term_slug'] : $cat['term_id'])).'">'.$cat['term_name'].'</a></li>'.n;
			
			$i = 0;
			foreach ($categories as $cat2)
			{
				if ($cat2['term_parent'] != $cat['term_id']) continue;
				if ($i == 0) $html .= '<ul class="children">'.n;
				$html .= '<li class="cat-item cat-item-'.$cat2['term_id'].'"><a href="'.url('posts/category/'.($options['rewrite'] == 1? $cat2['term_slug'] : $cat2['term_id'])).'">'.$cat2['term_name'].'</a></li>'.n;
				$i++;
			}
			if ($i >= 1)
			{
				$html .= '</ul>'.n;
			}
		}
		$html .= '</ul>'.n;
		unset($cat, $cat2);
	}
	else
	{
		$html = 'بدون موضوع';
	}
	return $html;
}

function block_tags_cloud($op = null, $id = null, $position= null)
{
	if ($op == 'remove-cache') // admin
	{
		remove_cache('module-posts-block-tags-cloud-'.$id);
		return true;
	}
	if (!$list = get_cache('module-posts-block-tags-cloud-'.$id))
	{
		global $d;

		$op['total'] = !isset($op['total']) || intval($op['total']) <= 0? 50 : intval($op['total']);

		$counts = array();
		$tags = array();
		$list = array();
		$sizes = array('clouds-xsmall', 'clouds-small', 'clouds-medium', 'clouds-large', 'clouds-xlarge');
		$min = 1;
		$max = 1;
		$range = 1;

		$sql_select = "
		SELECT t.term_name, t.term_slug, COUNT(p.post_id) AS count
		FROM #__terms AS t
		LEFT JOIN #__posts AS p ON FIND_IN_SET(t.term_id, p.post_tags)
		WHERE t.term_type='p-tag'
		GROUP BY t.term_id
		LIMIT ".$op['total'];

		$d->query($sql_select);

		while($row = $d->fetch())
		{
			$tags[$row['term_name']] = array(
				'slug' => $row['term_slug'],
				'count' => $row['count']
			);
			$counts[] = $row['count'];
		}
		$d->free_result();

		if (count($counts))
		{
			$min = min($counts);
			$max = max($counts);
			$range = ($max-$min);
		}

		if (!$range) $range = 1;

		foreach ($tags as $tag => $value)
		{
			$list[$tag]['tag']   = $tag;
			$list[$tag]['size']  = $sizes[sprintf('%d', ($value['count']-$min)/$range*4 )];
			$list[$tag]['count']  = $value['count'];
			$list[$tag]['slug']  = $value['slug'];
		}
		
		$counts = array();	
		$tags = array();	
		set_cache('module-posts-block-tags-cloud-'.$id, $list);
	}
	
	if (is_array($list) && count($list))
	{
		foreach ($list as $value)
		{
			if (trim($value['tag']) != '')
			{
				$tags[] = '<a href="'.url('posts/tag/'.$value['slug']).'" class="'.$value['size'].'" title="در '.$value['count'].' پست استفاده شده است">'.$value['tag'].'</a>';
			}
		}
		$html = '<div id="apadana-block-tags-cloud">'.implode('، ', $tags).'</div>';
		unset($tags, $list, $value);
	}
	else
	{
		$html = 'بدون برچسب!';
	}
	return $html;
}

function block_last_posts($op = null, $id = null, $position = null)
{
	if ($op == 'remove-cache') // admin
	{
		remove_cache('module-posts-block-last-'.$id);
		return true;
	}
	if (!$rows = get_cache('module-posts-block-last-'.$id, 'short'))
	{
		$op['total'] = !isset($op['total']) || intval($op['total'])<=0? 10 : intval($op['total']);
		$op['hits'] = !isset($op['hits']) || strtolower($op['hits'])!='true'? false : true;
		$op['order'] = !isset($op['order'])? 'DESC' : strtoupper($op['order']);
		$op['order'] = $op['order']!='DESC' && $op['order']!='ASC'? 'DESC' : $op['order'];
		
		global $d;

		$query = "SELECT post_title, post_name, post_id, post_hits FROM `#__posts` WHERE post_approve='1' AND post_date <= '".time_now."' ORDER BY ".($op['hits']==1? "post_hits {$op['order']}" : "post_date {$op['order']}, post_id {$op['order']}")." LIMIT {$op['total']}";
		$rows = $d->get_row($query);
		set_cache('module-posts-block-last-'.$id, $rows);
	}

	if (is_array($rows) && count($rows))
	{
		global $options;
	
		$html = '<ul class="apadana-block-last-posts">'.n;
		foreach ($rows as $row)
		{
			$row['post_title'] = nohtml($row['post_title']);
			if (apadana_strlen($row['post_title']) > 30)
			{
				$row['post_title'] = apadana_substr($row['post_title'], 0, 27);
				$row['post_title'] .= ' ...';
			}
			$html .= '<li><a href="'.url('posts/'.($options['rewrite'] == 1? $row['post_name'] : $row['post_id'])).'">'.$row['post_title'].'</a>'.(isset($op['hits']) && $op['hits']? '<span>&nbsp;'.$row['post_hits'].' بازدید</span>' : null).'</li>'.n;
		}
		$html .= '</ul>';
	}
	else
	{
		$html = 'هیچ پستی وجود ندارد!';
	}
	return $html;
}

function block_posts_comments($op = null, $id = null, $position = null)
{
	if ($op == 'remove-cache') // admin
	{
		remove_cache('module-posts-comments-block-'.$id);
		return true;
	}
	if (!$rows = get_cache('module-posts-comments-block-'.$id, 'short'))
	{
		$op['total'] = !isset($op['total']) || intval($op['total'])<=0? 10 : intval($op['total']);
		$op['order'] = !isset($op['order'])? 'DESC' : strtoupper($op['order']);
		$op['order'] = $op['order']!='DESC' && $op['order']!='ASC'? 'DESC' : $op['order'];
		
		global $d;
		$query = "
			SELECT c.*,p.post_name,p.post_title
			FROM #__comments AS c
			LEFT JOIN #__posts AS p ON (p.post_id = c.comment_link)
			WHERE c.comment_type='posts' AND c.comment_approve='1'
			GROUP BY c.comment_id
			ORDER BY c.comment_id {$op['order']}
			LIMIT {$op['total']}
		";
		$rows = $d->get_row($query);
		set_cache('module-posts-comments-block-'.$id, $rows);
	}

	if (is_array($rows) && count($rows))
	{
		global $options;
		
		require_once(engine_dir.'bbcode.class.php');
		$bbcode = new bbcode();

		$html = '<ul id="apadana-block-posts-last-comments">'.n;
		foreach ($rows as $row)
		{
			$row['comment_text'] = $bbcode->parse($row['comment_text']);
			$row['comment_text'] = nohtml($row['comment_text']);
			if (empty($row['comment_text'])) continue;

			if (apadana_strlen($row['comment_text']) > 50)
			{
				$row['comment_text'] = apadana_substr($row['comment_text'], 0, 46);
				$row['comment_text'] .= ' ...';
			}
			if (apadana_strlen($row['post_title']) > 30)
			{
				$row['post_title'] = apadana_substr($row['post_title'], 0, 25);
				$row['post_title'] .= ' ...';
			}
			$html .= '<li><b>'.(validate_url($row['comment_author_url'])? '<a href="'.$row['comment_author_url'].'" target="_blank" rel="nofollow">'.$row['comment_author'].'</a>' : $row['comment_author']).'</b><a href="'.url('posts/'.($options['rewrite'] == 1? $row['post_name'] : $row['comment_link'])).'#comment-'.$row['comment_id'].'"> در '.$row['post_title'].' گفته: '.$row['comment_text'].'</a></li>'.n;
		}
		$html .= '</ul>';
	}
	else
	{
		$html = 'هیچ نظری وجود ندارد!';
	}
	return $html;
}

function module_posts_run()
{
	require_once(root_dir.'modules/posts/functions.php');

	$_GET['b'] = get_param($_GET, 'b');

	switch ($_GET['b'])
	{
		case 'category':
		if (!isset($_GET['c']) || $_GET['c'] == '')
		{
			module_error_run('404');
		}
		else
		{
			_category();
		}
		break;
		
		case 'tag':
		if (!isset($_GET['c']) || $_GET['c'] == '')
		{
			module_error_run('404');
		}
		else
		{
			_tag();
		}
		break;
		
		case 'author':
		if (!isset($_GET['c']) || !is_alphabet($_GET['c']))
		{
			module_error_run('404');
		}
		else
		{
			_author();
		}
		break;

		case 'pdf':
		if (!isset($_GET['c']) || !isnum($_GET['c']))
		{
			module_error_run('404');
		}
		else
		{
			_pdf();
		}
		break;

		case 'print':
		if (!isset($_GET['c']) || !isnum($_GET['c']))
		{
			module_error_run('404');
		}
		else
		{
			_print();
		}
		break;
		
		default:
		if ($_GET['b'] == '' || $_GET['b'] == 'page')
		{
			_default();
		}
		else
		{
			_single();
		}
		break;
	}
}

function module_posts_search($search)
{
	global $d, $options;
	
	$where  = $search['type']==0 || $search['type']==2? " OR p.post_title LIKE '%".$d->escape_string($search['story'])."%'" : null;
	$where .= $search['type']==1 || $search['type']==2? " OR p.post_text LIKE '%".$d->escape_string($search['story'])."%' OR p.post_more LIKE '%".$d->escape_string($search['story'])."%'" : null;
	$where .= !empty($search['author']) && $search['author-full']==0? " OR m.member_name LIKE '%".$d->escape_string($search['author'])."%'" : null;
	$where .= !empty($search['author']) && $search['author-full']==1? " OR m.member_name='".$d->escape_string($search['author'])."'" : null;
	$where  = trim($where, ' OR ');
	
	$query  = "SELECT p.*,m.member_name\n";
	$query .= "FROM #__posts AS p\n";
	$query .= "LEFT JOIN #__members AS m ON p.post_author = m.member_id\n";
	$query .= "WHERE p.post_approve='1' AND p.post_date <= '".time_now."' AND (".$where.")\n";
	$query .= "GROUP BY p.post_id\n";
	$query .= "ORDER BY p.post_id DESC";

	$query = $d->query($query);
	while ($p = $d->fetch($query)) 
	{
		$d->insert('search', array(
			'search_key' => $search['key'],
			'search_title' => str_replace($search['story'], '<span style="background:#FFCC33;color:#000000">'.$search['story'].'</span>', $p['post_title']),
			'search_author' => $p['member_name'],
			'search_content' => str_replace($search['story'], '<span style="background:#FFCC33;color:#000000">'.$search['story'].'</span>', nl2br(trim(strip_tags($p['post_text'])))),
			'search_date' => $p['post_date'],
			'search_module' => 'posts',
			'search_url' => url('posts/'.($options['rewrite'] == 1? $p['post_name'] : $p['post_id'])),
			'search_time' => time(),
			'search_keywords' => $search['story']
		));
	}
}

function module_posts_sitemap($sitemap)
{
	global $d, $options;

	$categories = posts_categories();

	if (isset($categories) && is_array($categories) && count($categories))
	{
		foreach ($categories as $cat)
		{
			$sitemap->addItem(url('posts/category/'.($options['rewrite'] == 1? $cat['term_slug'] : $cat['term_id'])), time_now - 100, 'monthly', '0.8');
		}
		unset($cat);
	}

	$query = "SELECT post_date, post_name, post_id FROM `#__posts` WHERE post_approve='1' AND post_date <= '".time_now."' ORDER BY post_date DESC LIMIT 1000";
	$posts = $d->get_row($query);

	if (is_array($posts) && count($posts))
	{
		foreach ($posts as $p)
		{
			$sitemap->addItem(url('posts/'.($options['rewrite'] == 1? $p['post_name'] : $p['post_id'])), $p['post_date'], 'monthly', '0.8');
		}
	}
	unset($posts, $p, $query);
}

function module_posts_feed($feeds)
{
	global $d, $options;

	$query = "SELECT post_title, post_text, post_date, post_name, post_id FROM `#__posts` WHERE post_approve='1' AND post_date <= '".time_now."' ORDER BY post_date DESC LIMIT ".intval($options['feed-limit']);
	$posts = $d->get_row($query);

	if (is_array($posts) && count($posts))
	{
		foreach ($posts as $p)
		{
			$url = url('posts/'.($options['rewrite'] == 1? $p['post_name'] : $p['post_id']));
			$feeds->addItem(new FeedItem($url, $p['post_title'], $url, $p['post_text'], date3339($p['post_date'])));
		}
	}
	unset($posts, $p, $query);
}

?>