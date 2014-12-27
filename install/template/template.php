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

function print_header($title = 'Install')
{
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
	echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="fa-ir" dir="rtl">'."\n";
	echo '<head profile="http://gmpg.org/xfn/11">'."\n";
	echo '<title>Apadana &bull; '.$title.'</title>'."\n";
	echo '<meta http-equiv="Content-Language" content="fa" />'."\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\n";
	echo '<meta http-equiv="Designer" content="Iman Moodi" />'."\n";
	echo '<meta name="generator" content="ApadanaCms.ir v1.0" />'."\n";
	echo '<meta name="robots" content="noindex, nofollow" />'."\n";
	echo '<link href="template/styles/install.css" type="text/css" rel="stylesheet" />'."\n";
	echo '<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />'."\n";
	echo '<script type="text/javascript" src="../engine/javascript/jquery.js"></script>'."\n";
	echo '<script type="text/javascript" src="../engine/javascript/core.js"></script>'."\n";
	echo '<script type="text/javascript" src="template/js.js"></script>'."\n";
	echo '</head>'."\n";
	echo '<body>'."\n";
	echo '<div align="center">'."\n";
	echo '<div id="content">'."\n";
	echo '<div id="top"><div id="percent"></div></div>'."\n";
	echo '<div id="main">'."\n";
}

function print_footer()
{
	echo '<div class="clear"></div>'."\n";
	echo '</div>'."\n";
	echo '<div id="bottom"></div>'."\n";
	echo '</div>'."\n";
	echo '</div>'."\n";
	echo '</body>'."\n";
	echo '</html>';
	exit;
}

function print_info($subject, $text)
{
	echo '<div class="info">'."\n";
	echo '<h1>'.$subject.'</h1>'."\n";
	echo $text."\n";
	echo '</div>'."\n";
}

?>