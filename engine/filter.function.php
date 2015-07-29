<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92) & Mohammad Sadegh Dehghan Niri (MSDN)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

// Scan image files for malicious code
function verify_image($file)
{
	$txt = file_get_contents($file);
	$image_safe = true;
	if (preg_match('#&(quot|lt|gt|nbsp|<?php);#i', $txt)) { $image_safe = false; }
	elseif (preg_match("#<\?php#i", $txt)) { $image_safe = false; }
	elseif (preg_match("#&\#x([0-9a-f]+);#i", $txt)) { $image_safe = false; }
	elseif (preg_match('#&\#([0-9]+);#i', $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt)) { $image_safe = false; }
	elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt)) { $image_safe = false; }
	return $image_safe;
}

function isnum($string)
{
	return is_numeric($string) && strpos($string, '.') === false && $string > 0? true : false;
}

function is_alphabet($string)
{
	return !is_array($string) && !is_object($string)? preg_match('/^[0-9a-z-_]+$/i', $string) : false;
}

function alphabet($string)
{
	return preg_replace('([^A-Za-z-0-9-_]*)', null, $string);
}

function htmlencode($string)
{
	return trim(htmlentities(html_entity_decode( $string, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8'));
}

function htmldecode($string)
{
	return trim(html_entity_decode( $string, ENT_QUOTES, 'UTF-8'));
}

function nohtml($string)
{
	return trim(strip_tags(htmldecode($string)));
}

function slug($text, $urlencode = true)
{
	$text = nohtml($text);
	$text = str_replace(array('!','@','#','$','%','^','*',';',':','÷','(',')','{','}','_','-','=','+','|','¦','/','\\','~','≈','`','´','\'','"','&','?','؟','>','<','»','«','⇔','⇒','→','×','.',',','،','¸','…','‌',' ','“','”','„','‛','¤','♦','•','►','—','–','¯','¨','º','·','€','©','®','¢','£','¶','™'),'-',$text);
	$text = preg_replace('#-{2,}#', '-', $text);
	$text = trim($text, '-');
	$text = function_exists('mb_strtolower')? mb_strtolower($text) : $text;
	return ($urlencode? urlencode($text) : $text);
}

function clean_url($url) 
{
    if ($url == '') return;
    $url = str_replace('http://', null, apadana_strtolower($url));
    $url = str_replace('https://', null, $url );
    if (substr($url, 0, 4) == 'www.')  $url = apadana_substr($url, 4);
    $url = explode('/', $url);
    $url = reset($url);
    $url = explode(':', $url);
    $url = reset($url);
    return $url;
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

function validate_url($url)
{
    if (phpversion() >= '5.2.0')
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
    else
    {
        return preg_match('/^(http(s?)://|ftp://{1})((w+.){1,})w{2,}$/i', $url);
    }
}

function check_xss()
{
	$url = html_entity_decode( urldecode($_SERVER['QUERY_STRING']) );
	$url = str_replace('\\', '/', $url);

	if ($url)
	{
		if ( (strpos( $url, '<' ) !== false) || (strpos( $url, '>' ) !== false) || (strpos( $url, '"' ) !== false) || (strpos( $url, './' ) !== false) || (strpos( $url, '../' ) !== false) || (strpos( $url, '\'' ) !== false) /*|| (strpos( $url, '.php' ) !== false)*/ )
		{
			exit('Hacking attempt!');
		}
	}
	$url = html_entity_decode( urldecode($_SERVER['REQUEST_URI']) );
	$url = str_replace('\\', '/', $url);

	if ($url)
	{
		if ( (strpos( $url, '<' ) !== false) || (strpos( $url, '>' ) !== false) || (strpos( $url, '"' ) !== false) || (strpos( $url, '\'' ) !== false) )
		{
			exit('Hacking attempt!');
		}
	}
}