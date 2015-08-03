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

function apadana_strlen($string)
{
    $string = preg_replace('#&\#([0-9]+);#', '-', $string);
    if (strtolower(charset) == 'utf-8')
    {
        # Get rid of any excess RTL and LTR override for they are the workings of the devil
        $string = str_replace(dec_to_utf8(8238), '', $string);
        $string = str_replace(dec_to_utf8(8237), '', $string);
        # Remove dodgy whitespaces
        $string = str_replace(chr(0xCA), '', $string);
    }
	$string = trim($string);
	return function_exists('mb_strlen')? mb_strlen($string) : strlen($string);
}

function apadana_substr($string, $start, $length = '', $handle_entities = false)
{
	if ($handle_entities)
	{
		$string = trim(html_entity_decode($string) );
	}
	if (function_exists('mb_substr'))
	{
		if ($length != '')
		{
			$cut_string = mb_substr($string, $start, $length);
		}
		else
		{
			$cut_string = mb_substr($string, $start);
		}
	}
	else
	{
		if ($length != '')
		{
			$cut_string = substr($string, $start, $length);
		}
		else
		{
			$cut_string = substr($string, $start);
		}
	}
	if ($handle_entities)
	{
		$cut_string = htmspecialcharsuni($cut_string);
	}
	return $cut_string;
}

function apadana_strtolower($string)
{
	return function_exists('mb_strtolower')? mb_strtolower($string) : strtolower($string);
}

function apadana_strtoupper($string)
{
	return function_exists('mb_strtoupper')? mb_strtoupper($string) : strtoupper($string);
}

function apadana_strpos($haystack, $needle, $offset = 0)
{
	if ($needle == '')
	{
		return false;
	}
	return function_exists('mb_strpos')? mb_strpos($haystack, $needle, $offset) : strpos($haystack, $needle, $offset);
}

function apadana_stripos($haystack, $needle, $offset = 0)
{
	if ($needle == '')
	{
		return false;
	}
	return function_exists('mb_stripos')? mb_stripos($haystack, $needle, $offset) : stripos($haystack, $needle, $offset);
}

function htmspecialcharsuni($string)
{
	$string = preg_replace('#&(?!\#[0-9]+;)#si', '&amp;', $string); # Fix & but allow unicode
	$string = str_replace('<', '&lt;', $string);
	$string = str_replace('>', '&gt;', $string);
	$string = str_replace('"', '&quot;', $string);
	return $string;
}

function dec_to_utf8($src)
{
	$dest = '';
	if($src < 0)
	{
  		return false;
 	}
	elseif($src <= 0x007f)
	{
		$dest .= chr($src);
	}
	elseif($src <= 0x07ff)
	{
		$dest .= chr(0xc0 | ($src >> 6));
		$dest .= chr(0x80 | ($src & 0x003f));
	}
	elseif($src <= 0xffff)
	{
		$dest .= chr(0xe0 | ($src >> 12));
		$dest .= chr(0x80 | (($src >> 6) & 0x003f));
		$dest .= chr(0x80 | ($src & 0x003f));
	}
	elseif($src <= 0x10ffff)
	{
		$dest .= chr(0xf0 | ($src >> 18));
		$dest .= chr(0x80 | (($src >> 12) & 0x3f));
		$dest .= chr(0x80 | (($src >> 6) & 0x3f));
		$dest .= chr(0x80 | ($src & 0x3f));
	}
	else
	{
		# Out of range
		return false;
	}
	return $dest;
}
