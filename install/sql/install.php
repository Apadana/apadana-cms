<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright (C) 2012-2013 apadanacms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

# --------------------------------------------------------
#
# Table structure for table '#__admin'
#

$d->query("DROP TABLE IF EXISTS `#__admin`;");
$d->query("CREATE TABLE `#__admin` (
  `admin_rights` varchar(20) NOT NULL DEFAULT '',
  `admin_image` varchar(300) NOT NULL DEFAULT '',
  `admin_title` varchar(50) NOT NULL DEFAULT '',
  `admin_link` varchar(100) NOT NULL DEFAULT 'reserved',
  `admin_page` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`admin_rights`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__admin'
#

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
$d->query("INSERT INTO `#__admin` VALUES('files', 'engine/images/admin/files.png', 'فایل های دانلودی', '?admin={admin}&amp;module=files', '2');");


# --------------------------------------------------------
#
# Table structure for table '#__antiflood'
#

$d->query("DROP TABLE IF EXISTS `#__antiflood`;");
$d->query("CREATE TABLE `#__antiflood` (
  `flood_ip` varchar(50) NOT NULL,
  `flood_time` int(10) NOT NULL,
  KEY `flood_ip` (`flood_ip`),
  KEY `flood_time` (`flood_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

# --------------------------------------------------------
#
# Table structure for table '#__banned'
#

$d->query("DROP TABLE IF EXISTS `#__banned`;");
$d->query("CREATE TABLE `#__banned` (
  `ban_id` int(255) NOT NULL AUTO_INCREMENT,
  `ban_ip` varchar(50) NOT NULL,
  `ban_reason` varchar(400) NOT NULL,
  `ban_date` int(10) NOT NULL,
  PRIMARY KEY (`ban_id`),
  KEY `ban_ip` (`ban_ip`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");


# --------------------------------------------------------
#
# Table structure for table '#__blocks'
#

$d->query("DROP TABLE IF EXISTS `#__blocks`;");
$d->query("CREATE TABLE `#__blocks` (
  `block_id` int(255) NOT NULL AUTO_INCREMENT,
  `block_ordering` int(255) NOT NULL,
  `block_position` varchar(100) NOT NULL,
  `block_title` varchar(255) NOT NULL,
  `block_content` text NOT NULL,
  `block_view` int(1) NOT NULL,
  `block_function` varchar(200) NOT NULL,
  `block_access` text NOT NULL,
  `block_access_type` int(1) NOT NULL DEFAULT '1',
  `block_active` int(1) NOT NULL,
  `block_language` varchar(100) NOT NULL DEFAULT 'persian',
  PRIMARY KEY (`block_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__blocks'
#

$d->query("INSERT INTO `#__blocks` VALUES('1', '8', 'right', 'موضوعات', '', '1', 'categories', '', '1', '1', 'persian');");
$d->query("INSERT INTO `#__blocks` VALUES('2', '13', 'top', 'جستجو در سایت', '[- options -]\nsize = 300', '1', 'search', '', '1', '0', '');");
$d->query("INSERT INTO `#__blocks` VALUES('3', '4', 'left', 'سامانه کاربری', '', '1', 'login', '', '1', '1', '');");
$d->query("INSERT INTO `#__blocks` VALUES('4', '12', 'right', 'اطلاعات آماری', '', '1', 'counter', '', '1', '1', '');");
$d->query("INSERT INTO `#__blocks` VALUES('5', '5', 'left', 'صفحات اضافی', '[- options -]\ntotal = 15\norder = desc', '1', 'pages', '', '1', '1', 'persian');");
$d->query("INSERT INTO `#__blocks` VALUES('6', '11', 'right', 'لینکستان', '', '1', 'simple_links', '', '1', '1', '');");
$d->query("INSERT INTO `#__blocks` VALUES('7', '9', 'right', 'جدیدترین مطالب', '[- options -]\ntotal = 15', '1', 'last_posts', '', '1', '1', '');");
$d->query("INSERT INTO `#__blocks` VALUES('8', '2', 'bottom', 'بیشترین بازدید شده ها', '[- options -]\ntotal = 10\nhits = true\norder = desc', '1', 'last_posts', '', '0', '0', '');");
$d->query("INSERT INTO `#__blocks` VALUES('9', '10', 'right', 'نظرسنجی', '', '1', 'voting', '', '1', '1', '');");
$d->query("INSERT INTO `#__blocks` VALUES('10', '1', 'bottom', 'آخرین نظرات', '[- options -]\ntotal = 15\norder = desc', '1', 'posts_comments', '', '1', '0', '');");
$d->query("INSERT INTO `#__blocks` VALUES('11', '6', 'left', 'جعبه پیام', '', '1', 'shoutbox', '', '1', '1', '');");
$d->query("INSERT INTO `#__blocks` VALUES('12', '7', 'left', 'برچسب ها', '[- options -]\ntotal = 50', '1', 'tags_cloud', '', '1', '1', '');");
$d->query("INSERT INTO `#__blocks` VALUES('13', '3', 'left', 'کاربران آنلاین', '', '1', 'onlines', '', '1', '0', '');");


# --------------------------------------------------------
#
# Table structure for table '#__comments'
#

$d->query("DROP TABLE IF EXISTS `#__comments`;");
$d->query("CREATE TABLE `#__comments` (
  `comment_id` int(255) NOT NULL AUTO_INCREMENT,
  `comment_type` varchar(100) NOT NULL,
  `comment_link` int(255) NOT NULL,
  `comment_member_id` int(255) NOT NULL,
  `comment_author` varchar(255) NOT NULL,
  `comment_author_email` varchar(255) NOT NULL,
  `comment_author_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `comment_author_ip` varchar(50) NOT NULL DEFAULT '0.0.0.0',
  `comment_date` int(10) NOT NULL DEFAULT '0',
  `comment_text` text NOT NULL,
  `comment_answer_author` varchar(255) NOT NULL,
  `comment_answer` text NOT NULL,
  `comment_approve` int(1) NOT NULL DEFAULT '0',
  `comment_language` varchar(100) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_type` (`comment_type`),
  KEY `comment_link` (`comment_link`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");


# --------------------------------------------------------
#
# Table structure for table '#__counter'
#

$d->query("DROP TABLE IF EXISTS `#__counter`;");
$d->query("CREATE TABLE `#__counter` (
  `counter_name` varchar(200) NOT NULL,
  `counter_value` varchar(400) NOT NULL,
  `counter_version` varchar(20) NOT NULL,
  KEY `counter_version` (`counter_version`),
  KEY `counter_name` (`counter_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__counter'
#

$d->query("INSERT INTO `#__counter` VALUES('Total', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Internet Explorer', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Firefox', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Chrome', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Mozilla', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Opera', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Netscape', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Konqueror', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Safari', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Galeon', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Epiphany', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-K-Meleon', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-IBrowse', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Camino', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-iCab', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-OmniWeb', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-w3m', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Lynx', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Links', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-ELinks', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Curl', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Voyager', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Amaya', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-Bot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Browser-unknown', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-Windows', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-Linux', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-Android', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-iPhone', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-iPod', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-Mac OS', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-SunOS', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-FreeBSD', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-NetBSD', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-OpenBSD', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-IRIX', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-BeOS', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-OS/2', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-AIX', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-Amiga', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-Darwin', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-HP-UX', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-QNX', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('OS-unknown', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Googlebot-Image', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Mediapartners-Google', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Googlebot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Altavista', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Inktomi', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Yahoo!', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Infoseek', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Nutch', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Fireball', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-AlltheWeb', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Alexa (web.archive.org)', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Alexa', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-WiseNutBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-W3C Validator', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-W3C CSS Validator', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-SurveyBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-QuepasaCreep', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-PHP', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Java', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Overture', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-MSNBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Claymont', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Baiduspider', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Almaden', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Il Trovatore', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Teoma', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Gigabot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Girafabot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-WebCopier', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-HTTrack', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-WGet', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-lwp-request', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-JetBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-NaverBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Larbin', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-ObjectsSearch', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Robozilla', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Walhello appie', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Grub', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Gaisbot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-mozDex', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-GeonaBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Openbot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Boitho', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Pompos', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Exabot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Xenu Link Sleuth', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-W3C-checklink', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Versus', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-FindLinks', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-wwwster', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Steeler', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Ocelli', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-BecomeBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Seekbot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Psbot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-IRLbot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-PhpDig', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-gazz', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-MJ12bot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-getRAX Crawler', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Amfibibot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-GigabotSiteSearch', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-pipeLiner', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-ZipppBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-TurnitinBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-KazoomBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-NetResearchServer', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-gamekitbot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Vagabondo', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-TheSuBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-NPBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Cerberian', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-ConveraCrawler', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-search.ch', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-ichiro', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-CydralSpider', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Szukacz', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Patwebbot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-SpeedySpider', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Mackster', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-thumbshots-de-Bot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Digger', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Zao', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Tutorial Crawler', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-InelaBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-ASPseek', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Francis', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-TutorGigBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-CipinetBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-ES.NET_Crawler', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-eventax', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-stat', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Xaldon WebSpider', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Faxobot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Sherlock', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-Holmes', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-lmspider', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-SeznamBot', '0', '');");
$d->query("INSERT INTO `#__counter` VALUES('Robot-other', '0', '');");

# --------------------------------------------------------
#
# Table structure for table '#__fields'
#

$d->query("DROP TABLE IF EXISTS `#__fields`;");
$d->query("CREATE TABLE `#__fields` (
  `field_type` varchar(50) NOT NULL,
  `field_link` int(255) NOT NULL,
  `field_name` varchar(200) NOT NULL,
  `field_value` longtext NOT NULL,
  KEY `field_type` (`field_type`),
  KEY `field_link` (`field_link`),
  KEY `field_name` (`field_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");


# --------------------------------------------------------
#
# Table structure for table '#__files'
#

$d->query("DROP TABLE IF EXISTS `#__files`;");
$d->query("CREATE TABLE `#__files` (
  `file_id` int(255) NOT NULL AUTO_INCREMENT,
  `file_slug` varchar(200) NOT NULL,
  `file_url` varchar(300) NOT NULL,
  `file_date` int(10) NOT NULL DEFAULT '0',
  `file_author` int(255) NOT NULL DEFAULT '0',
  `file_access` int(1) NOT NULL DEFAULT '1',
  `file_count_downloads` int(255) NOT NULL DEFAULT '0',
  `file_members` text NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `file_slug` (`file_slug`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");


# --------------------------------------------------------
#
# Table structure for table '#__member_groups'
#

$d->query("DROP TABLE IF EXISTS `#__member_groups`;");
$d->query("CREATE TABLE `#__member_groups` (
  `group_id` int(255) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(40) NOT NULL DEFAULT '',
  `group_superAdmin` int(1) NOT NULL DEFAULT '0',
  `group_icon` varchar(200) NOT NULL DEFAULT '',
  `group_title` varchar(400) NOT NULL,
  `group_rights` text NOT NULL,
  `group_admin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__member_groups'
#

$d->query("INSERT INTO `#__member_groups` VALUES('1', 'مدیر کل', '1', 'engine/images/groups/1.png', '<font color=\"red\"><strong>{name}</strong></font>', 'account,backup,banned,blocks,cache,counter,groups,media,modules,newsletter,phpinfo,referer,robots,templates,pages,posts,posts-categories,posts-comments,simple-links,voting,account-op,options,posts-options', '1');");
$d->query("INSERT INTO `#__member_groups` VALUES('2', 'مدیر', '0', 'engine/images/groups/2.png', '<font color=\"green\"><strong>{name}</strong></font>', 'banned,blocks,counter,media,modules,newsletter,referer,extension,files,pages,posts,posts-categories,simple-links,voting,options', '1');");
$d->query("INSERT INTO `#__member_groups` VALUES('3', 'ویرایشگر', '0', 'engine/images/groups/3.png', '<strong>{name}</strong>', 'pages,posts,posts-categories,posts-comments', '1');");
$d->query("INSERT INTO `#__member_groups` VALUES('4', 'عضو سایت', '0', 'engine/images/groups/4.png', '{name}', '', '0');");
$d->query("INSERT INTO `#__member_groups` VALUES('5', 'میهمان', '0', 'engine/images/groups/5.png', '{name}', '', '0');");


# --------------------------------------------------------
#
# Table structure for table '#__members'
#

$d->query("DROP TABLE IF EXISTS `#__members`;");
$d->query("CREATE TABLE `#__members` (
  `member_id` int(255) NOT NULL AUTO_INCREMENT,
  `member_name` varchar(40) NOT NULL DEFAULT '',
  `member_key` varchar(32) NOT NULL,
  `member_password` varchar(32) NOT NULL DEFAULT '',
  `member_email` varchar(100) NOT NULL DEFAULT '',
  `member_avatar` varchar(100) NOT NULL DEFAULT '',
  `member_date` int(10) NOT NULL,
  `member_lastvisit` int(10) NOT NULL DEFAULT '0',
  `member_visits` int(255) NOT NULL DEFAULT '1',
  `member_ip` varchar(45) NOT NULL DEFAULT '0.0.0.0',
  `member_lastip` varchar(45) NOT NULL DEFAULT '0.0.0.0',
  `member_status` int(1) NOT NULL DEFAULT '1',
  `member_web` varchar(200) NOT NULL DEFAULT '',
  `member_alias` varchar(100) NOT NULL,
  `member_signature` varchar(400) NOT NULL,
  `member_group` int(255) NOT NULL DEFAULT '4',
  `member_newsletter` int(1) NOT NULL DEFAULT '1',
  `member_nationality` varchar(200) NOT NULL DEFAULT 'Iran (Islamic Republic of)',
  `member_location` varchar(150) NOT NULL,
  `member_gender` varchar(10) NOT NULL DEFAULT 'male',
  `member_language` varchar(100) NOT NULL,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `member_name` (`member_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__members'
#

$d->query("INSERT INTO `#__members` VALUES('1', 'admin', '', '', '', '', '0', '0', '0', '', '', '1', '', 'مدیر', '', '1', '1', 'Iran (Islamic Republic of)', '', 'male', '');");


# --------------------------------------------------------
#
# Table structure for table '#__modules'
#

$d->query("DROP TABLE IF EXISTS `#__modules`;");
$d->query("CREATE TABLE `#__modules` (
  `module_name` varchar(100) NOT NULL,
  `module_version` varchar(15) NOT NULL DEFAULT '1.0',
  `module_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__modules'
#

$d->query("INSERT INTO `#__modules` VALUES('account', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('contact-us', '1.0', '2');");
$d->query("INSERT INTO `#__modules` VALUES('counter', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('error', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('feed', '1.0', '2');");
$d->query("INSERT INTO `#__modules` VALUES('files', '1.0', '2');");
$d->query("INSERT INTO `#__modules` VALUES('m', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('pages', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('posts', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('private-messages', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('redirect', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('rules', '1.0', '2');");
$d->query("INSERT INTO `#__modules` VALUES('search', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('shoutbox', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('simple-links', '1.0', '1');");
$d->query("INSERT INTO `#__modules` VALUES('sitemap', '1.0', '2');");
$d->query("INSERT INTO `#__modules` VALUES('voting', '1.0', '1');");


# --------------------------------------------------------
#
# Table structure for table '#__options'
#

$d->query("DROP TABLE IF EXISTS `#__options`;");
$d->query("CREATE TABLE `#__options` (
  `option_name` varchar(100) NOT NULL,
  `option_value` longtext NOT NULL,
  `autoload` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__options'
#

$d->query("INSERT INTO `#__options` VALUES('title', 'سیستم مدیریت محتوای آپادانا', '1');");
$d->query("INSERT INTO `#__options` VALUES('slogan', 'یک سایت دیگر با آپادانا ایرانی ....', '1');");
$d->query("INSERT INTO `#__options` VALUES('admin', 'iran', '1');");
$d->query("INSERT INTO `#__options` VALUES('language', 'persian', '1');");
$d->query("INSERT INTO `#__options` VALUES('offline', '0', '1');");
$d->query("INSERT INTO `#__options` VALUES('offline-message', '<p>\r\n	سایت به منظور به روزرسانی تا اطلاع ثانوی غیرفعال می باشد، لطفا بعدا مراجعه فرمایید.</p>\r\n<p>\r\n	مدیر سایت</p>', '0');");
$d->query("INSERT INTO `#__options` VALUES('meta-desc', 'یک سایت دیگر با سیستم مدیریت محتوای ایرانی آپادانا', '1');");
$d->query("INSERT INTO `#__options` VALUES('meta-keys', 'رایگان,آپادانا,ایرانی,مدیریت محتوا,PHP,جدید,ساده,قدرتمند,سئو,حرفه ای', '1');");
$d->query("INSERT INTO `#__options` VALUES('default-module', 'posts', '1');");
$d->query("INSERT INTO `#__options` VALUES('theme', 'default', '1');");
$d->query("INSERT INTO `#__options` VALUES('account', 'a:9:{s:8:\"register\";i:1;s:7:\"members\";i:1;s:13:\"members-total\";i:20;s:6:\"avatar\";i:1;s:10:\"avatarsize\";i:40;s:11:\"minUsername\";i:3;s:11:\"minPassword\";i:6;s:5:\"email\";i:1;s:13:\"maxavatardims\";s:7:\"150x150\";}', '0');");
$d->query("INSERT INTO `#__options` VALUES('rules', '', '0');");
$d->query("INSERT INTO `#__options` VALUES('replace-link', '0', '1');");
$d->query("INSERT INTO `#__options` VALUES('last-banned', '".time()."', '1');");
$d->query("INSERT INTO `#__options` VALUES('posts', 'a:5:{s:11:\"total-posts\";i:10;s:14:\"total-category\";i:10;s:9:\"total-tag\";i:10;s:12:\"total-author\";i:10;s:6:\"fields\";a:0:{}}', '0');");
$d->query("INSERT INTO `#__options` VALUES('comments', 'a:5:{s:5:\"limit\";i:400;s:10:\"post-guest\";i:1;s:6:\"editor\";i:1;s:5:\"email\";i:1;s:7:\"approve\";i:1;}', '0');");
$d->query("INSERT INTO `#__options` VALUES('mail', 'no-reply@".domain."', '1');");
$d->query("INSERT INTO `#__options` VALUES('feed-limit', '10', '1');");
$d->query("INSERT INTO `#__options` VALUES('rewrite', '0', '1');");
$d->query("INSERT INTO `#__options` VALUES('http-referer', '1', '1');");
$d->query("INSERT INTO `#__options` VALUES('separator-rewrite', '/', '1');");
$d->query("INSERT INTO `#__options` VALUES('file-rewrite', '.html', '1');");
$d->query("INSERT INTO `#__options` VALUES('editor-color', '#D9D9D9', '1');");
$d->query("INSERT INTO `#__options` VALUES('smtp-host', '', '1');");
$d->query("INSERT INTO `#__options` VALUES('smtp-port', '25', '1');");
$d->query("INSERT INTO `#__options` VALUES('smtp-username', '', '1');");
$d->query("INSERT INTO `#__options` VALUES('smtp-password', '', '1');");
$d->query("INSERT INTO `#__options` VALUES('url-correction', '1', '1');");
$d->query("INSERT INTO `#__options` VALUES('antiflood', '0', '1');");
$d->query("INSERT INTO `#__options` VALUES('version', '1.0', '1');");


# --------------------------------------------------------
#
# Table structure for table '#__pages'
#

$d->query("DROP TABLE IF EXISTS `#__pages`;");
$d->query("CREATE TABLE `#__pages` (
  `page_id` int(255) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(300) NOT NULL,
  `page_slug` varchar(200) NOT NULL,
  `page_time` int(10) NOT NULL DEFAULT '0',
  `page_author` int(255) NOT NULL DEFAULT '0',
  `page_text` longtext NOT NULL,
  `page_theme` varchar(200) NOT NULL,
  `page_view` int(1) NOT NULL DEFAULT '1',
  `page_comment` int(1) NOT NULL DEFAULT '1',
  `page_comment_count` int(255) NOT NULL DEFAULT '0',
  `page_approve` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
  KEY `page_slug` (`page_slug`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__pages'
#

$d->query("INSERT INTO `#__pages` VALUES('1', 'درباره ما', 'about-us', '".time()."', '1', '<p>\r\n	این یک صفحه آزمایشی است!</p>\r\n<p>\r\n	از بخش مدیریت می توانید آن را حذف و یا ویرایش کنید.</p>\r\n<p>\r\n	برای انتخاب سیستم مدیریت محتوای ایرانی <a href=\"http://www.apadanacms.ir/\" target=\"_blank\" title=\"Apadana\"><strong>آپادانا</strong></a> از شما سپاس گذاریم.</p>', '', '1', '0', '0', '1');");


# --------------------------------------------------------
#
# Table structure for table '#__posts'
#

$d->query("DROP TABLE IF EXISTS `#__posts`;");
$d->query("CREATE TABLE `#__posts` (
  `post_id` int(255) NOT NULL AUTO_INCREMENT,
  `post_author` int(255) NOT NULL,
  `post_date` int(10) NOT NULL,
  `post_title` varchar(255) NOT NULL DEFAULT '',
  `post_name` varchar(250) NOT NULL,
  `post_text` text NOT NULL,
  `post_more` longtext NOT NULL,
  `post_hits` int(255) NOT NULL DEFAULT '0',
  `post_view` int(1) NOT NULL DEFAULT '1',
  `post_categories` varchar(500) NOT NULL,
  `post_tags` varchar(500) NOT NULL,
  `post_comment` int(1) NOT NULL DEFAULT '1',
  `post_comment_count` int(5) NOT NULL DEFAULT '0',
  `post_image` varchar(200) NOT NULL,
  `post_language` varchar(100) NOT NULL DEFAULT 'persian',
  `post_fixed` int(1) NOT NULL DEFAULT '0',
  `post_approve` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `post_author` (`post_author`),
  KEY `post_name` (`post_name`),
  KEY `post_categories` (`post_categories`(333)),
  KEY `post_tags` (`post_tags`(333))
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__posts'
#

$d->query("INSERT INTO `#__posts` VALUES('1', '1', '".time()."', 'سیستم مدیریت محتوای آپادانا', 'apadana-content-management-system', '<p style=\"text-align: center;\">\r\n	<img alt=\"سیستم مدیریت محتوای ایرانی آپادانا\" src=\"uploads/posts/demo.jpg\" /></p>\r\n<p style=\"text-align: center;\">\r\n	&nbsp;</p>\r\n<p>\r\n	برخی از امکانات سیستم مدیریت محتوای ایرانی آپادانا:</p>\r\n<p>\r\n	مدیریت پست ها به صورت حرفه ای<br />\r\n	امکان ساخت فیلدهای اضافه برای پست ها<br />\r\n	امکان دانلود پست ها با فرمت PDF<br />\r\n	امکان ساخت نسخه پرینت برای پست ها<br />\r\n	امکان نمایش مطالب به گروه خاصی از کاربران<br />\r\n	امکان ایجاد پست ثابت<br />\r\n	امکان ارسال پست برای آینده<br />\r\n	سامانه کاربری قدرتمند برای جذب کاربر<br />\r\n	امکان ساخت بی نهایت گروه کاربری<br />\r\n	امکان ارسال پیام خصوصی بین کاربران<br />\r\n	امکان ارسال خبرنامه برای اعضای سایت<br />\r\n	امکان استفاده از smtp برای ارسال ایمیل<br />\r\n	مدیریت بلوک ها به صورت ایجکس و با امکانات فراوان<br />\r\n	دارای جعبه پیام کوتاه به صورت ایجکس<br />\r\n	دارای نسخه موبایل برای نمایش ساده مطالب با امکان طراحی قالب برای آن<br />\r\n	ماژول پذیر و سادگی طراحی ماژول برای آن<br />\r\n	مدیریت تم ها به صورت حرفه ای و با امکان ویرایش آنها از بخش مدیریت<br />\r\n	طراحی قالب به صورت کاملا Html و با تگ گذاری های ساده<br />\r\n	امکان طراحی قالب برای جزء به جزء بخش های سیستم<br />\r\n	آمارگیر حرفه ای با قابلیت نمایش چارتهای آماری در بخش مدیریت<br />\r\n	امکان مسدود سازی آی پی کاربران به صورت حرفه ای<br />\r\n	امکان پشتیبان گیری از دیتابیس با امکان بازگردانی و دانلود پشتیبان به صورت فایل فشرده<br />\r\n	دارای سیستم کشینگ حرفه ای برای افزایش سرعت و بازدهی سیستم<br />\r\n	امکان مدیریت فایل های کش از بخش مدیریت<br />\r\n	ذخیره سازی اطلاعات کامل سایت های لینک دهنده به صورت نامحدود<br />\r\n	دارای بخش مدیریت رسانه ها به صورت حرفه ای و کاملا ایجکس<br />\r\n	استفاده از ادیتور محبوب ckeditor<br />\r\n	پشتیبانی از rss و atom<br />\r\n	امکان ساخت خودکار نقشه برای موتورهای جستجو مانند گوگل و ...<br />\r\n	امکان نمایش پرچم کشور کاربران با استفاده ار بانک آی پی GeoIP<br />\r\n	هماهنگ سازی شده با انجمن ساز محبوب MyBB<br />\r\n	پشتیبانی از BBcode کدها در بخش نظرات و پیام خصوصی<br />\r\n	دارای بخش تماس با ما به صورت ایجکس<br />\r\n	دارای نظرسنجی حرفه ای و به صورت ایجکس<br />\r\n	دارای بخش صفحات مجزا با امکان طراحی قالب اختصاصی برای هر صفحه<br />\r\n	امکان ارسال نظر توسط کاربران برای پست ها و صفحات اضافی<br />\r\n	دارای بخش جستجو به صورت کاملا حرفه ای و پیشرفته<br />\r\n	دارای بخش قوانین برای سایت<br />\r\n	امکان غیرفعال سازی سایت به صورت موقت<br />\r\n	دارای لینک های سئو با قابلیت شخصی سازی<br />\r\n	بخش مدیریت به صورت ایجکس</p>', '', '0', '1', '2', '4,5,6,7,8', '1', '0', '', 'persian', '1', '1');");


# --------------------------------------------------------
#
# Table structure for table '#__private_messages'
#

$d->query("DROP TABLE IF EXISTS `#__private_messages`;");
$d->query("CREATE TABLE `#__private_messages` (
  `msg_id` int(255) NOT NULL AUTO_INCREMENT,
  `msg_sender` varchar(40) NOT NULL,
  `msg_receiver` varchar(40) NOT NULL,
  `msg_subject` varchar(200) NOT NULL,
  `msg_text` text NOT NULL,
  `msg_date` int(10) NOT NULL,
  `msg_read` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");


# --------------------------------------------------------
#
# Table structure for table '#__referer'
#

$d->query("DROP TABLE IF EXISTS `#__referer`;");
$d->query("CREATE TABLE `#__referer` (
  `ref_id` int(255) NOT NULL AUTO_INCREMENT,
  `ref_url` varchar(600) NOT NULL DEFAULT '',
  `ref_domain` varchar(300) NOT NULL,
  `ref_time` int(10) NOT NULL,
  `ref_ip` varchar(50) NOT NULL,
  PRIMARY KEY (`ref_id`),
  KEY `ref_url` (`ref_url`(333))
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");


# --------------------------------------------------------
#
# Table structure for table '#__search'
#

$d->query("DROP TABLE IF EXISTS `#__search`;");
$d->query("CREATE TABLE `#__search` (
  `search_id` int(255) NOT NULL AUTO_INCREMENT,
  `search_key` varchar(32) DEFAULT NULL,
  `search_title` varchar(150) DEFAULT NULL,
  `search_author` varchar(40) DEFAULT NULL,
  `search_content` text,
  `search_date` int(10) NOT NULL,
  `search_module` varchar(100) DEFAULT NULL,
  `search_url` varchar(500) DEFAULT NULL,
  `search_time` int(10) DEFAULT NULL,
  `search_keywords` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`search_id`),
  KEY `search_key` (`search_key`),
  KEY `search_module` (`search_module`),
  KEY `search_title` (`search_title`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");


# --------------------------------------------------------
#
# Table structure for table '#__session'
#

$d->query("DROP TABLE IF EXISTS `#__session`;");
$d->query("CREATE TABLE `#__session` (
  `session_member` varchar(40) NOT NULL DEFAULT '',
  `session_time` int(10) NOT NULL,
  `session_ip` varchar(50) NOT NULL DEFAULT '',
  `session_page` varchar(600) NOT NULL,
  `session_guest` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_member`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");


# --------------------------------------------------------
#
# Table structure for table '#__shoutbox'
#

$d->query("DROP TABLE IF EXISTS `#__shoutbox`;");
$d->query("CREATE TABLE `#__shoutbox` (
  `shout_id` int(255) NOT NULL AUTO_INCREMENT,
  `shout_time` int(10) NOT NULL,
  `shout_member` varchar(40) NOT NULL,
  `shout_message` varchar(300) NOT NULL,
  PRIMARY KEY (`shout_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__shoutbox'
#

$d->query("INSERT INTO `#__shoutbox` VALUES('1', '".time()."', 'admin', 'گروه آپادانا افتتاح سایت شما را تبریک می گوید!');");

# --------------------------------------------------------
#
# Table structure for table '#__simple_links'
#

$d->query("DROP TABLE IF EXISTS `#__simple_links`;");
$d->query("CREATE TABLE `#__simple_links` (
  `link_id` int(255) NOT NULL AUTO_INCREMENT,
  `link_title` varchar(100) NOT NULL,
  `link_description` varchar(400) NOT NULL,
  `link_href` varchar(600) NOT NULL,
  `link_target` varchar(10) NOT NULL,
  `link_direct_link` int(1) NOT NULL DEFAULT '1',
  `link_color` varchar(100) NOT NULL DEFAULT '',
  `link_bold` int(1) NOT NULL DEFAULT '0',
  `link_strikethrough` int(1) NOT NULL DEFAULT '0',
  `link_active` int(1) NOT NULL DEFAULT '1',
  `link_language` varchar(100) NOT NULL DEFAULT 'persian',
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__simple_links'
#

$d->query("INSERT INTO `#__simple_links` VALUES('1', 'آپادانا', 'سیستم مدیریت محتوای ایرانی آپادانا', 'http://www.apadanacms.ir/', '_blank', '1', '#DE0000', '1', '0', '1', 'persian');");
$d->query("INSERT INTO `#__simple_links` VALUES('2', 'ایران', '', 'http://iran.ir', '_self', '1', '', '0', '0', '1', 'persian');");


# --------------------------------------------------------
#
# Table structure for table '#__terms'
#

$d->query("DROP TABLE IF EXISTS `#__terms`;");
$d->query("CREATE TABLE `#__terms` (
  `term_id` int(255) NOT NULL AUTO_INCREMENT,
  `term_type` varchar(15) NOT NULL DEFAULT 'p-tag',
  `term_name` varchar(200) NOT NULL DEFAULT '',
  `term_description` varchar(300) NOT NULL,
  `term_slug` varchar(200) NOT NULL DEFAULT '',
  `term_parent` int(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `term_type` (`term_type`),
  KEY `term_name` (`term_name`),
  KEY `term_slug` (`term_slug`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__terms'
#

$d->query("INSERT INTO `#__terms` VALUES('1', 'p-cat', 'عمومی', 'توضیحات عمومی ..', 'general', '0');");
$d->query("INSERT INTO `#__terms` VALUES('2', 'p-cat', 'اخبار', '', 'news', '1');");
$d->query("INSERT INTO `#__terms` VALUES('3', 'p-cat', 'وب', '', 'web', '1');");
$d->query("INSERT INTO `#__terms` VALUES('4', 'p-tag', 'رایگان', '', '%D8%B1%D8%A7%DB%8C%DA%AF%D8%A7%D9%86', '0');");
$d->query("INSERT INTO `#__terms` VALUES('5', 'p-tag', 'آپادانا', '', '%D8%A2%D9%BE%D8%A7%D8%AF%D8%A7%D9%86%D8%A7', '0');");
$d->query("INSERT INTO `#__terms` VALUES('6', 'p-tag', 'مدیریت محتوا', '', '%D9%85%D8%AF%DB%8C%D8%B1%DB%8C%D8%AA-%D9%85%D8%AD%D8%AA%D9%88%D8%A7', '0');");
$d->query("INSERT INTO `#__terms` VALUES('7', 'p-tag', 'سیستم', '', '%D8%B3%DB%8C%D8%B3%D8%AA%D9%85', '0');");
$d->query("INSERT INTO `#__terms` VALUES('8', 'p-tag', 'متن باز', '', '%D9%85%D8%AA%D9%86-%D8%A8%D8%A7%D8%B2', '0');");


# --------------------------------------------------------
#
# Table structure for table '#__voting'
#

$d->query("DROP TABLE IF EXISTS `#__voting`;");
$d->query("CREATE TABLE `#__voting` (
  `vote_id` int(255) NOT NULL AUTO_INCREMENT,
  `vote_title` varchar(255) NOT NULL DEFAULT '',
  `vote_case` text NOT NULL,
  `vote_ip` text,
  `vote_members` text,
  `vote_date` int(10) NOT NULL,
  `vote_result` text,
  `vote_button` varchar(200) NOT NULL DEFAULT 'Submit',
  `vote_status` int(1) NOT NULL DEFAULT '0',
  `vote_language` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`vote_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");


#
# Dumping data for table '#__voting'
#

$d->query("INSERT INTO `#__voting` VALUES('1', 'آپادانا از نظر شما؟', 'بسیارعالی|عالی|خوب|امیدوار کننده|بد نیست|خوب نیست|نظری ندارم!', '', '', '".time()."', '0,0,0,0,0,0,0,0', 'ثبت رای', '1', '');");

?>