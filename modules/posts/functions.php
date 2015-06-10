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

function _default()
{
	global $d, $options;

	require_once(engine_dir.'pagination.class.php');

	$posts_options = posts_options();
	$page_num = get_param($_GET, 'c', 1);
	$total = $d->num_rows("SELECT `post_id` FROM `#__posts` WHERE `post_approve` = '1' AND `post_date` <= '".time_now."'", true);
	$pagination = new pagination($total, $posts_options['total-posts'], $page_num);

	if ($page_num > $pagination->Pages && $pagination->Pages != 0)
	{
		redirect(url('posts'));
	}
	
	$posts = get_posts(array(
		'limit' => array($pagination->Start, $pagination->End)
	));

	if (!is_array($posts) || !count($posts))
	{
		set_content('بدون پست!', message('هیچ پستی برای نمایش در سایت یافت نشد!', 'error'));
	}
	else
	{
		if ($page_num > 1)
		{
			set_title('صفحه '.translate_number($page_num,'fa'));
		}
		
		if ($page_num <= 1)
		{
			if ($options['default-module'] == 'posts')
			{
				set_canonical(url);
			}
			else
			{
				set_canonical(url('posts'));
			}
		}
		else
		{
			set_canonical(url('posts/page/'.$page_num));
		}

		foreach ($posts as $post)
		{
			_theme($post);
		}

		$pagination->build(url('posts/page/{page}'));
	}

	unset($pagination, $posts, $total, $page_num);
}


function _author()
{
	global $d, $options;

	$author = get_param($_GET, 'c');

	if ($author == '' || (isset($_GET['d']) && !isnum($_GET['d'])))
	{
		module_error_run('404');
	}
	else
	{
		require_once(engine_dir.'pagination.class.php');

		$page_num = get_param($_GET, 'd', 1);
		$posts_options = posts_options();
		
		$total  = "SELECT p.post_id, m.member_name\n";
		$total .= "FROM #__posts AS p\n";
		$total .= "LEFT JOIN #__members AS m ON m.member_id=p.post_author\n";
		$total .= "WHERE p.post_approve='1' AND p.post_date <= '".time_now."' AND m.member_name='".$d->escape_string($author)."'";
		$total .= "GROUP BY p.post_id\n";
		$total = $d->num_rows($total, true);

		$pagination = new pagination($total, $posts_options['total-author'], $page_num);

		if ($page_num > $pagination->Pages && $pagination->Pages != 0)
		{
			redirect(url('posts/author/'.$author));
		}
		
		$posts = get_posts(array(
			'where' => "AND m.member_name='".$d->escape_string($author)."'",
			'limit' => array($pagination->Start, $pagination->End)
		));

		set_title('پست های '.(isset($posts[0]['post_author_alias']) && !empty($posts[0]['post_author_alias'])? $posts[0]['post_author_alias'] : $posts[0]['post_author_neme']));

		if ($page_num > $pagination->Pages || $page_num <= 1)
		{
			set_canonical(url('posts/author/'.$author));
		}
		else
		{
			set_canonical(url('posts/author/'.$author.'/'.$page_num));
		}
		
		if (!is_array($posts) || !count($posts))
		{
			module_error_run('404');
		}
		else
		{
			if ($page_num > 1)
			{
				set_title('صفحه '.translate_number($page_num,'fa'));
			}

			foreach ($posts as $post)
			{
				_theme($post);
			}

			$pagination->build(url('posts/author/'.$author.'/{page}'));
		}

		unset($pagination, $posts, $total, $page_num, $author, $posts_options);
	}
}

