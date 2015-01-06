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

member::check_admin_page_access('blocks') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $tpl, $options;

	require_once(engine_dir.'editor.function.php');

	set_title('بلوک ها');

	$template_info = template_info($options['theme']);
	$template_info = explode(',', $template_info['positions']);

	$positions = array();
	if (count($template_info))
	{
		foreach($template_info as $k => $pos)
		{
			$positions[$pos] = $pos;
		}
	}

	if (isset($positions) && count($positions))
	{
		$position = html::select('block[position]', $positions, null, 'id="block-position" dir=ltr');
	}
	else
	{
		$position = 'تم فعلی سایت شما بلوک ها را پشتیبانی نمی کند!';
	}
	
	if (isset($positions) && count($positions))
	{
		$edit_position = html::select('block[position]', $positions, null, 'id="block-edit-position" dir=ltr');
	}
	else
	{
		$edit_position = 'تم فعلی سایت شما بلوک ها را پشتیبانی نمی کند!';
	}

	$itpl = new template('engine/admin/template/blocks.tpl');
	$itpl->assign(array(
		'{my-url-1}' => str_replace('/', '\/', str_replace('http://www.', 'http://', url)),
		'{my-url-2}' => str_replace('/', '\/', str_replace('http://www.www.', 'http://www.', str_replace('http://', 'http://www.', url))),
		'{position}' => $position,
		'{edit-position}' => $edit_position,
		'{textarea}' => wysiwyg_textarea('block[content]', null),
		'{edit-textarea}' => wysiwyg_textarea('block[content2]', null),
		
		'{separator-rewrite}' => $options['separator-rewrite'],
		'{file-rewrite}' => $options['file-rewrite'],
		
		'[not-show-list]' => null,
		'[/not-show-list]' => null,
	));
	$itpl->block('#\\[show-list\\](.*?)\\[/show-list\\]#s', '');
	$tpl->assign('{content}', $itpl->get_var());
	unset($itpl);
}

