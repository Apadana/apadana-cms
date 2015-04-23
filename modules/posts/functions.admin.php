<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92) & Mohammad Sadegh Dehghan Niri
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

function _default()
{
	global $tpl, $d;

	member::check_admin_page_access('posts') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	require_once(engine_dir.'pagination.class.php');
	set_title('پست ها');

	$order = get_param($_GET, 'order', 'DESC');
	$order = $order=='DESC'? 'DESC' : 'ASC';
	
	$total = get_param($_GET, 'total', 20);
	$total = $total<=0? 20 : $total;

	$_page = get_param($_GET, 'page', 1);
	$_page = $_page<=0? 1 : $_page;

	$total_posts = $d->num_rows("SELECT `post_id` FROM `#__posts`", true);

	$pagination = new pagination($total_posts, $total, $_page);

	$itpl = new template('modules/posts/html/admin/posts.tpl');

	$d->query("SELECT * FROM #__posts ORDER BY post_fixed {$order}, post_date {$order}, post_id {$order} LIMIT $pagination->Start, $pagination->End");
	if ($d->num_rows() >= 1)
	{
		while ($data = $d->fetch()) 
		{
			$itpl->add_for('posts', array(
				'{odd-even}' => odd_even(),
				'{id}' => $data['post_id'],
				'{title}' => $data['post_title'],
				'{hits}' => $data['post_hits'],
				'{approve}' => $data['post_approve'],
				'{fixed}' => $data['post_fixed'],
				'{data}' => jdate('Y-m-d ساعت H:i:s', $data['post_date'], 1),
				'replace' => array(
					'#\\[next\\](.*?)\\[/next\\]#s' => $data['post_date'] > time_now? '\\1' : '',
					'#\\[not-next\\](.*?)\\[/not-next\\]#s' => $data['post_date'] < time_now? '\\1' : '',
					'#\\[fixed\\](.*?)\\[/fixed\\]#s' => $data['post_fixed'] == 1? '\\1' : '',
					'#\\[not-fixed\\](.*?)\\[/not-fixed\\]#s' => $data['post_fixed'] != 1? '\\1' : '',
					'#\\[approve\\](.*?)\\[/approve\\]#s' => $data['post_approve'] == 1? '\\1' : '',
					'#\\[not-approve\\](.*?)\\[/not-approve\\]#s' => $data['post_approve'] != 1? '\\1' : '',
				),
			));
		}

		$itpl->assign(array(
			'[posts]' => null,
			'[/posts]' => null,
		));
		$itpl->block('#\\[not-posts\\](.*?)\\[/not-posts\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-posts]' => null,
			'[/not-posts]' => null,
		));
		$itpl->block('#\\[posts\\](.*?)\\[/posts\\]#s', '');
	}

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

function _title()
{
	global $d;
	member::check_admin_page_access('posts') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_POST, 'id', 0);

	$d->query("SELECT `post_author`, `post_title` FROM `#__posts` WHERE `post_id`='{$id}' LIMIT 1");

	if ($d->num_rows() <= 0)
	{
		exit('این پست وجود ندارد!');
	}

	$data = $d->fetch();

	if ($data['post_author'] != member_id && group_super_admin != 1)
	{
		exit('شما دسترسی لازم را ندارید!');
	}

	$title = get_param($_POST, 'title', null, 1);
	$title = htmlencode($title);

	if (empty($title))
	{
		exit('عنوان را ننوشته اید!');
	}
	else
	{
		if ($title == $data['post_title'])
		{
			exit('ok');
		}

		$d->update('posts', array(
			'post_title' => $title,
		), "`post_id`='{$id}'", 1);	
		
		if ($d->affected_rows())
		{
			remove_cache('module-posts', true);
			exit('ok');
		}
		else
		{
			exit('در ذخیره خطایی رخ داده مجدد تلاش کنید!');
		}
	}
	exit;
}

function _approve()
{
	global $d;
	member::check_admin_page_access('posts') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT `post_author`, `post_approve` , `post_date` FROM #__posts WHERE `post_id`='{$id}' LIMIT 1");

	if ($d->num_rows() <= 0)
	{
		exit('این پست وجود ندارد!');
	}

	$data = $d->fetch();

	if ($data['post_author'] != member_id && group_super_admin != 1)
	{
		exit('شما دسترسی لازم را ندارید!');
	}

	$d->update('posts', array(
		'post_approve' => $data['post_approve'] == 1? 0 : 1,
	), "`post_id`='{$id}'", 1);	

	if ($d->affected_rows())
	{
		remove_cache('module-posts', true);
		remove_cache('posts_archive');
		exit($data['post_approve'] == 1? 'no' : 'ok');
	}
	else
	{
		exit('در ذخیره خطایی رخ داده مجدد تلاش کنید!');
	}
	exit;
}

function _fixed()
{
	global $d;
	member::check_admin_page_access('posts') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT `post_author`, `post_fixed` FROM #__posts WHERE `post_id`='$id' LIMIT 1");

	if ($d->num_rows() <= 0)
	{
		exit('این پست وجود ندارد!');
	}

	$data = $d->fetch();

	if ($data['post_author'] != member_id && group_super_admin != 1)
	{
		exit('شما دسترسی لازم را ندارید!');
	}
	
	$d->update('posts', array(
		'post_fixed' => $data['post_fixed']==1? 0 : 1,
	), "`post_id`='{$id}'", 1);	

	if ($d->affected_rows())
	{
		remove_cache('module-posts', true);
		exit($data['post_fixed'] == 1? 'no' : 'ok');
	}
	else
	{
		exit('در ذخیره خطایی رخ داده مجدد تلاش کنید!');
	}
	exit;
}

function _date($year, $month, $day, $hour, $minute, $second)
{
	if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day) || !is_numeric($hour) || !is_numeric($minute) || !is_numeric($second)) return 'numeric';
	if (apadana_strlen($year) < 4 || $year < 1370 || $year > jdate('Y', time_now, 0)+5) return 'year';
	if ($month <= 0 || $month > 12) return 'month';
	if ($day <= 0 || $day > 31 || ($month > 6 && $day > 30)) return 'day';
	if ($hour <= -1 || $hour > 23) return 'hour';
	if ($minute <= -1 || $minute > 59) return 'minute';
	if ($second <= -1 || $second > 59) return 'second';
	return 'true';
}