function _tag()
{
	global $d, $options;

	$slug = get_param($_GET, 'c');

	$term = $d->query("SELECT `term_id`,`term_name`,`term_slug` FROM `#__terms` WHERE term_type='p-tag' AND `term_slug`='".$d->escape_string(urlencode($slug))."' LIMIT 1");

	if ($d->num_rows($term) <= 0 || (isset($_GET['d']) && !isnum($_GET['d'])))
	{
		module_error_run('404');
	}
	else
	{
		require_once(engine_dir.'pagination.class.php');
	
		$term = $d->fetch($term);
		$slug = $term['term_slug'];

		$page_num = get_param($_GET, 'd', 1);
		$posts_options = posts_options();
		$total = $d->num_rows("SELECT `post_id` FROM `#__posts` WHERE `post_approve` = '1' AND `post_date` <= '".time_now."' AND FIND_IN_SET(".$term['term_id'].", `post_tags`)", true);
		$pagination = new pagination($total, $posts_options['total-tag'], $page_num);

		if ($page_num > $pagination->Pages && $pagination->Pages != 0)
		{
			redirect(url('posts/tag/'.$term['term_slug']));
		}
		
		$posts = get_posts(array(
			'where' => 'AND FIND_IN_SET('.$term['term_id'].', p.post_tags)',
			'limit' => array($pagination->Start, $pagination->End)
		));

		set_title($term['term_name']);
		set_meta('description', $term['term_name'], 'add');

		if ($page_num > $pagination->Pages || $page_num <= 1)
		{
			set_canonical(url('posts/tag/'.$term['term_slug']));
		}
		else
		{
			set_canonical(url('posts/tag/'.$term['term_slug'].'/'.$page_num));
		}
		
		if (!is_array($posts) || !count($posts))
		{
			module_error_run('404');
		}
		else
		{
			if ($page_num > 1)
			{
				set_title('صفحه '.translate_number($page_num,'fa'));
			}

			foreach ($posts as $post)
			{
				_theme($post);
			}

			$pagination->build(url('posts/tag/'.$term['term_slug'].'/{page}'));
		}

		unset($pagination, $posts, $total, $page_num, $term, $posts_options);
	}
}

function _category()
{
	global $d, $options;

	$cat_id = get_param($_GET, 'c');
	$categories = posts_categories();

	if ($options['rewrite'] == 1)
	{
		$cat_id = urlencode($cat_id);
		
		foreach ($categories as $cat)
		{
			if ($cat['term_slug'] == $cat_id)
			{
				$cat_id = $cat['term_id'];
				break;
			}
		}
	}
	else
	{
		$cat_id = intval($cat_id);
	}
	
	if (!isset($categories[$cat_id]) || !is_array($categories[$cat_id]) || (isset($_GET['d']) && !isnum($_GET['d'])))
	{
		module_error_run('404');
	}
	else
	{
		require_once(engine_dir.'pagination.class.php');
	
		$page_num = get_param($_GET, 'd', 1);
		$cat = $categories[$cat_id];

		$children = 'FIND_IN_SET('.$cat['term_id'].', p.post_categories)';
		if ($cat['term_parent'] == 0)
		{
			foreach ($categories as $c)
			{
				if ($c['term_parent'] != $cat['term_id']) continue;
				$children .= ' OR FIND_IN_SET('.$c['term_id'].', p.post_categories)';
			}
		}

		$posts_options = posts_options();
		$total = $d->num_rows("SELECT `post_id` FROM `#__posts` WHERE `post_approve` = '1' AND `post_date` <= '".time_now."' AND (".str_replace('p.post_categories', 'post_categories', $children).")", true);
		$pagination = new pagination($total, $posts_options['total-category'], $page_num);

		if ($page_num > $pagination->Pages && $pagination->Pages != 0)
		{
			redirect(url('posts/category/'.($options['rewrite'] == 1? $cat['term_slug'] : $cat['term_id'])));
		}

		$posts = get_posts(array(
			'where' => 'AND ('.$children.')',
			'limit' => array($pagination->Start, $pagination->End)
		));

		set_theme('category-post-'.$cat['term_id']);
		set_title($cat['term_name']);
		set_meta('description', $cat['term_description'], 'add');

		if ($page_num > $pagination->Pages || $page_num <= 1)
		{
			set_canonical(url('posts/category/'.($options['rewrite'] == 1? $cat['term_slug'] : $cat['term_id'])));
		}
		else
		{
			set_canonical(url('posts/category/'.($options['rewrite'] == 1? $cat['term_slug'] : $cat['term_id']).'/'.$page_num));
		}
		
		if (!is_array($posts) || !count($posts))
		{
			set_content('بدون پست!', message('هیچ پستی برای موضوع <u>'.$cat['term_name'].'</u> در سایت یافت نشد!', 'error'));
		}
		else
		{
			if ($page_num > 1)
			{
				set_title('صفحه '.translate_number($page_num,'fa'));
			}

			foreach ($posts as $post)
			{
				_theme($post);
			}

			$pagination->build(url('posts/category/'.($options['rewrite'] == 1? $cat['term_slug'] : $cat['term_id']).'/{page}'));
		}

		unset($pagination, $posts, $total, $page_num, $cat, $children, $posts_options);
	}
}

