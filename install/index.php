<?php
/**
 * @In the name of God!
 * @author:Apadana Development Team
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

@session_start();
@ob_start();
@ob_implicit_flush(0);

define('security', true);
define('root_dir', dirname(dirname(__FILE__)).'/');
define('engine_dir', root_dir.'/engine/');
define('install_dir', root_dir.'/install/');

define('member', 0);
define('member_id', 0);
define('member_name', 'GUEST');
define('member_group', 5);
$member = 0;

define('group_admin', 0);
define('group_super_admin', 0);
define('group_rights', null);

require_once(engine_dir.'functions.php');
require_once(engine_dir.'filter.function.php');
require_once(engine_dir.'template.class.php');

$tpl = new template( 'init.tpl' , install_dir.'template/',false );


if (!file_exists('apadana.lock'))
{

	if(isset($_GET['section']) && in_array($_GET['section'], array( 'install' , 'upgrade' ))){
		require_once ($_GET['section'].".php");
	}
	else
	{
		$itpl = new template( 'index.tpl' , install_dir.'template/',false );

		$itpl->assign(array(
			'[open]' => null ,
			'[/open]' => null
			));
		$itpl->block('#\\[lock\\](.*?)\\[/lock\\]#s', '');
	}
}
else
{
	$itpl = new template( 'index.tpl' , install_dir.'template/',false );

	$itpl->assign(array(
		'[lock]' => null ,
		'[/lock]' => null
		));
	$itpl->block('#\\[open\\](.*?)\\[/open\\]#s', '');
}

$tpl->assign('{title}', isset($title) && $title != null ? $title : null);

$tpl->assign('{content}', $itpl->get_var());

unset($itpl);

$tpl->display();
?>