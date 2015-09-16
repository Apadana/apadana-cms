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
		
		$info = module_info($dir);

		if($info){
			$tags = array(
				'{odd-even}' => odd_even(),
				'{name}' => $dir,
				'{show-name}' => (apadana_strtolower($info['name']) != apadana_strtolower($dir)? $info['name'].' <span style="visibility:hidden;font-size:2px">z</span><span style="color:#487d8c;font-size:10px">('.$dir.')</span><span style="visibility:hidden;font-size:2px">z</span>' : $dir),
				'replace' => array(
					'#\\[status\\](.*?)\\[/status\\]#s' =>  '\\1' ,
					'#\\[not-status\\](.*?)\\[/not-status\\]#s' => '',
					'#\\[compatibility\\](.*?)\\[/compatibility\\]#s' => apadana_compare($info['compatibility'])? '\\1' : '',
					'#\\[not-compatibility\\](.*?)\\[/not-compatibility\\]#s' => !apadana_compare($info['compatibility'])? '\\1' : '',
					'#\\[active\\](.*?)\\[/active\\]#s' => is_module($dir)? '\\1' : '',
					'#\\[not-active\\](.*?)\\[/not-active\\]#s' => !is_module($dir)? '\\1' : '',
					'#\\[install\\](.*?)\\[/install\\]#s' => is_module($dir, false)? '\\1' : '',
					'#\\[not-install\\](.*?)\\[/not-install\\]#s' => !is_module($dir, false)? '\\1' : '',
					'#\\[upgrade\\](.*?)\\[/upgrade\\]#s' => is_module($dir, false) && version_compare($info['version'], $modules[$dir]['module_version'], '>')? '\\1' : '',
					'#\\[not-upgrade\\](.*?)\\[/not-upgrade\\]#s' => !is_module($dir, false) || !version_compare($info['version'], $modules[$dir]['module_version'], '>')? '\\1' : '',
					'#\\[default\\](.*?)\\[/default\\]#s' => $options['default-module']==$dir? '\\1' : '',
					'#\\[not-default\\](.*?)\\[/not-default\\]#s' => $options['default-module']!=$dir? '\\1' : '',
				)
			);
		}
		else
		{
			$tags = array(
				'{odd-even}' => odd_even(),
				'{name}' => $dir,
				'{show-name}' => (apadana_strtolower($info['name']) != apadana_strtolower($dir)? $info['name'].' <span style="visibility:hidden;font-size:2px">z</span><span style="color:#487d8c;font-size:10px">('.$dir.')</span><span style="visibility:hidden;font-size:2px">z</span>' : $dir),
				'replace' => array(
					'#\\[status\\](.*?)\\[/status\\]#s' => '',
					'#\\[not-status\\](.*?)\\[/not-status\\]#s' => '\\1' ,
					'#\\[compatibility\\](.*?)\\[/compatibility\\]#s' => '',
					'#\\[not-compatibility\\](.*?)\\[/not-compatibility\\]#s' => '\\1' ,
					'#\\[active\\](.*?)\\[/active\\]#s' => is_module($dir)? '\\1' : '',
					'#\\[not-active\\](.*?)\\[/not-active\\]#s' => !is_module($dir)? '\\1' : '',
					'#\\[install\\](.*?)\\[/install\\]#s' => is_module($dir, false)? '\\1' : '',
					'#\\[not-install\\](.*?)\\[/not-install\\]#s' => !is_module($dir, false)? '\\1' : '',
					'#\\[upgrade\\](.*?)\\[/upgrade\\]#s' => is_module($dir, false) && version_compare($info['version'], $modules[$dir]['module_version'], '>')? '\\1' : '',
					'#\\[not-upgrade\\](.*?)\\[/not-upgrade\\]#s' => !is_module($dir, false) || !version_compare($info['version'], $modules[$dir]['module_version'], '>')? '\\1' : '',
					'#\\[default\\](.*?)\\[/default\\]#s' => $options['default-module']==$dir? '\\1' : '',
					'#\\[not-default\\](.*?)\\[/not-default\\]#s' => $options['default-module']!=$dir? '\\1' : '',
				)
			);
		}

		$itpl->add_for('modules', $tags);
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
		if ($d->affected_rows())
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
		'account',  'error', 'feed' , 'redirect', 'sitemap', 'search'
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
	global $tpl, $options, $page, $d;
	$name = get_param($_GET, 'name');
	$name = alphabet($name);
	if( module_exists($name) ){

		if (!is_module($name,false) )
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
					'module_status' => '0'
				));
				echo message('ماژول '.$name.' با موفقیت نصب شد.', 'success');
			}

			remove_cache('admin');
			remove_cache('modules');
			$GLOBALS['modules'] = $d->get_row("SELECT * FROM `#__modules`", 'assoc', 'module_name');
		}
		elseif (is_module($name,false))
		{
			if ($options['default-module'] == $name)
			{
				echo message('ماژول صفحه ی نخست را نمی توانید حذف کنید!', 'error');
			}
			elseif (in_array($name, _require()))
			{
				echo message('ماژول '.$name.' جزو ماژول های اجباری سیستم است و نمی توانید آن را حذف کنید!', 'error');
			}
			else{

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
				$GLOBALS['modules'] = $d->get_row("SELECT * FROM `#__modules`", 'assoc', 'module_name');
			}
		}
	}
	else
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


