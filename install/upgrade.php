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

# Determine Magic Quotes Status (< PHP 6.0)
if (version_compare(PHP_VERSION, '6.0', '<'))
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

if (!file_exists('apadana.lock'))
{
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

	if (is_dir('../modules/newsletter') && file_exists('../engine/jalaliDate.function.php'))
	{
		require_once('upgrade/old.php');
	}
	else
	{
		if (!isset($_SESSION['version']))
		{
			$q = $d->query("SELECT option_value FROM #__options WHERE option_name='version' LIMIT 1");
			$data = $d->fetch($q);
			$_SESSION['version'] = $data['option_value'];
		}
		$_SESSION['version'] = $_SESSION['version'];

		switch ($_SESSION['version'])
		{
			case '1.0':
			require_once('upgrade/1.0@1.0.1.php');
			break;

			default;
			if (!$ajax) print_header('Upgrade');
			print_info('خطا', '<font color=red>متاسفانه نگارش آپادانا برای بروزرسانی شناسایی نشد</font>');
			echo '<center><a href="http://www.apadanacms.ir" target="_blank"><b>مشاهده سایت رسمی آپادانا ایرانی!</b></a></center>'."\n";
			if ($ajax) echo '<div class="clear"></div>'."\n";
			if (!$ajax) print_footer();
			break;
		}
	}
}
else
{
	if (!$ajax) print_header('Upgrade');
	print_info('خطا', '<font color=red>آپادانا قبلا نصب شده است!<br />برای نصب مجدد فایل <b>apadana.lock</b> را از پوشه <b>install</b> حذف کنید.</font>');
	echo '<center><a href="http://www.apadanacms.ir" target="_blank"><b>مشاهده سایت رسمی آپادانا ایرانی!</b></a></center>'."\n";
	if ($ajax) echo '<div class="clear"></div>'."\n";
	if (!$ajax) print_footer();
}

?>