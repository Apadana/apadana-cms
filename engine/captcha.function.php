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

function create_captcha($key = 'apadana')
{
	return '<img src="'.url.'engine/captcha.php?key='.$key.'&amp;_='.time().'" id="apadana-captcha-'.$key.'" class="apadana-captcha" width="60" height="25" border="0" align="absbottom" />&nbsp;<img src="'.url.'engine/images/reload.png" class="apadana-captcha-reload" width="16" height="16" border="0" title="برای تغییر کد کلیک کنید" style="cursor:pointer" onclick="apadana.changeSrc(\'apadana-captcha-'.$key.'\', \''.url.'engine/captcha.php?key='.$key.'&amp;_=\'+apadana.random())" align="absbottom" />';
}

function validate_captcha($key, $code)
{
    if (is_alphabet($key) && isset($_SESSION['captcha'][$key]) && !empty($_SESSION['captcha'][$key]))
    {
        if (md5($code.sitekey) == $_SESSION['captcha'][$key])
        {
	        return true;
        }
    }
	return false;
}

function remove_captcha($key = 'apadana')
{
	unset($_SESSION['captcha'][$key]);
}

?>