function _list()
{
	global $d;

	$d->query("SELECT * FROM #__blocks ORDER BY block_position, block_ordering ASC");
	while ($data = $d->fetch()) 
	{
		$blocks[$data['block_position']][] = $data;
	}
	
	$itpl = new template('engine/admin/template/blocks.tpl');

	foreach($blocks as $position=>$data)
	{
		$x = 1;
		if (!is_array($data) || !count($data)) continue;

		foreach($data as $b)
		{
			if (count($data) <= 1)
			{
				$reposit = false;
			}
			else
			{
				$reposit  = $x-1 > 0? '<a href="javascript:return false" onclick="block_reposit(\'top\', '.$b['block_ordering'].')"><img src="'.url.'engine/images/icons/navigation-090-button.png" width="16" height="16" onmouseover="tooltip.show(\'بالا\')" onmouseout="tooltip.hide()"></a>' : null;				
				$reposit .= $x+1 <= count($data)? '&nbsp;<a href="javascript:return false" onclick="block_reposit(\'bottom\', '.$b['block_ordering'].')"><img src="'.url.'engine/images/icons/navigation-270-button.png" width="16" height="16" onmouseover="tooltip.show(\'پایین\')" onmouseout="tooltip.hide()"></a>' : null;
			}

			$itpl->add_for('list', array(
				'{odd-even}' => odd_even(),
				'{id}' => $b['block_id'],
				'{title}' => $b['block_title'],
				'{position}' => $b['block_position'],
				'{reposit}' => $reposit,
				'replace' => array(
					'#\\[reposit\\](.*?)\\[/reposit\\]#s' => $reposit? '\\1' : '',
					'#\\[not-reposit\\](.*?)\\[/not-reposit\\]#s' => !$reposit? '\\1' : '',
					'#\\[status\\](.*?)\\[/status\\]#s' => $b['block_active']==1? '\\1' : '',
					'#\\[not-status\\](.*?)\\[/not-status\\]#s' => $b['block_active']!=1? '\\1' : '',
				)
			));
			$x++;
		}
	}

	if (isset($itpl->foreach['list']))
	{
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

	if (is_ajax())
	{
		define('no_template', true);
		$itpl->display();
	}
	else
	{
		return $itpl->get_var();
	}
}

function _status()
{
	global $d;
	$id = get_param($_GET, 'id', 0);
	$d->query("SELECT `block_active` FROM `#__blocks` WHERE `block_id`='".$id."' LIMIT 1");
	$active = $d->fetch();
	$active = $active['block_active']==0? 1 : 0;
	
	$d->update('blocks', array(
		'block_active' => $active,
	), "`block_id`='{$id}'", 1);

	if ($d->affected_rows())
	{
		remove_cache('blocks');
		exit($active==1? 'active' : 'inactive');
	}
	else
	{
		exit('خطا');
	}
}

function _reposit()
{
	global $d;

	$type = get_param($_GET, 'type');
	$ordering = get_param($_GET, 'ordering', 0);

	$array = array();
	$d->query("SELECT `block_id` FROM `#__blocks` WHERE `block_ordering`='".intval($ordering)."' LIMIT 1");
	$id = $d->fetch();
	$id = $id['block_id'];

	if ($type == 'bottom')
	{
		$d->query("SELECT `block_ordering`,`block_id` FROM `#__blocks` WHERE `block_ordering`>'{$ordering}' ORDER BY `block_ordering` ASC LIMIT 1");
		$data = $d->fetch();

		if (is_array($data) && count($data))
		{
			$d->update('blocks', array(
				'block_ordering' => intval($ordering),
			), "`block_id`='".intval($data['block_id'])."'", 1);

			$d->update('blocks', array(
				'block_ordering' => intval($data['block_ordering']),
			), "`block_id`='".intval($id)."'", 1);
		}
	}
	else
	{
		$d->query("SELECT `block_ordering`,`block_id` FROM `#__blocks` WHERE `block_ordering`<'{$ordering}' ORDER BY `block_ordering` DESC LIMIT 1");
		$data = $d->fetch();

		if (is_array($data) && count($data))
		{
			$d->update('blocks', array(
				'block_ordering' => intval($ordering),
			), "`block_id`='".intval($data['block_id'])."'", 1);

			$d->update('blocks', array(
				'block_ordering' => intval($data['block_ordering']),
			), "`block_id`='".intval($id)."'", 1);
		}
	}	

	if (is_array($data) && count($data))
	{
		$data = null;
		$x = 1;
		$query = $d->query("SELECT `block_id` FROM #__blocks ORDER BY block_position, block_ordering ASC");
		while($data = $d->fetch($query)) 
		{
			$d->update('blocks', array(
				'block_ordering' => intval($x),
			), "`block_id`='".intval($data['block_id'])."'", 1);	
			$x++;
		}
	}

	remove_cache('blocks');
	_list();		
}

function _new()
{
	global $d;

	$json = array();
	$json['type'] = 'error';
	$json['message'] = array();
	
	$block = get_param($_POST, 'block', null, 2);

	if (isset($block) && is_array($block) && count($block))
	{
		$block['title'] = isset($block['title'])? htmlencode($block['title']) : null;
		$block['position'] = isset($block['position'])? alphabet($block['position']) : null;
		$block['function'] = isset($block['function'])? trim($block['function']) : null;
		$block['active'] = isset($block['active']) && $block['active'] == 1? 1 : 0;
		$block['view'] = isset($block['view'])? intval($block['view']) : 1;
		$block['content'] = isset($block['content'])? trim($block['content']) : null;
		$block['access'] = isset($block['access'])? nohtml($block['access']) : null;
		$block['access-type'] = isset($block['access-type']) && $block['access-type'] == 1? 1 : 0;

		if (!isset($block['title']) || empty($block['title']))
		{
			$json['message'][] = 'عنوان بلوک را ننوشته اید!';
		}
		
		if ($block['function'] != '' && (!is_alphabet($block['function']) || !function_exists('block_'.$block['function'])))
		{
			$json['message'][] = 'تابع <b>'.$block['function'].'</b> که برای این بلوک انتخاب شده است معتبر نمی باشد!';
		}
		
		if (count($json['message']))
		{
			$json['message'] = implode('<br/>', $json['message']);
		}
		else
		{
			if (!empty($block['function']) && apadana_strpos($block['content'], '[- options -]') !== FALSE)
			{
				$block['content'] = nohtml($block['content']);
				if (apadana_substr($block['content'], 0, 13) == '[- options -]')
				{
					$block['content'] = trim(apadana_substr($block['content'], 13));
					if (strpos($block['content'], '=') !== FALSE)
					{
						$content = null;
						$block['content'] = explode("\n", $block['content']);
						foreach($block['content'] as $c)
						{
							$c = explode('=', $c);
							if (!isset($c[0]) || !isset($c[1]) || trim($c[0])=='' || trim($c[1])=='') continue;
							$c[0] = trim($c[0]);
							$c[1] = trim($c[1]);
							$content .= $c[0].' = '.$c[1]."\n";
						}
						$block['content'] = empty($content)? null : "[- options -]\n".trim($content);
					}
					else
					{
						$block['content'] = null;
					}
				}
			}
			else
			{
				$block['content'] = template_off($block['content']);
			}
			
			if (!empty($block['access']))
			{
				$access = array();
				$block['access'] = explode("\n", $block['access']);
				if (count($block['access']))
				{
					foreach($block['access'] as $page)
					{
						$page = trim(urldecode($page));

						if ($page != '/')
						{
							$page = trim($page, '/');
						}
						if ($page == '')
						{
							continue;
						}

						$access[$page] = $page;
					}
				}
				$block['access'] = implode("\n", array_keys($access));
				$block['access'] = trim($block['access']);
				unset($access);
			}

			$d->query("SELECT MAX(block_ordering) as max FROM `#__blocks` WHERE `block_position`='".$block['position']."' LIMIT 1");
			$ordering = $d->fetch();
			$ordering = isset($ordering['max'])? ($ordering['max'] + 1) : 1;

			$d->insert('blocks', array(
				'block_title' => $block['title'],
				'block_content' => $block['content'],
				'block_function' => $block['function'],
				'block_position' => $block['position'],
				'block_access' => $block['access'],
				'block_access_type' => $block['access-type'],
				'block_active' => $block['active'],
				'block_view' => $block['view'],
				'block_ordering' => $ordering,
			));	
			
			if ($d->affected_rows())
			{
				remove_cache('blocks');
				$json['type'] = 'success';
				$json['message'] = 'بلوک با موفقیت ذخیره شد!';
			}
			else
			{
				$json['message'] = 'در ذخیره خطایی رخ داده مجدد تلاش کنید!';
			}
		}
	}

	exit(json_encode($json));
}

function _get_data()
{
	global $d;
	
	$id = get_param($_GET, 'id', 0);
	$d->query("SELECT * FROM `#__blocks` WHERE `block_id`='{$id}' LIMIT 1");

	if ($d->num_rows() <= 0)
	{
		exit('{"error":"not found"}');
	}
	
	$row = $d->fetch();

	// coloration
	if (!empty($row['block_function']) && apadana_strpos($row['block_content'], '[- options -]') !== FALSE)
	{
		$con = nohtml($row['block_content']);
		if (apadana_substr($con, 0, 13) == '[- options -]')
		{
			$con = trim(apadana_substr($con, 13));
			if (apadana_strpos($con, '=') !== FALSE)
			{
				$content = null;
				$con = explode("\n", $con);
				foreach($con as $c)
				{
					$c = explode('=', $c);
					if (!isset($c[0]) || !isset($c[1]) || trim($c[0])=='' || trim($c[1])=='') continue;
					$c[0] = trim($c[0]);
					$c[1] = trim($c[1]);
					$content .= '<span style="color:#009933">'.$c[0].'</span> <span style="color:#3366CC">=</span> <span style="color:#9900CC">'.$c[1].'</span>'."\n";
				}
				$con = empty($content)? null : '<span style="color:#CC0000">[- options -]</span>'."\n".trim($content);
			}

			$row['block_content'] = '<div style="text-align:left;direction:ltr;font-weight:bold">'.nl2br($con).'</div>';
		}
		unset($con);
	}
	
	exit(json_encode($row));
}

function _edit()
{
	global $d;

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT `block_id` FROM `#__blocks` WHERE `block_id`='{$id}' LIMIT 1");
	
	if ($d->num_rows() <= 0)
	{
		exit('{"type":"error", "message":"این بلوک یافت نشد!"}');
	}
	
	$json = array();
	$json['type'] = 'error';
	$json['message'] = array();
	
	$block = get_param($_POST, 'block', null, 2);

	if (isset($block) && is_array($block) && count($block))
	{
		$block['title'] = isset($block['title'])? htmlencode($block['title']) : null;
		$block['position'] = isset($block['position'])? alphabet($block['position']) : null;
		$block['function'] = isset($block['function'])? trim($block['function']) : null;
		$block['active'] = isset($block['active']) && $block['active'] == 1? 1 : 0;
		$block['view'] = isset($block['view'])? intval($block['view']) : 1;
		$block['content'] = isset($block['content2'])? trim($block['content2']) : null;
		$block['access'] = isset($block['access'])? nohtml($block['access']) : null;
		$block['access-type'] = isset($block['access-type']) && $block['access-type'] == 1? 1 : 0;

		if (!isset($block['title']) || empty($block['title']))
		{
			$json['message'][] = 'عنوان بلوک را ننوشته اید!';
		}
		
		if ($block['function'] != '' && (!is_alphabet($block['function']) || !function_exists('block_'.$block['function'])))
		{
			$json['message'][] = 'تابع <b>'.$block['function'].'</b> که برای این بلوک انتخاب شده است معتبر نمی باشد!';
		}
		
		if (count($json['message']))
		{
			$json['message'] = implode('<br/>', $json['message']);
		}
		else
		{
			if (!empty($block['function']) && apadana_strpos($block['content'], '[- options -]') !== FALSE)
			{
				$block['content'] = nohtml($block['content']);
				if (apadana_substr($block['content'], 0, 13) == '[- options -]')
				{
					$block['content'] = trim(apadana_substr($block['content'], 13));
					if (strpos($block['content'], '=') !== FALSE)
					{
						$content = null;
						$block['content'] = explode("\n", $block['content']);
						foreach($block['content'] as $c)
						{
							$c = explode('=', $c);
							if (!isset($c[0]) || !isset($c[1]) || trim($c[0])=='' || trim($c[1])=='') continue;
							$c[0] = trim($c[0]);
							$c[1] = trim($c[1]);
							$content .= $c[0].' = '.$c[1]."\n";
						}
						$block['content'] = empty($content)? null : "[- options -]\n".trim($content);
					}
					else
					{
						$block['content'] = null;
					}
				}
				call_user_func('block_'.$block['function'], 'remove-cache', $id, $block['position']);
			}
			else
			{
				$block['content'] = template_off($block['content']);
			}

			if (!empty($block['access']))
			{
				$access = array();
				$block['access'] = explode("\n", $block['access']);
				if (count($block['access']))
				{
					foreach($block['access'] as $page)
					{
						$page = trim(urldecode($page));

						if ($page != '/')
						{
							$page = trim($page, '/');
						}
						if ($page == '')
						{
							continue;
						}

						$access[$page] = $page;
					}
				}
				$block['access'] = implode("\n", array_keys($access));
				$block['access'] = trim($block['access']);
				unset($access);
			}

			$d->update('blocks', array(
				'block_title' => $block['title'],
				'block_content' => $block['content'],
				'block_function' => $block['function'],
				'block_position' => $block['position'],
				'block_access' => $block['access'],
				'block_access_type' => $block['access-type'],
				'block_active' => $block['active'],
				'block_view' => $block['view'],
			), "`block_id`='{$id}'", 1);	

			if ($d->affected_rows())
			{
				remove_cache('blocks');
				$json['type'] = 'success';
				$json['message'] = 'بلوک با موفقیت ویرایش شد!';
			}
			else
			{
				$json['message'] = 'در ذخیره خطایی رخ داده مجدد تلاش کنید!';
			}
		}
	}

	exit(json_encode($json));
}

function _delete()
{
	global $d;

	$id = get_param($_GET, 'id', 0);

	$d->query("SELECT `block_title`, `block_position` FROM `#__blocks` WHERE `block_id`='{$id}' LIMIT 1");
	
	if ($d->num_rows() <= 0)
	{
		echo message('این بلوک یافت نشد!', 'error');
		exit;
	}

	$row = $d->fetch();
	$d->delete('blocks', "`block_id`='{$id}'", 1);

	if ($d->affected_rows())
	{
		remove_cache('blocks');
		echo message('بلوک <b>'.$row['block_title'].'</b> با موفقیت حذف شد.', 'success');
		
		$x = 1;
		$query = $d->query("SELECT `block_id` FROM `#__blocks` WHERE `block_position`='".$row['block_position']."' ORDER BY block_position, block_ordering ASC");
		while($data = $d->fetch($query)) 
		{
			$d->update('blocks', array(
				'block_ordering' => intval($x),
			), "`block_id`='".intval($data['block_id'])."'", 1);	
			$x++;
		}
	}
	else
	{
		echo message('در حذف بلوک خطایی رخ داده مجدد تلاش کنید!', 'error');
	}

	_list();
}

$_GET['do'] = get_param($_GET, 'do');

switch($_GET['do'])
{
	case 'list':
	_list();
	break;
	
	case 'status':
	_status();
	break;

	case 'reposit':
	_reposit();
	break;

	case 'new':
	_new();
	break;

	case 'get-data':
	_get_data();
	break;
	
	case 'edit':
	_edit();
	break;

	case 'delete':
	_delete();
	break;

	default:
	_index();
	break;
}

?>