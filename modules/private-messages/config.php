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

function private_messages()
{
	global $d, $cache;
	if (!isset($cache['private-messages']) || !is_array($cache['private-messages']))
	{
		if (member)
		{
			$sql = $d->query("
				SELECT (SELECT COUNT(msg_id) FROM #__private_messages WHERE msg_receiver='".member_name."' AND msg_read='0') newpms,
				(SELECT COUNT(msg_id) FROM #__private_messages WHERE msg_receiver='".member_name."' AND msg_read='1') oldpms,
				(SELECT COUNT(msg_id) FROM #__private_messages WHERE msg_sender='".member_name."') sendpms,
				(SELECT COUNT(msg_id) FROM #__private_messages WHERE msg_sender='".member_name."' OR msg_receiver='".member_name."') allpms;
			");
			$cache['private-messages'] = $d->fetch($sql);
		}
		else
		{
			$cache['private-messages'] = array();	
		}
	}
	return $cache['private-messages'];
}

function module_private_messages_run()
{
	global $d, $options, $member;

	require_once(root_dir.'modules/private-messages/functions.php');

	$_GET['b'] = get_param($_GET, 'b');

	switch($_GET['b'])
	{
		case 'read':
		_read();
		break;

		case 'remove':
		_remove();
		break;
		
		case 'new':
		_new();
		break;

		case 'outbox':
		_outbox();
		break;
		
		default:
		_inbox();
		break;
	}
}

?>