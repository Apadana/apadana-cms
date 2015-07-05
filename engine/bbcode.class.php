<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2013 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
**/

defined('security') or exit('Direct Access to this location is not allowed.');

class bbcode
{
	private static $simple_search = array(
		'/\[b\](.*?)\[\/b\]/is',
		'/\[i\](.*?)\[\/i\]/is',
		'/\[u\](.*?)\[\/u\]/is',
		'/\[s\](.*?)\[\/s\]/is',
		'/\[size\=(.*?)\](.*?)\[\/size\]/is',
		'/\[color\=(.*?)\](.*?)\[\/color\]/is',
		'/\[center\](.*?)\[\/center\]/is',
		'/\[font\=(.*?)\](.*?)\[\/font\]/is',
		'/\[align\=(left|center|right)\](.*?)\[\/align\]/is',
	
		'/\[url\](.*?)\[\/url\]/is',
		'/\[url\=(.*?)\](.*?)\[\/url\]/is',
		'/\[mail\=(.*?)\](.*?)\[\/mail\]/is',
		'/\[mail\](.*?)\[\/mail\]/is',
		'/\[img\](.*?)\[\/img\]/is',
		'/\[img\=(\d*?)x(\d*?)\](.*?)\[\/img\]/is',

		'/\[quote\](.*?)\[\/quote\]/is',
		'/\[quote\=(.*?)\](.*?)\[\/quote\]/is',
		'/\[code\](.*?)\[\/code\]/is',
	
		'/\[sub\](.*?)\[\/sub\]/is',
		'/\[sup\](.*?)\[\/sup\]/is',
		'/\[p\](.*?)\[\/p\]/is',
	
		"/\[youtube\](.*?)\[\/youtube\]/i",
		"/\[gvideo\](.*?)\[\/gvideo\]/i",
	
		// "Specials", XHTML-like BBC Repository
		'/\[bull \/\]/i',
		'/\[copyright \/\]/i',
		'/\[registered \/\]/i',
		'/\[tm \/\]/i',
	);
  
	private static $simple_replace = array(
		'<strong>$1</strong>',
		'<em>$1</em>',
		'<u>$1</u>',		  // you can also use: '<span style="text-decoration: underline;">$1</span>'
		'<del>$1</del>',	  // you can also use: '<span style="text-decoration: line-through;">$1</span>'
		'<span style="font-size: $1;">$2</span>',
		'<span style="color: $1;">$2</span>',
		'<div style="text-align: center;">$1</div>',
		'<span style="font-family: $1;">$2</span>',
		'<div style="text-align: $1;">$2</div>',
	
		'<a href="$1">$1</a>',
		'<a href="$1">$2</a>',
		'<a href="mailto:$1">$2</a>',
		'<a href="mailto:$1">$1</a>',
		'<img src="$1" alt="" />',
		'<img height="$2" width="$1" alt="" src="$3" />',

		'<blockquote>$1</blockquote>',							  // you can also use: '<div class="quote">$1</div>'
		'<blockquote><strong>$1 wrote:</strong> $2</blockquote>',   // you can also use: '<div class="quote"><strong>$1 wrote:</strong> $2</div>
		'<pre>$1</pre>',
	
		'<sub>$1</sub>',
		'<sup>$1</sup>',
		'<p>$1</p>',
	
		"<object width=\"425\" height=\"350\"><embed src=\"http://www.youtube.com/v/$1\" type=\"application/x-shockwave-flash\" width=\"425\" height=\"350\"></embed></object>",
		"<embed src=\"http://video.google.com/googleplayer.swf?docId=$1\" type=\"application/x-shockwave-flash\" style=\"width: 425px; height: 350px;\">",
	
		// "Specials", XHTML-like BBC Repository
		'&bull;',
		'&copy;',
		'&reg;',
		'&trade;',
	);
  
  private static $lineBreaks_search = array(
		// [list]
		//'/\<br \/\>\s*\[list(.*?)\]/i',	 // uncomment to remove <br /> before the tag
		'/\[list(.*?)\](.+?)\[\/list\]/sie',
		'/\[\/list\]\s*\<br \/\>/i',
	  
		// [code]
		//'/\<br \/\>\s*\[code\]/i',		  // uncomment to remove <br /> before the tag
		'/\[code\](.+?)\[\/code\]/sie',
		'/\[\/code\]\s*\<br \/\>/i',
	  
		// [quote]
		//'/\<br \/\>\s*\[quote(.*?)\]/i',	// uncomment to remove <br /> before the tag
		'/\[\/quote\]\s*\<br \/\>/i',
	  
		// [p]
		//'/\<br \/\>\s*\[p\]/i',			 // uncomment to remove <br /> before the tag
		'/\[\/p\]\s*\<br \/\>/i',
	  
		// [center]
		//'/\<br \/\>\s*\[center\]/i',		// uncomment to remove <br /> before the tag
		'/\[\/center\]\s*\<br \/\>/i',
	  
		// [align]
		//'/\<br \/\>\s*\[align(.*?)\]/i',	// uncomment to remove <br /> before the tag
		'/\[\/align\]\s*\<br \/\>/i',
	);
	
