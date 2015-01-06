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

@ob_start();
@session_start();
define('security', 110);
define('engine_dir', dirname(__FILE__).'/');

function clean_url($url) 
{
    if($url == '') return;
    $url = str_replace('http://', null, strtolower($url));
    $url = str_replace('https://', null, $url );
    if(substr($url, 0, 4) == 'www.')  $url = substr($url, 4);
    $url = explode('/', $url);
    $url = reset($url);
    $url = explode(':', $url);
    $url = reset($url);
    return $url;
}

require_once(engine_dir.'config.inc.php');
error_reporting(debug_system ? E_ALL : 0);

if (debug_system  !== true && (!isset($_SERVER['HTTP_REFERER']) || clean_url($_SERVER['HTTP_REFERER']) != clean_url($_SERVER['HTTP_HOST'])))
{
	exit('Nice TRY!');
}

mt_srand((double)microtime()*1000000);
$code = mt_rand(10000, 999999);
$code = substr($code, 0, 4);
$key = isset($_GET['key']) && !empty($_GET['key'])? $_GET['key'] : 'iman';
$key = preg_replace("([^A-Za-z-0-9-_]*)", '', $key);
$_SESSION['captcha'][$key] = md5($code.sitekey);

$bg = engine_dir.'images/captcha/image_bg.jpg';
$numimgp = engine_dir.'images/captcha/%d/%d.JPG';

$numimg1 = sprintf($numimgp, rand(1,5), substr($code,0,1));
$numimg2 = sprintf($numimgp, rand(1,5), substr($code,1,1));
$numimg3 = sprintf($numimgp, rand(1,5), substr($code,2,1));
$numimg4 = sprintf($numimgp, rand(1,5), substr($code,3,1));
$ys1 = rand(0,6);
$ys2 = rand(0,6);
$ys3 = rand(0,6);
$ys4 = rand(0,6);

$bgImg = imagecreatefromjpeg($bg);
$nmImg1 = imagecreatefromjpeg($numimg1);
$nmImg2 = imagecreatefromjpeg($numimg2);
$nmImg3 = imagecreatefromjpeg($numimg3);
$nmImg4 = imagecreatefromjpeg($numimg4);

imagecopymerge($bgImg, $nmImg1,0,$ys1,0,0,15,20,80);
imagecopymerge($bgImg, $nmImg2,15,$ys2,0,0,15,20,80);
imagecopymerge($bgImg, $nmImg3,30,$ys3,0,0,15,20,80);
imagecopymerge($bgImg, $nmImg4,45,$ys4,0,0,15,20,80);

header('Content-type: image/jpg');
imagejpeg($bgImg);
imagedestroy($bgImg);
imagedestroy($nmImg1);
imagedestroy($nmImg2);
imagedestroy($nmImg3);
imagedestroy($nmImg4);

?>