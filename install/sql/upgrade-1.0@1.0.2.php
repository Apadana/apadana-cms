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

$d->query("UPDATE `#__options` SET `option_value`='1.0.2' WHERE `option_name`='version' LIMIT 1");
$d->query("UPDATE `#__modules` SET `module_version`='1.0.1' WHERE `module_name`='files' LIMIT 1");
$d->query("UPDATE `#__modules` SET `module_version`='1.0.1' WHERE `module_name`='m' LIMIT 1");
$d->query("UPDATE `#__modules` SET `module_version`='1.0.1' WHERE `module_name`='pages' LIMIT 1");
$d->query("UPDATE `#__modules` SET `module_version`='1.0.1' WHERE `module_name`='posts' LIMIT 1");
$d->query("UPDATE `#__modules` SET `module_version`='1.0.1' WHERE `module_name`='private-messages' LIMIT 1");
$d->query("UPDATE `#__modules` SET `module_version`='1.0.1' WHERE `module_name`='shoutbox' LIMIT 1");

?>