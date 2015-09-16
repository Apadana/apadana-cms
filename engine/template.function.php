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

function template_off($string, $simple = false)
{
	if (empty($string))
	{
		return $string;
	}
	if ($simple)
	{
		$string = str_replace('[', '&#x5B;', $string);
		$string = str_replace('{', '&#x7B;', $string);
	}
	else
	{
		$array = array();
		if ($number = preg_match_all('/<(script|style)(.*)>(.*)<\/\\1>/sUi', $string, $matches))
		{
			for ( $i = 0; $i < $number; $i++ )
			{
				if (!empty($matches[0][$i]))
				{
					$key = rand(11111111, 99999999).generate_password(20, null).rand(11111111, 99999999);
					$array[$key] = $matches[0][$i];
					$string = str_replace($matches[0][$i], '<~#code:'.$key.':code#~>', $string);
				}
			}
		}
		if (strpos($string, '[') !== FALSE)
		{
			$string = preg_replace('#\\[([a-zA-Z0-9-_]+)\\](.*?)\\[/\\1\\]#s', '&#x5B;\\1]\\2&#x5B;/\\1]', $string);
		}
		if (strpos($string, '[') !== FALSE)
		{
			$string = preg_replace('#\\[/([a-zA-Z0-9-_]+)\\]#s', '&#x5B;/\\1]', $string);
		}
		if (strpos($string, '{') !== FALSE)
		{
			$string = preg_replace('#\\{([a-zA-Z0-9-_]+)\\}#s', '&#x7B;\\1}', $string);
		}
		foreach ($array as $key => $code)
		{
			$string = str_replace('<~#code:'.$key.':code#~>', $code, $string);
		}
		unset($array, $key, $code, $matches, $number);
	}
	return $string;	
}

function template_exists($theme)
{
	if (!is_alphabet($theme) || !is_dir(root_dir.'templates') || !is_readable(root_dir.'templates'))
	{
		return -1;
	}
	elseif (file_exists(root_dir.'templates/'.$theme.'/body.tpl') && file_exists(root_dir.'templates/'.$theme.'/content.tpl') && file_exists(root_dir.'templates/'.$theme.'/post.tpl') && file_exists(root_dir.'templates/'.$theme.'/comments.tpl') && file_exists(root_dir.'templates/'.$theme.'/styles/engine.css'))
	{
		return true;
	}
	return false;
}

function get_templates($form)
{
	$handle = opendir((root_dir.'templates/'));

	if($handle){

		$temps = array();

		while (false !== ($dir = readdir($handle))) {
			$dir = trim($dir);
			if( (is_dir(root_dir.'templates/'.$dir)) && $dir != '.' && $dir != '..' && template_exists($dir))
				$temps[$dir] = $dir;
		}

		if($form == false)
			return $temps;

		global $options;
		return '<form method="GET" action="" />' . html::select( 'theme', $temps , $options['theme'] , 'onchange="submit()"' ) . '</form>';
	}
	return false;
}

function template_info($theme)
{
	static $ap_template_info;
	$array = array();
	$theme = alphabet($theme);

	if (isset($ap_template_info[$theme]) && is_array($ap_template_info[$theme]) && count($ap_template_info[$theme]))
		return $ap_template_info[$theme];

	if (!template_exists($theme))
		return false;

	if (file_exists(root_dir.'templates/'.$theme.'/styles/engine.css'))
	{
		$file = file_get_contents(root_dir.'templates/'.$theme.'/styles/engine.css');
		if (!empty($file))
		{
			preg_match_all('|/\*\*(.*)\*/|is', $file, $a);
			if (isset($a[1][0]) && !empty($a[1][0]))
			{
				preg_match_all('~\*\s{0,}@\s{0,}([a-zA-Z0-9_-]+)\s{0,}:(.*)\s{0,}\$~isU', $a[1][0], $b);
				if (isset($b[1]) && is_array($b[1]) && count($b[1]))
				{
					foreach ($b[1] as $i => $c)
					{
						$array[trim($c)] = trim($b[2][$i]);
					}
				}
			}
		}
		unset($file, $a, $b, $c, $i);
	}

	$array['name'] = !isset($array['name']) || empty($array['name'])? $theme : $array['name'];
	$array['version'] = !isset($array['version']) || empty($array['version'])? '1.0' : nohtml($array['version']);
	$array['compatibility'] = !isset($array['compatibility']) || empty($array['compatibility'])? '*' : trim($array['compatibility']);
	$array['creation-date'] = !isset($array['creation-date']) || empty($array['creation-date'])? date('Y-m-d H:i:s') : nohtml($array['creation-date']);
	$array['description'] = !isset($array['description']) || empty($array['description'])? null : $array['description'];
	$array['screenshot'] = !isset($array['screenshot']) || empty($array['screenshot'])? null : nohtml($array['screenshot']);
	$array['author'] = !isset($array['author']) || empty($array['author'])? null : $array['author'];
	$array['author-email'] = !isset($array['author-email']) || !validate_email($array['author-email'])? null : $array['author-email'];
	$array['author-url'] = !isset($array['author-url']) || !validate_url($array['author-url'])? null : $array['author-url'];
	$array['positions'] = !isset($array['positions']) || empty($array['positions'])? null : nohtml($array['positions']);
	$array['pages'] = !isset($array['pages']) || empty($array['pages'])? null : nohtml($array['pages']);
	$array['html-compression'] = !isset($array['html-compression']) || empty($array['html-compression'])? false : ($array['html-compression'] == 'true'? true : false);
	$array['license'] = !isset($array['license']) || empty($array['license'])? 'GNU/GPL' : $array['license'];

	$array['positions'] = explode(',', $array['positions']);
	$array['positions'] = array_map('trim', $array['positions']);
	$array['positions'] = implode(',', $array['positions']);

	$array['pages'] = explode(',', $array['pages']);
	$array['pages'] = array_map('trim', $array['pages']);
	$array['pages'] = implode(',', $array['pages']);

	return $ap_template_info[$theme] = $array;
}

