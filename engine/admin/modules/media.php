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

member::check_admin_page_access('media') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

if (isset($_GET['connector']))
{
	include_once engine_dir.'elfinder/php/elFinderConnector.class.php';
	include_once engine_dir.'elfinder/php/elFinder.class.php';
	include_once engine_dir.'elfinder/php/elFinderVolumeDriver.class.php';
	include_once engine_dir.'elfinder/php/elFinderVolumeLocalFileSystem.class.php';
	// Required for MySQL storage connector
	// include_once engine_dir.'elfinder/php/elFinderVolumeMySQL.class.php';
	// Required for FTP connector support
	// include_once engine_dir.'elfinder/php/elFinderVolumeFTP.class.php';


	/**
	 * Simple function to demonstrate how to control file access using "accessControl" callback.
	 * This method will disable accessing files/folders starting from  '.' (dot)
	 *
	 * @param  string  $attr  attribute name (read|write|locked|hidden)
	 * @param  string  $path  file path relative to volume root directory started with directory separator
	 * @return bool|null
	 **/
	function access($attr, $path, $data, $volume)
	{
		// engine\\admin\\backups\\apadana-backup-**********.php
		if(strpos($path, 'engine') !== false && strpos($path, 'admin') !== false && strpos($path, 'backups') !== false && strpos($path, 'apadana-backup-') !== false)
		{
			return true;
		}
		
		// engine\\elfinder
		if(strpos($path, 'engine') !== false && strpos($path, 'elfinder') !== false && strpos($path, '.php') !== false)
		{
			return true;
		}
		
		// engine\\config.inc.php
		if(strpos($path, 'engine') !== false && strpos($path, 'config.inc.php') !== false)
		{
			return true;
		}
		
		return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
			? !($attr == 'read' || $attr == 'write' || $attr == 'locked')    // set read+write to false, other (locked+hidden) set to true
			:  null;                                    // else elFinder decide it itself
	}

	if (group_super_admin == 1)
	{
		$opts = array(
			'debug' => true,
			'roots' => array(
				array(
					'alias' => 'Home',
					'driver' => 'LocalFileSystem',  // driver for accessing file system (REQUIRED)
					'path' => root_dir, // path to files (REQUIRED)
					'URL' => url, // URL to files (REQUIRED)
					'tmbPath' => 'uploads/.tmb',
					'quarantine' => 'uploads/.quarantine',
					'accessControl' => 'access'  // disable and hide dot starting files (OPTIONAL)
				)
			)
		);
	}
	else
	{
		$opts = array(
			'debug' => true,
			'roots' => array(
				array(
					'alias' => 'Home',
					'driver' => 'LocalFileSystem',  // driver for accessing file system (REQUIRED)
					'path' => root_dir.'uploads/', // path to files (REQUIRED)
					'URL' => url.'uploads/', // URL to files (REQUIRED)
					'tmbPath' => '.tmb',
					'quarantine' => '.quarantine',
					'accessControl' => 'access'  // disable and hide dot starting files (OPTIONAL)
				)
			)
		);
	}

	// run elFinder
	$connector = new elFinderConnector(new elFinder($opts));
	$connector->run();
	exit;
}

set_title('رسانه ها');
$itpl = new template('media.tpl', engine_dir.'admin/template/');

if (!isset($_GET['noTemplate']))
{
	$itpl->assign(array(
		'[template]' => null,
		'[/template]' => null,
	));
	$itpl->block('#\\[no-template\\](.*?)\\[/no-template\\]#s', '');
	$tpl->assign('{content}', $itpl->get_var(), 'add');
}
else
{
	$itpl->assign(array(
		'[no-template]' => null,
		'[/no-template]' => null,
	));
	$itpl->block('#\\[template\\](.*?)\\[/template\\]#s', '');
	
	if (isset($_GET['editor']))
	{
		$itpl->assign(array(
			'[editor]' => null,
			'[/editor]' => null,
		));
		$itpl->block('#\\[input\\](.*?)\\[/input\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'{input}' => isset($_GET['input']) && is_alphabet($_GET['input'])? $_GET['input'] : null,
			'[input]' => null,
			'[/input]' => null,
		));
		$itpl->block('#\\[editor\\](.*?)\\[/editor\\]#s', '');
	}
	
	define('no_template', true);
	echo '<html>';
	echo '<head>';
	echo head();
	echo '</head>';
	echo '<body>';
	$itpl->display();
	echo '</body>';
	echo '</html>';
}

?>