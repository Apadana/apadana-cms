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

defined('security') or die('Direct Access to this location is not allowed.');

function module_error_run($type = null, $exit = false)
{
	if($type=='400' || (isset($_GET['b']) && $_GET['b']=='400'))
	{
		Header('HTTP/1.0 400 Bad Request');
		set_theme('error-400');
		set_title('Bad Request!');
		set_canonical(url('error/400'));
		$message = 'Server didn\'t understand the URL you gave it.';
	}
	elseif($type=='403' || (isset($_GET['b']) && $_GET['b']=='403'))
	{
		Header('HTTP/1.0 403 Forbidden');
		set_theme('error-403');
		set_title('Forbidden!');
		set_canonical(url('error/403'));
		$message = 'Server refuses to give you a file, authentication won\'t help!';
	}
	elseif($type=='500' || (isset($_GET['b']) && $_GET['b']=='500'))
	{
		Header('HTTP/1.0 500 Internal Server Error');
		set_theme('error-500');
		set_title('Internal Server Error!');
		set_canonical(url('error/500'));
		$message = 'Something on the server didn\'t work right.';
	}
	elseif($type=='503' || (isset($_GET['b']) && $_GET['b']=='503'))
	{
		Header('HTTP/1.0 503 Service Unavailable');
		set_theme('error-503');
		set_title('Service Unavailable!');
		set_canonical(url('error/503'));
		$message = 'Too busy to respond to a client!';
	}
	else // 404
	{
		Header('HTTP/1.0 404 Not Found');
		set_theme('error-404');
		set_title('یافت نشد!');
		set_canonical(url('error/404')); // Not Found
		$message = 'اطلاعات درخواستی شما در سایت یافت نشد!'; // A file doesn't exist at that address
	}

	($hook = get_hook('module_error'))? eval($hook) : null;

	set_content('خطا', message($message, 'error'));

	if($exit)
	{
		exit;
	}
}


?>