function head()
{
	global $options, $page;

	$Header  = '<title>'.$page['title'].'</title>'.n;
	#$Header .= '<base href="'.url.'" />'.n;
	$Header .= '<meta http-equiv="content-type" content="text/html; charset='.charset.'" />'.n;
	$Header .= base64_decode('PG1ldGEgbmFtZT0iZ2VuZXJhdG9yIiBjb250ZW50PSJBcGFkYW5hIENtcyBDb3B5cmlnaHQgwqkg') . date('Y') . base64_decode('Ij4=').n;

	foreach($page['meta'] as $name => $content){
		$Header .= '<meta name="'. $name .'" content="'.$content.'" />'.n;
	}

    if (($_GET['a'] == $options['default-module'] && (!isset($_GET) || empty($_GET))) || (!isset($_GET) || empty($_GET)))
	{
		$Header .= '<link rel="canonical" href="'.url.'" />'. n;
	}
    elseif (!empty($page['canonical']))
	{
		$Header .= '<link rel="canonical" href="'.$page['canonical'].'" />'. n;
	}

	if (file_exists(template_dir.'favicon.ico'))
	{
		$Header .= '<link rel="shortcut icon" type="image/x-icon" href="'.url.'/templates/'.$options['theme'].'/favicon.ico" />'.n;
	}
	else
	{
		$Header .= '<link rel="shortcut icon" type="image/x-icon" href="'.url.'favicon.ico" />'.n;
	}

	$Header .= is_array($page['links']) && count($page['links'])? implode(n, $page['links']).n : null;

	$Header .= is_array($page['scripts']) && count($page['scripts'])? implode(n, $page['scripts']).n : null;

	$Header .= '<script type="text/javascript">apadana.site={\'domain\':\''.domain.'\',\'path\':\''.path.'\',\'url\':\''.url.'\'}</script>'. n;

	$Header .= is_array($page['head']) && count($page['head'])? implode(n, $page['head']).n : null;

    return trim($Header);
}

