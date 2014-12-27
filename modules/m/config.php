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

function module_m_run()
{
	global $d, $tpl, $options;
	require_once(root_dir.'modules/posts/functions.php');
	set_title('نسخه موبایل');

	$b = get_param($_GET, 'b', 'page');
	if (!is_numeric($b))
	{
		require_once(engine_dir.'pagination.class.php');
		if (file_exists(template_dir.'mobile/index.tpl'))
		{
			$tpl->load('mobile/index.tpl');
		}
		else
		{
			$tpl->base_dir = root_dir.'modules/m/html/';
			$tpl->load('index.tpl');
		}
		$page_num = get_param($_GET, 'c', 1);
		$total = $d->numRows("SELECT `post_id` FROM `#__posts` WHERE `post_approve` = '1' AND `post_date` <= '".time_now."'", true);
		$pagination = new pagination($total, 40, $page_num);
		$posts = get_posts(array(
			'limit' => array($pagination->Start, $pagination->End)
		));

		if ($page_num > $pagination->Pages && $pagination->Pages != 0)
		{
			redirect(url('m'));
		}
		
		if (is_array($posts) && count($posts))
		{
			if ($page_num > 1)
			{
				set_title('صفحه '.number2persian($page_num));
			}
			
			if ($pagination->Pages < $page_num || $page_num <= 1)
			{
				set_canonical(url('m'));
			}
			else
			{
				set_canonical(url('m/page/'.$page_num));
			}
			
			foreach($posts as $post)
			{
				$tpl->add_for('posts', array(
					'{odd-even}' => odd_even(),
					'{id}' => $post['post_id'],
					'{url}' => url('m/'.$post['post_id']),
					'{title}' => $post['post_title'],
					'{author}' => empty($post['post_author_alias'])? $post['post_author_neme'] : $post['post_author_alias'],
					'replace' => array(
						'|{date format=[\'"](.+?)[\'"]}|es' => 'jdate("\\1", "'.$post['post_date'].'")',
					),
				));
			}
			
			$p = $pagination->build(url('m/page/{page}'), true);
			if (is_array($p) && count($p)) 
			{
				foreach($p as $link) 
				{
					if (!isset($link['page'])) continue;

					$tpl->add_for('pages', array(
						'{number}' => $link['number'],
						'{url}' => $link['url'],
						'replace' => array(
							'#\\[selected\\](.*?)\\[/selected\\]#s' => $link['number']==$page_num? '\\1' : '',
						),
					));
				}

				$tpl->assign(array(
					'[pages]' => null,
					'[/pages]' => null,
				));
			}
			else
			{
				$tpl->block('#\\[pages\\](.*?)\\[/pages\\]#s', '');
			}
		}
		
		if (isset($tpl->foreach['posts']))
		{
			$tpl->assign(array(
				'[posts]' => null,
				'[/posts]' => null,
			));
			$tpl->block('#\\[not-posts\\](.*?)\\[/not-posts\\]#s', '');
		}
		else
		{
			$tpl->assign(array(
				'[not-posts]' => null,
				'[/not-posts]' => null,
			));
			$tpl->block('#\\[posts\\](.*?)\\[/posts\\]#s', '');
		}
	}
	else
	{
		if (file_exists(template_dir.'mobile/single.tpl'))
		{
			$tpl->load('mobile/single.tpl');
		}
		else
		{
			$tpl->base_dir = root_dir.'modules/m/html/';
			$tpl->load('single.tpl');
		}

		$post = get_posts(array(
			'where' => "AND p.post_id='".intval($b)."'",
			'limit' => array(1)
		));

		if (isset($post[0]) && is_array($post[0]) && count($post[0]))
		{
			set_title(nohtml($post[0]['post_title']));
			set_meta('description', $post[0]['post_title']);
			set_canonical(url('posts/'.($options['rewrite'] == 1? $post[0]['post_name'] : $post[0]['post_id'])));

			if (is_array($post[0]['post_tags']) && count($post[0]['post_tags']))
			{
				$tags = array();
				foreach ($post[0]['post_tags'] as $tag)
				{
					$tags[] = $tag['name'];
				}
				$tags = implode(', ', $tags);
				set_meta('keywords', $tags, 'add');
				unset($tags, $tag);
			}

			$tpl->assign(array(
				'{id}' => $post[0]['post_id'],
				'{url}' => url('m/'.$post[0]['post_id']),
				'{title}' => $post[0]['post_title'],
				'{text}' => $post[0]['post_text'].$post[0]['post_more'],
				'{author}' => empty($post[0]['post_author_alias'])? $post[0]['post_author_neme'] : $post[0]['post_author_alias'],
				'[post]' => null,
				'[/post]' => null,
			));

			$tpl->block('|{date format=[\'"](.+?)[\'"]}|es', 'jdate("\\1", "'.$post[0]['post_date'].'")');
			$tpl->block('#\\[not-post\\](.*?)\\[/not-post\\]#s', '');
		}
		else
		{
			set_title('یافت نشد!');
			set_canonical(url('m'));
			$tpl->assign(array(
				'[not-post]' => null,
				'[/not-post]' => null,
			));
			$tpl->block('#\\[post\\](.*?)\\[/post\\]#s', '');
		}
	}

	unset($pagination, $posts, $total, $query, $page_num);
	define('no_blocks', true);
}

?>