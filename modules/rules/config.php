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

function module_rules_run()
{
	global $tpl, $page, $d, $options;

	if(!$options['rules'] = get_cache('options-rules'))
	{
		$options['rules'] = $d->get_row("SELECT * FROM `#__options` WHERE `option_name`='rules'");
		$options['rules'] = $options['rules'][0]['option_value'];
		set_cache('options-rules', $options['rules'], false);
	}

	set_theme('rules');
	set_title('قوانین');
	set_meta('description', 'قوانین سایت', 'add');
	set_canonical(url('rules'));
	set_content('قوانین سایت', empty($options['rules'])? message('فعلا قانونی نیست!', 'info') : $options['rules']);

	($hook = get_hook('module_rules'))? eval($hook) : null;
}

function module_licence_sitemap($sitemap)
{
	$sitemap->addItem(url('rules'), 0, 'monthly', '0.6');
}

?>