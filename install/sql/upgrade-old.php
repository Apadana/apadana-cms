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

defined('security') or exit('Direct Access to this location is not allowed.');

$d->query("
	ALTER TABLE `#__admin`
	CHANGE `admin_rights` `admin_rights` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
	CHANGE `admin_page` `admin_page` INT( 1 ) NOT NULL DEFAULT '1'
");

$d->query("TRUNCATE TABLE `#__admin`");
$d->query("INSERT INTO `#__admin` VALUES('robots', 'engine/images/admin/robots.png', 'روبات ها', '?admin={admin}&amp;section=robots', '1');");
$d->query("INSERT INTO `#__admin` VALUES('blocks', 'engine/images/admin/blocks.png', 'بلوک ها', '?admin={admin}&amp;section=blocks', '1');");
$d->query("INSERT INTO `#__admin` VALUES('account', 'engine/images/admin/account.png', 'سامانه کاربری', '?admin={admin}&amp;module=account', '1');");
$d->query("INSERT INTO `#__admin` VALUES('modules', 'engine/images/admin/modules.png', 'ماژول ها', '?admin={admin}&amp;section=modules', '1');");
$d->query("INSERT INTO `#__admin` VALUES('groups', 'engine/images/admin/groups.png', 'گروه های کاربری', '?admin={admin}&amp;section=groups', '1');");
$d->query("INSERT INTO `#__admin` VALUES('referer', 'engine/images/admin/referer.png', 'لینک دهندگان', '?admin={admin}&amp;section=referer', '1');");
$d->query("INSERT INTO `#__admin` VALUES('banned', 'engine/images/admin/banned.png', 'مسدود سازی', '?admin={admin}&amp;section=banned', '1');");
$d->query("INSERT INTO `#__admin` VALUES('counter', 'engine/images/admin/counter.png', 'آمارگیر', '?admin={admin}&amp;module=counter', '1');");
$d->query("INSERT INTO `#__admin` VALUES('voting', 'engine/images/admin/voting.png', 'نظرسنجی', '?admin={admin}&amp;module=voting', '2');");
$d->query("INSERT INTO `#__admin` VALUES('options', 'engine/images/admin/options.png', 'تنظیمات عمومی', '?admin={admin}&amp;section=options', '3');");
$d->query("INSERT INTO `#__admin` VALUES('account-op', 'engine/images/admin/account-op.png', 'سامانه کاربری', '?admin={admin}&amp;module=account&amp;do=options', '3');");
$d->query("INSERT INTO `#__admin` VALUES('posts', 'engine/images/admin/posts.png', 'پست ها', '?admin={admin}&amp;module=posts', '2');");
$d->query("INSERT INTO `#__admin` VALUES('posts-categories', 'engine/images/admin/posts-categories.png', 'موضوعات پست ها', '?admin={admin}&amp;module=posts&amp;do=categories', '2');");
$d->query("INSERT INTO `#__admin` VALUES('posts-options', 'engine/images/admin/posts-options.png', 'تنظیمات پست ها', '?admin={admin}&amp;module=posts&amp;do=options', '3');");
$d->query("INSERT INTO `#__admin` VALUES('media', 'engine/images/admin/media.png', 'رسانه ها', '?admin={admin}&amp;section=media', '1');");
$d->query("INSERT INTO `#__admin` VALUES('pages', 'engine/images/admin/pages.png', 'صفحات اضافی', '?admin={admin}&amp;module=pages', '2');");
$d->query("INSERT INTO `#__admin` VALUES('comments', 'engine/images/admin/comments.png', 'نظرات', '?admin={admin}&amp;section=comments', '2');");
$d->query("INSERT INTO `#__admin` VALUES('cache', 'engine/images/admin/cache.png', 'مدیریت کش', '?admin={admin}&amp;section=cache', '1');");
$d->query("INSERT INTO `#__admin` VALUES('phpinfo', 'engine/images/admin/phpinfo.png', 'اطلاعات PHP', '?admin={admin}&amp;section=phpinfo', '1');");
$d->query("INSERT INTO `#__admin` VALUES('simple-links', 'engine/images/admin/simple-links.png', 'پیوندها', '?admin={admin}&amp;module=simple-links', '2');");
$d->query("INSERT INTO `#__admin` VALUES('templates', 'engine/images/admin/templates.png', 'تم ها', '?admin={admin}&amp;section=templates', '1');");
$d->query("INSERT INTO `#__admin` VALUES('backup', 'engine/images/admin/backup.png', 'پشتیبان گیری', '?admin={admin}&amp;section=backup', '1');");
$d->query("INSERT INTO `#__admin` VALUES('posts-fields', 'engine/images/admin/posts-fields.png', 'فیلدهای اضافی پست ها', '?admin={admin}&amp;module=posts&amp;do=fields', '3');");
$d->query("INSERT INTO `#__admin` VALUES('newsletter', 'engine/images/admin/newsletter.png', 'خبرنامه', '?admin={admin}&amp;section=newsletter', '1');");
$d->query("INSERT INTO `#__admin` VALUES('security-check', 'engine/images/admin/security-check.png', 'برسی امنیتی', '?admin={admin}&amp;section=security-check', '1');");

###############################

$d->query("
	CREATE TABLE IF NOT EXISTS `#__antiflood` (
	  `flood_ip` varchar(50) NOT NULL,
	  `flood_time` int(10) NOT NULL,
	  KEY `flood_ip` (`flood_ip`),
	  KEY `flood_time` (`flood_time`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
");

###############################

$d->query("
	ALTER TABLE `#__banned`
	CHANGE `ban_ip` `ban_ip` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `ban_date` `ban_date` INT( 10 ) NOT NULL ,
	ADD INDEX ( `ban_ip` ) 
");

###############################

$d->query("
	ALTER TABLE `#__blocks`
	CHANGE `block_function` `block_function` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `block_language` `block_language` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	ADD `block_access` TEXT NOT NULL AFTER `block_function` ,
	ADD `block_access_type` INT( 1 ) NOT NULL DEFAULT '1' AFTER `block_access` 
");

###############################

$d->query("
	ALTER TABLE `#__comments`
	CHANGE `comment_type` `comment_type` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL ,
	CHANGE `comment_author_ip` `comment_author_ip` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL ,
	CHANGE `comment_date` `comment_date` INT( 10 ) NOT NULL DEFAULT '0' ,
	ADD INDEX ( `comment_type` ) ,
	ADD INDEX ( `comment_link` )
");

$d->query("UPDATE `#__comments` SET `comment_type`='posts' WHERE `comment_type`='post'");

###############################

$d->query("
	ALTER TABLE `#__counter`
	ADD INDEX ( `counter_name` ) ,
	ADD INDEX ( `counter_version` )
");

$d->query("INSERT INTO `#__counter` VALUES('Browser-Chrome', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-Android', '0', '');");

###############################

$d->query("
	ALTER TABLE `#__fields`
	ADD INDEX ( `field_type` ) ,
	ADD INDEX ( `field_link` ) ,
	ADD INDEX ( `field_name` )
");

###############################

$d->query("
	ALTER TABLE `#__members`
	CHANGE `member_id` `member_id` INT( 255 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
	CHANGE `member_name` `member_name` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
	CHANGE `member_key` `member_key` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `member_password` `member_password` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
	CHANGE `member_status` `member_status` INT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
	CHANGE `member_group` `member_group` INT( 255 ) NOT NULL DEFAULT '4' ,
	ADD `member_location` VARCHAR( 150 ) NOT NULL AFTER `member_nationality`
");

###############################

$d->query("
	ALTER TABLE `#__member_groups`
	CHANGE `group_id` `group_id` INT( 255 ) NOT NULL AUTO_INCREMENT ,
	CHANGE `group_name` `group_name` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
	CHANGE `group_title` `group_title` VARCHAR( 400 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 
");

###############################

$d->query("INSERT INTO `#__options` VALUES('editor-color', '#D9D9D9', '1');");
$d->query("INSERT INTO `#__options` VALUES('smtp-host', '', '1');");
$d->query("INSERT INTO `#__options` VALUES('smtp-port', '25', '1');");
$d->query("INSERT INTO `#__options` VALUES('smtp-username', '', '1');");
$d->query("INSERT INTO `#__options` VALUES('smtp-password', '', '1');");
$d->query("INSERT INTO `#__options` VALUES('url-correction', '1', '1');");
$d->query("INSERT INTO `#__options` VALUES('antiflood', '0', '1');");
$d->query("UPDATE `#__options` SET `option_name`='rules' WHERE `option_name`='licence' LIMIT 1");
$d->query("UPDATE `#__options` SET `option_value`='1.0.1' WHERE `option_name`='version' LIMIT 1");

$query = $d->query("SELECT * FROM `#__options` WHERE `option_name`='posts' LIMIT 1");
$row = $d->fetch($query);
$row = unserialize($row['option_value']);
$fields = explode("\n", $row['fields']);
$row['fields'] = array();

if (count($fields))
{
	foreach ($fields as $f)
	{
		$f = trim($f);
		if ($f == '') continue;
		$row['fields'][$f] = array(
			'name' => $f,
			'title' => $f,
			'type' => 'textarea',
			'default' => null,
			'require' => 0
		);
	}
}

$row = serialize($row);
$d->query("UPDATE `#__options` SET `option_value`='".$d->escape_string($row)."' WHERE `option_name`='posts' LIMIT 1");

###############################

$d->query("DROP TABLE IF EXISTS `#__newsletter`;");
$d->query("DELETE FROM `#__modules` WHERE `module_name` = 'newsletter';");
$d->query("UPDATE `#__modules` SET `module_name`='rules' WHERE `module_name`='licence' LIMIT 1");
$d->query("INSERT INTO `#__modules` VALUES('private-messages', '1.0', '1');");

###############################

$query = $d->query("
	SELECT p.page_author, m.member_id
	FROM #__pages AS p	
	LEFT JOIN #__members AS m ON m.member_name=p.page_author
	GROUP BY p.page_author
	ORDER BY p.page_author ASC
");
if ($d->num_rows($query) >= 1)
{
	while($row = $d->fetch($query)) 
	{
		$d->query("UPDATE `#__pages` SET `page_author`='".$row['member_id']."' WHERE `page_author`='".$row['page_author']."';");
	}
	$d->free_result($query);
}

$d->query("
	ALTER TABLE `#__pages`
	CHANGE `page_id` `page_id` INT( 255 ) NOT NULL AUTO_INCREMENT ,
	CHANGE `page_slug` `page_slug` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `page_time` `page_time` INT( 10 ) NOT NULL DEFAULT '0',
	CHANGE `page_author` `page_author` INT( 255 ) NOT NULL ,
	ADD `page_comment` INT( 1 ) NOT NULL DEFAULT '1' AFTER `page_view` ,
	ADD `page_comment_count` INT( 255 ) NOT NULL DEFAULT '0' AFTER `page_comment` ,
	ADD INDEX ( `page_slug` )
");

###############################

$query = $d->query("
	SELECT p.post_author, m.member_id
	FROM #__posts AS p	
	LEFT JOIN #__members AS m ON m.member_name=p.post_author
	GROUP BY p.post_author
	ORDER BY p.post_author ASC
");
if ($d->num_rows($query) >= 1)
{
	while($row = $d->fetch($query)) 
	{
		$d->query("UPDATE `#__posts` SET `post_author`='".$row['member_id']."' WHERE `post_author`='".$row['post_author']."';");
	}
	$d->free_result($query);
}

$d->query("
	ALTER TABLE `#__posts`
	CHANGE `post_author` `post_author` INT( 255 ) NOT NULL ,
	CHANGE `post_date` `post_date` INT( 10 ) NOT NULL ,
	CHANGE `post_name` `post_name` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `post_fixed` `post_fixed` INT( 1 ) NOT NULL DEFAULT '0' ,
	CHANGE `post_approve` `post_approve` INT( 1 ) NOT NULL DEFAULT '0' ,
	ADD INDEX ( `post_author` ) ,
	ADD INDEX ( `post_name` ) ,
	ADD INDEX ( `post_categories` ) ,
	ADD INDEX ( `post_tags` )
");

###############################

$d->query("
	ALTER TABLE `#__private_messages`
	CHANGE `msg_sender` `msg_sender` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `msg_receiver` `msg_receiver` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `msg_date` `msg_date` INT( 10 ) NOT NULL
");

###############################

$d->query("
	ALTER TABLE `#__referer`
	CHANGE `ref_id` `ref_id` INT( 255 ) NOT NULL AUTO_INCREMENT ,
	CHANGE `ref_url` `ref_url` VARCHAR( 600 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
	CHANGE `ref_time` `ref_time` INT( 10 ) NOT NULL ,
	CHANGE `ref_ip` `ref_ip` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	ADD INDEX ( `ref_url` )
");

###############################

$d->query("
	ALTER TABLE `#__search`
	CHANGE `search_id` `search_id` INT( 255 ) NOT NULL AUTO_INCREMENT ,
	CHANGE `search_author` `search_author` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
	CHANGE `search_date` `search_date` INT( 10 ) NOT NULL ,
	CHANGE `search_module` `search_module` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
	CHANGE `search_time` `search_time` INT( 10 ) NULL DEFAULT NULL ,
	ADD INDEX ( `search_key` ) ,
	ADD INDEX ( `search_module` ) ,
	ADD INDEX ( `search_title` )
");

###############################

$d->query("
	ALTER TABLE `#__session`
	CHANGE `session_member` `session_member` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
	CHANGE `session_time` `session_time` INT( 10 ) NOT NULL ,
	CHANGE `session_ip` `session_ip` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
	CHANGE `session_page` `session_page` VARCHAR( 600 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	DROP INDEX `time` ,
	DROP INDEX `guest` ,
	ADD PRIMARY KEY(`session_member`)
");

###############################

$d->query("
	ALTER TABLE `#__shoutbox`
	CHANGE `shout_time` `shout_time` INT( 10 ) NOT NULL ,
	CHANGE `shout_member` `shout_member` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `shout_message` `shout_message` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 
");

###############################

$d->query("
	ALTER TABLE `#__terms`
	CHANGE `term_id` `term_id` INT( 255 ) NOT NULL AUTO_INCREMENT ,
	ADD INDEX ( `term_type` ) ,
	ADD INDEX ( `term_name` ) ,
	ADD INDEX ( `term_slug` )
");

###############################

$d->query("
	ALTER TABLE `#__voting`
	CHANGE `vote_id` `vote_id` INT( 255 ) NOT NULL AUTO_INCREMENT ,
	CHANGE `vote_date` `vote_date` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	CHANGE `vote_language` `vote_language` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL 
");

?>