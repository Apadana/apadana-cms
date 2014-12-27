<?php
/**
 * @In the name of God!
 * @author: Mohammad Sadegh Dehghan Niri (MSDN)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2014 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

function _index(){
	global $d;
	$note = get_param($_POST,'note','',false);

	$d->update('options',array(
		'option_value' => $note
	),"option_name= 'admin-notes'");

	if($d->affectedRows()){
		echo "<font color='green'>&nbsp;&nbsp;&nbsp;با موفق به روز شد</font>";
	}
	else{
		echo "<font color='red'>&nbsp;&nbsp;&nbsp;مشکلی در بروز رسانی وجود دارد</font>";
	}

	define('no_template', true);
}

if(is_ajax())
	_index();
else
	require(engine_dir.'admin/modules/404.php');