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

member::check_admin_page_access('backup') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $tpl, $options, $page, $d;
	set_title('پشتیبان گیری');

	$itpl = new template('engine/admin/template/backup.tpl');

	$handle = opendir(engine_dir.'admin/backups/');
	while ($file = readdir($handle))
	{
		if (file_exists(engine_dir.'admin/backups/'.$file) && apadana_substr($file, 0, 15) == 'apadana-backup-' && apadana_substr($file, -4) == '.php')
		{
			$file = str_replace(array('apadana-backup-', '.php'), null, $file);
			if (empty($file) || !is_numeric($file) || $file<1343487151) continue; // 1343487151 = شنبه 7 مرداد 1391 ساعت 07:22 بعد از ظهر
	
			$itpl->add_for('backup', array(
				'{odd-even}' => odd_even(),
				'{file}' => str_replace(array('apadana-backup-', '.php'), null, $file),
				'{time}' => jdate('l j F Y ساعت g:i A', $file),
				'{size}' => file_size(filesize(engine_dir.'admin/backups/apadana-backup-'.$file.'.php')),
			));
		}
	}
	closedir($handle);
	unset($handle, $file);
	
	if (isset($itpl->foreach['backup']))
	{
		$itpl->assign(array(
			'[backup]' => null,
			'[/backup]' => null,
		));
		$itpl->block('#\\[not-backup\\](.*?)\\[/not-backup\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[not-backup]' => null,
			'[/not-backup]' => null,
		));
		$itpl->block('#\\[backup\\](.*?)\\[/backup\\]#s', '');
	}

	if (is_ajax())
	{
		define('no_template', true);
		$itpl->display();
	}
	else
	{
		set_content(FALSE, $itpl->get_var());
	}
	unset($itpl);
}

function _new()
{
	global $d, $options;
	@set_time_limit(1200);
	$crlf = "\r\n";
	ob_start();

	echo '<?php'.$crlf;
	echo '/**'.$crlf;
	echo ' * @In the name of God!'.$crlf;
	echo ' * @author: Iman Moodi (Iman92)'.$crlf;
	echo ' * @email: info@apadanacms.ir'.$crlf;
	echo ' * @link: http://www.apadanacms.ir'.$crlf;
	echo ' * @license: http://www.gnu.org/licenses/'.$crlf;
	echo ' * @copyright: Copyright (C) 2012-'.date('Y').' apadanacms.ir. All rights reserved.'.$crlf;
	echo ' * @Apadana CMS is a Free Software'.$crlf;
	echo ' */'.$crlf;
	echo $crlf;
	echo 'defined(\'security\') or exit(\'Direct Access to this location is not allowed.\');'.$crlf;
	echo $crlf;
	echo 'if (group_admin != 1 || !member::check_admin_page_access(\'backup\') || !defined(\'backup_restore\') || backup_restore === false || !defined(\'admin_page\') || admin_page === false)'.$crlf;
	echo '{'.$crlf;
	echo '	exit(\'Nice TRY!\');'.$crlf;
	echo '}';
	echo $crlf;
	
	echo $crlf;
	echo '# ========================================================'.$crlf;
	echo '#'.$crlf;
	echo '# Apadana Cms v'.$options['version'].$crlf;
	echo '# Database saved: '.database_name.$crlf;
	echo '# Database prefix: '.database_prefix.$crlf;
	echo '# On '.jdate('Y-m-d').' at '.jdate('H:i').$crlf;
	echo '# On '.date('Y-m-d').' at '.date('H:i').$crlf;
	echo '#'.$crlf;
	echo '# ========================================================'.$crlf;
	echo $crlf;

	$tables = $d->query("SHOW TABLES FROM `" .database_name . "`");

	if ($d->num_rows($tables) <= 0)
	{
		echo '# No tables found in database.'.$crlf;
	}
	else
	{
		//$d->query('SET SQL_QUOTE_SHOW_CREATE=1');
		while ($row_tables = $d->fetch($tables, 'row'))
		{
			$table = $row_tables[0];
			$d->query("OPTIMIZE TABLE `$table`");
			$sql_table = $d->query("SHOW CREATE TABLE `$table`");
			while ($row_table = $d->fetch($sql_table, 'array'))
			{
				echo $crlf;
				echo '# --------------------------------------------------------'.$crlf;
				echo '#'.$crlf;
				echo '# Table structure for table \''.$table.'\''.$crlf;
				echo '#'.$crlf;
				echo $crlf;
				echo '$d->query("DROP TABLE IF EXISTS `'.$table.'`;");'.$crlf;
				echo '$d->query("'.$row_table['Create Table'].';");';
				echo $crlf;
				echo $crlf;
			}

			$sql_table = $d->query("SELECT * FROM `$table`");
			if ($d->num_rows($sql_table) > 0)
			{
				echo $crlf;
				echo '#'.$crlf;
				echo '# Dumping data for table \''.$table.'\''.$crlf;
				echo '#'.$crlf;
				echo $crlf;
				while ($row_table = $d->fetch($sql_table, 'row'))
				{
					echo '$d->query("INSERT INTO `'.$table.'` VALUES(\''.str_replace('$', '".\'$\'."', $d->escape_string($row_table[0])).'\'';
					for ($i=1; $i<sizeof($row_table); $i++)
					{
						echo ", '";
						echo str_replace('$', '".\'$\'."', $d->escape_string($row_table[$i]));
						echo "'";
					}
					echo ');");';
					echo $crlf;
				}
				echo $crlf;
			}
		}
	}

	echo '?>';
	$sql = ob_get_contents();
	ob_end_clean();
	if (@file_put_contents(engine_dir.'admin/backups/apadana-backup-'.time().'.php', $sql))
	{
		echo message('فایل پشتیبان جدید با موفقیت ایجاد شد!', 'success');
	}
	else
	{
		echo message('در ایجاد فایل پشتیبان خطایی رخ داده است!', 'error');
	};
	_index();
}

