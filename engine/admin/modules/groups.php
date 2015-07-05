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

member::check_admin_page_access('groups') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $tpl, $options, $member;

	set_title('گروه های کاربری');
	$itpl = new template('engine/admin/template/groups.tpl');
	$itpl->assign('{token}', member::token($member['member_key']));
	$itpl->assign('{select}', _select());
	$itpl->assign('{edit-select}', _select(null, 'group-edit-rights'));
	$itpl->assign(array(
		'[not-show-groups]' => null,
		'[/not-show-groups]' => null,
	));
	$itpl->block('#\\[show-groups\\](.*?)\\[/show-groups\\]#s', '');
	$tpl->assign('{content}', $itpl->get_var());
	unset($itpl);
}

function _select($rights = null, $id = null)
{
	global $admin;

	$rights = !empty($rights)? explode(',', $rights) : array();
	$groups = '<select name="groups[rights][]" size="10" style="width: 100%" multiple="multiple" id="'.$id.'">';
	$groups .= '<optgroup label="&nbsp;مدیریت سیستم">';
	foreach($admin as $row)
	{
		if ($row['admin_page'] != 1) continue;
		$groups .= '<option value="'.$row['admin_rights'].'"'.(in_array($row['admin_rights'], $rights)? ' selected="selected"' : null).' style="padding:2px 12px">&nbsp;&nbsp;'.$row['admin_title'].'</option>';
	}
	$groups .= '</optgroup>';
	$groups .= '<optgroup label="&nbsp;مدیریت محتوای سایت">';
	foreach($admin as $row)
	{
		if ($row['admin_page'] != 2) continue;
		$groups .= '<option value="'.$row['admin_rights'].'"'.(in_array($row['admin_rights'], $rights)? ' selected="selected"' : null).' style="padding:2px 12px">&nbsp;&nbsp;'.$row['admin_title'].'</option>';
	}
	$groups .= '</optgroup>';
	$groups .= '<optgroup label="&nbsp;تنظیمات سیستم">';
	foreach($admin as $row)
	{
		if ($row['admin_page'] != 3) continue;
		$groups .= '<option value="'.$row['admin_rights'].'"'.(in_array($row['admin_rights'], $rights)? ' selected="selected"' : null).' style="padding:2px 12px">&nbsp;&nbsp;'.$row['admin_title'].'</option>';
	}
	$groups .= '</optgroup>';
	$groups .= '</select>';
	return $groups;
}

function _list()
{
	global $d, $member_groups;

	$itpl = new template('engine/admin/template/groups.tpl');
	
	foreach($member_groups as $g)
	{
		$itpl->add_for('groups', array(
			'{odd-even}' => odd_even(),
			'{id}' => $g['group_id'],
			'{name}' => htmlencode($g['group_name']),
			'{icon}' => htmlencode($g['group_icon']),
			'{title}' => htmlencode($g['group_title']),
			'{data-admin}' => $g['group_admin'],
			'{data-superAdmin}' => $g['group_superAdmin'],
			'{data-rights}' => $g['group_rights'],
			'{members}' => $g['group_id']==5? '--' : $d->numRows("SELECT member_id FROM #__members WHERE member_group='".$g['group_id']."'", true),
			'{admin}' => $g['group_admin']==1? true : false,
			'{super-admin}' => $g['group_superAdmin']==1? true : false,
			'{delete}' => $g['group_id']<=5? false : true,
			'replace' => array(
				'#\\[group-admin\\](.*?)\\[/group-admin\\]#s' => $g['group_admin']==1? '\\1' : '',
				'#\\[not-group-admin\\](.*?)\\[/not-group-admin\\]#s' => $g['group_admin']!=1? '\\1' : '',
				'#\\[group-super-admin\\](.*?)\\[/group-super-admin\\]#s' => $g['group_superAdmin']==1? '\\1' : '',
				'#\\[not-group-super-admin\\](.*?)\\[/not-group-super-admin\\]#s' => $g['group_superAdmin']!=1? '\\1' : '',
				'#\\[group-delete\\](.*?)\\[/group-delete\\]#s' => $g['group_id']>5? '\\1' : '',
				'#\\[not-group-delete\\](.*?)\\[/not-group-delete\\]#s' => $g['group_id']<=5? '\\1' : '',
			)
		));
	}

	$itpl->assign(array(
		'[show-groups]' => null,
		'[/show-groups]' => null,
	));
	$itpl->block('#\\[not-show-groups\\](.*?)\\[/not-show-groups\\]#s', '');
	
	if (is_ajax())
	{
		define('no_template', true);
		$itpl->display();
	}
	else
	{
		return $itpl->get_var();
	}
	unset($itpl);
}

