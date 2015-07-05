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

switch ($_GET['action'])
{
	case 'start':
	if (!$ajax) print_header('Upgrade');
	echo '<script>set_percent(100)</script>'."\n";
	print_info('برسی قبل از بروزرسانی', 'فایل زیر باید قابل نوشتن باشد.');
	echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
	echo '<tr>'."\n";
	echo '	<td style="width:290px">فایل پیکربندی <font color="#CCCCCC" size="1" dir="ltr">(/engine/config.inc.php)</font></td>'."\n";
	echo '	<td>'.(file_exists('../engine/config.inc.php') && is_writable('../engine/config.inc.php')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '<tr>'."\n";
	echo '	<td>فایل <font color="#CCCCCC" size="1" dir="ltr">(/.htaccess)</font></td>'."\n";
	echo '	<td>'.(is_writable('../.htaccess')? '<b><font color="green">قابل نوشتن</font></b>' : '<b><font color="red">غیرقابل نوشتن</font></b>').'</td>'."\n";
	echo '</tr>'."\n";
	echo '</table>'."\n";
	echo file_exists('../engine/config.inc.php') && is_writable('../engine/config.inc.php')? '<button onClick="upgrade_load(\'admin\')">ادامه بروزرسانی</button>'."\n" : '<button onClick="upgrade_load(\'start\')">برسی مجدد</button>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;

	case 'admin':
	$q = $d->query("SELECT `member_name` FROM `#__members` WHERE `member_id`='1' LIMIT 1");
	$data = $d->fetch($q);
	
	unset($_SESSION['APADANA']);
	if (!$ajax) print_header('Upgrade');
	echo '<script>set_percent(200)</script>'."\n";
	print_info('اطلاعات کاربری مدیر سایت', 'برای ادامه کار لطفا پسورد خود را وارد کنید.');
	echo '<form id="form-admin" onSubmit="return false">'."\n";
	echo '<table cellpadding="0" cellspacing="0" class="padding">'."\n";
	echo '  <tr>'."\n";
	echo '    <td style="width:230px">نام کاربری مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin User Name)</font></td>'."\n";
	echo '    <td><input disabled="disabled" type="text" value="'.$data['member_name'].'" style="width:350px; float: left; direction: ltr" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td>پسورد مدیر <font color="#CCCCCC" size="1" dir="ltr" style="float: left">(Admin Password)</font></td>'."\n";
	echo '    <td><input name="password" id="input-password" type="Password" value="" style="width:350px; float: left; direction: ltr" /></td>'."\n";
	echo '  </tr>'."\n";
	echo '  <tr>'."\n";
	echo '    <td></td>'."\n";
	echo '    <td><button onClick="upgrade_load(\'config\')" disabled="disabled" id="button-disabled">ادامه بروزرسانی</button><button onClick="upgrade_check(\'admin\')" style="margin-left:10px">برسی اطلاعات</button></td>'."\n";
	echo '  </tr>'."\n";
	echo '</table>'."\n";
	echo '</form>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;
	
	case 'admin-check':
	$q = $d->query("SELECT `member_password` FROM `#__members` WHERE `member_id`='1' LIMIT 1");
	$data = $d->fetch($q);

	$_POST['password'] = html_entity_decode($_POST['password'], ENT_QUOTES, 'UTF-8');
	$_POST['password'] = strip_tags($_POST['password']);
	$_POST['password'] = trim($_POST['password']);
	$_POST['password'] = str_replace('\\', null, $_POST['password']);
	$_POST['password'] = md5('pars-'.sha1($d->escapeString($_POST['password'])).'-nuke');
	
	if ($data['member_password'] == $_POST['password'])
	{
		$_SESSION['APADANA'] = true;
		$result = 'success';
	}
	else
	{
		$result = 'password';
	}

	exit('{"result":"'.$result.'"}');
	break;

	case 'config':
	if (!$ajax) print_header('Upgrade');
	
	if (!isset($_SESSION['APADANA']) || $_SESSION['APADANA'] !== true)
	{
		echo '<script>set_percent(200)</script>'."\n";
		print_info('عدم دسترسی', 'لطفا باز گردید و پسورد خود را وارد کنید!');
		echo '<button onClick="upgrade_load(\'start\')">بازگشت</button>'."\n";
		if ($ajax) echo '<div class="clear"></div>'."\n";
		if (!$ajax) print_footer();
		exit;
	}
	
	require_once('../engine/config.inc.php');

	$php  = '<?php'."\r\n";
	$php .= '/**'."\r\n";
	$php .= ' * @In the name of God!'."\r\n";
	$php .= ' * @author: Iman Moodi (Iman92)'."\r\n";
	$php .= ' * @email: info@apadanacms.ir'."\r\n";
	$php .= ' * @link: http://www.apadanacms.ir'."\r\n";
	$php .= ' * @license: http://www.gnu.org/licenses/'."\r\n";
	$php .= ' * @copyright: Copyright © 2012-'.date('Y').' ApadanaCms.ir. All rights reserved.'."\r\n";
	$php .= ' * @Apadana CMS is a Free Software'."\r\n";
	$php .= ' */'."\r\n\r\n";
	$php .= 'defined(\'security\') or exit(\'Nice TRY!\');'."\r\n\r\n";
	$php .= 'define(\'database_host\', \''.database_host.'\');'."\r\n";
	$php .= 'define(\'database_user\', \''.database_user.'\');'."\r\n";
	$php .= 'define(\'database_password\', \''.database_password.'\');'."\r\n";
	$php .= 'define(\'database_name\', \''.database_name.'\');'."\r\n";
	$php .= 'define(\'database_prefix\', \''.database_prefix.'\');'."\r\n";
	$php .= 'define(\'database_charset\', \'utf8\');'."\r\n";
	$php .= 'define(\'database_save_queries\', false);'."\r\n\r\n";
	$php .= 'define(\'error_reporting\', false);'."\r\n";
	$php .= 'define(\'charset\', \'utf-8\');'."\r\n";
	$php .= 'define(\'sitekey\', \''.generate_password(40).'\');'."\r\n";
	$php .= 'define(\'domain\', \''.domain.'\');'."\r\n";
	$php .= 'define(\'path\', \''.my_path().'\');'."\r\n";
	$php .= 'define(\'url\', \''.url.'\');'."\r\n\r\n";
	$php .= '?>';

	if (file_put_contents('../engine/config.inc.php', $php))
	{
		$ht  = 'DirectoryIndex index.php index.html index.htm'."\n\n";
		$ht .= 'ErrorDocument 400 '.my_path().'index.php?a=error&b=400'."\n";
		$ht .= 'ErrorDocument 403 '.my_path().'index.php?a=error&b=403'."\n";
		$ht .= 'ErrorDocument 404 '.my_path().'index.php?a=error&b=404'."\n";
		$ht .= 'ErrorDocument 500 '.my_path().'index.php?a=error&b=500'."\n";
		$ht .= 'ErrorDocument 503 '.my_path().'index.php?a=error&b=503'."\n\n";
		$ht .= '<IfModule mod_rewrite.c>'."\n";
		$ht .= '	RewriteEngine On'."\n";
		$ht .= '	RewriteBase '.my_path().''."\n";
		$ht .= '	RewriteRule ^index\.php$ - [L]'."\n";
		$ht .= '	RewriteCond %{REQUEST_FILENAME} !-f'."\n";
		$ht .= '	RewriteCond %{REQUEST_FILENAME} !-d'."\n";
		$ht .= '	RewriteRule . '.my_path().'index.php [L]'."\n";
		$ht .= '</IfModule>'."\n\n";
		$ht .= '# Restrict access to files'."\n";
		$ht .= '<FilesMatch "\.(inc|sql|back|cache|tpl|log|ihtml|class|module|bin|ini|conf|h|spd)$">'."\n";
		$ht .= '	deny from all'."\n";
		$ht .= '</FilesMatch>';
		file_put_contents('../.htaccess', $ht);

		echo '<script>set_percent(400)</script>'."\n";
		print_info('پایان پیکربندی', 'فایل پیکربندی با موفقیت ویرایش شد، در مرحله بعد اطلاعات دیتابیس بروزرسانی خواهند شد، ممکن از این کار طول بکشد لطفا منتظر بمانید.');
		echo '<button onClick="upgrade_load(\'db-upgrade\')">بروزرسانی اطلاعات دیتابیس</button>'."\n";
	}
	else
	{
		print_info('خطا در پیکربندی', 'در نوشتن فایل پیکربندی خطایی رخ داده مجدد تلاش کنید.');
		echo '<button onClick="upgrade_load(\'config\')">تلاش مجدد</button>'."\n";
	}

	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;

	case 'db-upgrade':
	if (!$ajax) print_header('Upgrade');
	
	if (!isset($_SESSION['APADANA']) || $_SESSION['APADANA'] !== true)
	{
		print_info('عدم دسترسی', 'لطفا باز گردید و پسورد خود را وارد کنید!');
		echo '<button onClick="upgrade_load(\'start\')">بازگشت</button>'."\n";
		if ($ajax) echo '<div class="clear"></div>'."\n";
		if (!$ajax) print_footer();
		exit;
	}

	if ($d->connect)
	{
		@set_time_limit(1200);
		require_once('sql/upgrade-old.php');

		echo '<script>set_percent(500)</script>'."\n";
		print_info('پایان بروزرسانی دیتابیس', 'اطلاعات دیتابیس سایت شما با موفقیت بروزرسانی شده است.');
		echo '<button onClick="upgrade_load(\'end\')">مرحله بعد</button>'."\n";
	}
	else
	{
		print_info('خطا در پیکربندی دیتابیس', 'ارتباط برقرار نشد!');
		echo '<button onClick="upgrade_load(\'database\')">بازگشت</button>'."\n";
	}

	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;

	case 'end':
	if (!$ajax) print_header('Upgrade');

	if (!isset($_SESSION['APADANA']) || $_SESSION['APADANA'] !== true)
	{
		echo '<script>set_percent(200)</script>'."\n";
		print_info('عدم دسترسی', 'لطفا باز گردید و پسورد خود را وارد کنید!');
		echo '<button onClick="upgrade_load(\'start\')">بازگشت</button>'."\n";
		if ($ajax) echo '<div class="clear"></div>'."\n";
		if (!$ajax) print_footer();
		exit;
	}

	if (is_dir('../modules/newsletter'))
	{
		# delete newsletter
		@unlink('../modules/newsletter/admin.php');
		@unlink('../modules/newsletter/config.php');
		@unlink('../modules/newsletter/functions.admin.php');
		@unlink('../modules/newsletter/index.html');
		@unlink('../modules/newsletter/html/block.tpl');
		@unlink('../modules/newsletter/html/admin/index.tpl');
		@rmdir('../modules/newsletter/html/admin');
		@rmdir('../modules/newsletter/html');
		@rmdir('../modules/newsletter');
	}

	$caches = glob('../engine/cache/*.cache');
	if (is_array($caches) && count($caches))
	{
		foreach ($caches as $cache)
		{
			if (file_exists($cache) && is_writable($cache))
			{
				@unlink($cache);
			}
		}
	}

	file_put_contents('apadana.lock', 'Copyright © 2012-'.date('Y').' ApadanaCms.ir. All rights reserved.');

	$q = $d->query("SELECT `option_value` FROM `#__options` WHERE `option_name`='admin' LIMIT 1");
	$data = $d->fetch($q);
	unset($_SESSION['version']);

	echo '<script>set_percent(615)</script>'."\n";
	print_info('پایان بروزرسانی', 'تبریک، بروزرسانی سایت شما به اتمام رسیده است، لطفا سایت خود را چک کنید در صورت وجود مشکلی در انجمن پشیبانی سایت مطرح کنید.');

	echo '<br><center>'."\n";
	echo '<a href="'.url.'" target="_blank"><b>مشاهده سایت</b></a><br><br>'."\n";
	echo 'آدرس بخش مدیریت سایت<br>'."\n";
	echo '<a href="'.url.'?admin='.$data['option_value'].'" target="_blank"><b>'.url.'?admin='.$data['option_value'].'</b></a><br><br>'."\n";
	echo '<a href="http://www.apadanacms.ir" target="_blank"><b>مشاهده سایت رسمی آپادانا ایرانی!</b></a>'."\n";
	echo '</center>'."\n";

	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;

	default:
	if (!$ajax) print_header('Upgrade');
	echo '<script>set_percent(0)</script>'."\n";
	print_info('اخطار!', 'توصیه اکید می شود قبل از شروع بروزرسانی از اطلاعات دیتابیس خود یک پشتیبان تهیه کنید.');
	echo '<button onClick="upgrade_load(\'start\')">شروع بروزرسانی</button>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
	break;
}

?>