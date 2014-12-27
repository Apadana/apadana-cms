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
	$array['compaction'] = !isset($array['compaction']) || empty($array['compaction'])? false : ($array['compaction']=='true'? true : false);
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
	$Header .= base64_decode('PG1ldGEgbmFtZT0iZ2VuZXJhdG9yIiBjb250ZW50PSJBcGFkYW5hIENtcyBDb3B5cmlnaHQgwqkg') . date('Y') . base64_decode('IGJ5IEltYW4gTW9vZGkgKHd3dy5hcGFkYW5hY21zLmlyKSIgLz4=').n;
	$Header .= '<meta name="description" content="'.$page['meta']['description'].'" />'.n;
	$Header .= '<meta name="keywords" content="'.$page['meta']['keywords'].'" />'.n;
	$Header .= '<meta name="distribution" content="global" />'.n;
	$Header .= '<meta name="robots" content="'.(!empty($page['meta']['robots'])? $page['meta']['robots'] : 'index, follow').'" />'.n;
	$Header .= '<meta name="revisit-after" content="1 days" />'.n;
	$Header .= '<meta name="rating" content="general" />'.n;

    if (($_GET['a'] == $options['default-module'] && (!isset($_SERVER['QUERY_STRING']) || empty($_SERVER['QUERY_STRING']))) || (!isset($_SERVER['QUERY_STRING']) || empty($_SERVER['QUERY_STRING'])))
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
	$Header .= '<script type="text/javascript" src="'.url.'engine/javascript/jquery.js"></script>'. n;
	$Header .= '<script type="text/javascript" src="'.url.'engine/javascript/core.js"></script>'. n;
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
		'{today}' => jdate('l j F Y ساعت g:i A', time_now, 1),
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