function _tags($data)
{
    global $d;

	$data = nohtml($data);
	$data = str_replace(array('!','@','#','$','%','^','*','÷','(',')','{','}','_','--','=','+','|','¦','/','\\','~','≈','`','´','\'','"','&','?','؟','>','<','»','«','⇔','⇒','→','×','.','¸','…','‌',' ','“','”','„','‛','¤','♦','•','►','—','–','¯','¨','º','·','€','©','®','¢','£','¶','™'), ' ', $data);
	$data = preg_replace('#\s{2,}#', ' ', $data);
	$data = trim($data, ',');
	$tags = explode(',', $data);
    $tags = array_map('trim', $tags);

    $i = 0;
    if (is_array($tags) && count($tags))
	{
		foreach ($tags as $tag)
		{
			$slug = slug($tag);

			if (empty($tag) || empty($slug))
			{
				unset($tags[$i]);
				continue;
			}

			if ($d->num_rows("SELECT * FROM `#__terms` WHERE `term_type`='p-tag' AND `term_name`='".$d->escape_string($tag)."'", true) <= 0)
			{
				$d->insert('terms', array(
					'term_type' => 'p-tag',
					'term_name' => $tag,
					'term_slug' => $slug,
				));
			}
			$i++;
		}
	}

    $id = array();
    foreach ($tags as $tag)
    {
        if (!empty($tag))
        {
            $tagID = $d->query("SELECT term_id FROM `#__terms` WHERE `term_type`='p-tag' AND `term_name`='".$d->escape_string($tag)."' LIMIT 1");

			if ($d->num_rows($tagID) <= 0)
			{
				continue;
			}

			$tagID = $d->fetch($tagID);
            $tagID = (int) $tagID['term_id'];
            if ($tagID <= 0) continue;
            $id[] = $tagID;
        }
    }

    return array(
		'id' => $id,
		'tags' => $tags
	);
}

function _cats($cats)
{
	global $d;
	$cats = array_map('intval', $cats);

	$id = array();
	foreach ($cats as $i)
	{
		if ($i <= 0) continue;
		$id[] = (int) $i;
	}

	return $id;
}

