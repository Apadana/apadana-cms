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

member::check_admin_page_access('comments') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _types()
{
	global $tpl, $d, $modules;
	
	$types = array(
		'' => 'همه بخش ها'
	);
	foreach ($modules as $m => $v)
	{
		if (is_module($m) && file_exists(root_dir.'modules/'.$m.'/admin.php'))
		{
			$func = 'module_'.str_replace('-', '_', $m).'_admin_comments';
			require_once(root_dir.'modules/'.$m.'/admin.php');
			if (function_exists($func))
			{
				$func = $func('name');
				if (is_array($func))
				{
					$types = array_merge($types, $func);
					#array_push($types, $func);
				}
			}
		}
	}
	unset($m, $v, $func);
	
	return $types;
}

function _default()
{
	global $tpl, $d, $modules;

	require_once(engine_dir.'pagination.class.php');
	require_once(engine_dir.'editor.function.php');

	set_title('نظرات');
	
	$types = _types();

	$order = get_param($_GET, 'order', 'DESC');
	$order = $order=='DESC'? 'DESC' : 'ASC';
	
	$total = get_param($_GET, 'total', 20);
	$total = $total<=0? 20 : $total;

	$type = get_param($_GET, 'type');
	$type = in_array($type, array_keys($types))? $type : null;

	$_page = get_param($_GET, 'page', 1);
	$_page = $_page<=0? 1 : $_page;

	$total_posts = $d->numRows("SELECT `comment_id` FROM `#__comments`".($type != ''? " WHERE `comment_type`='".$d->escapeString($type)."'" : null)."", true);

	$pagination = new pagination($total_posts, $total, $_page);

	$itpl = new template('engine/admin/template/comments.tpl');
	
	$query = $d->query("
		SELECT c.*,m.member_avatar,m.member_group,m.member_name
		FROM #__comments AS c
		LEFT JOIN #__members AS m ON (m.member_id = c.comment_member_id)
		".($type != ''? "WHERE c.comment_type='".$d->escapeString($type)."'" : null)."
		GROUP BY c.comment_id
		ORDER BY c.comment_id $order
		LIMIT $pagination->Start, $pagination->End
	");
	
	if ($d->numRows($query) >= 1)
	{
		while ($data = $d->fetch($query)) 
		{
			$url = null;
			$func = 'module_'.str_replace('-', '_', $data['comment_type']).'_admin_comments';
			if (function_exists($func))
			{
				$func = $func('url', array('link' => $data['comment_link']));
				if ($func != '')
				{
					$url = $func;
				}
			}

			$itpl->add_for ('comments', array(
				'{odd-even}' => odd_even(),
				'{id}' => $data['comment_id'],
				'{type}' => $data['comment_type'],
				'{link}' => $data['comment_link'],
				'{url}' => $url,
				'{member-id}' => $data['comment_member_id'],
				'{member-name}' => $data['member_name'],
				'{author}' => $data['comment_author'],
				'{author-email}' => $data['comment_author_email'],
				'{author-url}' => $data['comment_author_url'],
				'{author-ip}' => $data['comment_author_ip'],
				'{date}' => jdate('l j F Y ساعت g:i A', $data['comment_date']),
				'{past-time}' => get_past_time($data['comment_date']),
				'{text}' => $data['comment_text'],
				'{msg}' => smiles_replace(apadana_strlen($data['comment_text']) > 100? apadana_substr($data['comment_text'], 0, 95).' ...' : $data['comment_text']),
				'{answer-author}' => $data['comment_answer_author'],
				'{answer}' => $data['comment_answer'],
				'{approve}' => $data['comment_approve'],
				'{language}' => $data['comment_language'],
				'replace' => array(
					'#\\[approve\\](.*?)\\[/approve\\]#s' => $data['comment_approve']==1? '\\1' : '',
					'#\\[not-approve\\](.*?)\\[/not-approve\\]#s' => $data['comment_approve']!=1? '\\1' : '',
					'#\\[member-name\\](.*?)\\[/member-name\\]#s' => !empty($data['member_name'])? '\\1' : '',
					'#\\[not-member-name\\](.*?)\\[/not-member-name\\]#s' => empty($data['member_name'])? '\\1' : '',
				)
			));
		}

		$itpl->assign(array(
			'[comments]' => null,
			'[/comments]' => null,
		));
		$itpl->block('#\\[not-comments\\](.*?)\\[/not-comments\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-comments]' => null,
			'[/not-comments]' => null,
		));
		$itpl->block('#\\[comments\\](.*?)\\[/comments\\]#s', '');
	}
	
	foreach ($types as $t_name => $t_title) 
	{
		$itpl->add_for('types', array(
			'{name}' => $t_name,
			'{title}' => $t_title,
			'replace' => array(
				'#\\[selected\\](.*?)\\[/selected\\]#s' => $t_name == $type? '\\1' : '',
			)
		));
	}
	unset($t_name, $t_title);
	
	$p = $pagination->build('{page}', true);
	if (is_array($p) && count($p)) 
	{	
		foreach ($p as $link) 
		{
			if (!isset($link['page'])) continue;

			$itpl->add_for('pages', array(
				'{number}' => $link['number'],
				'replace' => array(
					'#\\[selected\\](.*?)\\[/selected\\]#s' => $link['number'] == $_page? '\\1' : '',
				)
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
		'{textarea-text}' => wysiwyg_textarea('comment[text]', null, 'BBcode'),
		'{textarea-answer}' => wysiwyg_textarea('comment[answer]', null, 'BBcode'),
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

function _edit()
{
	global $page, $tpl, $d;

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT * FROM #__comments WHERE `comment_id`='$id' LIMIT 1");

	if ($d->numRows() <= 0)
	{
		exit('این نظر وجود ندارد!');
	}

	$data = $d->fetch();
	$comment = get_param($_POST, 'comment', null, 1);

	if (isset($comment) && is_array($comment) && count($comment))
	{
		$d->query("SELECT `option_value` FROM `#__options` WHERE `option_name`='comments' LIMIT 1");
		$comments_options = $d->fetch();
		$d->freeResult();
		$comments_options = maybe_unserialize($comments_options['option_value']);

		$msg = array();
		$comment['author'] = isset($comment['author'])? htmlencode($comment['author']) : null;
		$comment['author-email'] = isset($comment['author-email'])? nohtml($comment['author-email']) : null;
		$comment['author-url'] = isset($comment['author-url'])? nohtml($comment['author-url']) : null;
		$comment['approve'] = isset($comment['approve']) && $comment['approve'] == 1? 1 : 0;
		$comment['text'] = isset($comment['text'])? htmlencode($comment['text']) : null;
		$comment['answer'] = isset($comment['answer'])? htmlencode($comment['answer']) : null;

		if (empty($comment['author']))
		{
			$msg[] = 'نام نویسنده نظر را ننوشته اید!';
		}
		
		if (empty($comment['text']))
		{
			$msg[] = 'متن نظر را پاک کرده اید!';
		}
		
		if ($comments_options['email'] == 1)
		{
			if (empty($comment['author-email']) || !validate_email($comment['author-email']))
			{
				$msg[] = 'ایمیل وارد شده صحیح نیست!';
			}
		}

		if ($comment['author-url'] != '' && !validate_url($comment['author-url']))
		{
			$msg[] = 'آدرس وب سایت نویسنده نظر معتبر نیست!';
		}

		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
		}
		else
		{
			#$comment['text'] = template_off($comment['text']);
			#$comment['answer'] = template_off($comment['answer']);
			$comment['text'] = str_replace('{', '&#x7B;', $comment['text']);
			$comment['answer'] = str_replace('{', '&#x7B;', $comment['answer']);
		
			$d->update('comments', array(
				'comment_author' => $comment['author'],
				'comment_author_email' => $comment['author-email'],
				'comment_author_url' => $comment['author-url'],
				'comment_text' => $comment['text'],
				'comment_answer' => $comment['answer'],
				'comment_answer_author' => $comment['answer'] == ''? null : member_name,
				'comment_approve' => $comment['approve'],
			), "`comment_id`='{$id}'", 1);	

			if ($d->affectedRows())
			{
				if ($comment['approve'] != $data['comment_approve'] && is_module($data['comment_type']) && file_exists(root_dir.'modules/'.$data['comment_type'].'/admin.php'))
				{
					require_once(root_dir.'modules/'.$data['comment_type'].'/admin.php');
					$func = 'module_'.str_replace('-', '_', $data['comment_type']).'_admin_comments';
					if (function_exists($func))
					{
						$func('approve', array( 
							'link' => $data['comment_link'],
							'approve' => $comment['approve']
						));
					}
				}
			
				echo '<!--OK-->';
				echo message('نظر با موفقیت ویرایش شد.', 'success');
				remove_cache('comments', true);
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}
	exit;
}

function _approve()
{
	global $page, $tpl, $d;

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT `comment_approve`, `comment_type`, `comment_link` FROM #__comments WHERE `comment_id`='$id' LIMIT 1");

	if ($d->numRows() <= 0)
	{
		exit('این نظر وجود ندارد!');
	}

	$data = $d->fetch();

	$d->update('comments', array(
		'comment_approve' => $data['comment_approve'] == 1? 0 : 1,
	), "`comment_id`='{$id}'", 1);	

	if ($d->affectedRows())
	{
		if (is_module($data['comment_type']) && file_exists(root_dir.'modules/'.$data['comment_type'].'/admin.php'))
		{
			require_once(root_dir.'modules/'.$data['comment_type'].'/admin.php');
			$func = 'module_'.str_replace('-', '_', $data['comment_type']).'_admin_comments';
			if (function_exists($func))
			{
				$func('approve', array(
					'link' => $data['comment_link'],
					'approve' => $data['comment_approve'] == 1? 0 : 1
				));
			}
		}

		remove_cache('comments', true);
		exit($data['comment_approve'] == 1? 'no' : 'ok');
	}
	else
	{
		exit('در ذخیره خطایی رخ داده مجدد تلاش کنید!');
	}
}

function _delete()
{
	global $page, $tpl, $d;

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT `comment_approve`, `comment_type`, `comment_link` FROM #__comments WHERE `comment_id`='$id' LIMIT 1");

	if ($d->numRows() <= 0)
	{
		exit('این نظر وجود ندارد!');
	}

	$data = $d->fetch();

	$d->delete('comments', "`comment_id`='{$id}'", 1);	

	if ($d->affectedRows())
	{
		if (is_module($data['comment_type']) && file_exists(root_dir.'modules/'.$data['comment_type'].'/admin.php'))
		{
			require_once(root_dir.'modules/'.$data['comment_type'].'/admin.php');
			$func = 'module_'.str_replace('-', '_', $data['comment_type']).'_admin_comments';
			if (function_exists($func))
			{
				$func('delete', array( 
					'link' => $data['comment_link'],
					'approve' => $data['comment_approve']
				));
			}
		}

		echo message('نظر با موفقیت حذف شد.', 'success');
		remove_cache('comments', true);
	}

	_default();
}

$_GET['do'] = get_param($_GET, 'do');

switch ($_GET['do'])
{
	case 'edit';
	_edit();
	break;
	
	case 'approve';
	_approve();
	break;
	
	case 'delete';
	_delete();
	break;
	
	default;
	_default();
	break;
}

?>