function _restore()
{
	$_GET['file'] = get_param($_GET, 'file', 0);	
	if (file_exists(engine_dir.'admin/backups/apadana-backup-'.$_GET['file'].'.php'))
	{
		if (member::check_admin_page_access('backup'))
		{
			define('backup_restore', true);
			global $d;
		}
		else
		{
			define('backup_restore', false);
		}
	
		require_once(engine_dir.'admin/backups/apadana-backup-'.$_GET['file'].'.php');
		echo message('اطلاعات فایل پشتیبان با موفقیت جایگزین اطلاعات فعلی شد.', 'success');
		
		// remove caches
		$handle = opendir(engine_dir.'cache');
		while ($file = readdir($handle))
		{
			if (get_extension($file) == 'cache')
			{
				$cachedFile = engine_dir.'cache/'.$file;
				@unlink($cachedFile);
			}
		}
		closedir($handle);
		unset($handle, $file);
	}
	_index();
}

function _download()
{
	$_GET['file'] = get_param($_GET, 'file', 0);
	$_GET['type'] = get_param($_GET, 'type', 'gz');
	
	if (file_exists(engine_dir.'admin/backups/apadana-backup-'.$_GET['file'].'.php') && is_readable(engine_dir.'admin/backups/apadana-backup-'.$_GET['file'].'.php'))
	{
		require_once(engine_dir.'httpdownload.class.php');
		$contents = file_get_contents(engine_dir.'admin/backups/apadana-backup-'.$_GET['file'].'.php');
		$file = 'apadana-backup-'.$_GET['file'].'.php';
		$object = new httpdownload;
		$object->use_resume = false;
		if ($_GET['type'] == 'gz')
		{
 			$object->use_resume = false;
			$object->set_mime('application/x-gzip gz tgz');
			$object->set_bydata(gzencode($contents, 9));
			$object->set_filename($file.'.gz');
		}
		else
		{
 			$object->use_resume = false;
			$object->set_mime('text/php');
			$object->set_bydata($contents);
			$object->set_filename($file);
		}
		$object->download();
		exit;
	}
}

function _delete()
{	
	$_GET['file'] = get_param($_GET, 'file', 0);	
	if (file_exists(engine_dir.'admin/backups/apadana-backup-'.$_GET['file'].'.php'))
	{
		if (is_writable(engine_dir.'admin/backups/apadana-backup-'.$_GET['file'].'.php'))
		{
			if (unlink(engine_dir.'admin/backups/apadana-backup-'.$_GET['file'].'.php'))
			{
				echo message('فایل پشتیبان حذف شد.', 'success');
			}
			else
			{
				echo message('در حذف فایل خطایی رخ داده، مجدد تلاش کنید!', 'error');
			}
		}
		else
		{
			echo message('سیستم نمی تواند این فایل را حذف کند!', 'error');
		}
	}
	_index();
}

/* begin security code */
if (!is_dir(engine_dir.'admin/backups'))
{
	@mkdir(engine_dir.'admin/backups', 0777);
	apadana_chmod(engine_dir.'admin/backups', 0777);
}

$file = file_exists(engine_dir.'admin/backups/.htaccess')? @file_get_contents(engine_dir.'admin/backups/.htaccess') : null;
if (empty($file) || strpos($file, '#') !== FALSE || strpos($file, 'Order Allow,Deny') === FALSE || strpos($file, 'Deny from All') === FALSE)
{
	@file_put_contents(engine_dir.'admin/backups/.htaccess', "Order Allow,Deny\r\nDeny from All");
}

$file = file_exists(engine_dir.'admin/backups/index.html')? @file_get_contents(engine_dir.'admin/backups/index.html') : null;
if (empty($file) || strpos($file, 'apadanacms.ir') === FALSE)
{
	@file_put_contents(engine_dir.'admin/backups/index.html', 'www.apadanacms.ir');
}
/* end security code */

$_GET['do'] = get_param($_GET, 'do');
switch($_GET['do'])
{
	case 'new':
	_new();
	break;

	case 'restore':
	_restore();
	break;

	case 'download':
	_download();
	break;

	case 'delete':
	_delete();
	break;

	default:
	_index();
	break;
}

?>