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

member::check_admin_page_access('templates') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $tpl, $options, $d;
	set_title('تم ها');

	$itpl = new template('engine/admin/template/templates.tpl');
	
	$handle = opendir(root_dir.'templates');
	while($dir = readdir($handle))
	{
		if (!is_dir(root_dir.'templates/'.$dir) || $dir == '.' || $dir == '..') continue;
		
		$info = array(
			'name' => $dir,
			'version' => '1.0',
			'creationDate' => date('Y-m-d H:i:s', file_exists(root_dir.'templates/'.$dir.'/body.html')? filemtime(root_dir.'templates/'.$dir.'/body.html') : time()),
			'description' => null,
			'author' => null,
			'authorEmail' => null,
			'authorUrl' => null,
			'screenshot' => null,
			'positions' => null,
			'pages' => null,
			'compaction' => 0,
			'license' => 'GNU/GPL',
		);
		
		if (template_exists($dir))
		{
			$info = template_info($dir);
			$info['screenshot'] = nohtml($info['screenshot']);
		}

		$itpl->add_for('templates', array(
			'{odd-even}' => odd_even(),
			'{name}' => $dir,

			'{info-version}' => $info['version'],
			'{info-creationDate}' => jdate('l j F Y ساعت g:i A', strtotime($info['creationDate'])),
			'{info-description}' => empty($info['description'])? 'بدون توضیح' : $info['description'],
			'{info-screenshot}' => empty($info['screenshot']) || !file_exists(root_dir.'templates/'.$dir.'/'.$info['screenshot'])? url.'engine/images/screenshots.png' : url.'templates/'.$dir.'/'.$info['screenshot'],
			'{info-author}' => empty($info['author'])? 'ناشناخته' : htmlencode($info['author']),
			'{info-authorEmail}' => !validate_email($info['authorEmail'])? 'ناشناخته' : nohtml($info['authorEmail']),
			'{info-authorUrl}' => empty($info['authorUrl']) || !validate_url($info['authorUrl'])? 'unknown' : nohtml($info['authorUrl']),
			'{info-positions}' => empty($info['positions'])? 'ناشناخته' : nohtml($info['positions']),
			'{info-pages}' => empty($info['pages'])? 'ناشناخته' : nohtml($info['pages']),
			'{info-compaction}' => $info['compaction']? 'فعال' : 'غیرفعال',
			'{info-license}' => empty($info['license'])? 'GNU/GPL' : nohtml($info['license']),
			
			'replace' => array(
				'#\\[status\\](.*?)\\[/status\\]#s' => template_exists($dir)? '\\1' : '',
				'#\\[not-status\\](.*?)\\[/not-status\\]#s' => !template_exists($dir)? '\\1' : '',
				'#\\[default\\](.*?)\\[/default\\]#s' => $options['theme']==$dir? '\\1' : '',
				'#\\[not-default\\](.*?)\\[/not-default\\]#s' => $options['theme']!=$dir? '\\1' : '',
			)
		));
	}
	closedir($handle);
	unset($handle, $dir);

	$itpl->assign(array(
		'{theme}' => $options['theme'],
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
	unset($itpl);
}

function _default()
{
	global $tpl, $options, $page, $d;
	$result = 'status';
	$name = get_param($_GET, 'name');
	$name = alphabet($name);
	if (template_exists($name))
	{
		$d->update('options', array('option_value'=>$name), "option_name='theme'");
		if ($d->affectedRows())
		{
			if (file_exists(root_dir.'templates/'.$options['theme'].'/admin.php'))
			{
				require_once(root_dir.'templates/'.$options['theme'].'/admin.php');
				if (function_exists('template_'.str_replace('-', '_', $options['theme']).'_inactive'))
				{
					$inactive = 'template_'.str_replace('-', '_', $options['theme']).'_inactive';
					$inactive();
				}
			}

			if (file_exists(root_dir.'templates/'.$name.'/admin.php'))
			{
				require_once(root_dir.'templates/'.$name.'/admin.php');
				if (function_exists('template_'.str_replace('-', '_', $name).'_active'))
				{
					$active = 'template_'.str_replace('-', '_', $name).'_active';
					$active();
				}
			}

			remove_cache('options');
			$result = 'success';
		}
		else
		{
			$result = 'error';
		}
	}
	exit('{"result":"'.$result.'"}');
}

function _upload()
{
	global $tpl, $options, $page, $d;
	if (isset($_FILES['myfile']))
	{
		header('Content-type: text/html; charset='.charset);
		if (get_extension($_FILES['myfile']['name']) == 'zip')
		{
			require_once(engine_dir.'upload.class.php');
			$upload = upload::file($_FILES['myfile'], root_dir.'templates');
			$result = isset($upload['error'])? 0 : 1;
			
			if ($result)
			{
				require_once(engine_dir.'pclzip.class.php');
				$archive = new PclZip($upload['path'].'/'.$upload['filename']);
				
				$list = $archive->listContent();
				if (is_array($list) && count($list))
				{
					foreach($list as $f)
					{
						if ($f['folder']!=1 && file_exists(root_dir.'templates/'.$f['filename']) && !empty($f['filename']) && $f['filename']!='.htaccess' && $f['filename']!='index.html')
						{
							@unlink(root_dir.'templates/'.$f['filename']);
						}
					}
				}
				
				if ($archive->extract(PCLZIP_OPT_PATH, $upload['path']) == 0)
				{
					$result = '<font color=red>در باز کردن فایل زیپ خطایی رخ داده</font>';
				}
				else
				{
					$result = '<font color=green>تم با موفقیت آپلود شد.<br>با رفتن به تب لیست تم ها می توانید تم <b>'.str_ireplace('.zip', null, $upload['filename']).'</b> را فعال کنید!</font>';
				}

				@unlink($upload['path'].'/'.$upload['filename']);
			}
			else
			{
				$result = '<font color=red>در آپلود تم خطایی رخ داده!</font>';
			}
		}
		else
		{
			$result = '<font color=red>فقط فایل زیپ مجاز است!</font>';
		}
		echo '<script>window.top.window.templates_stopUpload("'.$result.'")</script>';
	}
	exit;
}

function _admin()
{
	global $options;
	if (file_exists(root_dir.'templates/'.$options['theme'].'/admin.php'))
	{
		require_once(root_dir.'templates/'.$options['theme'].'/admin.php');
		$func = 'template_'.str_replace('-', '_', $options['theme']).'_admin';

		if (function_exists($func))
		{
			$func();
		}
		else
		{
			redirect(admin_page.'&section=templates');
		}
	}
	else
	{
		redirect(admin_page.'&section=templates');
	}
}

function _files($dir)
{
	if ($dh = opendir($dir))
	{
		$files = Array();
		$inner_files = Array();
		while($file = readdir($dh))
		{
			if ($file != '.' && $file != '..' && $file[0] != '.')
			{
				if (is_dir($dir . '/' . $file))
				{
					$inner_files = _files($dir . '/' . $file);
	
					if (is_array($inner_files) && count($inner_files))
						$files = array_merge($files, $inner_files);
				}
				else
				{
					array_push($files, $dir . '/' . $file);
				}
			}
		}
		closedir($dh);
		return $files;
	}
}

function _edit()
{
	global $tpl, $options, $page, $d;

	$name = get_param($_GET, 'name');

	if (is_alphabet($name) && is_dir(root_dir.'templates/'.$name))
	{
		if (isset($_POST['new']))
		{
			$new = get_param($_POST, 'new');
			$new = nohtml($new);
			$new = str_replace('..', null, $new);

			if (empty($new))
			{
				exit('فیلد لازم را پر کنید!');
			}

			if (get_extension($new) == '')
			{
				if (is_dir(root_dir.'templates/'.$name.'/'.$new))
				{
					echo 'این پوشه وجود دارد!';
				}
				else
				{
					if (@mkdir(root_dir.'templates/'.$name.'/'.$new))
						echo 'پوشه ساخته شد!';
					else
						echo 'در ساختن پوشه خطایی رخ داده!';
				}
			}
			else
			{
				if (file_exists(root_dir.'templates/'.$name.'/'.$new))
				{
					echo 'این فایل وجود دارد!';
				}
				else
				{
					if (@file_put_contents(root_dir.'templates/'.$name.'/'.$new, ' '))
						echo 'OK';
					else
						echo 'در ساختن فایل خطایی رخ داده!';
				}
			}
			exit;
		}
		
		$file = get_param($_POST, 'file');
		$file = nohtml($file);
		
		if (!empty($file))
		{
			$file = str_replace('..', null, $file);

			if (file_exists(root_dir.'templates/'.$name.'/'.$file))
			{
				if (isset($_POST['contents']))
				{
					$contents = get_param($_POST, 'contents', null, 1);

					if (is_writable(root_dir.'templates/'.$name.'/'.$file))
					{
						echo 'فایل ویرایش شد!';
						file_put_contents(root_dir.'templates/'.$name.'/'.$file, $contents);
					}
					else
					{
						echo 'سیستم دسترسی لازم برای ویرایش این فایل را ندارد!';
					}
				}
				else
				{
					echo @file_get_contents(root_dir.'templates/'.$name.'/'.$file);
				}
				exit;
			}
			else
			{
				exit( md5(member_id.member_name.group_rights) );
			}
		}
		else
		{
			set_title('ویرایش تم');
			$list = _files(root_dir.'templates/'.$name);

			$files = array('html', 'htm', 'php', 'txt', 'tpl', 'css', 'inc', 'ihtml');
			$itpl = new template('engine/admin/template/templates-edit.tpl');
			
			foreach($list as $f)
			{
				if (!in_array(get_extension($f), $files)) continue;

				$f = str_replace(root_dir.'templates/'.$name.'/', null, $f);

				$itpl->add_for('files', array(
					'{file}' => $f,
				));
			}
			
			$itpl->assign(array(
				'{template}' => $name,
				'{error}' => md5(member_id.member_name.group_rights),
			));

			set_content(false, $itpl->get_var());
			unset($itpl);
		}
	}
	else
	{
		redirect(admin_page.'&section=templates');
	}
}

$_GET['do'] = get_param($_GET, 'do');

switch($_GET['do'])
{
	case 'default':
	_default();
	break;

	case 'upload':
	_upload();
	break;

	case 'admin':
	_admin();
	break;
	
	case 'edit':
	_edit();
	break;
	
	default:
	_index();
	break;
}

?>