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

@session_start();
@ob_start();
@ob_implicit_flush(0);

define('security', true);
define('root_dir', dirname(dirname(__FILE__)).'/');
define('engine_dir', root_dir.'/engine/');
require_once('common.php');
require_once('template/template.php');

date_default_timezone_set('Asia/Tehran');

if (extension_loaded('mbstring') && function_exists('mb_internal_encoding'))
{
    mb_internal_encoding('UTF-8');
}

# Determine Magic Quotes Status (< PHP 5.4)
if (version_compare(PHP_VERSION, '5.4', '<'))
{
	if (@get_magic_quotes_gpc())
	{
		strip_slashes_array($_POST);
		strip_slashes_array($_GET);
		strip_slashes_array($_COOKIE);
	}
	@set_magic_quotes_runtime(0);
	@ini_set('magic_quotes_gpc', 0);
	@ini_set('magic_quotes_runtime', 0);
}

$ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'? true : false;
$_GET['action'] = !isset($_GET['action']) || empty($_GET['action'])? null : $_GET['action'];

if (!file_exists('apadana.lock')):
switch ($_GET['action'])
{
	case 'check':
	if (!$ajax) print_header();
	echo '<script>set_percent(150)</script>'."\n";
	print_info('برسی قبل از نصب', 'هر کدام از آیتم های این بخش که قرمز رنگ باشد باید اقدامات لازم برای رفع مشکل آن انجام شود، در غیر اینصورت ممکن است نصب آپادانا با مشکل مواجه شود.');
	echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
	echo '<tr>'."\n";
	echo '	<td style="width:290px">نسخه PHP بالاتر از 5.0</td>'."\n";
	echo '	<td>'.(phpversion() >= '5.0'? '<b><font color="green">بله</font></b>' : '<b><font color="red">خیر</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>پشتیبانی از zlib</td>'."\n";
	echo '	<td>'.(extension_loaded('zlib')? '<b><font color="green">بله</font></b>' : '<b><font color="red">خیر</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>پشتیبانی از GD</td>'."\n";
	echo '	<td>'.(extension_loaded('gd')? '<b><font color="green">بله</font></b>' : '<b><font color="red">خیر</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>پشتیبانی از MySQL</td>'."\n";
	echo '	<td>'.(function_exists('mysql_connect')? '<b><font color="green">بله</font></b>' : '<b><font color="red">خیر</font></b>').'</td>'."\n";
	echo '	</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>فایل پیکربندی <font color="#CCCCCC" size="1" dir="ltr">(engine/config.inc.php)</font></td>'."\n";
	echo '	<td>'.(file_exists('../engine/config.inc.php')?(is_writable('../engine/config.inc.php')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>'):(is_writable('../engine')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>')).'</td>'."\n";
	echo '</tr>'."\n";
	echo '</table>'."\n";
	echo '<br>'."\n";
	print_info('تنظیمات توصیه شده برای آپادانا', 'توصیه می شود تنظیمات مقابل برای سازگاری کامل با آپادانا بر روی PHP انجام شود.');
	echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
	echo '<tr>'."\n";
	echo '	<th>نوع</th>'."\n";
	echo '	<th style="width:230px">توصیه شده </th>'."\n";
	echo '	<th style="width:100px">فعلی</th>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>Safe Mode</td>'."\n";
	echo '	<td>غیرفعال</td>'."\n";
	echo '	<td>'.(ini_get('safe_mode') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>Display Errors</td>'."\n";
	echo '	<td>غیرفعال</td>'."\n";
	echo '	<td>'.(ini_get('display_errors') == '1'? '<b><font color="red">فعال</font></b>' : '<b><font color="green">غیرفعال</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>File Uploads</td>'."\n";
	echo '	<td>فعال</td>'."\n";
	echo '	<td>'.(ini_get('file_uploads') == '1'? '<b><font color="green">فعال</font></b>' : '<b><font color="red">غیرفعال</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>Magic Quotes GPC</td>'."\n";
	echo '	<td>غیرفعال</td>'."\n";
	echo '	<td>'.(ini_get('magic_quotes_gpc') == '1'? '<b><font color="red">فعال</font></b>' : '<b><font color="green">غیرفعال</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>Magic Quotes Runtime</td>'."\n";
	echo '	<td>غیرفعال</td>'."\n";
	echo '	<td>'.(ini_get('magic_quotes_runtime') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>Register Globals</td>'."\n";
	echo '	<td>غیرفعال</td>'."\n";
	echo '	<td>'.(ini_get('register_globals') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>Output Buffering</td>'."\n";
	echo '	<td>غیرفعال</td>'."\n";
	echo '	<td>'.(ini_get('output_buffering') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>Session auto start</td>'."\n";
	echo '	<td>غیرفعال</td>'."\n";
	echo '	<td>'.(ini_get('session.auto_start') != '1'? '<b><font color="green">غیرفعال</font></b>' : '<b><font color="red">فعال</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '</table>'."\n";
	echo '<br>'."\n";
	print_info('دسترسی فایل ها', 'آپادانا نیاز دارد تا بتواند این فایل ها و پوشه ها را ویرایش کند.');
	echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
	echo '<tr>'."\n";
	echo '	<td style="width:290px">فایل پیکربندی <font color="#CCCCCC" size="1" dir="ltr">(/engine/config.inc.php)</font></td>'."\n";
	echo '	<td>'.(file_exists('../engine/config.inc.php')?(is_writable('../engine/config.inc.php')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>'):(is_writable('../engine')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>')).'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>پوشه کش <font color="#CCCCCC" size="1" dir="ltr">(/engine/cache/)</font></td>'."\n";
	echo '	<td>'.(is_writable('../engine/cache')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>پوشه فایل های پشتیبان <font color="#CCCCCC" size="1" dir="ltr">(/engine/admin/backups/)</font></td>'."\n";
	echo '	<td>'.(is_writable('../engine/admin/backups')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>پوشه آپلود <font color="#CCCCCC" size="1" dir="ltr">(/uploads/)</font></td>'."\n";
	echo '	<td>'.(is_writable('../uploads')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>فایل <font color="#CCCCCC" size="1" dir="ltr">(/.htaccess)</font></td>'."\n";
	echo '	<td>'.(is_writable('../.htaccess')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>فایل روبات ها <font color="#CCCCCC" size="1" dir="ltr">(/robots.txt)</font></td>'."\n";
	echo '	<td>'.(is_writable('../robots.txt')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '</table>'."\n";
	echo '<button onClick="install_load(\'config\')">ادامه نصب آپادانا</button><button onClick="install_load(\'check\')" style="margin-left:10px">برسی مجدد</button>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;
	
	case 'config':
	if (!$ajax) print_header();
	echo '<script>set_percent(250)</script>'."\n";
	echo '<form id="form-config" onSubmit="return false">'."\n";
	print_info('پیکربندی دیتابیس', 'لطفا اطلاعات لازم برای اتصال به دیتابیس را معین کنید.');
	echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
	echo '  <tr>'."\n";
	echo '    <td style="width:230px; cursor: help" title="معمولا localhost می باشد">نام هاست <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Host Name)</font></td>'."\n";
	echo '    <td><input name="config[host]" id="input-host" type="text" value="localhost" style="width:350px; float: left; direction: ltr" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>نام کاربری <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(MySQL User Name)</font></td>'."\n";
	echo '    <td><input name="config[user]" id="input-user" type="text" style="width:350px; float: left; direction: ltr" value="" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>پسورد دیتابیس <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(MySQL Password)</font></td>'."\n";
	echo '    <td><input name="config[password]" id="input-password" type="text" style="width:350px; float: left; direction: ltr" value="" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>نام دیتابیس <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(MySQL Database Name)</font></td>'."\n";
	echo '    <td><input name="config[name]" id="input-name" type="text" style="width:350px; float: left; direction: ltr" value="" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>پیشوند تیبل های دیتابیس <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Database Prefix)</font></td>'."\n";
	echo '    <td><input name="config[prefix]" id="input-prefix" type="text" style="width:350px; float: left; direction: ltr" value="'.strtolower(generate_password(5, null)).'_" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '</table>'."\n";
	
	print_info('پیکربندی آدرس سایت', 'لطفا اطلاعات زیر را برسی کنید، و در صورت صحیح نبود آن ها را اصلاح کنید.');
	echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
	echo '  <tr>'."\n";
	echo '    <td style="width:230px">دامنه سایت <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Domain)</font></td>'."\n";
	echo '    <td><input name="config[domain]" id="input-domain" type="text" value="'.str_replace('www.', '', $_SERVER['HTTP_HOST']).'" style="width:350px; float: left; direction: ltr" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>محل نصب سایت <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Path)</font></td>'."\n";
	echo '    <td><input name="config[path]" id="input-path" type="text" style="width:350px; float: left; direction: ltr" value="'.my_path().'" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>آدرس کامل سایت <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Full Url)</font></td>'."\n";
	echo '    <td><input name="config[url]" id="input-url" type="text" style="width:350px; float: left; direction: ltr" value="http://'.trim($_SERVER['HTTP_HOST'].my_path(), '/').'/" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td></td>'."\n";
	echo '    <td><button onClick="install_config()" disabled="disabled" id="button-disabled">ساختن فایل پیکربندی</button><button onClick="install_check(\'config\')" style="margin-left:10px">برسی اطلاعات</button></td>'."\n";
	echo '  </tr>'."\n";
	echo '</table>'."\n";
	
	echo '</form>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;
	
	case 'config-check':
	$_POST['config'] = array_map('trim', $_POST['config']);
	$result = 'error';
	
	$connect = @mysql_connect($_POST['config']['host'], $_POST['config']['user'], $_POST['config']['password'], true);
	if ($connect)
	{
		if (@mysql_select_db($_POST['config']['name'], $connect)) 
		{
			if (!preg_check("/^[0-9a-z_]{2,10}$/i", $_POST['config']['prefix']))
			{
				$result = 'prefix';
			}
			else
			{
				$result = 'success';
			}
		}
		else
		{
			$result = 'select';
		}
	}
	else
	{
		$result = 'connect';
	}
	
	exit('{"result":"'.$result.'"}');
	break;
	
	case 'create-config':
	if (!$ajax) print_header();
	$_POST['config'] = array_map('trim', $_POST['config']);
	
	$connect = @mysql_connect($_POST['config']['host'], $_POST['config']['user'], $_POST['config']['password'], true);
	if ($connect)
	{
		if (@mysql_select_db($_POST['config']['name'], $connect)) 
		{
			if (preg_check("/^[0-9a-z_]{2,10}$/i", $_POST['config']['prefix']))
			{
				$connect = 'success';
			}
		}
	}

	if ($connect == 'success')
	{
		$_POST['config'] = array_map(create_function('$a', 'return str_replace("\'", "\\\'", $a);'), $_POST['config']);
		
		$php  = '<?php'."\r\n";
		$php .= '/**'."\r\n";
		$php .= ' * @In the name of God!'."\r\n";
		$php .= ' * @author: Apadana Development Team'."\r\n";
		$php .= ' * @email: info@apadanacms.ir'."\r\n";
		$php .= ' * @link: http://www.apadanacms.ir'."\r\n";
		$php .= ' * @license: http://www.gnu.org/licenses/'."\r\n";
		$php .= ' * @copyright: Copyright © 2012-'.date('Y').' ApadanaCms.ir. All rights reserved.'."\r\n";
		$php .= ' * @Apadana CMS is a Free Software'."\r\n";
		$php .= ' */'."\r\n\r\n";
		$php .= 'defined(\'security\') or exit(\'Nice TRY!\');'."\r\n\r\n";
		$php .= 'define(\'database_host\', \''.$_POST['config']['host'].'\');'."\r\n";
		$php .= 'define(\'database_user\', \''.$_POST['config']['user'].'\');'."\r\n";
		$php .= 'define(\'database_password\', \''.$_POST['config']['password'].'\');'."\r\n";
		$php .= 'define(\'database_name\', \''.$_POST['config']['name'].'\');'."\r\n";
		$php .= 'define(\'database_prefix\', \''.$_POST['config']['prefix'].'\');'."\r\n";
		$php .= 'define(\'database_charset\', \'utf8\');'."\r\n";
		$php .= 'define(\'database_save_queries\', false);'."\r\n\r\n";
		$php .= 'define(\'error_reporting\', false);'."\r\n";
		$php .= 'define(\'charset\', \'utf-8\');'."\r\n";
		$php .= 'define(\'sitekey\', \''.generate_password(40).'\');'."\r\n";
		$php .= 'define(\'domain\', \''.$_POST['config']['domain'].'\');'."\r\n";
		$php .= 'define(\'path\', \''.$_POST['config']['path'].'\');'."\r\n";
		$php .= 'define(\'url\', \''.rtrim($_POST['config']['url'], '/').'/\');'."\r\n\r\n";
		$php .= '?>';

		if (file_put_contents('../engine/config.inc.php', $php))
		{
			$ht  = 'DirectoryIndex index.php index.html index.htm'."\n\n";
			$ht .= 'ErrorDocument 400 '.$_POST['config']['path'].'index.php?a=error&b=400'."\n";
			$ht .= 'ErrorDocument 403 '.$_POST['config']['path'].'index.php?a=error&b=403'."\n";
			$ht .= 'ErrorDocument 404 '.$_POST['config']['path'].'index.php?a=error&b=404'."\n";
			$ht .= 'ErrorDocument 500 '.$_POST['config']['path'].'index.php?a=error&b=500'."\n";
			$ht .= 'ErrorDocument 503 '.$_POST['config']['path'].'index.php?a=error&b=503'."\n\n";
			$ht .= '<IfModule mod_rewrite.c>'."\n";
			$ht .= '	RewriteEngine On'."\n";
			$ht .= '	RewriteBase '.$_POST['config']['path'].''."\n";
			$ht .= '	RewriteRule ^index\.php$ - [L]'."\n";
			$ht .= '	RewriteCond %{REQUEST_FILENAME} !-f'."\n";
			$ht .= '	RewriteCond %{REQUEST_FILENAME} !-d'."\n";
			$ht .= '	RewriteRule . '.$_POST['config']['path'].'index.php [L]'."\n";
			$ht .= '</IfModule>'."\n\n";
			$ht .= '# Restrict access to files'."\n";
			$ht .= '<FilesMatch "\.(inc|sql|back|cache|tpl|log|ihtml|class|module|bin|ini|conf|h|spd)$">'."\n";
			$ht .= '	deny from all'."\n";
			$ht .= '</FilesMatch>';
			file_put_contents('../.htaccess', $ht);

			echo '<script>set_percent(350)</script>'."\n";
			print_info('پایان پیکربندی', 'فایل پیکربندی با موفقیت ایجاد شده است، در مرحله ی بعدی اطلاعات لازم در دیتابیس سایت نوشته خواهد شد.<br>اطلاعات ثبت شده به شرح زیر است.');

			require_once('../engine/config.inc.php');
			echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
			echo '<tr>'."\n";
			echo '	<td style="width:290px">نام هاست <font color="#CCCCCC" size="1" dir="ltr">(Host Name)</font></td>'."\n";
			echo '	<td><b>'.database_host.'</b></td>'."\n";
			echo '</tr>'."\n";
			echo '<tr>'."\n";
			echo '	<td>نام کاربری <font color="#CCCCCC" size="1" dir="ltr">(MySQL User Name)</font></td>'."\n";
			echo '	<td><b>'.database_user.'</b></td>'."\n";
			echo '</tr>'."\n";
			echo '<tr>'."\n";
			echo '	<td>پسورد دیتابیس <font color="#CCCCCC" size="1" dir="ltr">(MySQL Password)</font></td>'."\n";
			echo '	<td><b>'.(database_password == ''? 'بدون پسورد' : database_password).'</b></td>'."\n";
			echo '</tr>'."\n";
			echo '<tr>'."\n";
			echo '	<td>نام دیتابیس <font color="#CCCCCC" size="1" dir="ltr">(MySQL Database Name)</font></td>'."\n";
			echo '	<td><b>'.database_name.'</b></td>'."\n";
			echo '</tr>'."\n";
			echo '<tr>'."\n";
			echo '	<td>دامنه سایت <font color="#CCCCCC" size="1" dir="ltr">(Domain)</font></td>'."\n";
			echo '	<td><b>'.domain.'</b></td>'."\n";
			echo '</tr>'."\n";
			echo '<tr>'."\n";
			echo '	<td>محل نصب سایت <font color="#CCCCCC" size="1" dir="ltr">(Path)</font></td>'."\n";
			echo '	<td dir=ltr><b>'.(path == '/'? 'روت سایت' : path).'</b></td>'."\n";
			echo '</tr>'."\n";
			echo '<tr>'."\n";
			echo '	<td>آدرس سایت <font color="#CCCCCC" size="1" dir="ltr">(Full Url)</font></td>'."\n";
			echo '	<td dir=ltr><b>'.url.'</b></td>'."\n";
			echo '</tr>'."\n";
			echo '</table>'."\n";

			echo '<button onClick="install_load(\'db-insert\')">نوشتن اطلاعات دیتابیس</button>'."\n";
		}
		else
		{
			print_info('خطا در پیکربندی', 'در نوشتن فایل پیکربندی خطایی رخ داده مجدد تلاش کنید.');
			echo '<button onClick="install_load(\'config\')">بازگشت</button>'."\n";
		}
	}
	else
	{
		print_info('خطا در پیکربندی', 'اطلاعات وارد شده صحیح نیست لطفا بازگردید و مجدد تلاش کنید.');
		echo '<button onClick="install_load(\'config\')">بازگشت</button>'."\n";
	}

	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;
	
	case 'db-insert':
	if (!$ajax) print_header();
	require_once('../engine/config.inc.php');
	
	$connect = @mysql_connect(database_host, database_user, database_password, true);
	if ($connect)
	{
		if (@mysql_select_db(database_name, $connect)) 
		{
			$connect = 'success';
		}
	}

	if ($connect == 'success')
	{
		@set_time_limit(1200);
		require_once('../engine/database.class.php');
		$d = new database;
		$d->connect(array(
			'host' => database_host,
			'user' => database_user,
			'password' => database_password,
			'name' => database_name,
			'prefix' => database_prefix,
			'charset' => database_charset,
		));
		require_once('sql/install.php');
		
		echo '<script>set_percent(450)</script>'."\n";
		print_info('پایان نوشتن دیتابیس', 'اطلاعات دیتابیس سایت شما با موفقیت نوشته شده است، در مرحله بعد یک مدیر برای سایت خواهید ساخت.');
		echo '<button onClick="install_load(\'admin\')">ساختن مدیر</button>'."\n";
	}
	else
	{
		print_info('خطا در پیکربندی دیتابیس', 'ارتباط برقرار نشد!');
		echo '<button onClick="install_load(\'database\')">بازگشت</button>'."\n";
	}

	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;

	case 'admin':
	if (!$ajax) print_header();
	echo '<script>set_percent(520)</script>'."\n";
	print_info('اطلاعات کاربری مدیر سایت', 'لطفا اطلاعات مورد نیاز برای ساختن مدیر سایت را وارد کنید، نام کاربری باید حداقل 4 حرف باشد و پسورد باید حداقل 6 حرف باشد.');
	echo '<form id="form-admin" onSubmit="return false">'."\n";
	echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
	echo '  <tr>'."\n";
	echo '    <td style="width:230px">نام کاربری مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin User Name)</font></td>'."\n";
	echo '    <td><input name="admin[name]" id="input-name" type="text" value="admin" style="width:350px; float: left; direction: ltr" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>ایمیل مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin Email)</font></td>'."\n";
	echo '    <td><input name="admin[email]" id="input-email" type="text" value="" style="width:350px; float: left; direction: ltr" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>پسورد مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin Password)</font></td>'."\n";
	echo '    <td><input name="admin[pass1]" id="input-pass1" type="Password" value="" style="width:350px; float: left; direction: ltr" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>تکرار پسورد مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin Password)</font></td>'."\n";
	echo '    <td><input name="admin[pass2]" id="input-pass2" type="Password" value="" style="width:350px; float: left; direction: ltr" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td></td>'."\n";
	echo '    <td><button onClick="install_admin()" disabled="disabled" id="button-disabled">ایجاد مدیر سایت</button><button onClick="install_check(\'admin\')" style="margin-left:10px">برسی اطلاعات</button></td>'."\n";
	echo '  </tr>'."\n";
	echo '</table>'."\n";
	echo '</form>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;
	
	case 'admin-check':
	$_POST['admin'] = array_map('trim', $_POST['admin']);
	$_POST['admin']['email'] = strip_tags($_POST['admin']['email']);
	$_POST['admin']['pass1'] = ($_POST['admin']['pass1']);
	$_POST['admin']['pass2'] = ($_POST['admin']['pass2']);
	$result = 'error';
	
	if (!preg_check("/^[0-9a-z-_]{4,40}$/i", $_POST['admin']['name']))
	{
		$result = 'name';
	}
	else
	{
		if (!validate_email($_POST['admin']['email']))
		{
			$result = 'email';
		}
		else
		{
			if (strlen($_POST['admin']['pass1'])<=5 || $_POST['admin']['pass1']!=$_POST['admin']['pass2'])
			{
				$result = 'pass';
			}
			else
			{
				$result = 'success';
			}
		}
	}
	
	exit('{"result":"'.$result.'"}');
	break;
	
	case 'admin-insert':
	if (!$ajax) print_header();

	require_once('../engine/config.inc.php');
	require_once('../engine/database.class.php');
	$d = new database;
	$d->connect(array(
		'host' => database_host,
		'user' => database_user,
		'password' => database_password,
		'name' => database_name,
		'prefix' => database_prefix,
		'charset' => database_charset,
	));
	
	$_POST['admin'] = array_map('trim', $_POST['admin']);
	$_POST['admin']['email'] = strip_tags($_POST['admin']['email']);
	$_POST['admin']['pass1'] = ($_POST['admin']['pass1']);
	$_POST['admin']['pass2'] = ($_POST['admin']['pass2']);
	
	if (preg_check("/^[0-9a-z-_]{4,25}$/i", $_POST['admin']['name']))
	{
		if (strlen($_POST['admin']['pass1']) > 5 && $_POST['admin']['pass1'] == $_POST['admin']['pass2'] && validate_email($_POST['admin']['email']))
		{
			$_POST['admin']['pass1'] = str_replace('\\', null, $_POST['admin']['pass1']);
			$d->update('members', array(
				'member_name' => strtolower($_POST['admin']['name']),
				'member_alias' => $_POST['admin']['name'],
				'member_password' => md5(sha1($d->escapeString($_POST['admin']['pass1']))),
				'member_ip' => get_ip(),
				'member_lastip' => get_ip(),
				'member_email' => strtolower($_POST['admin']['email']),
				'member_date' => time(),
				'member_lastvisit' => time(),
			), "member_id=1", 1);

			$d->update('shoutbox', array(
				'shout_member' => $_POST['admin']['name'],
			));

			$result = 'ok';
		}
	}
	
	if (isset($result))
	{
		echo '<script>set_percent(615)</script>'."\n";
		print_info('پایان نصب آپادانا', 'تبریک! سایت آپادانایی شما با موفقیت ایجاد شده است و می توانید از آن لذت ببرید.<br>برای انتخاب آپادانا از شما سپاس گذاریم.<br>ایمان مودی');
		
		echo '<br><center>'."\n";
		echo '<a href="'.url.'" target="_blank"><b>مشاهده سایت</b></a><br><br>'."\n";
		echo 'آدرس بخش مدیریت سایت<br>'."\n";
		echo '<a href="'.url.'?admin=iran" target="_blank"><b>'.url.'?admin=iran</b></a><br><br>'."\n";
		echo '<a href="http://www.apadanacms.ir" target="_blank"><b>مشاهده سایت رسمی آپادانا ایرانی!</b></a>'."\n";
		echo '</center>'."\n";

		$MailHeader  = 'MIME-Version: 1.0' . "\r\n";
		$MailHeader .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$MailHeader .= 'From: admin@apadanacms.ir'."\r\n"; // Sender's Email Address
		$MailHeader .= 'Return-Path: admin@apadanacms.ir <admin@apadanacms.ir> /n'; // Indicates Return-path
		$MailHeader .= 'Reply-To: admin@apadanacms.ir <admin@apadanacms.ir> /n'; // Reply-to Address
		$MailHeader .= 'X-Mailer: PHP/' . phpversion(); // For X-Mailer
		$Body = "<div style='direction: rtl;text-align: right;font-family: Tahoma;font-size: 10pt'><b>سایت شما با موفقیت ساخته شد!<br>از انتخاب آپادانا سپاس گذاریم.</b><br>ایمان مودی</div>";
		@mail($_POST['admin']['email'], "یک سایت جدید با آپادانا!", $Body, $MailHeader);

		file_put_contents('apadana.lock', 'Copyright © 2012-'.date('Y').' ApadanaCms.ir. All rights reserved.');
	}
	else
	{
		print_info('خطا در اطلاعات', 'اطلاعات وارد شده صحیح نیست، بازگردید و آن ها را به صورت صحیح وارد کنید!');
		echo '<button onClick="install_load(\'admin\')">بازگشت</button>'."\n";
	}
	
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;
	
	default:
	if (!$ajax) print_header();
	print_info('مجوز آپادانا', '<a href="http://www.apadanacms.ir">آپادانا</a> یک سیستم مدیریت محتوای رایگان است که تحت مجوز GNU منتشر می شود، طبق این مجوز شما می توانید از آپادانا به عنوان سکوی پرتاب در پروژه های شخصی خود استفاده کنید، اما به این نکته دقت کنید که نمی توانید کپی رایت آپادانا را از سورس صفحات حذف کنید.');
	echo '<iframe src="gpl/gpl.html" class="license" frameborder="0" scrolling="auto"></iframe>'."\n";
	echo '<button onClick="install_load(\'check\')">شروع نصب آپادانا ایرانی</button>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;
}
else:
	if (!$ajax) print_header();
	print_info('خطا', '<font color=red>آپادانا قبلا نصب شده است!<br />برای نصب مجدد فایل <b>apadana.lock</b> را از پوشه <b>install</b> حذف کنید.</font>');
	echo '<center><a href="http://www.apadanacms.ir" target="_blank"><b>مشاهده سایت رسمی آپادانا ایرانی!</b></a></center>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
endif;
