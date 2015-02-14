<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92) & Mohammad Sadegh Dehghan Niri
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCMS.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

function apadana_date($format, $timestamp = 'now', $number_type = null)
{
	($hook = get_hook('apadana_date'))? eval($hook) : null;

	if ($number_type === null)
	{
		$number_type = 'fa';
	}

	if (!function_exists('jdate'))
	{
		require_once(engine_dir.'jalali-date.function.php');
	}

	return jdate($format, $timestamp, $number_type);
}

function get_param($array, $name, $default = null, $safe = 0)
{
	($hook = get_hook('get_param'))? eval($hook) : null;

	if (isset($array[$name]) && !empty($array[$name]))
	{
		if (!$safe)
		{
			if (is_numeric($default))
			{
				$array[$name] = is_array($array[$name])? array_map('intval', $array[$name]) : intval($array[$name]);
			}
			else
			{
				$array[$name] = is_array($array[$name])? array_map('strip_tags', $array[$name]) : strip_tags($array[$name]);
			}
		}

		return $array[$name];
	}
	else
	{
		return $default;
	}
}

function replace_links($text)
{
    global $options;
	
	if (isset($options) && $options['replace-link'] == 1 && member == 0)
	{
		($hook = get_hook('replace_links'))? eval($hook) : null;
		return preg_replace('#<a(.*?)href=["\'](.*?)["\'](.*?)>(.*?)</a>#i', '<a\\1href="javascript:alert(\'فقط کاربران عضو به این لینک دسترسی دارند!\')"\\3 rel="nofollow">\\4</a><!-- apadanacms.ir -->', $text);
	}
	else
	{
		return $text;
	}
}

