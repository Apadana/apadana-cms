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

function redirect_link($link)
{
	($hook = get_hook('redirect_link'))? eval($hook) : null;

	$link = str_replace(array('<', '>', '"', "'", './', '../', '.php'), array('', '', '', '', '@SLASH1@', '@SLASH2@', '@PHP@'), $link);
	return url . '?a=redirect&amp;b=' . urlencode($link);
}

function module_redirect_run()
{
	$_GET['b'] = get_param($_GET, 'b');
	#$_GET['b'] = urldecode($_GET['b']);
	$_GET['b'] = nohtml($_GET['b']);
	$_GET['b'] = str_replace(array('@SLASH1@', '@SLASH2@', '@PHP@'), array('./', '../', '.php'), $_GET['b']);

	if (empty($_GET['b']) || !validate_url($_GET['b']))
	{
		Header('HTTP/1.0 400 Bad Request');
		warning('آدرس نامعتبر', 'متاسفانه سیستم قادر به انتقال شما به آدرس درخواستی نمی باشد!');
	}
	else
	{
		($hook = get_hook('module_redirect'))? eval($hook) : null;

		redirect($_GET['b']);
	}
}

?>