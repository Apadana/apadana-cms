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

member::check_admin_page_access('security-check') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	set_title('برسی امنیتی');
	
	$security = array();
	
	if (debug_system === true)
	{
		$security[] = message('نمایش خطاها در آپادانا فعال می باشد!', 'error');
	}
	
	$files = array(
		'uploads/.htaccess',
		'templates/.htaccess',
		'engine/config.inc.php',
		'engine/admin/.htaccess',
		'engine/admin/backups/.htaccess',
		'engine/admin/backups/index.html',
		'engine/cache/.htaccess',
		'engine/cache/index.html',
	);

	foreach ($files as $file)
	{
		if (!file_exists(root_dir.$file))
		{
			$security[] = message('فایل <b dir="ltr">'.$file.'</b> در سایت یافت نشد!', 'error');
		}
		else
		{
			if (is_writable(root_dir.$file))
			{
				$security[] = message('فایل <b dir="ltr">'.$file.'</b> قابل نوشتن است. <font color="#d90000" size="1">دسترسی مناسب: 0444</font>', 'error');
			}
		}
	}

	if (count($security))
	{
		$security = implode('<!-- security -->', $security);
	}
	else
	{
		$security = message('تبریک، در برسی مشکل امنیتی در آپادانا یافت نشد!', 'success');
	}

	set_content('برسی امنیتی در سیستم مدیریت محتوای آپادانا', $security);
}

_index();

?>