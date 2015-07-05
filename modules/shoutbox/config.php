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

function module_shoutbox_run()
{
	require_once(root_dir.'modules/shoutbox/class.php');
	$shoutbox = new shoutbox();

	$b = get_param($_GET, 'b');
	switch($b)
	{
		case 'send':
		$shoutbox->insert(get_param($_POST, 'msg'));
		break;
		
		case 'delete':
		$shoutbox->delete(get_param($_POST, 'id', 0));
		break;
		
		case 'content':
		$shoutbox->getMessages();
		break;
		
		default:
		$shoutbox->archive();
		break;
	}
}

function module_shoutbox_sitemap($sitemap)
{
	$sitemap->addItem(url('shoutbox'), 0, 'daily', '0.2');
}

function block_shoutbox($op, $id = null, $position = null)
{
	global $page, $options;

	if ($op=='remove-cache') return true;
	set_head(file_exists(template_dir.'styles/shoutbox.css')? '<link href="'.url.'templates/'.$options['theme'].'/styles/shoutbox.css" type="text/css" rel="stylesheet" />' : '<link href="'.url.'modules/shoutbox/styles/default.css" type="text/css" rel="stylesheet" />');
	set_head('<script type="text/javascript" src="'.url.'modules/shoutbox/javascript/functions.js"></script>');
	$itpl = new template('modules/shoutbox/html/block.tpl');

	($hook = get_hook('block_shoutbox'))? eval($hook) : null;

	return $itpl->get_var();
}

?>