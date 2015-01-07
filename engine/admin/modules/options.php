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

member::check_admin_page_access('options') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

function _index()
{
	global $options, $page, $d, $modules, $tpl;

	require_once(engine_dir.'editor.function.php');

	if (!$options['rules'] = get_cache('options-rules'))
	{
		$options['rules'] = $d->get_row("SELECT * FROM `#__options` WHERE `option_name`='rules'");
		$options['rules'] = $options['rules'][0]['option_value'];
		set_cache('options-rules', $options['rules'], false);
	}

	if (!$options['offline-message'] = get_cache('options-offline-message'))
	{
		$options['offline-message'] = $d->get_row("SELECT * FROM `#__options` WHERE `option_name`='offline-message'");
		$options['offline-message'] = $options['offline-message'][0]['option_value'];
		set_cache('options-offline-message', $options['offline-message'], false);
	}
	
	if (!$options['comments'] = get_cache('options-comments'))
	{
		$options['comments'] = $d->get_row("SELECT * FROM `#__options` WHERE `option_name`='comments'");
		$options['comments'] = maybe_unserialize($options['comments'][0]['option_value']);
		set_cache('options-comments', $options['comments']);
	}

	set_title('تنظیمات سیستم');
	set_head('<script type="text/javascript" src="'.url.'engine/javascript/jscolor/jscolor.js"></script>');

	foreach($modules as $mod)
	{
		if (is_module($mod['module_name'])) $m[$mod['module_name']] = $mod['module_name'];
	}

	$itpl = new template('options.tpl', root_dir.'engine/admin/template/');

	$itpl->assign(array(
		'{default-module}' => html::select('options[default-module]', $m, $options['default-module'], 'dir="ltr"'),
		'{offline-message}' => wysiwyg_textarea('options[offline-message]', $options['offline-message']),
		'{mail}' => $options['mail'],
		'{editor-color}' => $options['editor-color'],
		'{admin-key}' => $options['admin'],
		'{feed-limit}' => $options['feed-limit'],
		'{separator-rewrite}' => $options['separator-rewrite'],
		'{file-rewrite}' => $options['file-rewrite'],
		'{keywords}' => htmlspecialchars(str_replace(',', ', ', $options['meta-keys'])),
		'{rules}' => wysiwyg_textarea('options[rules]', $options['rules']),
		'{comment-limit}' => $options['comments']['limit'],
		'{smtp-host}' => $options['smtp-host'],
		'{smtp-port}' => $options['smtp-port'],
		'{smtp-username}' => $options['smtp-username'],
		'{smtp-password}' => htmlspecialchars($options['smtp-password']),
	));

	if ($options['antiflood'] == 1)
	{
		$itpl->assign(array(
			'[anti-flood]' => null,
			'[/anti-flood]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[anti-flood\\](.*?)\\[/anti-flood\\]#s', '');
	}
	
	if ($options['http-referer'] == 1)
	{
		$itpl->assign(array(
			'[referer]' => null,
			'[/referer]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[referer\\](.*?)\\[/referer\\]#s', '');
	}
	
	if ($options['replace-link'] == 1)
	{
		$itpl->assign(array(
			'[replace]' => null,
			'[/replace]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[replace\\](.*?)\\[/replace\\]#s', '');
	}
	
	if ($options['rewrite'] == 1)
	{
		$itpl->assign(array(
			'[rewrite-on]' => null,
			'[/rewrite-on]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[rewrite-on\\](.*?)\\[/rewrite-on\\]#s', '');
	}
	
	if ($options['url-correction'] == 1)
	{
		$itpl->assign(array(
			'[correction]' => null,
			'[/correction]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[correction\\](.*?)\\[/correction\\]#s', '');
	}
	
	if ($options['comments']['post-guest'] == 1)
	{
		$itpl->assign(array(
			'[comment-guest]' => null,
			'[/comment-guest]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[comment-guest\\](.*?)\\[/comment-guest\\]#s', '');
	}
	
	if ($options['comments']['editor'] == 1)
	{
		$itpl->assign(array(
			'[comment-editor]' => null,
			'[/comment-editor]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[comment-editor\\](.*?)\\[/comment-editor\\]#s', '');
	}
	
	if ($options['comments']['email'] == 1)
	{
		$itpl->assign(array(
			'[comment-email]' => null,
			'[/comment-email]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[comment-email\\](.*?)\\[/comment-email\\]#s', '');
	}
	
	if ($options['comments']['approve'] == 1)
	{
		$itpl->assign(array(
			'[comment-approve]' => null,
			'[/comment-approve]' => null,
		));
	}
	else
	{
		$itpl->block('#\\[comment-approve\\](.*?)\\[/comment-approve\\]#s', '');
	}
	
	if ($options['offline'] == 1)
	{
		$itpl->assign(array(
			'[offline-on]' => null,
			'[/offline-on]' => null,
		));
		$itpl->block('#\\[offline-off\\](.*?)\\[/offline-off\\]#s', '');
	}
	else
	{
		$itpl->assign(array(
			'[offline-off]' => null,
			'[/offline-off]' => null,
		));
		$itpl->block('#\\[offline-on\\](.*?)\\[/offline-on\\]#s', '');
	}
	
	$tpl->assign('{content}', $itpl->get_var());
}

function _save()
{
	global $d, $options;
	$save = 0;
	$op = isset($_POST['options'])? $_POST['options'] : null;
	
	if (is_array($op) && count($op))
	{
		$options_names = array(
			'admin', 'default-module', 'title', 'slogan', 'mail', 'editor-color', 'feed-limit', 'http-referer', 'replace-link',
			'rewrite', 'file-rewrite', 'separator-rewrite', 'meta-desc', 'meta-keys', 'comments', 'offline-message', 'offline',
			'rules', 'url-correction', 'antiflood',
			'smtp-host', 'smtp-username', 'smtp-password', 'smtp-port'
		);
		$msg = array();

		$op['admin'] = trim($op['admin']);
		$op['default-module'] = alphabet($op['default-module']);
		$op['title'] = htmlencode($op['title']);
		$op['slogan'] = htmlencode($op['slogan'], 'preview');
		$op['mail'] = apadana_strtolower(nohtml($op['mail']));
		$op['editor-color'] = is_alphabet($op['editor-color'])? '#'.$op['editor-color'] : '#d3d3d3';
		$op['feed-limit'] = intval($op['feed-limit']) <= 0? 1 : intval($op['feed-limit']);
		$op['antiflood'] = isset($op['antiflood']) && $op['antiflood'] == 1? 1 : 0;
		$op['http-referer'] = isset($op['http-referer']) && $op['http-referer'] == 1? 1 : 0;
		$op['replace-link'] = isset($op['replace-link']) && $op['replace-link'] == 1? 1 : 0;
		$op['rewrite'] = isset($op['rewrite']) && $op['rewrite'] == 1? 1 : 0;
		$op['separator-rewrite'] = nohtml($op['separator-rewrite']);
		$op['file-rewrite'] = nohtml($op['file-rewrite']);
		$op['meta-desc'] = nohtml($op['meta-desc']);
		$op['meta-keys'] = nohtml($op['meta-keys']);
		$op['meta-keys'] = str_replace(array('،', '  ', ' , ', ', ', ' ,'), array(',', ' ', ',', ',', ','), $op['meta-keys']);
		$op['url-correction'] = isset($op['url-correction']) && $op['url-correction'] == 1? 1 : 0;

		$op['comments']['limit'] = intval($op['comments']['limit']) <= 100? 100 : intval($op['comments']['limit']);
		$op['comments']['post-guest'] = !isset($op['comments']['post-guest']) || intval($op['comments']['post-guest'])<=0? 0 : 1;
		$op['comments']['editor'] = !isset($op['comments']['editor']) || intval($op['comments']['editor'])<=0? 0 : 1;
		$op['comments']['email'] = !isset($op['comments']['email']) || intval($op['comments']['email'])<=0? 0 : 1;
		$op['comments']['approve'] = !isset($op['comments']['approve']) || intval($op['comments']['approve'])<=0? 0 : 1;
		$op['comments'] = serialize($op['comments']);

		$op['offline-message'] = isset($op['offline-message'])? $op['offline-message'] : null;
		$op['offline'] = isset($op['offline']) && $op['offline'] == 1? 1 : 0;
		$op['rules'] = isset($op['rules'])? $op['rules'] : null;

		$op['smtp-host'] = isset($op['smtp-host'])? nohtml($op['smtp-host']) : null;
		$op['smtp-username'] = isset($op['smtp-username'])? nohtml($op['smtp-username']) : null;
		$op['smtp-password'] = isset($op['smtp-password'])? trim($op['smtp-password']) : null;
		$op['smtp-port'] = isset($op['smtp-port'])? intval($op['smtp-port']) : null;

		if (!is_alphabet($op['admin']) || is_dir(root_dir.'modules/'.$op['admin']))
		{
			$msg[] = 'نام انتخابی شما برای کلید بخش مدیریت معتبر نیست!';
		}

		if (!is_module($op['default-module']))
		{
			$msg[] = 'ماژول انتخاب شده برای پبشفرض معتبر نیست!';
		}

		if (!validate_email($op['mail']))
		{
			$msg[] = 'ایمل وارد شده برای سایت معتبر نیست!';
		}
		
		if (empty($op['separator-rewrite']))
		{
			$msg[] = 'جداکننده لینکهای سئو نباید خالی باشد!';
		}
		
		if (count($msg))
		{
			echo message(implode('<br/>', $msg), 'error');
		}
		else
		{
			foreach($op as $name => $value)
			{
				if (!in_array($name, $options_names)) continue;

				$d->update('options', array('option_value'=>trim($value)), "option_name='".$d->escape_string($name)."'", 1);
				if ($d->affected_rows())
				{
					$save++;
					switch($name)
					{
						case 'offline-message':
						remove_cache('options-offline-message');
						break;
						
						case 'rules':
						remove_cache('options-rules');
						break;

						case 'comments':
						remove_cache('options-comments');
						break;
						
						case 'rewrite':
						case 'separator-rewrite':
						case 'file-rewrite':
						remove_cache('sitemap');
						break;
					}
				}
			}
			
			if ($save)
			{
				if ($op['admin'] != $options['admin'])
				{
					refresh('?admin='.$op['admin'].'&section=options', 5);
					echo message('کلید بخش مدیریت تغییر کرده، لطفا صبر کنید تا چند لحظه دیگر صفحه مجدد بارگذاری خواهد شد.<br>کلید جدید: <u dir=ltr>'.$op['admin'].'</u>', 'info');
				}
				echo message('تنظیمات با موفقیت ذخیره شد!', 'success');
				remove_cache('options');
			}
			else
			{
				echo message('در ذخیره تنظیمات خطایی رخ داده مجدد تلاش کنید!', 'error');
			}
		}
	}

	//dump($op);
	exit;
}

if (isset($_GET['do']) && $_GET['do'] == 'save')
	_save();
else
	_index();

?>