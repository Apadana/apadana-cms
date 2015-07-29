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

class template
{
	public $tags = array();
	public $blocks = array();
	public $blocks_callback = array();
	public $foreach = array();
	public $base_dir = null;
	public $include = true;
	public $function = true;
	public $template = null;
	public $parsed = null;

	function __construct($templatefile = null, $base_dir = null, $global = true)
	{
		if (empty($base_dir) || !is_dir($base_dir))
		{
			$this->base_dir = root_dir;
		}
		else
		{
			$this->base_dir = $base_dir;
		}

		if (!empty($templatefile))
		{
			if (!file_exists($this->base_dir.$templatefile) || !is_readable($this->base_dir.$templatefile))
			{
				exit('Could not find the template file <b>'. str_replace(root_dir, null, $templatefile) .'</b>!');
			}
			$this->load($templatefile);
		}
		
		if ($global == true)
		{
			$this->assign(global_tags());
		}
		return TRUE;
	}

	function load($file, $type = null)
	{
		if (empty($file))
		{
			return FALSE;
		}

		if ($type == 'include')
		{
			$file = str_replace('..', null, $file);
			if (get_extension($file) == 'tpl' && file_exists($this->base_dir.$file) && is_readable($this->base_dir.$file))
			{
				$template = @file_get_contents($this->base_dir.$file);
				if ($template != FALSE)
				{
					if (strpos($template, '{include file=') !== FALSE)
					{
						$template = preg_replace_callback('#\\{include file=[\'"](.+?)[\'"]\\}#s', array($this, 'load_include_callback'), $template);
					}
					return $template;
				}
			}
			elseif (get_extension($file) == 'php')
			{
				exit('Hacking attempt!<hr>Include file: <b>'.str_replace(root_dir, null, $this->base_dir).$file.'</b>');
			}
			return '{include file="'.$file.'}';
		}
		
		$template = is_readable($this->base_dir.$file)? file_get_contents($this->base_dir.$file) : false;

		if ($template == false)
		{
			exit('Could not find the template file <b>'. str_replace(root_dir, null, $file) .'</b>!');
		}

		$this->template = $template;
		unset($template);
	}

	function get_function($name, $argumant)
	{
		$bad = array('rmdir', 'chmod', 'unlink', 'file_put_contents', 'file_get_contents', 'file', 'rename', 'eval');
		if (is_alphabet($name) && function_exists($name) && !in_array($name, $bad))
		{
			$args = array();
			if (!empty($argumant))
			{
				$argumant = explode('|', $argumant);
				$argumant = array_map('trim', $argumant);

				foreach($argumant as $arg)
				{
					if ($arg != '')
					{
						$arg2 = strtolower($arg);
						if (is_numeric($arg) || $arg2 == 'null' || $arg2 == 'true' || $arg2 == 'false' || substr($arg2, 0, 6) == 'array(')
						{
							$args[] = str_replace('\\\'', '\'', $arg);
						}
						else
						{
							$args[] = '\''.$arg.'\'';
						}
					}
				}

				$args = implode(', ', $args);
				ob_start();
				eval('echo '.$name.'('.$args.');');
				$content = ob_get_contents();
				ob_end_clean();
				unset($name, $args, $argumant, $arg, $arg2);
				return $content;
			}
		}
		else
		{
			return '<!-- Could not find the function "'.$name.'" -->';
		}
	}

	function assign($input, $value = null, $type = 'new')
	{
		if (is_array($input))
		{
			foreach($input as $tag => $value)
			{
				$this->assign($tag, $value, $type);
			}
		}
		elseif (is_string($input))
		{
			if (empty($input))
			{
				echo 'Tag name ist empty!';
				return FALSE;
			}
			else
			{
				$this->tags[$input] = isset($this->tags[$input]) && $type=='add'? $this->tags[$input].$value : $value;
			}
		}
		else
		{
			return FALSE;
		}
		return TRUE;
	}

	function block($pattern, $replacement)
	{
		if (!is_string($pattern) || empty($pattern))
		{
			echo 'Block pattern is not a string or is empty!';
			return FALSE;
		}
		if (!is_string($replacement))
		{
			echo 'Block replacement is not an string!';
			return FALSE;
		}
		$this->blocks[$pattern] = $replacement;
	}

	function block_callback($pattern, $callback, $data = null)
	{
		if (!is_string($pattern) || empty($pattern))
		{
			echo 'Block pattern is not a string or is empty!';
			return false;
		}

		if (empty($callback))
		{
			echo 'Block callback is a empty!';
			return false;
		}

		$this->blocks_callback[$pattern] = array($callback, $data);
	}

