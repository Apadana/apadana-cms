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

function blocks()
{
	global $tpl, $cache, $options;

	if (!isset($cache['blocks']) || !is_array($cache['blocks']) || !count($cache['blocks']))
	{
		if (!$cache['blocks'] = get_cache('blocks'))
		{
			global $d;
			$cache['blocks'] = array();
			$d->query("SELECT * FROM `#__blocks` WHERE `block_active`='1' ORDER BY `block_position`, `block_ordering` ASC");
			while ($row = $d->fetch())
			{
				$cache['blocks'][ $row['block_position'] ][ $row['block_ordering'] ] = $row;
			}
			$d->freeResult();			
			set_cache('blocks', $cache['blocks']);
		}
	}

	$positions = template_info($options['theme']);
	$positions = explode(',', $positions['positions']);

	if (!isset($cache['blocks']) || !is_array($cache['blocks']) || !count($cache['blocks']) || !is_array($positions) || !count($positions))
	{
		return null;
	}
	
	$current = current_url();
	$parse = parse_url($current);
	$strrpos = apadana_strpos($current, path, $parse['scheme'] == 'https'? 8 : 7);
	$strlen = apadana_strlen(path);
	$current = apadana_substr($current, $strrpos + $strlen);
	$current = trim(urldecode($current), '/');

	foreach($positions as $position)
	{
		if (!isset($cache['blocks'][$position]) || !is_array($cache['blocks'][$position]) || !count($cache['blocks'][$position])) continue;

		ksort($cache['blocks'][$position]);
		foreach($cache['blocks'][$position] as $block)
		{
			if ($block['block_view'] == 2 && !member) continue;
			if ($block['block_view'] == 3 && member) continue;
			if ($block['block_view'] == 4 && !group_admin) continue;
			if ($block['block_view'] == 5 && !group_super_admin) continue;
			
			if ($block['block_access'] != '')
			{				
				$match = false;
				$block['block_access'] = explode("\n", $block['block_access']);
				if (count($block['block_access']))
				{
					foreach($block['block_access'] as $p)
					{
						if ($p == '/' && home == true)
						{
							$match = true;
							break;
						}
						
						$pattern = apadana_substr($p, -1) == '*'? true : false;
						
						if ($pattern == true)
						{
							$p = apadana_substr($p, 0, -1);
							$strlen = apadana_strlen($p);

							if (apadana_substr($current, 0, $strlen) == $p)
							{
								$match = true;
								break;
							}
						}
						else
						{
							if ($current == $p)
							{
								$match = true;
								break;
							}
						}
					}
				}
				if ($block['block_access_type'] == 1 && $match !== true)
				{
					continue;
				}
				elseif ($block['block_access_type'] != 1 && $match === true)
				{
					continue;
				}
			}

			$block['block_content'] = trim($block['block_content']);

			if (is_alphabet($block['block_function']) && function_exists('block_'.$block['block_function']))
			{
				if (apadana_strpos($block['block_content'], '[- options -]') !== FALSE)
				{
					$block['block_content'] = nohtml($block['block_content']);
					if (apadana_substr($block['block_content'], 0, 13) == '[- options -]')
					{
						$block['block_content'] = trim(apadana_substr($block['block_content'], 13));
						if (strpos($block['block_content'], '=') !== FALSE)
						{
							$content = array();
							$block['block_content'] = explode("\n", $block['block_content']);
							foreach($block['block_content'] as $c)
							{
								$c = explode('=', $c);
								if (!isset($c[0]) || !isset($c[1]) || trim($c[0]) == '' || trim($c[1]) == '') continue;
								$c[0] = trim($c[0]);
								$c[1] = trim($c[1]);
								$content[$c[0]] = $c[1];
							}
							$block['block_content'] = !count($content)? null : $content;
						}
						else
						{
							$block['block_content'] = null;
						}
					}
				}
				else
				{
					$block['block_content'] = null;
				}

				$func = 'block_'.$block['block_function'];
				$block['block_content'] = $func($block['block_content'], $block['block_id'], $position);
			}
			elseif (!empty($block['block_function']))
			{
				continue;
			}

			$file = null;

			if (!empty($block['block_function']))
			{
				$func = str_replace('_', '-', $block['block_function']);
				$file = file_exists(template_dir.'block-'.$position.'-function-'.$func.'.tpl')? 'block-'.$position.'-function-'.$func.'.tpl' : null;
				$file = empty($file) && file_exists(template_dir.'block-function-'.$func.'.tpl')? 'block-function-'.$func.'.tpl' : $file;
			}
			
			$file = empty($file) && file_exists(template_dir.'block-'.$position.'.tpl')? 'block-'.$position.'.tpl' : $file;
			$file = empty($file) && file_exists(template_dir.'block.tpl')? 'block.tpl' : $file;

			if (empty($file))
			{
				continue;
			}

			$itpl = new template($file, template_dir);
			$itpl->assign(array(
				'{id}' => $block['block_id'],
				'{title}' => $block['block_title'],
				'{content}' => $block['block_content'],
			));

			$tpl->assign(array(
				'{block-'.$position.'}' => $itpl->get_var(),
				'[block-'.$position.']' => null,
				'[/block-'.$position.']' => null,
			), null, 'add');
			unset($itpl);
			$test[$position] = true;
		}
	}
	
	foreach($positions as $position)
	{
		if (!isset($test[$position]))
		{
			$tpl->block('#\\[block-'.$position.'\\](.*?)\\[/block-'.$position.'\\]#s', '');
			$tpl->assign(array(
				'{block-'.$position.'}' => null,
				'[not-block-'.$position.']' => null,
				'[/not-block-'.$position.']' => null,
			));
		}
		else
		{
			$tpl->block('#\\[not-block-'.$position.'\\](.*?)\\[/not-block-'.$position.'\\]#s', '');
		}
	}

	unset($position, $blocks, $block, $page, $p, $positions, $test, $parse, $current);
}

?>