function _new()
{
	global $tpl, $d;

	member::check_admin_page_access('posts') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	require_once(engine_dir.'editor.function.php');

	set_title('پست جدید');

	$fields = posts_options();
	$fields = $fields['fields'];
	
	$posts = get_param($_POST, 'posts', null, 1);

	if (isset($posts) && is_array($posts) && count($posts))
	{
		$msg = array();
		$posts['title'] = isset($posts['title'])? htmlencode($posts['title']) : null;
		$posts['name'] = isset($posts['name']) && $posts['name'] != ''? slug($posts['name']) : slug($posts['title']);
		$posts['image'] = isset($posts['image'])? nohtml($posts['image']) : null;
		$posts['text'] = isset($posts['text'])? trim($posts['text']) : null;
		$posts['more'] = isset($posts['more'])? trim($posts['more']) : null;
		$posts['comment'] = isset($posts['comment']) && $posts['comment'] == 1? 1 : 0;
		$posts['fixed'] = isset($posts['fixed']) && $posts['fixed'] == 1? 1 : 0;
		$posts['approve'] = isset($posts['approve']) && $posts['approve'] == 1? 1 : 0;
		$posts['view'] = isset($posts['view'])? intval($posts['view']) : 0;
		$posts['tags'] = isset($posts['tags'])? trim($posts['tags']) : null;

		if (!isset($posts['categories']) || !is_array($posts['categories']))
		{
			$posts['categories'] = array();
		}

		$categories = _cats($posts['categories']);
		$tags = _tags($posts['tags']);

		$posts['text'] = preg_replace('#(\A[\s]*<br[^>]*>[\s]*|<br[^>]*>[\s]*\Z)#is', '', $posts['text']); // remove <br/> at end of string
		$posts['more'] = preg_replace('#(\A[\s]*<br[^>]*>[\s]*|<br[^>]*>[\s]*\Z)#is', '', $posts['more']); // remove <br/> at end of string

		if ($posts['title'] == '')
		{
			$msg[] = 'عنوان پست را ننوشته اید!';
		}

		if ($posts['name'] == '')
		{
			$msg[] = 'نام مستعار پست را ننوشته اید!';
		}
		else
		{
			if (apadana_strlen($posts['name']) > 250)
			{
				$msg[] = 'نام مستعار بیش از انداره طولانی است!';
			}
			elseif ($d->num_rows("SELECT `post_id` FROM `#__posts` WHERE `post_name`='".$d->escape_string($posts['name'])."'", true) >= 1)
			{
				$msg[] = 'نام مستعار تکراری است یک نام مستعار دیگر انتخاب کنید!';
			}
		}

		if ($posts['text'] == '')
		{
			$msg[] = 'متن پست را ننوشته اید!';
		}

		$date = _date($posts['year'], $posts['month'], $posts['day'], $posts['hour'], $posts['minute'], $posts['second']);
		if ($date != 'true')
		{
			if ($date == 'numeric') $msg[] = 'تاریخ و یا زمان انتخاب شده برای پست معتبر نیست!';
			if ($date == 'year') $msg[] = 'سال انتخاب شده برای انتشار پست معتبر نیست!';
			if ($date == 'month') $msg[] = 'ماه انتخاب شده برای انتشار پست معتبر نیست!';
			if ($date == 'day') $msg[] = 'روز انتخاب شده برای انتشار پست معتبر نیست!';
			if ($date == 'hour') $msg[] = 'ساعت انتخاب شده برای انتشار پست معتبر نیست!';
			if ($date == 'minute') $msg[] = 'دقیقه انتخاب شده برای انتشار پست معتبر نیست!';
			if ($date == 'second') $msg[] = 'ثانیه انتخاب شده برای انتشار پست معتبر نیست!';
		}
		else
		{
			$date = jalali_to_gregorian($posts['year'], $posts['month'], $posts['day']);
			$date = strtotime($date[0].'-'.$date[1].'-'.$date[2].' '.$posts['hour'].':'.$posts['minute'].':'.$posts['second']);
		}

		if (is_array($fields) && count($fields))
		{
			foreach ($fields as $f_name => $f_info)
			{
				if ($f_info['require'] == 1 && (!isset($posts['fields'][$f_name]) || trim($posts['fields'][$f_name]) == ''))
				{
					$msg[] = 'پر کردن فیلد اضافی <b>'.$f_info['title'].'</b> الزامی می باشد!';
				}
			}
			unset($f_name, $f_info);
		}
		
		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
			echo '<script>$("#posts-name").val("'.urldecode($posts['name']).'")</script>';
		}
		else
		{
			$posts['text'] = template_off($posts['text']);
			$posts['more'] = template_off($posts['more']);
			// $posts['text'] = str_replace('[', '&#x5B;', $posts['text']);
			// $posts['text'] = str_replace('{', '&#x7B;', $posts['text']);
			// $posts['more'] = str_replace('[', '&#x5B;', $posts['more']);
			// $posts['more'] = str_replace('{', '&#x7B;', $posts['more']);
		
			$id = $d->insert('posts', array(
				'post_title' => $posts['title'],
				'post_name' => $posts['name'],
				'post_image' => $posts['image'],
				'post_text' => $posts['text'],
				'post_more' => $posts['more'],
				'post_date' => intval($date),
				'post_comment' => $posts['comment'],
				'post_fixed' => $posts['fixed'],
				'post_approve' => $posts['approve'],
				'post_view' => intval($posts['view']),
				'post_categories' => implode(',', $categories),
				'post_tags' => implode(',', $tags['id']),
				'post_author' => member_id,
			));
			
			if ($d->affected_rows())
			{
				$id = intval($id);
				if (isset($posts['fields']) && is_array($posts['fields']) && count($posts['fields']) && $id > 0)
				{
					foreach ($posts['fields'] as $field => $value)
					{
						$value = trim($value);
						if (!isset($fields[$field]) || $value == '') continue;

						$value = template_off($value);
						$value = template_off($value);
						// $value = str_replace('[', '&#x5B;', $value);
						// $value = str_replace('{', '&#x7B;', $value);

						$d->insert('fields', array(
							'field_link' => $id,
							'field_name' => trim($field),
							'field_value' => $value,
							'field_type' => 'p',
						));
					}
				}

				if( $date <= time_now &&  ($links = get_cache('posts_archive')) ){

					foreach ($links as $k => $v) {
						if( $date >= $v[0] && $date <= $v[1]){
							$links[$k][2]++;
							$find = true;
							break;
						}
					}

					if(!isset($find))
						remove_cache('posts_archive');
					else
						set_cache('posts_archive' , $links );
				}


				echo message('پست جدید با موفقیت ذخیره شد!', 'success');
				echo message('<a href="javascript:void(0)" onclick="post_reset()">برای ارسال پست جدید کلیک کنید ...</a>', 'info');
				echo '<script>$("#form-new-posts").slideUp("slow")</script>';
				remove_cache('module-posts', true);

			}
			else
			{
				echo message('در ذخیره اطلاعات خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
		exit;
	}

	$categories = posts_categories();

	$itpl = new template('modules/posts/html/admin/posts-new.tpl');

	$array['{textarea}'] = wysiwyg_textarea('posts[text]', null);
	$array['{textarea-more}'] = wysiwyg_textarea('posts[more]', null);
	$array['{watch}'] = jdate('Y-m-d ساعت H:i:s', time_now, 1);
	$array['{approve}'] = html::radio('posts[approve]', array(1=>'منتشر شده', 0=>'چرکنویس'), 1);
	$array['{comment}'] = html::radio('posts[comment]', array(1=>'فعال', 0=>'غیرفعال'), 1);
	$array['{fixed}'] = html::radio('posts[fixed]', array(1=>'فعال', 0=>'غیرفعال'), 0);
	$array['{view}'] = html::select('posts[view]', array(1=>'همه کاربران', 2=>'فقط کاربران عضو سایت', 3=>'فقط کاربران غیر عضو سایت', 4=>'فقط مدیران سایت', 5=>'فقط مدیر کل سایت'), 1);

	$data = explode(' ', jdate('Y-m-d H:i:s', time_now, 0));
	$date = explode('-', $data[0]);
	$date['year'] = $date[0];
	$date['month'] = $date[1];
	$date['day'] = $date[2];
	$date['hour'] = explode(':', $data[1]);
	$date['minute'] = $date['hour'][1];
	$date['second'] = $date['hour'][2];
	$date['hour'] = $date['hour'][0];

	/**
	* Chosen Library
	* @since 1.1
	*/
	set_script('chosen',url.'engine/javascript/chosen/chosen.jquery.min.js');
	set_link('chosen',url.'engine/javascript/chosen/chosen.min.css');

	$array['{categories}'] = '<select data-placeholder="موضوع پست خود را وارد کنید" name="posts[categories][]" style="width:755px" multiple="true" class="chosen-select chosen-rtl">';
	$array['{categories}'] .= '<option value="0" style="font-weight: bold">بدون موضوع!</option>';
	if (isset($categories) && is_array($categories) && count($categories))
	{
		foreach ($categories as $c)
		{
			if ($c['term_parent'] != 0) continue;
			$array['{categories}'] .= '<option value="'.$c['term_id'].'" style="font-weight: bold">'.$c['term_name'].'</option>';
			foreach ($categories as $p)
			{
				if ($p['term_parent'] != $c['term_id']) continue;
				$array['{categories}'] .= '<option value="'.$p['term_id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$p['term_name'].'</option>';
			}
		}
	}
	$array['{categories}'] .= '</select>';

	$year = $date['year'] - 3;
	$op = array();
	for ($year; $year < jdate('Y', time_now, 0)+5; $year++)
	{
		$op[$year] = $year;
	}
	$array['{year}'] = html::select('posts[year]', $op, $date['year']);

	$op = array();
	for ($i = 1; $i <= 12; $i++)
	{
		$op[$i <= 9? '0'.$i : $i] = $i <= 9? '0'.$i : $i;
	}
	$array['{month}'] = html::select('posts[month]', $op, $date['month']);

	$op = array();
	for ($i = 1; $i <= 31; $i++)
	{
		$op[$i <= 9? '0'.$i : $i] = $i <= 9? '0'.$i : $i;
	}
	$array['{day}'] = html::select('posts[day]', $op, $date['day']);

	$op = array();
	for ($i = 0; $i <= 23; $i++)
	{
		$op[$i <= 9? '0'.$i : $i] = $i <= 9? '0'.$i : $i;
	}
	$array['{hour}'] = html::select('posts[hour]', $op, $date['hour']);

	$op = array();
	for ($i = 0; $i <= 59; $i++)
	{
		$op[$i <= 9? '0'.$i : $i] = $i <= 9? '0'.$i : $i;
	}
	$array['{minute}'] = html::select('posts[minute]', $op, $date['minute']);
	$array['{second}'] = html::select('posts[second]', $op, $date['second']);

	$itpl->assign($array);

	if (is_array($fields) && count($fields))
	{
		foreach ($fields as $f_name => $f_info)
		{
			switch ($f_info['type'])
			{
				case 'text':
				$input = '<input name="posts[fields]['.$f_name.']" type="text" value="'.htmlspecialchars($f_info['default']).'" style="width:744px" />';
				break;
			
				case 'select':
				$f_info['default'] = explode("\n", $f_info['default']);
				$options = array();
				foreach ($f_info['default'] as $o)
				{
					$o = trim($o);
					if ($o == '') continue;
					$o = htmlspecialchars($o);
					$options[$o] = $o;
				}
				$input = html::select('posts[fields]['.$f_name.']', $options);
				unset($options, $o);
				break;

				case 'editor':
				$itpl->add_for('editors', array(
					'{editor-id}' => 'textarea_'.trim(str_replace(array('[', ']', '-', '__', ' '), '_', 'posts[fields]['.$f_name.']'), '_'),
				));

				$input = wysiwyg_textarea('posts[fields]['.$f_name.']', $f_info['default']);
				break;

				default:
				$input = '<textarea name="posts[fields]['.$f_name.']" style="width:744px;height:70px">'.htmlspecialchars($f_info['default']).'</textarea>';
				break;
			}
			
			$itpl->add_for('fields', array(
				'{name}' => trim($f_name),
				'{title}' => $f_info['title'],
				'{input}' => $input,
				'replace' => array(
					'#\\[require\\](.*?)\\[/require\\]#s' => $f_info['require'] == 1? '\\1' : '',
				),
			));
		}
		unset($fields, $f_name, $f_info, $input);
	}

	if (isset($itpl->foreach['fields']))
	{
		$itpl->assign(array(
			'[is-fields]' => null,
			'[/is-fields]' => null,
		));
		$itpl->block('#\\[is-not-fields\\](.*?)\\[/is-not-fields\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[is-not-fields]' => null,
			'[/is-not-fields]' => null,
		));
		$itpl->block('#\\[is-fields\\](.*?)\\[/is-fields\\]#s', '');
	}
	
	$tpl->assign('{content}', $itpl->get_var());
	unset($itpl);
}

function _edit()
{
	global $tpl, $d;

	member::check_admin_page_access('posts') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	require_once(engine_dir.'editor.function.php');

	set_title('ویرایش پست');

	$id = get_param($_GET, 'id', 0);

	$data = get_posts(array(
		'approve' => 0,
		'date' => 0,
		'where' => "p.post_id='".$id."'",
		'limit' => array(1)
	));

	if (!isset($data[0]) || !is_array($data[0]) || !count($data[0]))
	{
		redirect(admin_page.'&module=posts');
	}
	
	$data = $data[0];
	
	if ($data['post_author'] == member_id || group_super_admin == 1)
	{
		$fields = posts_options();
		$fields = $fields['fields'];
		
		$posts = get_param($_POST, 'posts', null, 1);

		if (isset($posts) && is_array($posts) && count($posts))
		{
			$msg = array();
			$posts['title'] = isset($posts['title'])? htmlencode($posts['title']) : null;
			$posts['name'] = isset($posts['name']) && $posts['name'] != ''? slug($posts['name']) : slug($posts['title']);
			$posts['image'] = isset($posts['image'])? nohtml($posts['image']) : null;
			$posts['text'] = isset($posts['text'])? trim($posts['text']) : null;
			$posts['more'] = isset($posts['more'])? trim($posts['more']) : null;
			$posts['comment'] = isset($posts['comment']) && $posts['comment'] == 1? 1 : 0;
			$posts['fixed'] = isset($posts['fixed']) && $posts['fixed'] == 1? 1 : 0;
			$posts['approve'] = isset($posts['approve']) && $posts['approve'] == 1? 1 : 0;
			$posts['view'] = isset($posts['view'])? intval($posts['view']) : 0;
			$posts['tags'] = isset($posts['tags'])? trim($posts['tags']) : null;

			if (!isset($posts['categories']) || !is_array($posts['categories']))
			{
				$posts['categories'] = array();
			}

			$categories = _cats($posts['categories']);
			$tags = _tags($posts['tags']);

			$posts['text'] = preg_replace('#(\A[\s]*<br[^>]*>[\s]*|<br[^>]*>[\s]*\Z)#is', '', $posts['text']); // remove <br/> at end of string
			$posts['more'] = preg_replace('#(\A[\s]*<br[^>]*>[\s]*|<br[^>]*>[\s]*\Z)#is', '', $posts['more']); // remove <br/> at end of string

			if ($posts['title'] == '')
			{
				$msg[] = 'عنوان پست را ننوشته اید!';
			}

			if ($posts['name'] == '')
			{
				$msg[] = 'نام مستعار پست را ننوشته اید!';
			}
			else
			{
				if (apadana_strlen($posts['name']) > 250)
				{
					$msg[] = 'نام مستعار بیش از انداره طولانی است!';
				}
				elseif ($data['post_name'] != $posts['name'] && $d->num_rows("SELECT `post_id` FROM `#__posts` WHERE `post_name`='".$d->escape_string($posts['name'])."'", true) >= 1)
				{
					$msg[] = 'نام مستعار تکراری است یک نام مستعار دیگر انتخاب کنید!';
				}
			}

			if ($posts['text'] == '')
			{
				$msg[] = 'متن پست را ننوشته اید!';
			}

			$date = _date($posts['year'], $posts['month'], $posts['day'], $posts['hour'], $posts['minute'], $posts['second']);
			if ($date != 'true')
			{
				if ($date == 'numeric') $msg[] = 'تاریخ ویا زمان انتخاب شده برای پست معتبر نیست!';
				if ($date == 'year') $msg[] = 'سال انتخاب شده برای انتشار پست معتبر نیست!';
				if ($date == 'month') $msg[] = 'ماه انتخاب شده برای انتشار پست معتبر نیست!';
				if ($date == 'day') $msg[] = 'روز انتخاب شده برای انتشار پست معتبر نیست!';
				if ($date == 'hour') $msg[] = 'ساعت انتخاب شده برای انتشار پست معتبر نیست!';
				if ($date == 'minute') $msg[] = 'دقیقه انتخاب شده برای انتشار پست معتبر نیست!';
				if ($date == 'second') $msg[] = 'ثانیه انتخاب شده برای انتشار پست معتبر نیست!';
			}
			else
			{
				$date = jalali_to_gregorian($posts['year'], $posts['month'], $posts['day']);
				$date = strtotime($date[0].'-'.$date[1].'-'.$date[2].' '.$posts['hour'].':'.$posts['minute'].':'.$posts['second']);
			}

			if (is_array($fields) && count($fields))
			{
				foreach ($fields as $f_name => $f_info)
				{
					if ($f_info['require'] == 1 && (!isset($posts['fields'][$f_name]) || trim($posts['fields'][$f_name]) == ''))
					{
						$msg[] = 'پر کردن فیلد اضافی <b>'.$f_info['title'].'</b> الزامی می باشد!';
					}
				}
				unset($f_name, $f_info);
			}
			
			if (count($msg))
			{
				echo message(implode('<br/>', $msg), 'error');
				echo '<script>apadana.value("posts-name", "'.urldecode($posts['name']).'")</script>';
			}
			else
			{
				$save = 0;
				if (isset($posts['fields']) && is_array($posts['fields']) && count($posts['fields']) && $id > 0)
				{
					$d->delete('fields', "`field_type`='p' AND `field_link`='".$id."'");
					foreach ($posts['fields'] as $field => $value)
					{
						$value = trim($value);
						if (!isset($fields[$field]) || $value == '') continue;

						$value = template_off($value);
						$value = template_off($value);
						// $value = str_replace('[', '&#x5B;', $value);
						// $value = str_replace('{', '&#x7B;', $value);
						
						$d->insert('fields', array(
							'field_link' => $id,
							'field_name' => trim($field),
							'field_value' => $value,
							'field_type' => 'p',
						));
						if ($d->affected_rows()) $save++;
					}
				}

				$posts['text'] = template_off($posts['text']);
				$posts['more'] = template_off($posts['more']);
				// $posts['text'] = str_replace('[', '&#x5B;', $posts['text']);
				// $posts['text'] = str_replace('{', '&#x7B;', $posts['text']);
				// $posts['more'] = str_replace('[', '&#x5B;', $posts['more']);
				// $posts['more'] = str_replace('{', '&#x7B;', $posts['more']);

				$d->update('posts', array(
					'post_title' => $posts['title'],
					'post_name' => $posts['name'],
					'post_image' => $posts['image'],
					'post_text' => $posts['text'],
					'post_more' => $posts['more'],
					'post_date' => intval($date),
					'post_comment' => $posts['comment'],
					'post_fixed' => $posts['fixed'],
					'post_approve' => $posts['approve'],
					'post_view' => intval($posts['view']),
					'post_categories' => implode(',', $categories),
					'post_tags' => implode(',', $tags['id']),
				), "post_id = '{$id}'", 1);
				
				if ($d->affected_rows() || $save > 0)
				{
					# delete old tags
					$new = isset($tags['id']) && is_array($tags['id']) && count($tags['id'])? $tags['id'] : array();
					$data['post_tags_id'] = explode(',', $data['post_tags_id']);
					if (is_array($data['post_tags_id']) && count($data['post_tags_id']))
					{
						foreach ($data['post_tags_id'] as $tag)
						{
							if (in_array($tag, $new)) continue;
							
							$tagsQuery = $d->query("SELECT `post_id` FROM `#__posts` WHERE FIND_IN_SET(".intval($tag).", `post_tags`)");
							if ($d->num_rows($tagsQuery) <= 0)
							{
								$d->delete('terms', "`term_type`='p-tag' AND `term_id`='".intval($tag)."'", 1);
							}
							$d->free_result($tagsQuery);
						}
					}

					echo message('پست با موفقیت ویرایش شد!', 'success');
					remove_cache('module-posts', true);
					remove_cache('posts_archive');
				}
				else
				{
					echo message('در ذخیره اطلاعات خطایی رخ داده مجدد تلاش کنید!', 'error');
				}
			}
			exit;
		}

		$categories = posts_categories();

		$itpl = new template('modules/posts/html/admin/posts-edit.tpl');

		$array['{id}'] = $data['post_id'];
		$array['{title}'] = $data['post_title'];
		$array['{name}'] = urldecode($data['post_name']);
		$array['{image}'] = $data['post_image'];
		$array['{textarea}'] = wysiwyg_textarea('posts[text]', $data['post_text']);
		$array['{textarea-more}'] = wysiwyg_textarea('posts[more]', $data['post_more']);
		$array['{watch}'] = jdate('Y-m-d ساعت H:i:s', time_now, 1);
		$array['{approve}'] = html::radio('posts[approve]', array(1=>'منتشر شده', 0=>'چرکنویس'), $data['post_approve']);
		$array['{comment}'] = html::radio('posts[comment]', array(1=>'فعال', 0=>'غیرفعال'), $data['post_comment']);
		$array['{fixed}'] = html::radio('posts[fixed]', array(1=>'فعال', 0=>'غیرفعال'), $data['post_fixed']);
		$array['{view}'] = html::select('posts[view]', array(1=>'همه کاربران', 2=>'فقط کاربران عضو سایت', 3=>'فقط کاربران غیر عضو سایت', 4=>'فقط مدیران سایت', 5=>'فقط مدیر کل سایت'), $data['post_view']);

		set_script('chosen',url.'engine/javascript/chosen/chosen.jquery.min.js');
		set_link('chosen',url.'engine/javascript/chosen/chosen.min.css');

		$array['{categories}'] = '<select data-placeholder="موضوع پست خود را وارد کنید" name="posts[categories][]" style="width:755px" multiple="true" class="chosen-select chosen-rtl">';
		$array['{categories}'] .= '<option value="0" style="font-weight: bold">بدون موضوع!</option>';
		if (isset($categories) && is_array($categories) && count($categories))
		{
			$cats = explode(',', $data['post_categories_id']);
			foreach ($categories as $c)
			{
				if ($c['term_parent'] != 0) continue;
				$array['{categories}'] .= '<option value="'.$c['term_id'].'"'.(in_array($c['term_id'], $cats)? ' selected="selected"' : null).' style="font-weight: bold">'.$c['term_name'].'</option>';
				foreach ($categories as $p)
				{
					if ($p['term_parent'] != $c['term_id']) continue;
					$array['{categories}'] .= '<option value="'.$p['term_id'].'"'.(in_array($p['term_id'], $cats)? ' selected="selected"' : null).'>&nbsp;&nbsp;&nbsp;&nbsp;'.$p['term_name'].'</option>';
				}
			}
		}
		$array['{categories}'] .= '</select>';

		$array['{tags}'] = null;
		if (is_array($data['post_tags']) && count($data['post_tags']))
		{
			foreach ($data['post_tags'] as $tag)
			{
				$array['{tags}'] .= $tag['name'].',';
			}
		}

		$data['post_date'] = explode(' ', jdate('Y-m-d H:i:s', $data['post_date'], 0));
		$date = explode('-', $data['post_date'][0]);
		$date['year'] = $date[0];
		$date['month'] = $date[1];
		$date['day'] = $date[2];
		$date['hour'] = explode(':', $data['post_date'][1]);
		$date['minute'] = $date['hour'][1];
		$date['second'] = $date['hour'][2];
		$date['hour'] = $date['hour'][0];
		
		$year = $date['year'] - 3;
		$op = array();
		for ($year; $year < jdate('Y', time_now, 0)+5; $year++)
		{
			$op[$year] = $year;
		}
		$array['{year}'] = html::select('posts[year]', $op, $date['year']);

		$op = array();
		for ($i = 1; $i <= 12; $i++)
		{
			$op[$i <= 9? '0'.$i : $i] = $i <= 9? '0'.$i : $i;
		}
		$array['{month}'] = html::select('posts[month]', $op, $date['month']);

		$op = array();
		for ($i = 1; $i <= 31; $i++)
		{
			$op[$i <= 9? '0'.$i : $i] = $i <= 9? '0'.$i : $i;
		}
		$array['{day}'] = html::select('posts[day]', $op, $date['day']);

		$op = array();
		for ($i = 0; $i <= 23; $i++)
		{
			$op[$i <= 9? '0'.$i : $i] = $i <= 9? '0'.$i : $i;
		}
		$array['{hour}'] = html::select('posts[hour]', $op, $date['hour']);

		$op = array();
		for ($i = 0; $i <= 59; $i++)
		{
			$op[$i <= 9? '0'.$i : $i] = $i <= 9? '0'.$i : $i;
		}
		$array['{minute}'] = html::select('posts[minute]', $op, $date['minute']);
		$array['{second}'] = html::select('posts[second]', $op, $date['second']);

		$itpl->assign($array);

		if (is_array($fields) && count($fields))
		{
			foreach ($fields as $f_name => $f_info)
			{
				switch ($f_info['type'])
				{
					case 'text':
					$value = htmlspecialchars(isset($data['post_fields'][$f_name])? $data['post_fields'][$f_name] : $f_info['default']);
					$value = str_replace('&amp;#x5B;', '&#x5B;', $value);
					$value = str_replace('&amp;#x7B;', '&#x7B;', $value);

					$input = '<input name="posts[fields]['.$f_name.']" type="text" value="'.$value.'" style="width:744px" />';
					break;
				
					case 'select':
					$f_info['default'] = explode("\n", $f_info['default']);
					$options = array();
					foreach ($f_info['default'] as $o)
					{
						$o = trim($o);
						if ($o == '') continue;
						$o = htmlspecialchars($o);
						$o = str_replace('&amp;#x5B;', '&#x5B;', $o);
						$o = str_replace('&amp;#x7B;', '&#x7B;', $o);

						$options[$o] = $o;
					}
					$input = html::select('posts[fields]['.$f_name.']', $options, isset($data['post_fields'][$f_name])? htmlspecialchars($data['post_fields'][$f_name]) : null);
					unset($options, $o);
					break;

					case 'editor':
					$itpl->add_for('editors', array(
						'{editor-id}' => 'textarea_'.trim(str_replace(array('[', ']', '-', '__', ' '), '_', 'posts[fields]['.$f_name.']'), '_'),
					));

					$input = wysiwyg_textarea('posts[fields]['.$f_name.']', (isset($data['post_fields'][$f_name])? $data['post_fields'][$f_name] : $f_info['default']));
					break;

					default:
					$value = htmlspecialchars(isset($data['post_fields'][$f_name])? $data['post_fields'][$f_name] : $f_info['default']);
					$value = str_replace('&amp;#x5B;', '&#x5B;', $value);
					$value = str_replace('&amp;#x7B;', '&#x7B;', $value);

					$input = '<textarea name="posts[fields]['.$f_name.']" style="width:744px;height:70px">'.$value.'</textarea>';
					break;
				}
				
				$itpl->add_for('fields', array(
					'{name}' => trim($f_name),
					'{title}' => $f_info['title'],
					'{input}' => $input,
					'replace' => array(
						'#\\[require\\](.*?)\\[/require\\]#s' => $f_info['require'] == 1? '\\1' : '',
					),
				));
			}
			unset($fields, $f_name, $f_info, $input);
		}

		if (isset($itpl->foreach['fields']))
		{
			$itpl->assign(array(
				'[is-fields]' => null,
				'[/is-fields]' => null,
			));
			$itpl->block('#\\[is-not-fields\\](.*?)\\[/is-not-fields\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[is-not-fields]' => null,
				'[/is-not-fields]' => null,
			));
			$itpl->block('#\\[is-fields\\](.*?)\\[/is-fields\\]#s', '');
		}
		
		$tpl->assign('{content}', $itpl->get_var());
		unset($itpl);
	}
	else
	{
		set_content(false, message('شما دسترسی لازم برای ویرایش این پست را ندارید!', 'error'));
	}
}

function _delete()
{
	global $d;

	member::check_admin_page_access('posts') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT `post_author`, `post_tags`,`post_date` FROM `#__posts` WHERE `post_id`='{$id}' LIMIT 1");

	if ($d->num_rows() <= 0)
	{
		redirect(admin_page.'&module=posts');
	}

	$data = $d->fetch();

	if ($data['post_author'] == member_id || group_super_admin == 1)
	{
		$data['post_tags'] = explode(',', $data['post_tags']);
		if (is_array($data['post_tags']) && count($data['post_tags']))
		foreach ($data['post_tags'] as $tag)
		{
			if ($d->num_rows("SELECT * FROM `#__posts` WHERE FIND_IN_SET(".intval($tag).", post_tags) AND `post_id`!='".intval($id)."'", true) <= 0)
			{
				$d->delete('terms', "`term_type`='p-tag' AND `term_id`='".intval($tag)."'", 1);
			}
		}
		$d->delete('fields', "`field_type`='p' AND `field_link`='".intval($id)."'");
		$d->delete('comments', "`comment_type`='posts' AND `comment_link`='".intval($id)."'");
		$d->delete('posts', "`post_id`='".intval($id)."'", 1);
		echo message('پست با موفقیت حذف شد!', 'success');
		remove_cache('module-posts', true);
		remove_cache('posts_archive');

	}
	else
	{
		echo message('شما دسترسی لازم برای حذف این پست را ندارید!', 'error');
	}
	_default();
}

######################## COMMENTS FIELDS ########################

function _fields()
{
	global $page, $tpl, $d;

	member::check_admin_page_access('posts-fields') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	set_title('فیلدهای اضافی پست ها');

	$itpl = new template('modules/posts/html/admin/fields.tpl');
	
	$types = array('text', 'textarea', 'editor', 'select');

	$fields = posts_options();
	$fields = $fields['fields'];

	if (is_array($fields) && count($fields))
	{
		foreach ($fields as $data) 
		{
			$itpl->add_for ('fields', array(
				'{odd-even}' => odd_even(),
				'{type}' => $data['type'],
				'{name}' => $data['name'],
				'{title}' => $data['title'],
				'{default}' => $data['default'],
				'{require}' => $data['require'],
				'replace' => array(
					'#\\['.$data['type'].'\\](.*?)\\[/'.$data['type'].'\\]#s' => '\\1',
					'#\\[('.implode('|', array_diff($types, array($data['type']))).')\\](.*?)\\[/\\1\\]#s' => '',
					'#\\[require\\](.*?)\\[/require\\]#s' => $data['require'] == 1? '\\1' : '',
					'#\\[not-require\\](.*?)\\[/not-require\\]#s' => $data['require'] != 1? '\\1' : '',
				)
			));
		}
		
		$itpl->assign(array(
			'[fields]' => null,
			'[/fields]' => null,
		));
		$itpl->block('#\\[not-fields\\](.*?)\\[/not-fields\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-fields]' => null,
			'[/not-fields]' => null,
		));
		$itpl->block('#\\[fields\\](.*?)\\[/fields\\]#s', '');
	}

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

function _fields_new()
{
	global $page, $tpl, $d;

	member::check_admin_page_access('posts-fields') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$op = posts_options();
	
	$types = array('text', 'textarea', 'editor', 'select');
	$fields = get_param($_POST, 'fields', null, 1);

	if (isset($fields) && is_array($fields) && count($fields))
	{
		$msg = array();
		$fields['name'] = isset($fields['name'])? trim($fields['name']) : null;
		$fields['title'] = isset($fields['title'])? htmlencode($fields['title']) : null;
		$fields['type'] = isset($fields['type'])? trim($fields['type']) : null;
		$fields['default'] = isset($fields['default'])? trim($fields['default']) : null;
		$fields['require'] = isset($fields['require']) && $fields['require'] == 1? 1 : 0;

		if (!is_alphabet($fields['name']))
		{
			$msg[] = 'نام فیلد باید شمال حروف و اعداد لاتین باشد!';
		}
		else
		{
			if (isset($op['fields'][$fields['name']]))
			{
				$msg[] = 'از این نام قبلا استفاده شده است!';
			}
		}
		
		if ($fields['title'] == '')
		{
			$msg[] = 'عنوان فیلد را لطفا مشخص کنید!';
		}
		
		if (!in_array($fields['type'], $types))
		{
			$msg[] = 'نوع انتخابی برای فیلد معتبر نمی باشد!';
		}
		
		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
		}
		else
		{
			$op['fields'][$fields['name']] = array(
				'name' => $fields['name'],
				'title' => $fields['title'],
				'type' => $fields['type'],
				'default' => $fields['default'],
				'require' => $fields['require']
			);

			ksort($op['fields']);
			$d->update('options', array(
				'option_value' => serialize($op)
			), "`option_name`='posts'", 1);	

			if ($d->affected_rows())
			{
				echo message('فیلد <b>'.$fields['name'].'</b> با موفقیت ساخته شد.', 'success');
				remove_cache('options-posts');
				echo '<script>apadana.hideID("new-fields-form")</script>';
			}
			else
			{
				echo message('آخ! در ذخیره خطایی رخ داده مجدد تلاش کنید.', 'error');
			}
		}
	}
	exit;
}

