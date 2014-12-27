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

if (!defined('admin_page') || !isset($_GET['admin']) || !is_alphabet($_GET['admin']) || url.'?admin='.$_GET['admin'] != admin_page || $_GET['admin'] != $options['admin'])
{
	exit('Hacking attempt!');
}

if (!$admin = get_cache('admin', 'always'))
{
	$admin = $d->get_row("SELECT * FROM `#__admin` ORDER BY admin_rights ASC", 'assoc', 'admin_right');
	ksort($admin);
	set_cache('admin', $admin);
}

set_title('آپادانا', 'new');
set_meta('robots', 'noindex, nofollow');
define('no_blocks', true);

if (member != 1 || group_admin != 1)
{
	$_GET['section'] = 'login';
}

if (group_admin == 1 && isset($_GET['module']))
{
	$_GET['section'] = 'module';
}

if (isset($_GET['section']) && !empty($_GET['section']))
{
	if (is_alphabet($_GET['section']) && file_exists(engine_dir.'admin/modules/'.$_GET['section'].'.php') && is_readable(engine_dir.'admin/modules/'.$_GET['section'].'.php'))
	{
		require(engine_dir.'admin/modules/'.$_GET['section'].'.php');
	}
	else
	{
		require(engine_dir.'admin/modules/404.php');
	}
}
else
{
	require(engine_dir.'admin/modules/index.php');
}

?>