	function add_for($name, $array)
	{
		if (!is_string($name) || empty($array))
		{
			echo 'For name is not a string or is empty!';
			return FALSE;
		}
		if (!is_array($array))
		{
			echo 'For array is not an array!';
			return FALSE;
		}
		$this->foreach[$name][] = $array;
	}
	
	function parse()
	{
		if (empty($this->template))
		{
			return;
		}

		if ($this->include && strpos($this->template, '{include file=') !== FALSE)
		{
			$this->template = preg_replace_callback('#\\{include file=[\'"](.+?)[\'"]\\}#s', array($this, 'load_include_callback'), $this->template);
		}

		if (strpos($this->template, '{a href=') !== FALSE)
		{
			$this->template = preg_replace_callback('#\\{a href=[\'"](.+?)[\'"]\\}#s', create_function('$match', 'return url($match[1]);'), $this->template);
		}

		if (strpos($this->template, '[not-module=') !== false)
		{
			$this->template = preg_replace_callback('#\\[not-module=([a-zA-Z0-9-_]+)\\](.*?)\\[/not-module\\]#s', array($this, 'not_module_callback'), $this->template);
		}

		if (strpos($this->template, '[module=') !== false)
		{
			$this->template = preg_replace_callback('#\\[module=([a-zA-Z0-9-_]+)\\](.*?)\\[/module\\]#s', array($this, 'module_callback'), $this->template);
		}

		if (strpos($this->template, '[not-group=') !== false)
		{
			$this->template = preg_replace_callback('#\\[not-group=([0-9,]+)\\](.*?)\\[/not-group\\]#s', array($this, 'not_group_callback'), $this->template);
		}

		if (strpos($this->template, '[group=') !== false)
		{
			$this->template = preg_replace_callback('#\\[group=([0-9,]+)\\](.*?)\\[/group\\]#s',  array($this, 'group_callback'), $this->template);
		}

		if (strpos($this->template, '[not-page=') !== false)
		{
			$this->template = preg_replace_callback('#\\[not-page=([a-zA-Z0-9-_,]+)\\](.*?)\\[/not-page\\]#s', array($this, 'not_page_callback'), $this->template);
		}

		if (strpos($this->template, '[page=') !== false)
		{
			$this->template = preg_replace_callback('#\\[page=([a-zA-Z0-9-_,]+)\\](.*?)\\[/page\\]#s', array($this, 'page_callback'), $this->template);
		}

		if (strpos($this->template, '[member]') !== false)
		{
			$this->template = preg_replace('#\\[member\\](.*?)\\[/member\\]#s', defined('member') && member==1? '\\1' : null, $this->template);
		}

		if (strpos($this->template, '[admin]') !== false)
		{
			$this->template = preg_replace('#\\[admin\\](.*?)\\[/admin\\]#s', defined('group_admin') && group_admin==1? '\\1' : null, $this->template);
		}

		if (strpos($this->template, '[super-admin]') !== false)
		{
			$this->template = preg_replace('#\\[super-admin\\](.*?)\\[/super-admin\\]#s', defined('group_super_admin') && group_super_admin==1? '\\1' : null, $this->template);
		}

		if (strpos($this->template, '[ajax]') !== false)
		{
			$this->template = preg_replace('#\\[ajax\\](.*?)\\[/ajax\\]#s', is_ajax()? '\\1' : null, $this->template);
		}

		if (strpos($this->template, '[not-ajax]') !== false)
		{
			$this->template = preg_replace('#\\[not-ajax\\](.*?)\\[/not-ajax\\]#s', !is_ajax()? '\\1' : null, $this->template);
		}

		if ($this->function && strpos($this->template, '{function name=') !== FALSE)
		{
			$this->template = preg_replace_callback('#\\{function name=[\'"]([a-zA-Z0-9_]+)[\'"]( args=[\'"](.+?)[\'"])?\\}#s', array($this, 'get_function_callback'), $this->template);
		}

		# for
		foreach ($this->foreach as $for_name => $for_arrays)
		{
			if ($number = preg_match_all('#\\[for '. preg_quote($for_name, '/') .'\\](.*)\\[/for '. preg_quote($for_name, '/') .'\\]#sU', $this->template, $matches))
			{
				for ($i = 0; $i < $number; $i++)
				{
					$for_plus_definition = $matches[0][$i];
					$foreach = $matches[1][$i];

					$parsed_for = '';
					foreach ($for_arrays as $for_array)
					{
						$tmp = $foreach;
						if (isset($for_array['replace']) && is_array($for_array['replace']) && count($for_array['replace']))
						{
							$tmp = preg_replace(array_keys($for_array['replace']), array_values($for_array['replace']), $tmp);
							unset($for_array['replace']);
						}

						if (isset($for_array['callback']) && is_array($for_array['callback']) && count($for_array['callback']))
						{
							$tmp = preg_replace_callback(array_keys($for_array['callback']), array_values($for_array['callback']), $tmp);
							unset($for_array['callback']);
						}

						if (is_array($for_array) && count($for_array))
						{
							$tmp = str_replace(array_keys($for_array), array_values($for_array), $tmp);
						}
						$parsed_for .= $tmp;
					}
					$this->template = str_replace($for_plus_definition, $parsed_for, $this->template);
					unset($for_plus_definition, $parsed_for, $foreach, $for_array, $tmp);
				}
			}
		}
		
		# delete fors
		$this->template = preg_replace('#\\[for ([a-zA-Z0-9_-]+)\\].*\\[/for \\1\\](\r\n|\r|\n)?#msU', null, $this->template);
		
		# content tag
		if (strpos($this->template, '{content}') !== FALSE) 
		{
			if (isset($this->tags['{content}']))
			{
			    $this->template = str_replace('{content}', $this->tags['{content}'], $this->template);	
			}
			unset($this->tags['{content}']);
		}
		
		# blocks
		if (is_array($this->blocks) && count($this->blocks))
		{
			$this->template = preg_replace(array_keys($this->blocks), array_values($this->blocks), $this->template);
		}

		# blocks callback
		if (is_array($this->blocks_callback) && count($this->blocks_callback))
		{
			foreach ($this->blocks_callback as $pattern => $item)
			{
				$GLOBALS['template_callback_data'] = $item[1];
				$this->template = preg_replace_callback($pattern, $item[0], $this->template);
			}
			unset($pattern, $item, $GLOBALS['template_callback_data']);
		}

		# tags
		if (is_array($this->tags) && count($this->tags))
		{
			$this->template = str_replace(array_keys($this->tags), array_values($this->tags), $this->template);
		}

		$this->parsed = $this->template;
		$this->template = null;
	}