function _single()
{
	global $d, $options;

	$id = get_param($_GET, 'b');

	$post = get_posts(array(
		'where' => 'AND p.post_'.($options['rewrite'] == 1? 'name' : 'id')."='".($options['rewrite'] == 1? urlencode($id) : intval($id))."'",
		'limit' => array(1)
	));

	if (!isset($post[0]) || !is_array($post[0]) || !count($post[0]))
	{
		module_error_run('404');
	}
	else
	{
		$post = $post[0];
		$post['post_text'] = mb_substr(trim(str_replace(array("\n", "\r", "\t"), ' ', nohtml($post['post_text']))), 0, 100);
		$post['post_text'] = mb_strlen($post['post_text']) < 30? $post['post_title'] : $post['post_text'];

		set_theme('single-post');
		set_title($post['post_title']);
		set_meta('description', $post['post_text']);
		set_canonical(url('posts/'.($options['rewrite'] == 1? $post['post_name'] : $post['post_id'])));

		if (is_array($post['post_tags']) && count($post['post_tags']))
		{
			$tags = array();
			foreach ($post['post_tags'] as $tag)
			{
				$tags[] = $tag['name'];
			}
			$tags = implode(', ', $tags);
			set_meta('keywords', $tags, 'add');
			unset($tags, $tag);
		}

		$d->query("UPDATE `#__posts` SET `post_hits`=`post_hits`+1 WHERE `post_id`='".intval($post['post_id'])."' AND `post_approve`='1' LIMIT 1");
		$post['post_hits']++;

		_theme($post, true);

		if ($post['post_comment'] == 1)
		{
			require_once(engine_dir.'comments.class.php');

			$comments = new comments('posts', $post['post_id'], url('posts/'.($options['rewrite'] == 1? $post['post_name'] : $post['post_id'])));

			//I think it's better to get the comments options from its class. its more logical than common way!! (MSDN)
			if($comments->options['pagination'] == 1){

				require_once(engine_dir.'pagination.class.php');

				$total = $comments->get_total_comments();

				if( isset($_GET['c']) && $_GET['c'] == 'comments-page' && isset($_GET['d']) && !empty($_GET['d']) && is_numeric($_GET['d']) ) {
					$page = $_GET['d'] ;
				}
				else
					$page = 1;

				$pagination = new pagination($total, $comments->options['per-page'] , $page);

				if ($page > $pagination->Pages && $pagination->Pages != 0)
				{
					redirect( $url );
				}

				$pagination->Pages != 1 && $comments->action = url('posts/'.($options['rewrite'] == 1? $post['post_name'] : $post['post_id']) ."/comments-page/".$pagination->Pages );

				$comments->set_limits( $pagination->Start , $pagination->End );

				$pagination->build( url( ('posts/'.($options['rewrite'] == 1? $post['post_name'] : $post['post_id'])."/comments-page/{page}")  ) ) ;
			}

			$comments->build();

			if( $comments->options['pagination'] == 1 && $comments->comment_posted == true){

				if (group_super_admin || member::check_admin_page_access("comments") || $comments->options['approve'] == 0){
					$d->query("UPDATE `#__posts` SET `post_comment_count`= `post_comment_count` + 1  WHERE `post_id`='".intval($post['post_id'])."' LIMIT 1");
				}
				
				if( ($total%$comments->options['per-page']) + 1 == 1){
					$page = $pagination->Pages + 1;
					redirect(url('posts/'.($options['rewrite'] == 1? $post['post_name'] : $post['post_id']) ."/comments-page/".$page ));
				}
							
			}

		}
	}

	unset($id, $post, $comments);
}

