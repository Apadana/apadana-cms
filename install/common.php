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

function get_ip()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	$ip = strip_tags($ip);
	return $ip;
}

function my_path()
{
    if(!isset($_SERVER['REQUEST_URI']))
	{
        $url = $_SERVER['PHP_SELF'];
	}
    else
	{
        $url = $_SERVER['REQUEST_URI'];
        $url = explode('?', $url);
		$url = str_replace(array('/install/install.php', '/install/upgrade.php'), '', $url[0]);
	}
    $url = str_replace(array('http://', 'https://'), null, $url);   
    $url = trim($url, '/');
	return ($url != ''? '/'.$url : null).'/';
}

function generate_password($count = 8, $add = '!@#%^&*()_+=-:;?~{}|÷.,')
{
	mt_srand(microtime()*1000000);
	$words = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'.$add;
	$return = '';
	for ($i=0; $i<$count; $i++) $return .= $words[mt_rand(0, strlen($words)-1)];
	return $return;
}

function validate_email($email)
{
    if (phpversion() >= '5.2.0')
	{
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
	else
	{
		if (strpos($email, ' ') !== false)
		{
			return false;
		}
		// Valid local characters for email addresses: http://www.remote.org/jochen/mail/info/chars.html
		return preg_match("/^[a-zA-Z0-9&*+\-_.{}~^\?=\/]+@[a-zA-Z0-9-]+\.([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,}$/si", $email);
    }
}

function strip_slashes_array(&$array)
{
	foreach ($array as $key => $val)
	{
		if (is_array($array[$key]))
		{
			strip_slashes_array($array[$key]);
		}
		else
		{
			$array[$key] = stripslashes($array[$key]);
		}
	}
}

function preg_check($expression, $value)
{
	if (is_string($value))
	{
		return preg_match($expression, $value);
	}
	else
	{
		return false;
	}
}

?>