function _fields_edit()
{
	global $page, $tpl, $d;

	member::check_admin_page_access('posts-fields') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$op = posts_options();
	
	$types = array('text', 'textarea', 'editor', 'select');
	$fields = get_param($_POST, 'fields', null, 1);
	$name = get_param($_GET, 'name');

	if (!isset($op['fields'][$name]))
	{
		exit('این فیلد وجود ندارد!');
	}
	
	if (isset($fields) && is_array($fields) && count($fields))
	{
		$msg = array();
		$fields['name'] = isset($fields['name'])? trim($fields['name']) : null;
		$fields['title'] = isset($fields['title'])? htmlencode($fields['title']) : null;
		$fields['type'] = isset($fields['type'])? trim($fields['type']) : null;
		$fields['default'] = isset($fields['default'])? trim($fields['default']) : null;
		$fields['require'] = isset($fields['require']) && $fields['require'] == 1? 1 : 0;

		if (!is_alphabet($fields['name']))
		{
			$msg[] = 'نام فیلد باید شمال حروف و اعداد لاتین باشد!';
		}
		else
		{
			if (isset($op['fields'][$fields['name']]) && $name != $fields['name'])
			{
				$msg[] = 'از این نام قبلا استفاده شده است!';
			}
		}
		
		if ($fields['title'] == '')
		{
			$msg[] = 'عنوان فیلد را لطفا مشخص کنید!';
		}
		
		if (!in_array($fields['type'], $types))
		{
			$msg[] = 'نوع انتخابی برای فیلد معتبر نمی باشد!';
		}
		
		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
		}
		else
		{
			unset($op['fields'][$name]);
			$op['fields'][$fields['name']] = array(
				'name' => $fields['name'],
				'title' => $fields['title'],
				'type' => $fields['type'],
				'default' => $fields['default'],
				'require' => $fields['require']
			);

			ksort($op['fields']);
			$d->update('options', array(
				'option_value' => serialize($op)
			), "`option_name`='posts'", 1);	

			if ($d->affected_rows())
			{
				echo message('فیلد <b>'.$name.'</b> با موفقیت ویرایش شد.', 'success');
				remove_cache('options-posts');
				
				if ($name != $fields['name'])
				{
					$d->update('fields', array(
						'field_name' => $fields['name']
					), "`field_type`='p' AND `field_name`='".$d->escape_string($name)."'", 1);	
				}
			}
			else
			{
				echo message('آخ! در ویرایش خطایی رخ داده مجدد تلاش کنید.', 'error');
			}
		}
	}
	exit;
}