function set_content($title, $content, $type = 'add')
{
	global $tpl, $options;
	
	if (!admin_page)
		$itpl = new template('content.tpl', root_dir.'templates/'.$options['theme'].'/');
	else
		$itpl = new template('content.tpl', root_dir.'engine/admin/template/');
	
	if (!empty($title))
	{
		$itpl->block('#\\[not-title\\](.*?)\\[/not-title\\]#s', '');
		$itpl->assign(array(
			'[title]' => null,
			'[/title]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[title\\](.*?)\\[/title\\]#s', '');
		$itpl->assign(array(
			'[not-title]' => null,
			'[/not-title]' => null,
		));
	}
	
	$array['{title}'] = $title;
	$array['{content}'] = $content;
	
	$itpl->assign($array);
	$tpl->assign('{content}', $itpl->get_var(), $type);
	unset($itpl, $array);
}

function get_tpl($default, $custom)
{
	global $options;

	$custom = explode('||', $custom);
	if (file_exists($custom[0].$custom[1]))
	{
		return array_merge($custom, array('custom'));
	}
	else
	{
		return explode('||', $default);
	}
}

function global_tags()
{
	global $cache;

	//with this cache this function runs 6 times faster and Apadana runs 10 percent faster!!
	if(isset($cache['global_tags']) && is_array($cache['global_tags'])) return $cache['global_tags'];

	global $options, $member;

	$member = member::is('info');
	return $cache['global_tags'] = array(
		'{member-name}' => defined('member') && member? $member['member_name'] : 'مهمان',
		'{member-ip}' => get_ip(),
		'{today}' => jdate('l j F Y ساعت g:i A', time_now, 'fa'),
		'{site-url}' => url,
		'{site-title}' => $options['title'],
		'{site-slogan}' => $options['slogan'],
		'{template}' => url.'templates/'.$options['theme'].'/',
		'{description}' => $options['meta-desc'],
		'{keywords}' => $options['meta-keys'],
		'{admin-page}' => defined('group_admin') && defined('admin_page') && (group_admin || admin_page)? url.'?admin='.$options['admin'] : '#ADMIN',
	);
}

function warning($title, $message)
{
	global $options;

	header('Content-type: text/html; charset='.charset);
	$tpl = get_tpl(engine_dir.'templates/||warning.tpl', template_dir.'||warning.tpl');
	$tpl = new template($tpl[1], $tpl[0]);
	$tpl->assign(array(
		'{title}' => $title,
		'{message}' => $message
	));
	$tpl->display();
	gzip_out();
}

function message($message, $type = 'info')
{
	($hook = get_hook('message'))? eval($hook) : null;

	return '<div class="apadana-message-'.$type.'"><div class="apadana-message-body">'.$message.'</div></div>';
}

function odd_even($even = 'apadana-even', $odd = 'apadana-odd', $start = null)
{
    static $number;

	($hook = get_hook('odd_even'))? eval($hook) : null;

	if ($start !== null && (!isset($number) || $number != $start))
	{
		$number = intval($start);
	}
	elseif (!isset($number))
	{
		$number = 0;
	}

	$number++;

    return $number%2 == 0? $even : $odd;
}

function set_title($title, $type = 'add')
{
	global $page;

	if ($title == '')
	{
		return false;
	}

	$page['title'] = $type == 'add'? $page['title'].' &bull; '.$title : $title;
}

function set_meta($name, $content, $type = 'new')
{
	global $page;

	if ($content == '')
	{
		return false;
	}

	$page['meta'][$name] = $type == 'add'? $page['meta'][$name].($name == 'keywords'? ', ' : ' - ').$content : $content;
}

function set_canonical($url)
{
	global $page;

	$page['canonical'] = $url;
}

function set_head($code)
{
	global $page;

	$code = trim($code);

	if ($code == '')
	{
		return false;
	}

	if(!in_array($code, $page['head']))
	{
		$page['head'][] = $code;
	}
}

function set_foot($code)
{
	global $page;

	$code = trim($code);

	if ($code == '')
	{
		return false;
	}

	if (!in_array($code, $page['foot']))
	{
		$page['foot'][] = $code;
	}
}
/**
* Set site script
*
* Add a link tag to page with parameter you set.
*
* @since 1.1
*
* @param string $name An identifier for the script to handle it easily for example unset it throw modules etc.
* @param string $type The type of script to show
* @param string $src The source of script file.
*
* @return bool False if $src is empty
*/
function set_script($name = null, $src ,$type="text/javascript" )
{
	global $page;

	if (!empty($src) && is_string($src))
	{
		if(empty($name))
			$page['scripts'][] = '<script type="'.$type.'" src="'.$src.'" ></script>';
		else
			$page['scripts'][$name] = '<script type="'.$type.'" src="'.$src.'" ></script>';

		return true;
	}
	else
	{
		return false;
	}

}
/**
* Set site links
*
* Add a link tag to page with parameter you set.
*
* @since 1.1
*
* @param string $name An identifier for the link to handle it easily for example unset it throw modules etc.
* @param string $rel The rel of link to show
* @param string $type The type of link to show
* @param string $href The source of link file
* @param string $title The title of link
*
* @return bool False if $href is empty
*/
function set_link($name = null, $href , $rel = "stylesheet" ,$type="text/css" , $title = null)
{
	global $page;

	if (!empty($href) && is_string($href))
	{
		$rel = empty($rel) ? null : 'rel="'.$rel.'" ';
		$type = empty($type) ? null : 'type="'.$type.'" ';
		$href = empty($href) ? null : 'href="'.$href.'" ';
		$title = empty($title) ? null : 'title="'.$title.'"';

		$link = '<link ' . $rel . $type . $href . $title . ' />';
		if(empty($name))
			$page['links'][] = $link;
		else
			$page['links'][$name] = $link;

		return true;
	}
	else
	{
		return false;
	}

}

function set_theme($file)
{
	global $page;

	if (!is_alphabet($file))
	{
		return false;
	}

	$page['theme'] = $file;
}
