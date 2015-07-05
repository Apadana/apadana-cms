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

function get_simple_links()
{
	if(!$links = get_cache('simple-links'))
	{
		global $d;
		$links = $d->get_row("SELECT * FROM #__simple_links WHERE link_active='1' ORDER BY link_id DESC");
		set_cache('simple-links', $links);
	}
	return $links;
}

function module_simple_links_run()
{
	global $tpl, $d, $options;
	set_title('لینکستان');
	set_meta('description', 'لینکستان', 'add');
	set_canonical(url('simple-links'));
	set_content('لینکستان', block_simple_links());

	($hook = get_hook('simple_links'))? eval($hook) : null;
}

function module_simple_links_sitemap($sitemap)
{
	$sitemap->addItem(url('simple-links'), 0, 'monthly', '0.6');
}

function block_simple_links($op = null, $id = null, $position = null)
{
	if($op=='remove-cache') return true;
	global $tpl, $d, $options;
	$links = get_simple_links();
	if(is_array($links) && count($links))
	{
		$content = '<ul id="block-simple-links">'.n;
		foreach($links as $row)
		{
			$row['link_href'] = $row['link_direct_link']==1? $row['link_href'] : redirect_link($row['link_href']);
			$row['link_direct_link'] = $row['link_direct_link']==0? ' rel="nofollow"' : "";
			$row['link_target'] = !empty($row['link_target'])? ' target="'.$row['link_target'].'"' : "";
			$row['link_description'] = !empty($row['link_description'])? ' title="'.$row['link_description'].'"' : "";
			$row['link_title'] = !empty($row['link_color'])? '<font color="'.$row['link_color'].'">'.$row['link_title'].'</font>' : $row['link_title'];
			$row['link_title'] = $row['link_bold']==1? '<b>'.$row['link_title'].'</b>' : $row['link_title'];
			$row['link_title'] = $row['link_strikethrough']==1? '<s>'.$row['link_title'].'</s>' : $row['link_title'];
			$content .= '<li><a href="'.$row['link_href'].'"'.$row['link_target'].$row['link_description'].$row['link_direct_link'].'>'.$row['link_title'].'</a></li>'.n;
		}
		$content .= '</ul>';

		($hook = get_hook('block_simple_links'))? eval($hook) : null;

		unset($links, $row);
	}
	else
	{
		$content = 'هیچ لینکی برای نمایش یافت نشد!';
	}
	return $content;
}

?>