function _fields_delete()
{
	global $page, $tpl, $d;

	member::check_admin_page_access('posts-fields') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$op = posts_options();
	
	$name = get_param($_GET, 'name');

	if (!isset($op['fields'][$name]))
	{
		echo message('فیلد مورد نظر شما وجود ندارد!', 'error');
		exit;
	}
	
	unset($op['fields'][$name]);

	ksort($op['fields']);
	$d->update('options', array(
		'option_value' => serialize($op)
	), "`option_name`='posts'", 1);	

	if ($d->affected_rows())
	{
		echo message('فیلد <b>'.$name.'</b> با موفقیت حذف شد.', 'success');
		remove_cache('options-posts');
		
		$d->delete('fields', "`field_type`='p' AND `field_name`='".$d->escape_string($name)."'");
		if ($d->affected_rows())
		{
			echo message('اطلاعات فیلد <b>'.$name.'</b> با موفقیت از دیتابیس حذف شد!', 'success');
		}
	}
	else
	{
		echo message('آخ! در حذف خطایی رخ داده مجدد تلاش کنید.', 'error');
	}

	_fields();
}

######################## CATEGORIES FUNCTIONS ########################

function _categories()
{
	global $tpl;

	member::check_admin_page_access('posts-categories') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	posts_categories();
	set_title('موضوعات پست ها');

	$itpl = new template('modules/posts/html/admin/categories.tpl');
	$tpl->assign('{content}', $itpl->get_var());
}