function _get_info()
{
	$name = get_param($_GET, 'name');

	$json = array(
		'message' => array(),
		'result' => 'error'
	);

	if (!is_alphabet($name))
	{
		$json['message'][] = 'نام ماژول مورد نظر معتبر نیست!';
	}
	else
	{
		if (!module_exists($name))
		{
			$json['message'][] = 'ماژول مورد نظر در سیستم وجود ندارد و یا ناقص است!';
		}
	}

	if (count($json['message']))
	{
		$json['message'] = implode('<br>', $json['message']);
	}
	else
	{
		$json['result'] = 'success';

		$info = module_info($name);

		$json['info']  = '<div style="margin-bottom:4px;padding-bottom:4px"><b>نام ماژول</b>:&nbsp;&nbsp;'.(apadana_strtolower($info['name']) != apadana_strtolower($name)? $info['name'].' <span style="visibility:hidden;font-size:2px">z</span><span style="color:#487d8c;font-size:10px">('.$name.')</span><span style="visibility:hidden;font-size:2px">z</span>' : $name).'</div>';
		$json['info'] .= '<div style="margin-bottom:4px;padding-bottom:4px"><b>نگارش ماژول</b>:&nbsp;&nbsp;'.translate_number($info['version']).' <span style="visibility:hidden;font-size:2px">z</span><span style="color:#487d8c;font-size:10px">('.(apadana_compare($info['compatibility'])? 'بر اساس گفته سازنده با نسخه آپادانا شما سازگار است' : '<span style="color:red;">با نگارش آپادانا ی شما سازگار نیست</span>').')</span><span style="visibility:hidden;font-size:2px">z</span></div>';
		$json['info'] .= '<div style="margin-bottom:4px;padding-bottom:4px"><b>تاریخ ساخت</b>:&nbsp;&nbsp;'.apadana_date('l j F Y ساعت g:i A', strtotime($info['creation-date'])).'</div>';
		$json['info'] .= !empty($info['description']) && $info['description'] != 'unknown'? '<div style="margin-bottom:4px;padding-bottom:4px"><b>توضیحات سازنده</b>:&nbsp;&nbsp;<br>'.$info['description'].'</div>' : null;
		$json['info'] .= !empty($info['author']) && $info['author'] != 'unknown'? '<div style="margin-bottom:4px;padding-bottom:4px"><b>نام سازنده</b>:&nbsp;&nbsp;'.$info['author'].'</div>' : null;
		$json['info'] .= !empty($info['author-email']) && $info['author-email'] != 'unknown'? '<div style="margin-bottom:4px;padding-bottom:4px"><b>رایانامه سازنده</b>:&nbsp;&nbsp;'.$info['author-email'].'</div>' : null;
		$json['info'] .= !empty($info['author-url']) && $info['author-url'] != 'unknown'? '<div style="margin-bottom:4px;padding-bottom:4px"><b>وب‌سایت سازنده</b>:&nbsp;&nbsp;<a href="'.redirect_link($info['author-url']).'" target="_blank">'.basename($info['author-url']).'</a></div>' : null;
		$json['info'] .= !empty($info['license']) && $info['license'] != 'unknown'? '<div><b>مجوز ماژول</b>:&nbsp;&nbsp;'.$info['license'].'</div>' : null;
	}

	exit(json_encode($json));
}

$_GET['do'] = get_param($_GET, 'do');

switch($_GET['do'])
{
	case 'get_info':
	_get_info();
	break;

	case 'default':
	_default();
	break;

	case 'active':
	_active();
	break;
	
	case 'install':
	_install();
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