	function check_module($name, $block, $action = true)
	{
		global $modules;
		static $mods;

		if (!isset($modules) || !is_array($modules))
		{
			return null;
		}

		if (!isset($mods))
		{
			$mods = array();

			foreach ($modules as $mod)
			{
				if ($mod['module_status'] == 0)
				{
					continue;
				}

				$mods[] = $mod['module_name'];
			}
			unset($mod);
		}

		if ($action)
		{
			if (in_array($name, $mods) !== true) return null;
		}
		else
		{
			if (in_array($name, $mods) === true) return null;
		}

		return $block;
	}
	
	function check_group($groups, $block, $action = true)
	{
		$groups = explode(',', $groups);

		if ($action)
		{
			if (!defined('member_group') || !in_array(member_group, $groups)) return null;
		}
		else
		{
			if (defined('member_group') && in_array(member_group, $groups)) return null;
		}

		return $block;
	}

	function check_page($pages, $block, $action = true)
	{
		$pages = explode(',', $pages);

		if ($action)
		{
			if (!in_array($_GET['a'], $pages)) return null;
		}
		else
		{
			if (in_array($_GET['a'], $pages)) return null;
		}

		return $block;
	}

	function display()
	{
		if (!empty($this->template))
		{
			$this->parse();
		}
		echo $this->parsed;
	}

	function get_var()
	{
		if (!empty($this->template))
		{
			$this->parse();
		}
		return $this->parsed;
	}

	function free()
	{
		$this->template = null;
		$this->parsed = null;
		$this->tags = array();
		$this->blocks = array();
	}

	# callbacks
	function get_function_callback($matches)
	{
		return $this->get_function($matches[1], $matches[3]);
	}

	function load_include_callback($matches)
	{
		return $this->load($matches[1], 'include');
	}

	function not_module_callback($matches)
	{
		return $this->check_module($matches[1], $matches[2], false);
	}

	function module_callback($matches)
	{
		return $this->check_module($matches[1], $matches[2]);
	}

	function not_group_callback($matches)
	{
		return $this->check_group($matches[1], $matches[2], false);
	}

	function group_callback($matches)
	{
		return $this->check_group($matches[1], $matches[2]);
	}

	function not_page_callback($matches)
	{
		return $this->check_page($matches[1], $matches[2], false);
	}

	function page_callback($matches)
	{
		return $this->check_page($matches[1], $matches[2]);
	}
}
