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

member::check_admin_page_access('modules') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $tpl, $options, $page, $d, $modules;
	set_title('ماژول ها');

	$itpl = new template('engine/admin/template/modules.tpl');
	
	$handle = opendir(root_dir.'modules');
	while($dir = readdir($handle))
	{
		if (!is_dir(root_dir.'modules/'.$dir) || $dir=='.' || $dir=='..') continue;
		
		$info = array(
			'name' => $dir,
			'version' => '1.0',
			'creationDate' => date('Y-m-d H:i:s', file_exists(root_dir.'modules/'.$dir.'/config.php')? filemtime(root_dir.'modules/'.$dir.'/config.php') : time()),
			'description' => null,
			'author' => 'unknown',
			'authorEmail' => 'unknown',
			'authorUrl' => 'unknown',
			'license' => 'GNU/GPL',
		);
		
		if (file_exists(root_dir.'modules/'.$dir.'/admin.php'))
		{
			require_once(root_dir.'modules/'.$dir.'/admin.php');
			if (function_exists('module_'.str_replace('-', '_', $dir).'_info'))
			{
				$info = 'module_'.str_replace('-', '_', $dir).'_info';
				$info = $info();
			}
		}

		$itpl->add_for('modules', array(
			'{odd-even}' => odd_even(),
			'{name}' => $dir,

			'{info-version}' => $info['version'],
			'{info-creationDate}' => jdate('l j F Y ساعت h:i A', strtotime($info['creationDate'])),
			'{info-description}' => empty($info['description'])? 'بدون توضیح' : $info['description'],
			'{info-author}' => $info['author']=='unknown'? 'ناشناخته' : htmlencode($info['author']),
			'{info-authorEmail}' => $info['authorEmail']=='unknown' || !validate_email($info['authorEmail'])? 'unknown' : nohtml($info['authorEmail']),
			'{info-authorUrl}' => $info['authorUrl']=='unknown' || !validate_url($info['authorUrl'])? 'unknown' : nohtml($info['authorUrl']),
			'{info-license}' => empty($info['license'])? 'GNU/GPL' : nohtml($info['license']),
			
			'replace' => array(
				'#\\[status\\](.*?)\\[/status\\]#s' => file_exists(root_dir.'modules/'.$dir.'/config.php')? '\\1' : '',
				'#\\[not-status\\](.*?)\\[/not-status\\]#s' => !file_exists(root_dir.'modules/'.$dir.'/config.php')? '\\1' : '',
				'#\\[active\\](.*?)\\[/active\\]#s' => is_module($dir)? '\\1' : '',
				'#\\[not-active\\](.*?)\\[/not-active\\]#s' => !is_module($dir)? '\\1' : '',
				'#\\[install\\](.*?)\\[/install\\]#s' => is_module($dir, false)? '\\1' : '',
				'#\\[not-install\\](.*?)\\[/not-install\\]#s' => !is_module($dir, false)? '\\1' : '',
				'#\\[uninstall\\](.*?)\\[/uninstall\\]#s' => is_module($dir, false)? '\\1' : '',
				'#\\[not-uninstall\\](.*?)\\[/not-uninstall\\]#s' => !is_module($dir, false)? '\\1' : '',
				'#\\[upgrade\\](.*?)\\[/upgrade\\]#s' => is_module($dir, false) && version_compare($info['version'], $modules[$dir]['module_version'], '>')? '\\1' : '',
				'#\\[not-upgrade\\](.*?)\\[/not-upgrade\\]#s' => !is_module($dir, false) || !version_compare($info['version'], $modules[$dir]['module_version'], '>')? '\\1' : '',
				'#\\[default\\](.*?)\\[/default\\]#s' => $options['default-module']==$dir? '\\1' : '',
				'#\\[not-default\\](.*?)\\[/not-default\\]#s' => $options['default-module']!=$dir? '\\1' : '',
			)
		));
	}
	closedir($handle);
	unset($handle, $dir);

	$itpl->assign(array(
		'{default-module}' => $options['default-module'],
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
	global $tpl, $options, $page, $d, $modules;
	$result = 'inactive';
	$name = get_param($_GET, 'name');
	$name = alphabet($name);
	if (is_module($name))
	{
		$d->update('options', array('option_value'=>$name), "option_name='default-module'");
		if ($d->affectedRows())
		{
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

function _require()
{
	return array(
		'account', 'counter', 'error', 'feed', 'posts', 'redirect', 'sitemap', 'search'
	);
}

function _active()
{
	global $tpl, $options, $page, $d, $modules;
	$name = get_param($_GET, 'name');
	$name = alphabet($name);

	if (is_module($name, false))
	{
		if ($options['default-module'] == $name)
		{
			echo message('ماژول صفحه ی نخست را نمی توانید غیرفعال کنید!', 'error');
		}
		elseif (in_array($name, _require()))
		{
			echo message('ماژول '.$name.' جزو ماژول های اجباری سیستم است و نمی توانید آن را غیرفعال کنید!', 'error');
		}
		else
		{
			if (file_exists(root_dir.'modules/'.$name.'/admin.php'))
			{
				require_once(root_dir.'modules/'.$name.'/admin.php');
			}
			
			if (is_module($name)) // module active
			{
				if (function_exists('module_'.str_replace('-', '_', $name).'_inactive'))
				{
					$inactive = 'module_'.str_replace('-', '_', $name).'_inactive';
					$inactive();
				}
				else
				{
					$d->update('modules', array('module_status'=>0), "module_name='".$name."'", 1);
					echo message('ماژول '.$name.' غیرفعال شد.', 'success');
				}
			}
			else // module inactive
			{
				if (function_exists('module_'.str_replace('-', '_', $name).'_active'))
				{
					$active = 'module_'.str_replace('-', '_', $name).'_active';
					$active();
				}
				else
				{
					$d->update('modules', array('module_status'=>1), "module_name='".$name."'", 1);
					echo message('ماژول '.$name.' فعال شد.', 'success');
				}
			}

			remove_cache('admin');
			remove_cache('modules');
			$modules[$name]['module_status'] = is_module($name)? 0 : 1;
		}
	}
	elseif (!is_module($name, false))
	{
		echo message('ماژول '.$name.' نصب نشده است!', 'error');
	}
	_index();
}

function _install()
{
	global $tpl, $options, $page, $d, $modules;
	$name = get_param($_GET, 'name');
	$name = alphabet($name);
	if (!is_module($name) && file_exists(root_dir.'modules/'.$name.'/config.php'))
	{
		if (file_exists(root_dir.'modules/'.$name.'/admin.php'))
		{
			require_once(root_dir.'modules/'.$name.'/admin.php');
		}

		if (function_exists('module_'.str_replace('-', '_', $name).'_install'))
		{
			$install = 'module_'.str_replace('-', '_', $name).'_install';
			$install();
		}
		else
		{
			$d->insert('modules', array(
				'module_name' => $name,
				'module_version' => '1.0',
				'module_status' => '0',
			));
			echo message('ماژول '.$name.' با موفقیت نصب شد.', 'success');
		}

		remove_cache('admin');
		remove_cache('modules');
		$modules = $d->get_row("SELECT * FROM `#__modules`", 'assoc', 'module_name');
	}
	elseif (is_module($name))
	{
		echo message('ماژول '.$name.' نصب شده است!', 'error');
	}
	elseif (!file_exists(root_dir.'modules/'.$name.'/config.php'))
	{
		echo message('ماژول '.$name.' ناقص است!', 'error');
	}
	_index();
}

function _uninstall()
{
	global $tpl, $options, $page, $d, $modules;
	$name = get_param($_GET, 'name');
	$name = alphabet($name);
	
	if ($options['default-module'] == $name)
	{
		echo message('ماژول صفحه ی نخست را نمی توانید حذف کنید!', 'error');
	}
	elseif (in_array($name, _require()))
	{
		echo message('ماژول '.$name.' جزو ماژول های اجباری سیستم است و نمی توانید آن را حذف کنید!', 'error');
	}	
	elseif (is_module($name, false))
	{
		if (file_exists(root_dir.'modules/'.$name.'/admin.php'))
		{
			require_once(root_dir.'modules/'.$name.'/admin.php');
		}
		
		if (function_exists('module_'.str_replace('-', '_', $name).'_uninstall'))
		{
			$uninstall = 'module_'.str_replace('-', '_', $name).'_uninstall';
			$uninstall();
		}
		else
		{
			$d->delete('modules', "module_name='".$name."'", 1);
			echo message('ماژول '.$name.' با موفقیت حذف شد.', 'success');
		}

		remove_cache('admin');
		remove_cache('modules');
		$modules = $d->get_row("SELECT * FROM `#__modules`", 'assoc', 'module_name');
	}
	elseif (!is_module($name, false))
	{
		echo message('ماژول '.$name.' نصب نشده است!', 'error');
	}
	elseif (!file_exists(root_dir.'modules/'.$name.'/config.php'))
	{
		echo message('ماژول '.$name.' ناقص است!', 'error');
	}
	_index();
}

function _upgrade()
{
	global $tpl, $options, $page, $d, $modules;
	$name = get_param($_GET, 'name');
	$name = alphabet($name);
	
	if (is_module($name, false))
	{
		$info = array(
			'name' => $name,
			'version' => '1.0',
			'creationDate' => date('Y-m-d H:i:s'),
			'description' => null,
			'author' => 'unknown',
			'authorEmail' => 'unknown',
			'authorUrl' => 'unknown',
			'license' => 'GNU/GPL',
		);

		if (file_exists(root_dir.'modules/'.$name.'/admin.php'))
		{
			require_once(root_dir.'modules/'.$name.'/admin.php');
		}
		
		if (function_exists('module_'.str_replace('-', '_', $name).'_info'))
		{
			$info = 'module_'.str_replace('-', '_', $name).'_info';
			$info = $info();
		}
		
		if (function_exists('module_'.str_replace('-', '_', $name).'_upgrade'))
		{
			if (version_compare($info['version'], $modules[$name]['module_version'], '>'))
			{
				$upgrade = 'module_'.str_replace('-', '_', $name).'_upgrade';
				$upgrade();
			}
			else
			{
				$upgrade = 'NO';
			}
		}
		else
		{
			if (version_compare($info['version'], $modules[$name]['module_version'], '>'))
			{
				$d->update('modules', array(
					'module_version' => nohtml($info['version']),
				), "module_name='".$name."'", 1);
				echo message('ماژول '.$name.' با موفقیت بروزرسانی شد.', 'success');
				$upgrade = 'OK';
			}
			else
			{
				$upgrade = 'NO';
			}
		}

		if ($upgrade != 'NO')
		{
			remove_cache('admin');
			remove_cache('modules');
			$modules = $d->get_row("SELECT * FROM `#__modules`", 'assoc', 'module_name');
		}
		else
		{
			echo message('ماژول '.$name.' نیاز به بروزرسانی ندارد!', 'error');
		}
	}
	elseif (!is_module($name, false))
	{
		echo message('ماژول '.$name.' نصب نشده است!', 'error');
	}
	elseif (!file_exists(root_dir.'modules/'.$name.'/config.php'))
	{
		echo message('ماژول '.$name.' ناقص است!', 'error');
	}
	_index();
}

function _upload()
{
	global $tpl, $options, $page, $d, $modules;
	if (isset($_FILES['myfile']))
	{
		header('Content-type: text/html; charset='.charset);
		if (get_extension($_FILES['myfile']['name'])=='zip')
		{
			require_once(engine_dir.'upload.class.php');
			$upload = upload::file($_FILES['myfile'], root_dir.'modules');
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
						if ($f['folder']!=1 && file_exists(root_dir.'modules/'.$f['filename']) && !empty($f['filename']) && $f['filename']!='.htaccess' && $f['filename']!='index.html')
						{
							@unlink(root_dir.'modules/'.$f['filename']);
						}
					}
				}
				
				if ($archive->extract(PCLZIP_OPT_PATH, $upload['path']) == 0)
				{
					$result = '<font color=red>در باز کردن فایل زیپ خطایی رخ داده</font>';
				}
				else
				{
					$result = '<font color=green>ماژول با موفقیت آپلود شد.<br>با رفتن به تب فهرست ماژول ها می توانید ماژول <b>'.str_ireplace('.zip', null, $upload['filename']).'</b> را نصب و فعال کنید!</font>';
				}

				@unlink($upload['path'].'/'.$upload['filename']);
			}
			else
			{
				$result = '<font color=red>در آپلود ماژول خطایی رخ داده!</font>';
			}
		}
		else
		{
			$result = '<font color=red>فقط فایل زیپ مجاز است!</font>';
		}
		echo '<script>window.top.window.modules_stopUpload("'.$result.'")</script>';
	}
	die;
}

$_GET['do'] = get_param($_GET, 'do');

switch($_GET['do'])
{
	case 'default':
	_default();
	break;

	case 'active':
	_active();
	break;
	
	case 'install':
	_install();
	break;
	
	case 'uninstall':
	_uninstall();
	break;
	
	case 'upgrade':
	_upgrade();
	break;
	
	case 'upload':
	_upload();
	break;
	
	default:
	_index();
	break;
}

?>