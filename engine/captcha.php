<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2014 ApadanaCMS.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

ob_start();
session_start();
define('security', true);
define('engine_dir', dirname(__FILE__) . DIRECTORY_SEPARATOR);

require_once(engine_dir.'config.inc.php');
error_reporting(debug_system? E_ALL : 0);

function clean_url($url) 
{
    if ($url == '') return;
    $url = str_replace('http://', null, strtolower($url));
    $url = str_replace('https://', null, $url );
    if (substr($url, 0, 4) == 'www.')  $url = substr($url, 4);
    $url = explode('/', $url);
    $url = reset($url);
    $url = explode(':', $url);
    $url = reset($url);
    return $url;
}

if (debug_system !== true && (!isset($_SERVER['HTTP_REFERER']) || clean_url($_SERVER['HTTP_REFERER']) != clean_url($_SERVER['HTTP_HOST'])))
{
	exit('Nice TRY!');
}

if (!isset($_GET['key']) || empty($_GET['key']) || !preg_match('~^([a-z-0-9-_]+)$~i', $_GET['key']))
{
	exit('Invalid key!');
}

if( function_exists('imagecreatetruecolor') ) 
	$imagecreate = 'imagecreatetruecolor';
	else 
	$imagecreate = 'imagecreate';
// Adapted for The Art of Web: www.the-art-of-web.com // Please acknowledge use of this code by including this header. 
// initialise image with dimensions of 160 x 45 pixels 
$image = @ $imagecreate(100, 30) or die("Cannot Initialize new GD image stream");
 // set background and allocate drawing colours 
$background = imagecolorallocate($image, 0x66, 0xCC, 0xFF);
 imagefill($image, 0, 0, $background);
 $linecolor = imagecolorallocate($image, 0x33, 0x99, 0xCC);
 $textcolor1 = imagecolorallocate($image, 0x00, 0x00, 0x00);
 $textcolor2 = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
 // draw random lines on canvas 
 for($i=0;$i < 8;$i++) {
 imagesetthickness($image, mt_rand(1,3));
 imageline($image, mt_rand(0,100), 0, mt_rand(0,100), 30, $linecolor);
 }
 // using a mixture of TTF fonts 
 $fonts = array();
 $fonts[] = "fonts/bkodak.ttf";
 $fonts[] = "fonts/bkodak_2.ttf";
 // add random digits to canvas using random black/white colour 
 $digit = '';
 for($x = 10;$x <= 80;$x += 20) {
 $textcolor = (mt_rand(0,10) % 2) ? $textcolor1 : $textcolor2;
 $digit .= ($num = mt_rand(0, 9));
 imagettftext($image, 20, mt_rand(-30,30), $x, mt_rand(20, 25), $textcolor, $fonts[array_rand($fonts)], $num);
 } // record digits in session variable 

$_SESSION['captcha'][$_GET['key']] = md5($digit . sitekey);

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