function _print()
{
	global $d, $options;

	$id = get_param($_GET, 'c', 0);
	
	$post = get_posts(array(
		'where' => "AND p.post_id='".intval($id)."'",
		'limit' => array(1)
	));
	
	if (!isset($post[0]) || !is_array($post[0]) || !count($post[0]))
	{
		module_error_run('404');
	}
	else
	{
		$post = $post[0];
		set_title($post['post_title']);
		set_meta('description', $post['post_title'], 'add');
		set_meta('robots', 'noindex, nofollow');
		set_canonical(url('posts/'.($options['rewrite'] == 1? $post['post_name'] : $post['post_id'])));

		if ($post['post_view'] == 2 && !member) $post['post_more'] = message('این بخش فقط برای اعضا نمایش داده می شود!', 'error');
		elseif ($post['post_view'] == 3 && member) $post['post_more'] = message('این بخش فقط برای کاربران مهمان نمایش داده می شود!', 'error');
		elseif ($post['post_view'] == 4 && !group_admin) $post['post_more'] = message('این بخش فقط برای مدیران سایت نمایش داده می شود!', 'error');
		elseif ($post['post_view'] == 5 && !group_super_admin) $post['post_more'] = message('این بخش فقط برای مدیر کل سایت نمایش داده می شود!', 'error');
		else
		{
			$post['post_more'] = replace_links($post['post_more']);
		}

		($hook = get_hook('posts_print'))? eval($hook) : null;

		echo '<html dir="rtl">'."\n";
		echo '<head>'."\n";
		echo head();
		echo '<style>'."\n";
		echo 'body{background:#DDD;font-family:Tahoma;font-size:9pt;color:Black;padding:0px;margin:15px;direction:rtl}'."\n";
		echo 'a{text-decoration:none;color:Black}'."\n";
		echo 'a:hover{color:Blue}'."\n";
		echo 'a:active{color:Red}'."\n";
		echo 'img{max-width:99%;border:none}'."\n";
		echo '#wrap{background:White;border:#AAA 1px solid;padding:10px}'."\n";
		echo '#wrap p{margin:10px 0px 2px 0px;padding:0px}'."\n";
		echo 'h1#title{background:#3399CC;color:White;padding:6px 10px 7px 10px;font-size:11pt;margin:0px 0px 10px 0px}'."\n";
		echo '#info{background:#EEE;border:#dadada 1px solid;padding:6px;margin-top:10px}'."\n";
		echo '</style>'."\n";
		echo '</head>'."\n";
		echo '<body onload="window.print()">'."\n";
		echo '<div id="wrap">'."\n";
		echo '<h1 id="title">'.$post['post_title'].'</h1>'."\n";
		echo replace_links($post['post_text']);
		echo $post['post_more'] == ''? null : '<br/>' . $post['post_more'];
		echo '<div id="info">'."\n";
		echo 'ارسال شده در '.jdate('l j F Y ساعت g:i A', $post['post_date'], 1);
		echo ' توسط <a href="'.url('account/profile/'.$post['post_author_neme']).'">'.($post['post_author_alias'] == ''? $post['post_author_neme'] : $post['post_author_alias']).'</a>';
		echo '</div>'."\n";
		echo '</div>'."\n";
		echo '</body>'."\n";
		echo '</html>';
		exit;
	}
}

