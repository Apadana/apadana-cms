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

function module_posts_info()
{
	return array(
		'name' => 'posts',
		'version' => '1.0.1',
		'creationDate' => '2012-07-21 18:00:03',
		'description' => 'ماژول پست های آپادانا.',
		'author' => 'iman moodi',
		'authorEmail' => 'imanmoodi@yahoo.com',
		'authorUrl' => 'http://www.apadanacms.ir',
		'license' => 'GNU/GPL'
	);
}

function module_posts_admin_comments($action, $data = array())
{
	global $d, $options;

	member::check_admin_page_access('comments') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	switch ($action)
	{
		case 'name';
		return array(
			'posts' => 'پست ها'
		);
		break;
		
		case 'url';
		$query = "SELECT post_name, post_id FROM `#__posts` WHERE post_id='".intval($data['link'])."' LIMIT 1";
		$post = $d->get_row($query);
		return url('posts/'.($options['rewrite'] == 1? $post[0]['post_name'] : $post[0]['post_id']));
		break;
		
		case 'approve';
		$total = $d->num_rows("SELECT `comment_id` FROM `#__comments` WHERE `comment_type`='posts' AND `comment_approve`='1' AND `comment_link`='".intval($data['link'])."'", true);
		$d->query("UPDATE `#__posts` SET `post_comment_count`='".intval($total)."' WHERE `post_id`='".intval($data['link'])."' LIMIT 1");
		break;
		
		case 'delete';
		$total = $d->num_rows("SELECT `comment_id` FROM `#__comments` WHERE `comment_type`='posts' AND `comment_approve`='1' AND `comment_link`='".intval($data['link'])."'", true);
		$d->query("UPDATE `#__posts` SET `post_comment_count`='".intval($total)."' WHERE `post_id`='".intval($data['link'])."' LIMIT 1");
		break;
	}
	return false;
}

function module_posts_admin()
{
	global $tpl, $d, $options, $cache;

	(member::check_admin_page_access('posts') || member::check_admin_page_access('posts-fields') || member::check_admin_page_access('posts-categories') || member::check_admin_page_access('posts-options')) or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	require_once(root_dir.'modules/posts/functions.admin.php');
	$_GET['do'] = get_param($_GET, 'do');

	switch ($_GET['do'])
	{
		case 'categories';
		_categories();
		break;

		case 'categories-parent';
		_categories_parent();
		break;

		case 'categories-new';
		_categories_new();
		break;

		case 'categories-edit';
		_categories_edit();
		break;

		case 'categories-list';
		_categories_list();
		break;

		case 'categories-delete';
		_categories_delete();
		break;

		case 'options';
		_options();
		break;

		case 'fields';
		_fields();
		break;
		
		case 'fields-new';
		_fields_new();
		break;
		
		case 'fields-edit';
		_fields_edit();
		break;
		
		case 'fields-delete';
		_fields_delete();
		break;
		
		case 'posts-new';
		_new();
		break;

		case 'posts-edit';
		_edit();
		break;

		case 'posts-title';
		_title();
		break;

		case 'posts-approve';
		_approve();
		break;

		case 'posts-fixed';
		_fixed();
		break;
		
		case 'posts-delete';
		_delete();
		break;

		default:
		_default();
		break;
	}
}

?>