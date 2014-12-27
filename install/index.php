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

if (!file_exists('apadana.lock'))
{
	print_header();
	print_info('نصب یا ارتقاع!', 'در صورتی که نگارش قدیمی را نصب کرده اید روی گزینه به روزرسانی کلیک کنید در غیر این صورت بر روی نصب کلیک کنید تا نصب آپادانا آغاز شود.');
	echo '<center>'."\n";
	echo '<button id="install" onClick="apadana.location(\'install.php\')">نصب آپادانا</button>'."\n";
	echo '<button id="upgrade" onClick="apadana.location(\'upgrade.php\')">بروز رسانی</button>'."\n";
	echo '</center>'."\n";
	print_footer();
}
else
{
	print_header();
	print_info('خطا', '<font color=red>آپادانا قبلا نصب شده است!<br />برای نصب مجدد فایل <b>apadana.lock</b> را از پوشه <b>install</b> حذف کنید.</font>');
	echo '<center><a href="http://www.apadanacms.ir" target="_blank"><b>مشاهده سایت رسمی آپادانا ایرانی!</b></a></center>'."\n";
	print_footer();
}

?>