	private static $lineBreaks_replace = array(
		// [list]
		//"\n[list$1]",		 // uncomment to remove <br /> before the tag
		"'[list$1]'.str_replace('<br />', '', '$2').'[/list]'",
		"[/list]",
	  
		// [code]
		//"\n[code]",		   // uncomment to remove <br /> before the tag
		"'[code]'.str_replace('<br />', '', '$1').'[/code]'",
		"[/code]",
	  
		// [quote]
		//"\n[quote$1]",		// uncomment to remove <br /> before the tag
		"[/quote]",
	  
		// [p]
		//"\n[p]",			  // uncomment to remove <br /> before the tag
		"[/p]",
	  
		// [center]
		//"\n[center]",		 // uncomment to remove <br /> before the tag
		"[/center]",

		// [align]
		//"\n[align$1]",		// uncomment to remove <br /> before the tag
		"[/align]",
	);

	private static function process_list_items($list_items)
	{
		$result_list_items = array();
	
		// Check for [li][/li] tags
		preg_match_all("/\[li\](.*?)\[\/li\]/is", $list_items, $li_array);
		$li_array = $li_array[1];
	
		if (empty($li_array))
		{
			// we didn't find any [li] tags
			$list_items_array = explode("[*]", $list_items);
			foreach ($list_items_array as $li_text)
			{
				$li_text = trim($li_text);
				if (empty($li_text))
					continue;
				
				$li_text = nl2br($li_text);
				$result_list_items[] = '<li>'.$li_text.'</li>';
			}
		}
		else
		{
			// we found [li] tags!
			foreach ($li_array as $li_text)
			{
				$li_text = nl2br($li_text);
				$result_list_items[] = '<li>'.$li_text.'</li>';
			}
		}
	
		$list_items = implode("\n", $result_list_items);
	
		return $list_items;
	}

	public static function parse($string)
	{
		$s = (string) $string;
	
		if (empty($s))
			return null;
	
		// Preappend http:// to url address if not present
		$s = preg_replace('/\[url\=([^(http)].+?)\](.*?)\[\/url\]/i', '[url=http://$1]$2[/url]', $s);
		$s = preg_replace('/\[url\]([^(http)].+?)\[\/url\]/i', '[url=http://$1]$1[/url]', $s);
	
		// Add line breaks
		$s = nl2br($s);
	
		// Remove the trash made by previous 
		$s = preg_replace(self::$lineBreaks_search, self::$lineBreaks_replace, $s);
	
		// Parse bbcode
		$s = preg_replace(self::$simple_search, self::$simple_replace, $s);

		// Parse [list] tags
		$s = preg_replace('/\[list\](.*?)\[\/list\]/sie', '"<ul>\n".self::process_list_items("$1")."\n</ul>"', $s);
		$s = preg_replace(
			'/\[list\=(disc|circle|square|decimal|decimal-leading-zero|lower-roman|upper-roman|lower-greek|lower-alpha|lower-latin|upper-alpha|upper-latin|hebrew|armenian|georgian|cjk-ideographic|hiragana|katakana|hiragana-iroha|katakana-iroha|none)\](.*?)\[\/list\]/sie',
			'"<ol style=\"list-style-type: $1;\">\n".self::process_list_items("$2")."\n</ol>"', 
			$s
		);

		$s = smiles_replace($s);
		
		if (strpos($s, '[php]') !== false)
		{
			require_once engine_dir.'phphighlight/PHP_Highlight.php';
			preg_match_all("#\[php\](.*?)\[/php\]#si",$s,$matches,PREG_PATTERN_ORDER);
			for ($i=0; $i<count($matches[0]); $i++)
			{
				$input = str_replace('<br>','',str_replace('<br  />','', str_replace('<br />', '', stripslashes($matches[1][$i]))));
				$search = array("\\", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", "&amp;");
				$replace = array("\\\\", "\"", "'", "\\", "\"", "\'", "<", ">", "&");
				$input = str_replace($search,$replace, $input);
				$start_php = !preg_match("/<\?php/i", $input)?"<?php\n":"";
				$end_php = !preg_match("/\?>/i", $input)?"\n?>":"";
				$h = new PHP_Highlight;
				$h->loadString($start_php.$input.$end_php);
				$parsed = $h->toList(true, true, false);

				$text2 = "<div class='apadana-bbcode-php-title' style='width:400px'><strong>کد PHP</strong></div><div class='apadana-bbcode-php-content' style='width:400px;height:auto;white-space:nowrap;overflow:auto;background-color:#ffffff;direction:ltr;text-align:left'>".$parsed."</div>";
				$s = str_replace($matches[0][$i], $text2, $s);
				$s = str_replace("<ol>\n", "<ol>", $s);
				$s = str_replace("</li>\n", "</li>", $s);
				$s = str_replace("</ol>\n", "</ol>", $s);
			}
			unset($i, $matches, $search, $replace, $input, $h, $parsed, $text2);
		}

		return $s;
	}
}

?>