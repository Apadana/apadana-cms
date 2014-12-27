<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92) & Mohammad Sadegh Dehghan Niri (MSDN)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2013 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

function get_param($arr, $name, $def = null, $mask = 0)
{
	if (isset($arr[$name]) && !empty($arr[$name]))
	{
		if (!$mask)
		{
			if (is_numeric($def))
			{
				$arr[$name] = is_array($arr[$name])? array_map('intval', $arr[$name]) : intval($arr[$name]);
			}
			else
			{
				$arr[$name] = is_array($arr[$name])? array_map('strip_tags', $arr[$name]) : strip_tags($arr[$name]);
			}
		}
		return $arr[$name];
	}
	else
	{
		return $def;
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

function memoryGetUsage()
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
    return 'Powered by <a href="http://www.apadanacms.ir" target="_blank" rel="copyright">Apadana Cms</a>.';
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

function redirect($url, $siteurl = true)
{
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
	Header("Location: ".$url."");
    exit("Link Redirect:<br /><br />Please click <a href=\"{$url}\">here.</a>");
}

function refresh($url, $time = 3, $siteurl = true)
{
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
	Header("Refresh: ".intval($time)."; url=".$url."");
}

function smiles_replace($text)
{
	($hook = get_hook('smiles_replace'))? eval($hook) : null;

	// $replace = array(
		// ':)' => ':s-2:',
		// ':(' => ':s-3:',
		// ';)' => ':s-4:',
	// );
	// $text = str_replace(array_keys($replace), array_values($replace), $text);
	
	for ($o = 1; $o <= 75; $o++)
	{
		$text = str_replace(':s-'.$o.':', '<img src="'.url.'engine/images/smiles/'.$o.'.gif" class="apadana-smiles" />', $text);
	}
	unset($o, $replace);
	return $text;
}

function maybe_unserialize( $original )
{
	if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}

function is_serialized( $data )
{
	// if it isn't a string, it isn't serialized
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	if ( 'N;' == $data )
		return true;
	if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
		return false;
	switch ( $badions[1] ) {
		case 'a' :
		case 'O' :
		case 's' :
			if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
				return true;
			break;
		case 'b' :
		case 'i' :
		case 'd' :
			if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
				return true;
			break;
	}
	return false;
}

function get_extension($file)
{
	return strtolower(substr(strrchr($file, '.'), 1));
}

function generate_password($count = 8, $add = '!@#%^&*()_+=-:;?~{}|÷.,')
{
	mt_srand(microtime()*1000000);
	$words = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'.$add;
	$return = '';
	for ($i=0; $i<$count; $i++) $return .= $words[mt_rand(0, strlen($words)-1)];
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
	$arabic = array('ي', 'ك', '٤', '٥', '٦');
	$persian = array('ی', 'ک', '۴', '۵', '۶');
	return str_replace($arabic, $persian, $content);
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

	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
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
	$name = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	return $size? round($size / pow(1024, ($i = floor(log($size, 1024)))), 2).' '.$name[$i] : $size.' Bytes';
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
/* Author: Amir Hossein Hodjaty Pour ~ Boplo.ir */
function get_past_time($time, $year=1, $month=1, $day=1, $hour=1, $minute=1, $second=0)
{
	$output = array();
	$div = (time() - $time) / 31536000; // 365 * 24 * 60 * 60 = 31536000
	$floor = floor($div);
	$rest = $div - $floor;

	if ($year && $floor > 0)
		$output[] = $floor .' سال';

	if ($rest > 0)
	{
		$div = $rest * 31536000 / 2592000; // 30 * 24 * 60 * 60 = 2592000
		$floor = floor($div);
		$rest = $div - $floor;
		
		if ($month && $floor > 0)
			$output[] = $floor .' ماه';
		
		if ($rest > 0)
		{
			$div = $rest * 2592000 / 86400; // 24 * 60 * 60 = 86400
			$floor = floor($div);
			$rest = $div - $floor;
			
			if ($day && $floor > 0)
				$output[] = $floor .' روز';
			
			if ($rest > 0)
			{
				$div = $rest * 86400 / 3600; // 60 * 60 = 3600
				$floor = floor($div);
				$rest = $div - $floor;
				
				if ($hour && $floor > 0)
					$output[] = $floor .' ساعت';
				
				if ($rest > 0)
				{
					$div = $rest * 60;
					$floor = floor($div);
					$rest = $div - $floor;
					
					if ($minute && $floor > 0)
						$output[] = $floor .' دقیقه';
					
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
	return (empty($output) ? 'چند لحظه پیش' : join(' و ', $output) . ' پیش');
}

function summarize($str, $limit = 600, $offset = 100, $nlineChars = array(' ','.','!','?',"\n",')',';',',','؟','،'))
{
    if (strlen($str)<=$limit)
        return $str;

    for ($i=$limit; $i>$limit-$offset; $i--)
	{
        if (in_array($str{$i}, $nlineChars))
		{
            $length = $i;
            break;
        }
        if (!isset($spaceLength) && $str{$i}==' ')
            $spaceLength = $i;
    }
    if (isset($length))
        return substr($str, 0, $length+1);

    for ($i=$limit; $i<$limit+$offset; $i++)
	{
        if (in_array($str{$i}, $nlineChars))
		{
            $length = $i;
            break;
        }
        if (!isset($spaceLength) && $str{$i}==' ')
            $spaceLength = $i;
    }
    if (isset($length))
        return substr($str, 0, $length+1);

    if (isset($spaceLength))
        return substr($str, 0, $spaceLength);

    return substr($str, 0, $limit+1);
}

function dump($var, $filter = 1, $stop = 1)
{
	if ($filter == 1)
		if (is_array($var))
			$var = array_map('htmlspecialchars', $var);

	echo('<pre style="direction:ltr;text-align:left">'.print_r($var, true).'</pre>');

	if ($stop == 1)
		exit;
}

function is_cli()
{
	return (PHP_SAPI === 'cli' OR defined('STDIN'));
}

function html_compaction($html)
{
	($hook = get_hook('html_compaction_start'))? eval($hook) : null;

    $array = array();
	if ($number = preg_match_all('/<(script|pre)(.*)>(.*)<\/\\1>/sUi', $html, $matches))
	{
		for ( $i = 0; $i < $number; $i++ )
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

	($hook = get_hook('html_compaction_end'))? eval($hook) : null;

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
	global $tpl, $d, $page, $options;

	$contents = ob_get_contents();
	
	if (ob_get_length() !== FALSE)
	{
		ob_end_clean();
	}

	$info = template_info($options['theme']);

	if ($info['compaction'] === true && admin_page === false) 
	{
		$contents = html_compaction($contents);
	}

	($hook = get_hook('gzip_out'))? eval($hook) : null;

	$encoding = check_can_gzip();
	if ((!defined('disable_gzip') || disable_gzip !== true) && $encoding)
	{
		header('Content-Encoding: '.$encoding);
		$contents = gzencode($contents, 9, FORCE_GZIP);
	}
	else
	{
		if (ob_get_length() !== FALSE)
		{
			ob_end_flush();
		}
	}

	$d->close();
	unset($tpl, $d, $page, $options);
	exit($contents);
}

?>