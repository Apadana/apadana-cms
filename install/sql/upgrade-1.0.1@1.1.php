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

$d->query("UPDATE `#__options` SET `option_value`='1.1-alpha3' WHERE `option_name`='version' LIMIT 1");
$d->query("INSERT INTO `k5qno_options` VALUES('first_install', '1', '1');");
$d->query("INSERT INTO `k5qno_options` VALUES('allow_change_theme', '1', '1');");


?>