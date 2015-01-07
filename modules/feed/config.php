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

function module_feed_run()
{
	global $options;

	$_GET['b'] = get_param($_GET, 'b', $options['default-module']);
	$_GET['b'] = alphabet($_GET['b']);
	$_GET['b'] = is_module($_GET['b'])? $_GET['b'] : 'posts';
	$_GET['c'] = get_param($_GET, 'c', 'rss');
	$_GET['c'] = $_GET['c']=='atom'? 'atom' : 'rss';
	$type = $_GET['c']=='atom'? 'AtomGenerator' : 'RSSGenerator';
	
	($hook = get_hook('feed'))? eval($hook) : null;

	if (function_exists('module_'.str_replace('-', '_', $_GET['b']).'_feed'))
	{
		try
		{
			require_once(engine_dir.'feedGenerator/lib/FeedGenerator.php');
			$feeds = new FeedGenerator;
			$feeds->setGenerator(new $type);
			$feeds->setAuthor($options['mail']);
			$feeds->setTitle($options['title']);
			$feeds->setChannelLink(url('feed/'.$_GET['b'].'/'.$_GET['c']));
			$feeds->setLink(url);
			$feeds->setDescription($options['slogan']);
			$feeds->setID(url('feed/'.$_GET['b'].'/'.$_GET['c']));
			$feeds->setGeneratorName('Apadana Cms Copyright (c) '.date('Y').' (www.apadanacms.ir)');

			$func = 'module_'.str_replace('-', '_', $_GET['b']).'_feed';
			$func($feeds);

			$feeds->display();
			unset($feeds, $d, $tpl, $member_admin);
		}
		catch(FeedGeneratorException $e)
		{
			echo 'Error: '.$e->getMessage();
		}
		exit;
	}
	else
	{
		module_error_run('404');
	}
}

?>