function _new()
{
	global $d, $member;

	$msg = null;
	$groups = get_param($_POST, 'groups', null, 1);

	$groups['token'] = trim($groups['token']);
	$groups['name'] = nohtml($groups['name']);
	$groups['icon'] = nohtml($groups['icon']);
	$groups['superAdmin'] = intval($groups['superAdmin'])==1? 1 : 0;
	$groups['admin'] = intval($groups['admin'])==1 || $groups['superAdmin']==1? 1 : 0;
	$groups['rights'] = !isset($groups['rights']) || !is_array($groups['rights'])? array() : array_map('alphabet', $groups['rights']);

	if ($groups['token'] != member::token($member['member_key']))
	{
		$msg .= 'کد مجوز معتبر نیست!<br />';
	}

	if (empty($groups['name']))
		$msg .= 'عنوان گروه را ننوشته اید!<br>';
	
	if (empty($groups['icon']))
		$msg .= 'عکس گروه را ننوشته اید!<br>';
	
	if (empty($groups['title']))
		$msg .= 'استایل عنوان گروه را ننوشته اید!<br>';
	elseif (strpos($groups['title'], '{name}') === false)
		$msg .= 'در استایل انتخاب شده تگ {name} وجود ندارد!<br>';
	
	if (!empty($msg))
		echo message($msg, 'error');
	else
	{
		$d->insert('member_groups', array(
			'group_name' => $groups['name'],
			'group_superAdmin' => $groups['superAdmin'],
			'group_icon' => $groups['icon'],
			'group_title' => $groups['title'],
			'group_rights' => $groups['admin']==1? implode(',', $groups['rights']) : null,
			'group_admin' => $groups['admin']
		));

		if ($d->affectedRows())
		{
			remove_cache('member-groups');
			echo '<script>apadana.hideID("form-new-group")</script>';
			echo message('گروه جدید با موفقیت ساخته شد!', 'success');
			if ($groups['superAdmin']==1) echo message('کاربران این گروه در سایت دسترسی مدیرکل را خواهند داشت!', 'info');
			elseif ($groups['admin']==1) echo message('کاربران این گروه به بخش مدیریت سایت دسترسی خواهند داشت!', 'info');
		}
		else
		{
			echo message('در ذخیره اطلاعات خطایی رخ داده مجدد تلاش کنید!', 'error');
		}
	}
	define('no_template', true);
}

function _edit()
{
	global $page, $d, $member, $member_groups;
	$_GET['c'] = get_param($_GET, 'c', 0);

	if (!isset($member_groups[$_GET['c']]) || !is_array($member_groups[$_GET['c']]) || !count($member_groups[$_GET['c']]))
		redirect(admin_page.'&section=groups');

	$groups = get_param($_POST, 'groups', null, 1);

	if (is_array($groups) && count($groups))
	{
		$msg = null;
		$groups['token'] = trim($groups['token']);
		$groups['name'] = nohtml($groups['name']);
		$groups['icon'] = nohtml($groups['icon']);
		$groups['superAdmin'] = intval($groups['superAdmin'])==1? 1 : 0;
		$groups['admin'] = intval($groups['admin'])==1 || $groups['superAdmin']==1? 1 : 0;
		$groups['rights'] = !isset($groups['rights']) || !is_array($groups['rights'])? array() : array_map('alphabet', $groups['rights']);

		if ($groups['token'] != member::token($member['member_key']))
		{
			$msg .= 'کد مجوز معتبر نیست!<br />';
		}

		if (($_GET['c']==1 || $_GET['c']==2 || $_GET['c']==3) && $groups['admin']==0)
			$msg .= 'این گروه حتما باید دسترسی مدیر داشته باشند!<br>';
		
		if (($_GET['c']==2 || $_GET['c']==3) && $groups['superAdmin']==1)
			$msg .= 'این گروه نمی تواند دسترسی مدیرکل را داشته باشد!<br>';
		
		if ($_GET['c']==1 && $groups['superAdmin']==0)
			$msg .= 'این گروه حتما باید دسترسی مدیر کل را داشته باشند!<br>';
		
		if (($_GET['c']==4 || $_GET['c']==5) && $groups['admin']==1)
			$msg .= 'این گروه نمی تواند دسترسی مدیریت داشته باشد!<br>';
		
		if (empty($groups['name']))
			$msg .= 'عنوان گروه را ننوشته اید!<br>';
		
		if (empty($groups['icon']))
			$msg .= 'عکس گروه را ننوشته اید!<br>';
		
		if (empty($groups['title']))
			$msg .= 'استایل عنوان گروه را ننوشته اید!<br>';
		elseif (strpos($groups['title'], '{name}') === false)
			$msg .= 'در استایل انتخاب شده تگ {name} وجود ندارد!<br>';
		
		if (!empty($msg))
		{
			echo message($msg, 'error');
		}
		else
		{
			$d->update('member_groups', array(
				'group_name' => $groups['name'],
				'group_superAdmin' => $groups['superAdmin'],
				'group_icon' => $groups['icon'],
				'group_title' => $groups['title'],
				'group_rights' => $groups['admin']==1? implode(',', $groups['rights']) : null,
				'group_admin' => $groups['admin']
			), "group_id='".$_GET['c']."'", 1);

			if ($d->affectedRows())
			{
				remove_cache('member-groups');
				echo message('گروه کاربری با موفقیت ویرایش شد!', 'success');
				if ($groups['superAdmin']==1) echo message('کاربران این گروه در سایت دسترسی مدیرکل را خواهند داشت!', 'info');
				elseif ($groups['admin']==1) echo message('کاربران این گروه به بخش مدیریت سایت دسترسی خواهند داشت!', 'info');
			}
			else
			{
				echo message('در ذخیره اطلاعات خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}
	define('no_template', true);
}

function _delete()
{
	global $d, $member;
	$result = 'ERROR';
	$_GET['c'] = get_param($_GET, 'c', 0);
	$_GET['d'] = get_param($_GET, 'd');

	if ($_GET['c'] > 5 && $_GET['d'] == member::token($member['member_key']))
	{
		$d->delete('member_groups', "group_id='".$_GET['c']."'", 1);
		if ($d->affectedRows())
		{
			$d->update('members', array('member_group'=>4), "member_group='".$_GET['c']."'");
			remove_cache('member-groups');
			$result = 'SUCCESS';
		}
	}
		
	exit('{"result":"'.$result.'"}');
}

$_GET['do'] = get_param($_GET, 'do');

switch($_GET['do'])
{
	case 'list':
	_list();
	break;

	case 'new':
	_new();
	break;
	
	case 'edit':
	_edit();
	break;
	
	case 'delete':
	_delete();
	break;
	
	default:
	_index();
	break;
}

?>