function _theme($post, $single = false)
{
	global $tpl, $options;

	($hook = get_hook('posts_theme_start'))? eval($hook) : null;

	if ($single == false)
	{
		$post['post_more'] = nohtml($post['post_more']);
		$post['post_more'] = empty($post['post_more'])? false : true;
	}

	$categories = array();
	if (is_array($post['post_categories']) && count($post['post_categories']))
	{
		foreach ($post['post_categories'] as $cat)
		{
			$categories[] = '<a href="'.url('posts/category/'.($options['rewrite'] == 1? $cat['slug'] : $cat['id'])).'">'.$cat['name'].'</a>';
		}
		unset($cat);
	}
	$categories = implode('، ', $categories);

	$tags = array();
	if (is_array($post['post_tags']) && count($post['post_tags']))
	{
		foreach ($post['post_tags'] as $tag)
		{
			$tags[] = '<a href="'.url('posts/tag/'.$tag['slug']).'">'.$tag['name'].'</a>';
		}
		unset($tag);
	}
	$tags = implode('، ', $tags);

	$itpl = new template('post.tpl', $tpl->base_dir);
	$array = array(
		'{url}' => url('posts/'.($options['rewrite'] == 1? $post['post_name'] : $post['post_id'])),
		'{print}' => url('posts/print/'.$post['post_id']),
		'{id}' => $post['post_id'],
		'{title}' => $post['post_title'],
		'{author}' => $post['post_author_alias'] == ''? $post['post_author_neme'] : $post['post_author_alias'],
		'{author-profile}' => url('account/profile/'.$post['post_author_neme']),
		'{author-url}' => url('posts/author/'.$post['post_author_neme']),
		'{image}' => $post['post_image'] == ''? null : $post['post_image'],
		'{text1}' => replace_links($post['post_text']),
		'{text2}' => null,
		'{hits}' => $post['post_hits'],
		'{categories}' => !empty($categories)? $categories : 'بدون موضوع',
		'{tags}' => !empty($tags)? $tags : 'بدون برچسب',
		'{comment-count}' => $post['post_comment_count'],
		'{language}' => $post['post_language'],
	);
	
	if ($post['post_image'])
	{
		$itpl->assign(array(
			'[image]' => null,
			'[/image]' => null,
		));
		$itpl->block('#\\[not-image\\](.*?)\\[/not-image\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-image]' => null,
			'[/not-image]' => null,
		));
		$itpl->block('#\\[image\\](.*?)\\[/image\\]#s', '');
	}
	
	if ($post['post_fixed'] == 1)
	{
		$itpl->assign(array(
			'[fixed]' => null,
			'[/fixed]' => null,
		));
		$itpl->block('#\\[not-fixed\\](.*?)\\[/not-fixed\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-fixed]' => null,
			'[/not-fixed]' => null,
		));
		$itpl->block('#\\[fixed\\](.*?)\\[/fixed\\]#s', '');
	}
	
	if (!empty($categories))
	{
		$itpl->assign(array(
			'[categories]' => null,
			'[/categories]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[categories\\](.*?)\\[/categories\\]#s', '');
	}
	
	if (!empty($tags))
	{
		$itpl->assign(array(
			'[tags]' => null,
			'[/tags]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[tags\\](.*?)\\[/tags\\]#s', '');
	}
	
	if ($post['post_comment'] == 1)
	{
		$itpl->assign(array(
			'[comment]' => null,
			'[/comment]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[comment\\](.*?)\\[/comment\\]#s', '');
	}
	
	if (($post['post_view'] == 2 && !member) || ($post['post_view'] == 3 && member) || ($post['post_view'] == 4 && !group_admin) || ($post['post_view'] == 5 && !group_super_admin))
	{
		$itpl->assign(array(
			'[not-view]' => null,
			'[/not-view]' => null,
		));
		$itpl->block('#\\[view\\](.*?)\\[/view\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[view]' => null,
			'[/view]' => null,
		));
		$itpl->block('#\\[not-view\\](.*?)\\[/not-view\\]#s', '');
	}
	
	if ($single)
	{
		$itpl->assign(array(
			'[single]' => null,
			'[/single]' => null,
		));
		$itpl->block('#\\[not-single\\](.*?)\\[/not-single\\]#s', '');
		
		if ($post['post_view'] == 2 && !member) $array['{text2}'] = message('این بخش فقط برای اعضا نمایش داده می شود!', 'error');
		elseif ($post['post_view'] == 3 && member) $array['{text2}'] = message('این بخش فقط برای کاربران مهمان نمایش داده می شود!', 'error');
		elseif ($post['post_view'] == 4 && !group_admin) $array['{text2}'] = message('این بخش فقط برای مدیران سایت نمایش داده می شود!', 'error');
		elseif ($post['post_view'] == 5 && !group_super_admin) $array['{text2}'] = message('این بخش فقط برای مدیر کل سایت نمایش داده می شود!', 'error');
		else
		{
			$array['{text2}'] = replace_links($post['post_more']);
		}
		
		$itpl->assign(array(
			'[text2]' => null,
			'[/text2]' => null,
		));
		$itpl->block('#\\[more\\](.*?)\\[/more\\]#s', '');
	}
	else
	{
		if ($post['post_more'])
		{
			$itpl->assign(array(
				'[more]' => null,
				'[/more]' => null,
			));
		}
		else
		{
			$itpl->block('#\\[more\\](.*?)\\[/more\\]#s', '');
		}
		
		$itpl->assign(array(
			'[not-single]' => null,
			'[/not-single]' => null,
		));
		
		$itpl->block('#\\[single\\](.*?)\\[/single\\]#s', '');
		$itpl->block('#\\[text2\\](.*?)\\[/text2\\]#s', '');
	}

	$posts_fields = posts_options();
	$posts_fields = $posts_fields['fields'];

	if (is_array($posts_fields) && count($posts_fields))
	{
		foreach ($posts_fields as $fname => $fvalue)
		{
			if (!is_alphabet($fname)) continue;
	
			$array['{field-'.$fname.'}'] = isset($post['post_fields'][$fname])? $post['post_fields'][$fname] : false;

			if (!empty($array['{field-'.$fname.'}']))
			{
				$itpl->assign(array(
					'[field-'.$fname.']' => null,
					'[/field-'.$fname.']' => null,
				));
				$itpl->block('#\\[not-field-'.$fname.'\\](.*?)\\[/not-field-'.$fname.'\\]#s', '');
			}
			else
			{
				$itpl->assign(array(
					'[not-field-'.$fname.']' => null,
					'[/not-field-'.$fname.']' => null,
				));
				$itpl->block('#\\[field-'.$fname.'\\](.*?)\\[/field-'.$fname.'\\]#s', '');
			}
		}
		unset($fname, $fvalue);
	}

	$itpl->assign($array);
	$itpl->block_callback('|{date format=[\'"](.+?)[\'"]}|s', create_function('$m','return jdate($m[1],'.$post['post_date'].');'));

	($hook = get_hook('posts_theme_end'))? eval($hook) : null;

	$tpl->assign('{content}', $itpl->get_var(), 'add');

	unset($categories, $tags, $post, $array, $itpl, $posts_fields);
}

function _archives(){

	if( ! $links = get_cache('posts_archive') ){
		global $d;

		$date = $d->fetch("SELECT MIN(post_date) AS min_date ,MAX(post_date) AS max_date FROM #__posts WHERE `post_approve` = '1' AND `post_date` <= '".time_now."'",'assoc',true);

		$max_month = jdate('n',$date['max_date']) + 1;
		$max_year = jdate('Y',$date['max_date']);

		if ($max_month > 12) {
			$max_month = 1;
			$max_year++;
		}

		$min_month = jdate('n',$date['min_date']);
		$min_year = jdate('Y',$date['min_date']);

		$min_time = jmktime(0,0,0,$min_month,1,$min_year);

		$up_month = $max_month;
		$up_year = $max_year;

		$links = array();

		while( jmktime(0,0,0,$up_month,1,$up_year) >= $min_time ){

			$down_month = $up_month - 1;
			$down_year = $up_year;

			if ($down_month < 1) {
				$down_month = 12;
				$down_year--;
			}

			$down_time = jmktime(0,0,0,$down_month,1,$down_year);

			$up_time = jmktime(0,0,0,$up_month,1,$up_year);

			$count = $d->fetch('SELECT COUNT(post_id) AS count FROM #__posts WHERE `post_approve` = "1" AND  post_date BETWEEN "'.$down_time.'" AND "'. ( $up_time > time_now ? time_now : $up_time ).'"',"assoc",true);
			$count = $count['count'];

			if($count >= 1){
				$links[] = array( $down_time , $up_time ,$count ) ;
			}

			$up_year = $down_year;
			$up_month = $down_month;

		}
		set_cache('posts_archive' , $links );
	}

	foreach ($links as $key => $value) {
		$links[$key] = '<a href="'.url('posts/archives/'.jdate('Y\/n',$value[0])).'" >'.jdate('F Y',$value[0])."({$value[2]})".'</a>';
	}
	set_content('آرشیو پست ها',implode('<br>', $links));
}

function _archives_show_posts()
{
	$year = $month = $day = $page = false;

	$year = get_param( $_GET , 'c' , null );
	$year = ( empty($year) || ! is_numeric($year) || $year < 1380 || $year > jdate('Y') ) ? false : $year ;

	if(! $year){
		module_error_run();
		return;
	}

	$_d = get_param( $_GET , 'd' , false );

	if( $_d ){

		if( ! empty($_d) && is_numeric($_d) && $_d >= 1 && $_d <= 12){
			$month = $_d;
		}
		elseif ( ! empty($_d) && $_d == "page" ) {

			$page = get_param( $_GET , 'e' , 1 );
			if( !is_numeric($page) ){
				module_error_run();
				return;
			}
		}else{
			module_error_run();
			return;
		}
	}

	$e = get_param( $_GET , 'e' , false );

	if( $e ){

		if( $month && ! empty($e) && is_numeric($e) && $e >= 1 && $e <= 31){
			$day = $e;
		}
		elseif ( ! $page && ! empty($e) && $e == "page" ) {
			
			$page = get_param( $_GET , 'f' , 1 );
			if( !is_numeric($page) ){
				module_error_run();
				return;
			}
		}else{
			module_error_run();
			return;
		}
	}

	if(! $page){
		$f = get_param( $_GET , 'f' , false );
		if($f == 'page'){
			$page = get_param( $_GET , 'g' , 1 );
			if( !is_numeric($page) ){
				module_error_run();
				return;
			}
		}
	}

	if( $day && ! jcheckdate( $month  , $day , $year ) ){
		module_error_run();
		return;
	}

	if( $day ){
		$start_time = jmktime( 0, 0, 0, $month, $day, $year );
		$end_time = jmktime( 24, 0, 0, $month, $day, $year );
	}elseif( $month ) {
		$start_time = jmktime( 0, 0, 0, $month, 1, $year ) ;
		$end_time = jmktime( 0, 0, 0, ($month + 1 ), 1 , $year ) ;
	}else{
		$start_time = jmktime( 0, 0, 0, 1 , 1, $year ) ;
		$end_time = jmktime( 0, 0, 0, 1 , 1 , ($year + 1) ) ;
	}

	$posts = array();

	if( $start_time < time_now ){

		global $d, $options;

		require_once(engine_dir.'pagination.class.php');

		$posts_options = posts_options();
		$total = $d->num_rows("SELECT `post_id` FROM `#__posts` WHERE `post_approve` = '1' AND `post_date` BETWEEN '".$start_time."' AND '". ( $end_time > time_now ? time_now : $end_time )."'", true);
		$pagination = new pagination($total, $posts_options['total-posts'], $page);

		$url = 'posts/archives/'.$year;
		$url .= $month ? ('/'.$month) : '';
		$url .= $day ? ('/'.$day) : '';
		$full_url = $url . ($page ? ('/page/'.$page) : '');

		$page = !$page ? 1 : $page ;
		if ($page > $pagination->Pages && $pagination->Pages != 0)
		{
			redirect(url($url));
		}
		
		// dump(array($start_time,$end_time));
		$posts = get_posts(array(
			'limit' => array($pagination->Start, $pagination->End),
			'date' => array($start_time, ( $end_time > time_now ? time_now : $end_time ) )
		));

	}

	if (!is_array($posts) || !count($posts))
	{
		set_content('بدون مطلب!', message('هیچ مطلبی برای نمایش در سایت یافت نشد!', 'error'));
	}
	else
	{
		if ($page > 1)
		{
			set_title('صفحه '.translate_number($page,'fa'));
		}
		

		set_canonical(url($full_url));

		foreach ($posts as $post)
		{
			_theme($post);
		}

		$pagination->build(url($url.'/page/{page}'));
	}

}
?>
