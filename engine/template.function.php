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

function template_info($theme)
{
	static $save;
	$array = array();
	$theme = alphabet($theme);

	if (isset($save[$theme]) && is_array($save[$theme]) && count($save[$theme]))
		return $save[$theme];

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
	$array['creationDate'] = !isset($array['creationDate']) || empty($array['creationDate'])? date('Y-m-d H:i:s') : nohtml($array['creationDate']);
	$array['description'] = !isset($array['description']) || empty($array['description'])? null : $array['description'];
	$array['screenshot'] = !isset($array['screenshot']) || empty($array['screenshot'])? null : nohtml($array['screenshot']);
	$array['author'] = !isset($array['author']) || empty($array['author'])? null : $array['author'];
	$array['authorEmail'] = !isset($array['authorEmail']) || !validate_email($array['authorEmail'])? null : $array['authorEmail'];
	$array['authorUrl'] = !isset($array['authorUrl']) || !validate_url($array['authorUrl'])? null : $array['authorUrl'];
	$array['positions'] = !isset($array['positions']) || empty($array['positions'])? null : nohtml($array['positions']);
	$array['pages'] = !isset($array['pages']) || empty($array['pages'])? null : nohtml($array['pages']);
	$array['html-compression'] = !isset($data['html-compression']) || empty($data['html-compression'])? false : ($data['html-compression'] == 'true'? true : false);
	$array['license'] = !isset($array['license']) || empty($array['license'])? 'GNU/GPL' : $array['license'];

	$array['positions'] = explode(',', $array['positions']);
	$array['positions'] = array_map('trim', $array['positions']);
	$array['positions'] = implode(',', $array['positions']);

	$array['pages'] = explode(',', $array['pages']);
	$array['pages'] = array_map('trim', $array['pages']);
	$array['pages'] = implode(',', $array['pages']);

	return $save[$theme] = $array;
}

function head()
{
	global $options, $page;

	$Header  = '<title>'.$page['title'].'</title>'.n;
	$Header .= '<base href="'.url.'" />'.n;
	$Header .= '<meta http-equiv="content-type" content="text/html; charset='.charset.'" />'.n;
	$Header .= '<meta name="author" content="'.$options['title'].'" />'.n;
	$Header .= base64_decode('PG1ldGEgbmFtZT0iZ2VuZXJhdG9yIiBjb250ZW50PSJBcGFkYW5hIENtcyBDb3B5cmlnaHQgwqkg') . date('Y') . base64_decode('Ij4=').n;
	$Header .= '<meta name="description" content="'.$page['meta']['description'].'" />'.n;
	$Header .= '<meta name="keywords" content="'.$page['meta']['keywords'].'" />'.n;
	$Header .= '<meta name="distribution" content="global" />'.n;
	$Header .= '<meta name="robots" content="'.(!empty($page['meta']['robots'])? $page['meta']['robots'] : 'index, follow').'" />'.n;
	$Header .= '<meta name="revisit-after" content="1 days" />'.n;
	$Header .= '<meta name="rating" content="general" />'.n;

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

	$Header .= '<link rel="start" href="'.url.'" title="Home" />'.n;
	$Header .= '<link rel="sitemap" href="'.url.($options['rewrite'] == 1? 'sitemap.xml' : '?a=sitemap').'" />'.n;
	$Header .= '<link rel="search" type="application/opensearchdescription+xml" href="'.url('search/opensearch').'" title="'.$options['title'].'" />'.n;
	$Header .= '<link rel="alternate" type="application/rss+xml" href="'.url('feed/posts/rss').'" title="'.$options['title'].'" />'.n;
	$Header .= is_array($page['script']) && count($page['script'])? implode(n, $page['script']).n : null;
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
	global $options, $member;

	$member = member::is('info');
	return array(
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

    @Header('Content-type: text/html; charset='.charset);
	$tpl = get_tpl(engine_dir.'templates/||warning.tpl', template_dir.'||warning.tpl');
	$tpl = new template($tpl[1], $tpl[0]);
	$tpl->assign(array(
		'{title}' => $title,
		'{message}' => $message
	));
	$tpl->display();
	gzip_out();
}

function message($message, $type = 'info', $go_back = false)
{
	switch ($type) 
	{
		case 'error':
		$class = 'error';
		break;

		case 'success':
		$class = 'success';
		break;

		default:
		$class = 'info';
		break;
	}

	$html  = '<div class="apadana-message-'.$class.'"><span>'.$message;

	if ($go_back)
	{
		$html .= '&nbsp;&nbsp;[<a href="javascript:history.go(-1);" target="_self" title="Go Back!" class="apadana-go-back">بازگشت به صفحه قبل</a>] ';
	}

	$html .= '</span></div>';
	return $html;
}

function odd_even($even = 'apadana-even', $odd = 'apadana-odd')
{
    static $save;

    $save = (!$save || $save == $odd? $even : $odd);
    return $save;
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

	if ($code == '')
	{
		return false;
	}

	$code = trim($code);
	if(!in_array($code, $page['head']))
	{
		$page['head'][] = $code;
	}
}

/**
* Set site head script
*
* Add a script tag to the head tag with parameter you set.
*
* @since 1.1
*
* @param string $name An identifier for the script to handle it easily for example unset it throw modules etc.
* @param string $type The type of script to show
* @param string $src The source of script file.
*
* @return bool False if $src is empty
*/
function set_head_script($name = null, $type="type/javascript" , $src = null)
{
	global $page;

	if (!empty($src) && is_string($src))
	{
		if(!empty($name))
			$page['script'][] = '<script type="'.$type.'" src="'.$src.'">';
		else
			$page['script'][$name] = '<script type="'.$type.'" src="'.$src.'">';

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

?>