function apadana_memory_get_usage()
{
	if (function_exists('memory_get_usage'))
    {
	    $memory_size = memory_get_usage(); // 36640
		$unit = array('بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت');
		return @round($memory_size/pow(1024,($i=floor(log($memory_size,1024)))),2).' '.$unit[$i];
	}

	return false;
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

function current_url()
{
    if (!isset($_SERVER['REQUEST_URI']))
	{
        $request = $_SERVER['PHP_SELF'];
	}
    else
	{
        $request = $_SERVER['REQUEST_URI'];
	}
	
    $s = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'? 's' : null;
    $protocol = apadana_substr(apadana_strtolower($_SERVER['SERVER_PROTOCOL']), 0, apadana_strpos(apadana_strtolower($_SERVER['SERVER_PROTOCOL']), '/')).$s;
    $port = $_SERVER['SERVER_PORT'] == 80? null : ':'.$_SERVER['SERVER_PORT'];

    return $protocol.'://'.$_SERVER['SERVER_NAME'].$port.$request;   
}

function check_url()
{
    global $options;

	if (is_ajax() || !isset($options['url-correction']) || $options['url-correction'] != 1)
	{
		return false;
	}

	$current = current_url();
	$parse = parse_url($current);
	$strrpos = apadana_strpos($current, path, $parse['scheme'] == 'https'? 8 : 7);
	$strlen = apadana_strlen(path);
	$self = apadana_substr($current, 0, $strrpos + $strlen);

	if ($self != url)
	{
		$redirect = apadana_substr($current, $strrpos + $strlen);
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '. url . $redirect);
		exit;
	}

	unset($current, $parse, $strrpos, $strlen, $self);
}

function set_cookie($name, $value, $expires = null, $domain = domain, $path = path)
{
	if (is_null($expires))
	{
		$expires = time() + 31536000; # One year
	}
	elseif (is_numeric($expires))
	{
		$expires = time() + $expires;
	}
	else
	{
		$expires = false; # At end of session
	}

	$domain = $domain == 'localhost'? false : $domain;

	($hook = get_hook('set_cookie'))? eval($hook) : null;

	if (PHP_VERSION < 5.2)
    {
		setcookie($name, $value, $expires, $path, $domain . '; HttpOnly');
	}
    else
    {
		setcookie($name, $value, $expires, $path, $domain, null, true);
	}
}

function un_set_cookie($cookieName)
{
    set_cookie($cookieName, NULL, -20);
}

function copyright()
{
    return 'Powered by <a href="http://www.apadanacms.ir" target="_blank" rel="copyright">Apadana CMS</a>.';
}

function redirect($url, $siteurl = true)
{
	($hook = get_hook('redirect'))? eval($hook) : null;

	if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://' && $siteurl) 
	{
		$url = url . $url;
	}

	$url = str_replace('&amp;', '&', $url);

	// First check if the current HTTP-Request is an Ajax-Request
	if (is_ajax())
	{
		exit('<script type="text/javascript">document.location.href="'.$url.'";</script>');
	}

	header('Location: '.$url);
	exit('Link Redirect:<br /><br />Please click <a href="'.$url.'">here.</a>');
}

function refresh($url, $time = 3, $siteurl = true)
{
	($hook = get_hook('refresh'))? eval($hook) : null;

	if (substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://' && $siteurl) 
	{
		$url = url . $url;
	}

	$url = str_replace('&amp;', '&', $url);

	// First check if the current HTTP-Request is an Ajax-Request
	if (is_ajax())
	{
		echo('<script type="text/javascript">setTimeout(\'document.location.href="'.$url.'"\', '.intval($time).'000)</script>');
	}

	header('Refresh: '.intval($time).'; url='.$url);
	exit('Link Redirect:<br /><br />Please click <a href="'.$url.'">here.</a>');
}

function smiles_replace($text)
{
	($hook = get_hook('smiles_replace'))? eval($hook) : null;

	for ($o = 1; $o <= 75; $o++)
	{
		$text = str_replace(':s-'.$o.':', '<img src="'.url.'engine/images/smiles/'.$o.'.gif" class="apadana-smiles" />', $text);
	}
	unset($o, $replace);

	return $text;
}

function is_serialized($data, $strict = true)
{
	// if it isn't a string, it isn't serialized
	if (!is_string($data))
	{
		return false;
	}

	$data = trim($data);

	if ('N;' == $data)
	{
		return true;
	}

	if (strlen($data) < 4)
	{
		return false;
	}

	if (':' !== $data[1])
	{
		return false;
	}

	if ($strict)
	{
		$lastc = substr($data, -1);
		if (';' !== $lastc && '}' !== $lastc)
		{
			return false;
		}
	}
	else
	{
		$semicolon = strpos($data, ';');
		$brace     = strpos($data, '}');

		// Either ; or } must exist.
		if (false === $semicolon && false === $brace)
		{
			return false;
		}

		// But neither must be in the first X characters.
		if (false !== $semicolon && $semicolon < 3)
		{
			return false;
		}

		if (false !== $brace && $brace < 4)
		{
			return false;
		}
	}

	$token = $data[0];

	switch ($token)
	{
		case 's':
			if ($strict)
			{
				if ('"' !== substr($data, -2, 1))
				{
					return false;
				}
			}
			elseif (false === strpos($data, '"'))
			{
				return false;
			}

		// or else fall through
		case 'a':
		case 'O':
			return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);

		case 'b':
		case 'i':
		case 'd':
			$end = $strict ? '$' : '';
			return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
	}

	return false;
}

function maybe_unserialize($original)
{
	// don't attempt to unserialize data that wasn't serialized going in
	if (is_serialized($original))
	{
		return @unserialize($original);
	}

	return $original;
}

function get_extension($file)
{
	return strtolower(substr(strrchr($file, '.'), 1));
}

function generate_password($count = 8, $add = '!@#%^&*()_+=-:;?~{}|÷.,', $reset = false)
{
	mt_srand(microtime()*1000000);

	$words = ($reset? null : 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') . $add;
	$return = '';

	for ($i = 0; $i < $count; $i++)
	{
		$return .= $words[mt_rand(0, strlen($words)-1)];
	}

	return $return;
}

function is_ajax()
{
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

function is_rtl()
{
	return (defined('rtl') && rtl === true? true : false);
}

function apadana_chmod($file, $mode)
{
	// Passing $mode as an octal number causes strlen and substr to return incorrect values. Instead pass as a string
	if (substr($mode, 0, 1) != '0' || strlen($mode) !== 4)
	{
		return false;
	}
	$old_umask = umask(0);

	// We convert the octal string to a decimal number because passing a octal string doesn't work with chmod
	// and type casting subsequently removes the prepned 0 which is needed for octal numbers
	$result = chmod($file, octdec($mode));
	umask($old_umask);
	return $result;
}

function get_ip()
{
	($hook = get_hook('get_ip'))? eval($hook) : null;

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
	{
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
	{
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return nohtml($ip);
}

function solve_persian($content)
{
	($hook = get_hook('solve_persian'))? eval($hook) : null;

	$arabic = array('ي', 'ك', '٤', '٥', '٦');
	$persian = array('ی', 'ک', '۴', '۵', '۶');

	return str_replace($arabic, $persian, $content);
}

function translate_number($text, $mod = null, $mf = null)
{
	($hook = get_hook('translate_number'))? eval($hook) : null;

	if ($mod === null)
	{
		$mod = 'fa';
	}

	if ($mf === null)
	{
		$mf = '٫';
	}

	$num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
	$key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf);

	return $mod == 'fa'? str_replace($num_a, $key_a, $text) : str_replace($key_a, $num_a, $text);
}

function un_register_globals()
{
	if (!ini_get('register_globals'))
	{
		return;
	}

	if (isset($_REQUEST['GLOBALS']))
	{
		exit('GLOBALS overwrite attempt detected');
	}

	// Variables that shouldn't be unset
	$noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', 'apadana');

	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION)? $_SESSION : array());
	foreach ($input as $k => $v)
	{
		if (!in_array($k, $noUnset) && isset($GLOBALS[$k]))
        {
			$GLOBALS[$k] = NULL;
			unset($GLOBALS[$k]);
		}
	}
}

function file_size($size)
{
	($hook = get_hook('file_size'))? eval($hook) : null;

	$name = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	return $size? round($size / pow(1024, ($i = floor(log($size, 1024)))), 2).' '.$name[$i] : $size.' Bytes';
}

/* Author: Amir Hossein Hodjaty Pour ~ Boplo.ir */
function get_past_time($time, $year = 1, $month = 1, $day = 1, $hour = 1, $minute = 1, $second = 0, $translate_number = null)
{
	($hook = get_hook('get_past_time.start'))? eval($hook) : null;

	if ($translate_number === null)
	{
		$translate_number = 'fa';
	}

	$output = array();
	$div = (time() - $time) / 31536000; // 365 * 24 * 60 * 60 = 31536000
	$floor = floor($div);
	$rest = $div - $floor;

	if ($year && $floor > 0)
	{
		$output[] = $floor .' سال';
	}

	if ($rest > 0)
	{
		$div = $rest * 31536000 / 2592000; // 30 * 24 * 60 * 60 = 2592000
		$floor = floor($div);
		$rest = $div - $floor;

		if ($month && $floor > 0)
		{
			$output[] = $floor .' ماه';
		}

		if ($rest > 0)
		{
			$div = $rest * 2592000 / 86400; // 24 * 60 * 60 = 86400
			$floor = floor($div);
			$rest = $div - $floor;

			if ($day && $floor > 0)
			{
				$output[] = $floor .' روز';
			}

			if ($rest > 0)
			{
				$div = $rest * 86400 / 3600; // 60 * 60 = 3600
				$floor = floor($div);
				$rest = $div - $floor;

				if ($hour && $floor > 0)
				{
					$output[] = $floor .' ساعت';
				}

				if ($rest > 0)
				{
					$div = $rest * 60;
					$floor = floor($div);
					$rest = $div - $floor;

					if ($minute && $floor > 0)
					{
						$output[] = $floor .' دقیقه';
					}

					if ($second && $rest > 0)
					{
						$div = $rest * 60;
						$floor = floor($div);
						
						$output[] = $floor .' ثانیه';
					}
				}
			}
		}
	}

	$output = (empty($output) ? 'چند لحظه پیش' : join(' و ', $output) . ' پیش');

	if ($translate_number && function_exists('translate_number'))
	{
		$output = translate_number($output, $translate_number);
	}

	($hook = get_hook('get_past_time.end'))? eval($hook) : null;

	return $output;
}

function summarize($str, $limit = 600, $offset = 100, $nlineChars = array(' ','.','!','?',"\n",')',';',',','؟','،'))
{
	($hook = get_hook('summarize.start'))? eval($hook) : null;

    if (apadana_strlen($str) <= $limit)
	{
        return $str;
	}

    for ($i = $limit; $i > $limit-$offset; $i--)
	{
        if (in_array($str{$i}, $nlineChars))
		{
            $length = $i;
            break;
        }
        if (!isset($spaceLength) && $str{$i} == ' ')
		{
            $spaceLength = $i;
		}
    }
    if (isset($length))
	{
        return apadana_substr($str, 0, $length+1);
	}

    for ($i = $limit; $i < $limit+$offset; $i++)
	{
        if (in_array($str{$i}, $nlineChars))
		{
            $length = $i;
            break;
        }
        if (!isset($spaceLength) && $str{$i} == ' ')
		{
            $spaceLength = $i;
		}
    }
    if (isset($length))
	{
        return apadana_substr($str, 0, $length+1);
	}

    if (isset($spaceLength))
	{
        return apadana_substr($str, 0, $spaceLength);
	}

    $str = apadana_substr($str, 0, $limit+1);

	($hook = get_hook('summarize.end'))? eval($hook) : null;

    return $str;
}

function apadana_compare($compatibility, $version = version)
{
	if ($compatibility == '*')
	{
		return true;
	}

	$compatibility = explode(',', $compatibility);
	$compatibility = array_map('trim', $compatibility);

	foreach ($compatibility as $number)
	{
		$not = false;
		$number = trim($number);

		if (apadana_substr($number, 0, 1) == '!')
		{
			$number = trim(apadana_substr($number, 1));
			$not = true;
		}

		if ($number == '')
		{
			continue;
		}

		$number = str_replace(array('+', '*'), array('.+', '.*'), preg_quote($number));
		$number = str_replace(array('\.+', '\.*'), array('.+', '.*'), $number);
		$match = preg_match('~^'.$number.'$~i', $version);

		if (($not === true && !$match) || ($not === false && $match))
		{
			return true;
		}
	}

	return false;
}

function dump($var, $filter = 1, $stop = 1)
{
	if ($filter == 1)
	{
		if (is_array($var))
		{
			$var = array_map('htmlspecialchars', $var);
		}
	}

	echo('<pre style="direction:ltr;text-align:left">'.print_r($var, true).'</pre>');

	if ($stop == 1)
	{
		exit;
	}
}

function get_size($file) {
	
	if(  is_file( $file ) ) return filesize( $file );

	if( ! is_dir( $file ) ) return false;
	
	$size = 0;
	$dir = opendir( $file );

	if ( $dir ) {
		
		while ( ($dirfile = readdir( $dir )) !== false ) {
			
			if( $dirfile == '.' || $dirfile == '..' ) continue;
			
			if( @is_file( $file . '/' . $dirfile ) ) $size += filesize( $file . '/' . $dirfile );
			
			else if( @is_dir( $file . '/' . $dirfile ) ) {
				
				$dirSize = dirsize( $file . '/' . $dirfile );
				if( $dirSize >= 0 ) $size += $dirSize;
				else return - 1;
			
			}
		
		}
		
	closedir( $dir );
	
	}
	
	return $size;

}

function html_compression($html)
{
	($hook = get_hook('html_compression.start'))? eval($hook) : null;

    $array = array();
	if ($number = preg_match_all('/<(script|pre|textarea|style)(.*)>(.*)<\/\\1>/sUi', $html, $matches))
	{
		for ($i = 0; $i < $number; $i++)
		{
			if (!empty($matches[0][$i]))
			{
				$key = rand(11111111, 99999999).generate_password(20, null).rand(11111111, 99999999);
				$array[$key] = trim($matches[0][$i]);
				$html = str_replace($matches[0][$i], '<#code:'.$key.':code#>', $html);
			}
		}
	}

    $html = preg_replace('/<!--(.*)-->/', '', $html);

    // remove tabs, spaces, newlines, etc.
	$html = preg_replace('#\n\s+#', ' ', $html);
	$html = preg_replace('#[\r\n\t]+<#', '<', $html);
	$html = preg_replace('#>[\r\n\t]+#', '>', $html);
	$html = preg_replace('# {2,}#', ' ', $html);

    foreach ($array as $key => $js)
    {
		$html = str_replace('<#code:'.$key.':code#>', $js, $html);
    }
    unset($array, $key, $js, $matches, $number);

	($hook = get_hook('html_compression.end'))? eval($hook) : null;

    return $html;
}

function check_can_gzip()
{
	if (headers_sent() || connection_aborted() || ! function_exists('ob_gzhandler') || ini_get('zlib.output_compression')) return false;
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false) return 'x-gzip';
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) return 'gzip';
	return false;
}

function gzip_out()
{
	global $d, $options;

	$contents = ob_get_contents();
	ob_end_clean();

	$template_info = template_info($options['theme']);
	$encoding = check_can_gzip();
	$encoding = false;
	$gzip_level = 9;

	($hook = get_hook('gzip_out'))? eval($hook) : null;

	if ($template_info['html-compression'] === true && admin_page === false) 
	{
		$contents = html_compression($contents);
	}

	if ((!defined('disable_gzip') || disable_gzip !== true) && $encoding)
	{
		header('Content-Encoding: '.$encoding);
		$contents = gzencode($contents, $gzip_level, FORCE_GZIP);
	}

	while (ob_get_length() !== false)
	{
		ob_end_flush();
	}

	$d->close();

	exit($contents);
}

/**
* Fix the JSON extension IF it is not loaded
*
* @since 1.1
*/

if (!extension_loaded('json') || !function_exists('json_encode') || !function_exists('json_encode') )
{
	require_once(engine_dir.'json.class.php');

	function json_encode($data)
	{
		$json = new Services_JSON();
		return $json->encode($data);
	}

	require_once(engine_dir.'json.class.php');

	function json_decode($data)
	{
		$json = new Services_JSON();
		return $json->decode($data);
	}
}