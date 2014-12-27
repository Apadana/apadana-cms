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

member::check_admin_page_access('files') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $options, $d, $tpl;
	require_once(engine_dir.'pagination.class.php');

	set_title('فایل های دانلودی');

	$order = get_param($_GET, 'order', 'DESC');
	$order = $order=='DESC'? 'DESC' : 'ASC';
	
	$total = get_param($_GET, 'total', 20);
	$total = $total<=0? 20 : $total;

	$_page = get_param($_GET, 'page', 1);
	$_page = $_page<=0? 1 : $_page;

	$search = get_param($_GET, 'search');

	$total_posts = $d->numRows("SELECT `file_id` FROM `#__files` ".(empty($search)? null : "WHERE `file_slug` LIKE '%".$d->escapeString($search)."%' OR `file_url` LIKE '%".$d->escapeString($search)."%'"), true);

	$pagination = new pagination($total_posts, $total, $_page);

	$itpl = new template('modules/files/html/admin/index.tpl');

	if (is_ajax())
	{
		$d->query("
			SELECT f.*, m.member_name, m.member_alias
			FROM #__files AS f	
			LEFT JOIN #__members AS m ON m.member_id=f.file_author
			".(empty($search)? null : "WHERE f.file_slug LIKE '%".$d->escapeString($search)."%' OR f.file_url LIKE '%".$d->escapeString($search)."%'")."
			GROUP BY f.file_id
			ORDER BY f.file_id {$order}
			LIMIT $pagination->Start, $pagination->End
		");
		if ($d->numRows() >= 1)
		{
			while($data = $d->fetch()) 
			{
				$itpl->add_for('list', array(
					'{odd-even}' => odd_even(),
					'{id}' => $data['file_id'],
					'{name}' => basename($data['file_url']),
					'{count-downloads}' => $data['file_count_downloads'],
					'{author}' => empty($data['member_alias'])? $data['member_alias'] : $data['member_name'],
					'{past-time}' => get_past_time($data['file_date'])
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
				'{msg}' => empty($search)? 'هیچ فایلی در سیستم ثبت نشده است!' : 'هیچ فایلی برای جستجوی <b>'.$search.'</b> یافت نشد!',
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
		'{search}' => $search,
		'{my-url-1}' => str_replace('/', '\/', str_replace('http://www.', 'http://', url)),
		'{my-url-2}' => str_replace('/', '\/', str_replace('http://www.www.', 'http://www.', str_replace('http://', 'http://www.', url))),
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
	global $d, $options;

	$json = array();
	$json['type'] = 'error';
	$json['message'] = array();

	$url = str_replace('http://www.', 'http://', url);
	$url2 = str_replace('http://www.www.', 'http://www.', str_replace('http://', 'http://www.', url));

	$files = get_param($_POST, 'files', null, 1);
	$files['url'] = isset($files['url'])? nohtml($files['url']) : null;
	$files['url'] = str_replace(array($url, $url2), null, $files['url']);
	$files['slug'] = isset($files['slug']) && $files['slug'] != ''? slug($files['slug']) : slug(basename($files['url']));
	$files['access'] = isset($files['access'])? intval($files['access']) : 1;

	if (empty($files['url']))
	{
		$json['message'][] = 'آدرس فایل را ننوشته اید!';
	}
	else
	{
		if (!file_exists(root_dir.$files['url']))
		{
			$json['message'][] = 'فایل مشخص شده در هاست سایت یافت شد!';
		}
		elseif (!is_readable(root_dir.$files['url']))
		{
			$json['message'][] = 'فایل مشخص شده قابل خواندن نیست!';
		}
	}

	if ($files['slug'] == '')
	{
		$json['message'][] = 'نام مستعار فایل را ننوشته اید!';
	}
	else
	{
		if (apadana_strlen($files['slug']) > 200)
		{
			$json['message'][] = 'نام مستعار بیش از انداره طولانی است!';
		}
		elseif ($d->numRows("SELECT `file_id` FROM `#__files` WHERE `file_slug`='".$d->escapeString($files['slug'])."'", true) >= 1)
		{
			$json['message'][] = 'نام مستعار تکراری است یک نام مستعار دیگر انتخاب کنید!';
		}
	}

	if (count($json['message']))
	{
		$json['message'] = implode('<br/>', $json['message']);
	}
	else
	{
		$id = $d->insert('files', array(
			'file_url' => $files['url'],
			'file_slug' => $files['slug'],
			'file_access' => $files['access'],
			'file_author' => member_id,
			'file_date' => time(),
		));

		if ($d->affectedRows())
		{
			remove_cache('module-files', true);
			$json['url'] = url.'?a=files&b='.$id;

			if ($options['rewrite'] == 1)
			{
				$json['urlSeo'] = url('files/'.$files['slug']);
			}

			$json['type'] = 'success';
			$json['message'] = 'فایل با موفقیت ذخیره شد.';
		}
		else
		{
			$json['message'] = 'در ذخیره اطلاعات خطایی رخ داده مجدد تلاش کنید!';
		}
	}

	$json['slug'] = urldecode($files['slug']);
	exit(json_encode($json));
}

function _get_info()
{
	global $d, $options;
	
	$_GET['id'] = get_param($_GET, 'id', 0);
	$d->query("SELECT * FROM `#__files` WHERE `file_id`='".$_GET['id']."' LIMIT 1");

	if ($d->numRows() <= 0)
	{
		exit('{"error":"not found"}');
	}
	
	$row = $d->fetch();

	if ($row['file_author'] != member_id && group_super_admin != 1)
	{
		exit('{"error":"access"}');
	}
	
	$row['file_date'] = jdate('l j F Y ساعت g:i A', $row['file_date']);
	$row['url'] = url.'?a=files&b='.$row['file_id'];

	if ($options['rewrite'] == 1)
	{
		$row['urlSeo'] = url('files/'.$row['file_slug']);
	}

	unset($row['file_members']);
	exit(json_encode($row));
}

function _get_members()
{
	global $d, $options;
	
	$_GET['id'] = get_param($_GET, 'id', 0);
	$d->query("SELECT `file_author`, `file_members` FROM `#__files` WHERE `file_id`='".$_GET['id']."' LIMIT 1");

	if ($d->numRows() <= 0)
	{
		exit('{"error":"not found"}');
	}
	
	$row = $d->fetch();

	if ($row['file_author'] != member_id && group_super_admin != 1)
	{
		exit('{"error":"access"}');
	}

	$json = array();
	$json['members'] = array();

	$row['file_members'] = explode(',', $row['file_members']);
	$row['file_members'] = array_map('trim', $row['file_members']);
	if (count($row['file_members']))
	{
		foreach ($row['file_members'] as $m)
		{
			if ($m == '') continue;
			$json['members'][] = '<a href="'.url('account/profile/'.$m).'" target="_blank">'.$m.'</a>';
		}
	}

	$count = count($json['members']);
	$json['members'] = implode(', ', $json['members']);
	if ($count <= 0)
	{
		$json['members'] = message('هنوز هیچ کاربری این فایل را دانلود نکرده است!', 'info');
	}
	else
	{
		$json['members'] = message('در مجموع '.$count.' کاربر فایل را دانلود کرده اند.', 'info') . $json['members'];
	}

	exit(json_encode($json));
}


function _get_data()
{
	global $d;
	
	$_GET['id'] = get_param($_GET, 'id', 0);
	$d->query("SELECT * FROM `#__files` WHERE file_id='".$_GET['id']."' LIMIT 1");

	if ($d->numRows() <= 0)
	{
		exit('{"error":"not found"}');
	}
	
	$row = $d->fetch();

	if ($row['file_author'] != member_id && group_super_admin != 1)
	{
		exit('{"error":"access"}');
	}

	$row['file_slug'] = urldecode($row['file_slug']);
	unset($row['file_members']);
	exit(json_encode($row));
}

function _edit()
{
	global $d, $options;
	
	$_GET['id'] = get_param($_GET, 'id', 0);
	$d->query("SELECT * FROM `#__files` WHERE `file_id`='".$_GET['id']."' LIMIT 1");

	if ($d->numRows() <= 0)
	{
		exit('{"type":"error", "message":"این فایل یافت نشد!"}');
	}
	
	$row = $d->fetch();

	if ($row['file_author'] != member_id && group_super_admin != 1)
	{
		exit('{"type":"error", "message":"شما دسترسی لازم برای ویرایش این فایل را ندارید!"}');
	}
	
	$json = array();
	$json['type'] = 'error';
	$json['message'] = array();

	$url = str_replace('http://www.', 'http://', url);
	$url2 = str_replace('http://www.www.', 'http://www.', str_replace('http://', 'http://www.', url));

	$files = get_param($_POST, 'files', null, 1);
	$files['url'] = isset($files['url'])? nohtml($files['url']) : null;
	$files['url'] = str_replace(array($url, $url2), null, $files['url']);
	$files['slug'] = isset($files['slug']) && $files['slug'] != ''? slug($files['slug']) : slug(basename($files['url']));
	$files['access'] = isset($files['access'])? intval($files['access']) : 1;

	if (empty($files['url']))
	{
		$json['message'][] = 'آدرس فایل را ننوشته اید!';
	}
	else
	{
		if (!file_exists(root_dir.$files['url']))
		{
			$json['message'][] = 'فایل مشخص شده در هاست سایت یافت شد!';
		}
		elseif (!is_readable(root_dir.$files['url']))
		{
			$json['message'][] = 'فایل مشخص شده قابل خواندن نیست!';
		}
	}

	if ($files['slug'] == '')
	{
		$json['message'][] = 'نام مستعار فایل را ننوشته اید!';
	}
	else
	{
		if (apadana_strlen($files['slug']) > 200)
		{
			$json['message'][] = 'نام مستعار بیش از انداره طولانی است!';
		}
		elseif ($files['slug'] != $row['file_slug'] && $d->numRows("SELECT `file_id` FROM `#__files` WHERE `file_slug`='".$d->escapeString($files['slug'])."'", true) >= 1)
		{
			$json['message'][] = 'نام مستعار تکراری است یک نام مستعار دیگر انتخاب کنید!';
		}
	}

	if (count($json['message']))
	{
		$json['message'] = implode('<br/>', $json['message']);
	}
	else
	{
		$d->update('files', array(
			'file_url' => $files['url'],
			'file_slug' => $files['slug'],
			'file_access' => $files['access'],
		), "file_id='".$_GET['id']."'", 1);

		if ($d->affectedRows())
		{
			remove_cache('module-files', true);
			$json['url'] = url.'?a=files&b='.$_GET['id'];

			if ($options['rewrite'] == 1)
			{
				$json['urlSeo'] = url('files/'.$files['slug']);
			}

			$json['type'] = 'success';
			$json['message'] = 'فایل با موفقیت ویرایش شد.';
		}
		else
		{
			$json['message'] = 'در ذخیره اطلاعات خطایی رخ داده مجدد تلاش کنید!';
		}
	}

	$json['slug'] = urldecode($files['slug']);
	exit(json_encode($json));
}

function _delete()
{
	global $d;
	
	$_GET['id'] = get_param($_GET, 'id', 0);
	$d->query("SELECT file_url, file_author FROM #__files WHERE file_id='".$_GET['id']."' LIMIT 1");

	if ($d->numRows() <= 0)
	{
		echo message('این فایل یافت نشد!', 'error');
		exit;
	}
	
	$row = $d->fetch();

	if ($row['file_author'] == member_id || group_super_admin == 1)
	{
		$d->delete('files', "file_id='".$_GET['id']."'", 1);
		remove_cache('module-files', true);
		echo message('فایل <b>'.basename($row['file_url']).'</b> با موفقیت حذف شد.', 'success');
	}
	else
	{
		echo message('شما دسترسی لازم برای حذف این فایل را ندارید!', 'error');
	}

	_index();
}

?>