function _categories_parent()
{
	member::check_admin_page_access('posts-categories') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	
	global $cache;

	if( !isset($cache['posts_categories']) || !$cache['posts_categories'] )
		posts_categories();

	$list = array();
	$list[0] = 'بدون سردسته (این موضوع سردسته است)';
	if (isset($cache['posts_categories']) && is_array($cache['posts_categories']) && count($cache['posts_categories']))
	{
		_get_categories_parent(0,$list);
	}

	echo html::select('categories[parent]', $list, isset($_GET['edit'])? -1 : 0, isset($_GET['edit'])? 'id="categories-parent-edit"' : 'id="categories-parent"');
	exit;
}

function _get_categories_parent($parent , &$array  , $depth = ''){
	global $cache;

	foreach ($cache['posts_categories'] as $cat)
	{
		if ($cat['term_parent'] != $parent) continue;
		$array[$cat['term_id']] = $depth . $cat['term_name'];
		_get_categories_parent($cat['term_id'],$array, ( $depth.'&nbsp;&nbsp;&nbsp;') );
	}
	return $array;
}

function _categories_new()
{
	global $cache, $d;

	member::check_admin_page_access('posts-categories') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	$categories_data = posts_categories();

	$categories = get_param($_POST, 'categories', null, 1);

	if (isset($categories) && is_array($categories) && count($categories))
	{
		$categories['parent'] = isset($categories['parent'])? intval($categories['parent']) : 0;
		$categories['name'] = isset($categories['name'])? htmlencode($categories['name']) : null;
		$categories['description'] = isset($categories['description'])? htmlencode($categories['description']) : null;
		$categories['slug'] = isset($categories['slug']) && $categories['slug'] != ''? slug($categories['slug']) : slug($categories['name']);

		$msg = array();

		if (empty($categories['name']))
		{
			$msg[] = 'عنوان موضوع را ننوشته اید!';
		}
		
		if ($categories['slug'] == '')
		{
			$msg[] = 'نام مستعار موضوع را ننوشته اید!';
		}
		else
		{
			if (apadana_strlen($categories['slug']) > 200)
			{
				$msg[] = 'نام مستعار بیش از انداره طولانی است!';
			}
			elseif ($d->num_rows("SELECT `term_id` FROM `#__terms` WHERE `term_type`='p-cat' AND `term_slug`='".$d->escape_string($categories['slug'])."'", true) >= 1)
			{
				$msg[] = 'نام مستعار تکراری است یک نام مستعار دیگر انتخاب کنید!';
			}
		}

		if ($categories['parent'] != 0 && !isset($categories_data[$categories['parent']]))
		{
			$msg[] = 'سردسته انتخاب شده معتبر نیست!';
		}

		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
			echo '<script>apadana.value("categories-slug", "'.urldecode($categories['slug']).'")</script>';
		}
		else
		{
			$d->insert('terms', array(
				'term_name' => $categories['name'],
				'term_slug' => $categories['slug'],
				'term_parent' => $categories['parent'],
				'term_description' => $categories['description'],
				'term_type' => 'p-cat',
			));	
			
			if ($d->affected_rows())
			{
				remove_cache('module-posts-categories', true);
				echo message('موضوع با موفقیت ذخیره شد!', 'success');
				echo '<script>apadana.hideID("form-new-categories")</script>';
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}

	exit;
}

function _categories_edit()
{
	global $cache, $d;
	member::check_admin_page_access('posts-categories') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT * FROM #__terms WHERE `term_id`='$id' LIMIT 1");

	if ($d->num_rows() <= 0)
	{
		echo message('این موضوع وجود ندارد!', 'error');
		exit;
	}

	$data = $d->fetch();
	$categories_data = posts_categories();
	$categories = get_param($_POST, 'categories', null, 1);

	if (isset($categories) && is_array($categories) && count($categories))
	{
		$categories['parent'] = isset($categories['parent'])? intval($categories['parent']) : 0;
		$categories['name'] = isset($categories['name'])? htmlencode($categories['name']) : null;
		$categories['description'] = isset($categories['description'])? htmlencode($categories['description']) : null;
		$categories['slug'] = isset($categories['slug']) && $categories['slug'] != ''? slug($categories['slug']) : slug($categories['name']);

		$msg = array();

		if (empty($categories['name']))
		{
			$msg[] = 'عنوان موضوع را ننوشته اید!';
		}
		
		if ($categories['slug'] == '')
		{
			$msg[] = 'نام مستعار موضوع را ننوشته اید!';
		}
		else
		{
			if (apadana_strlen($categories['slug']) > 200)
			{
				$msg[] = 'نام مستعار بیش از انداره طولانی است!';
			}
			elseif ($categories['slug'] != $data['term_slug'] && $d->num_rows("SELECT `term_id` FROM `#__terms` WHERE `term_type`='p-cat' AND `term_slug`='".$d->escape_string($categories['slug'])."'", true) >= 1)
			{
				$msg[] = 'نام مستعار تکراری است یک نام مستعار دیگر انتخاب کنید!';
			}
		}

		if (($categories['parent'] != 0 && !isset($categories_data[$categories['parent']])) || (isset($categories_data[$categories['parent']]) && $categories_data[$categories['parent']]['term_id'] == $id))
		{
			$msg[] = 'سردسته انتخاب شده معتبر نیست!';
		}

		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
			echo '<script>apadana.value("categories-slug-edit", "'.urldecode($categories['slug']).'")</script>';
		}
		else
		{
			$d->update('terms', array(
				'term_name' => $categories['name'],
				'term_slug' => $categories['slug'],
				'term_parent' => $categories['parent'],
				'term_description' => $categories['description'],
			), "`term_type`='p-cat' AND `term_id`='{$id}'", 1);	
			
			if ($d->affected_rows())
			{
				remove_cache('module-posts-categories', true);
				echo message('موضوع با موفقیت ویرایش شد!', 'success');

				if ($categories['parent'] != 0)
				{
					$d->update('terms', array(
						'term_parent' => $categories['parent'],
					), "`term_type`='p-cat' AND `term_parent`='{$id}'");

					if ($d->affected_rows())
					{
						echo message('سردسته موضوعاتی که این موضوع، سردسته آنها بود به سردسته جدید این موضوع تغییر پیدا کرد!', 'success');
					}
				}
			}
			else
			{
				echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}

	exit;
}

function _categories_list()
{
	global $cache, $d;

	member::check_admin_page_access('posts-categories') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	posts_categories();

	$itpl = new template('modules/posts/html/admin/categories-list.tpl');

	if (isset($cache['posts_categories']) && is_array($cache['posts_categories']) && count($cache['posts_categories']))
	{
		_get_categories_list(0,$itpl,'');
		
		$itpl->assign(array(
			'[categories]' => null,
			'[/categories]' => null,
		));
		$itpl->block('#\\[not-categories\\](.*?)\\[/not-categories\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-categories]' => null,
			'[/not-categories]' => null,
		));
		$itpl->block('#\\[categories\\](.*?)\\[/categories\\]#s', '');
	}

	$itpl->display();
	define('no_template', true);
}

function _get_categories_list($parent , &$itpl  , $depth = ''){
	global $cache;
	//print_r(get_class_methods($itpl));exit;
	foreach ($cache['posts_categories'] as $cat)
	{
		if ($cat['term_parent'] != $parent) continue;
		$array = array(
			'{odd-even}' => odd_even(),
			'{id}' => $cat['term_id'],
			'{name}' => $depth . $cat['term_name'],
			'{description}' => $cat['term_description'],
			'{slug}' => urldecode($cat['term_slug']),
			'{parent}' => $cat['term_parent'],
			'{parent-name}' => $parent != 0 ? $cache['posts_categories'][$cat['term_parent']]['term_name'] : $cat['term_name']
		);

		if( $parent != 0 ){
			$array['[child]'] = null;
			$array['[/child]'] = null;
			$array['replace']['#\\[not-child\\](.*?)\\[/not-child\\]#s'] = '';
		}else{
			$array['[not-child]'] = null;
			$array['[/not-child]'] = null;
			$array['replace']['#\\[child\\](.*?)\\[/child\\]#s'] = '';
		}
		$itpl->add_for('categories', $array );
		_get_categories_list($cat['term_id'],$itpl, ( $depth.':: ') );
	}
}

function _categories_delete()
{
	global $d;

	member::check_admin_page_access('posts-categories') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	$id = get_param($_GET, 'id', 0);

	$d->delete('terms', "`term_type`='p-cat' AND `term_id`='{$id}'", 1);

	if ($d->affected_rows())
	{
		remove_cache('module-posts-categories', true);
		$d->update('terms', array(
			'term_parent' => 0,
		), "`term_type`='p-cat' AND `term_parent`='{$id}'");
		/**
		* Very Optimized query than one used in 1.0.1
		* @since 1.1
		*/
		$d->query("UPDATE #__posts SET post_categories = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', post_categories, ','), ',".$id.",', ',')) WHERE FIND_IN_SET('".$id."', post_categories)");
		
		echo message('موضوع مورد نظر با موفقیت حذف شد.', 'success');
	}
	else
	{
		echo message('در حذف موضوع خطایی رخ داده مجدد تلاش کنید!', 'error');
	}

	_categories_list();
}

######################## OPTIONS FUNCTION ########################

function _options()
{
	global $d, $tpl;

	member::check_admin_page_access('posts-options') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	
	$opp = posts_options();
	$op = get_param($_POST, 'op');

	if (isset($op) && is_array($op) && count($op))
	{
		$op['total-posts'] = intval($op['total-posts']) <= 0? 1 : intval($op['total-posts']);
		$op['total-category'] = intval($op['total-category']) <= 0? 1 : intval($op['total-category']);
		$op['total-tag'] = intval($op['total-tag']) <= 0? 1 : intval($op['total-tag']);
		$op['total-author'] = intval($op['total-author']) <= 0? 1 : intval($op['total-author']);
		
		$op['fields'] = $opp['fields'];
		$d->update('options', array(
			'option_value' => serialize($op)
		), "`option_name`='posts'", 1);	
		
		if ($d->affected_rows())
		{
			remove_cache('options-posts');
			echo message('تنظیمات با موفقیت ذخیره شد!', 'success');
		}
		else
		{
			echo message('در ذخیره خطایی رخ داده مجدد تلاش کنید!', 'error');
		}
		exit;
	}

	$itpl = new template('modules/posts/html/admin/options.tpl');

	$itpl->assign(array(
		'{posts}' => $opp['total-posts'],
		'{category}' => $opp['total-category'],
		'{tag}' => $opp['total-tag'],
		'{author}' => $opp['total-author']
	));

	set_content(false, $itpl->get_var());
}

?>