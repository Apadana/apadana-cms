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

class shoutbox
{
	public function insert($msg)
	{
		if (member != 1)
		{
			echo '<div class="shoutbox-error">فقط اعضا می توانند پیام ارسال کنند!</div>';
		}
		else
		{
			global $d;
			$msg = nohtml($msg);
			$msg = str_replace('[', '&#x5B;', $msg);
			$msg = str_replace('{', '&#x7B;', $msg);
			$msg = preg_replace('#\s{2,}#', ' ', $msg);

			($hook = get_hook('shoutbox_insert_start'))? eval($hook) : null;

			if (!group_admin && $d->num_rows("SELECT `shout_id` FROM `#__shoutbox` WHERE `shout_member`='".member_name."' AND `shout_time`>'".(time_now-30)."'", true) > 0)
			{
				echo '<div class="shoutbox-error">میان هر پیام باید 30 ثانیه زمان باشد!</div>';
			}
			else
			{
				if (empty($msg) || $msg == 'پیام شما ...')
				{
					echo '<div class="shoutbox-error">متن پیام را ننوشته اید!</div>';
				}
				else
				{
					if (apadana_strlen($msg) > 250)
					{
						echo '<div class="shoutbox-error">پیام شما '.apadana_strlen($msg).' حرف است!<br>250 حرف می توانید استفاده کنید!</div>';
						echo '<script>apadana.$("shoutbox-textarea").style.border="#CC0000 1px solid"</script>';
					}
					else
					{
						$array = array(
							'shout_time' => time_now,
							'shout_member' => member_name,
							'shout_message' => $msg
						);
						
						($hook = get_hook('shoutbox_insert_query'))? eval($hook) : null;

						$d->insert('shoutbox', $array);
						echo '<script>apadana.value("shoutbox-textarea", "")</script>';
					}
				}
			}

			($hook = get_hook('shoutbox_insert_end'))? eval($hook) : null;
		}
		$this->getMessages();
	}
	
	public function delete($id = 0)
	{
		global $d;
		$id = intval($id);

		$d->query("SELECT `shout_member` FROM `#__shoutbox` WHERE `shout_id`='".$id."' LIMIT 1");
		if ($d->num_rows() <= 0)
		{
			echo '<div class="shoutbox-error">این پیام وجود ندارد!</div>';
		}
		else
		{
			$row = $d->fetch();
			if (member_name == $row['shout_member'] || group_admin == 1)
			{
				($hook = get_hook('shoutbox_delete'))? eval($hook) : null;

				$d->delete('shoutbox', "`shout_id`='".$id."'", 1);
				echo '<div class="shoutbox-success">پیام شماره '.$id.' حذف شد!</div>';
			}
			else
			{
				echo '<div class="shoutbox-error">شما اجازه حذف این پیام را ندارید!</div>';
			}
		}
		if (isset($_GET['archive']))
		{
			$this->archive();
		}
		else
		{
			$this->getMessages();
		}
	}
	
	public function getMessages($total = 30)
	{
		global $d;
		$total = intval($total) <= 0? 30 : intval($total);

		$d->query("SELECT * FROM `#__shoutbox` ORDER BY shout_id DESC LIMIT $total");
		if ($d->num_rows() <= 0)
		{
			echo '<div class="shoutbox-error">هیچ پیامی وجود ندارد!</div>';
		}
		else
		{
			$file = get_tpl(root_dir.'modules/shoutbox/html/||block.tpl', template_dir.'||shoutbox/block.tpl');
			$itpl = new template($file[1], $file[0]);
			
			($hook = get_hook('shoutbox_get_messages_start'))? eval($hook) : null;

			while ($row = $d->fetch())
			{
				$itpl->add_for('message', array(
					'{odd-even}' => odd_even(),
					'{id}' => $row['shout_id'],
					'{time}' => jdate('Y-m-d H:i:s', $row['shout_time']),
					'{member}' => $row['shout_member'],
					'{message}' => nl2br(smiles_replace($row['shout_message'])),
					'replace' => array(
						'#\\[delete\\](.*?)\\[/delete\\]#s' => member_name == $row['shout_member'] || group_admin == 1? '\\1' : ''
					)
				));
			}

			($hook = get_hook('shoutbox_get_messages_end'))? eval($hook) : null;

			$itpl->display();
		}
		exit;
	}
	
	public function archive()
	{
		global $tpl, $options, $page, $d;
		require_once(engine_dir.'pagination.class.php');
		set_title('جعبه پیام');
		set_meta('canonical', url('shoutbox'));
		set_head(file_exists(template_dir.'styles/shoutbox.css')? '<link href="'.url.'templates/'.$options['theme'].'/styles/shoutbox.css" type="text/css" rel="stylesheet" />' : '<link href="'.url.'modules/shoutbox/styles/default.css" type="text/css" rel="stylesheet" />');
		set_head('<script type="text/javascript" src="'.url.'modules/shoutbox/javascript/functions.js"></script>');

		$order = get_param($_GET, 'order', 'DESC');
		$order = $order=='DESC'? 'DESC' : 'ASC';
		
		$total = get_param($_GET, 'total', 40);
		$total = $total<=0? 40 : $total;

		$_page = get_param($_GET, 'page', 1);
		$_page = $_page<=0? 1 : $_page;

		$total_shout = $d->num_rows("SELECT `shout_id` FROM `#__shoutbox`", true);

		$pagination = new pagination($total_shout, $total, $_page);

		$file = get_tpl(root_dir.'modules/shoutbox/html/||index.tpl', template_dir.'||shoutbox/archive.tpl');
		$itpl = new template($file[1], $file[0]);

		($hook = get_hook('shoutbox_archive_start'))? eval($hook) : null;

		$d->query("SELECT * FROM `#__shoutbox` ORDER BY `shout_id` {$order} LIMIT $pagination->start, $pagination->end");
		if ($d->num_rows() >= 1)
		{
			while ($row = $d->fetch()) 
			{
				$array = array(
					'{odd-even}' => odd_even(),
					'{id}' => $row['shout_id'],
					'{time}' => jdate('Y-m-d H:i:s', $row['shout_time']),
					'{member}' => $row['shout_member'],
					'{message}' => nl2br(smiles_replace($row['shout_message']))
				);
				
				if (member_name == $row['shout_member'] || group_admin == 1)
				{
					$array['[delete]'] = '';
					$array['[/delete]'] = '';
					$array['replace']['#\\[not-delete\\](.*?)\\[/not-delete\\]#s'] = '';
				}
				else
				{
					$array['[not-delete]'] = '';
					$array['[/not-delete]'] = '';
					$array['replace']['#\\[delete\\](.*?)\\[/delete\\]#s'] = '';
				}
				
				($hook = get_hook('shoutbox_archive_item'))? eval($hook) : null;

				$itpl->add_for('message', $array);
			}
			
			$itpl->assign(array(
				'[shoutbox]' => null,
				'[/shoutbox]' => null,
			));
			$itpl->block('#\\[not-shoutbox\\](.*?)\\[/not-shoutbox\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[not-shoutbox]' => null,
				'[/not-shoutbox]' => null,
			));
			$itpl->block('#\\[shoutbox\\](.*?)\\[/shoutbox\\]#s', '');
		}

		$p = $pagination->build('{page}', true);
		if (is_array($p) && count($p)) 
		{	
			foreach ($p as $link) 
			{
				if (!isset($link['page'])) continue;

				$itpl->add_for('pages', array(
					'{number}' => $link['number'],
					'replace' => array(
						'#\\[selected\\](.*?)\\[/selected\\]#s' => $link['number']==$_page? '\\1' : '',
					),
				));
			}

			$itpl->assign(array(
				'[pages]' => null,
				'[/pages]' => null,
			));
		}
		else
		{
			$itpl->block('#\\[pages\\](.*?)\\[/pages\\]#s', '');
		}
		
		if ($order=='DESC')
		{
			$itpl->assign(array(
				'[desc]' => null,
				'[/desc]' => null,
			));
			$itpl->block('#\\[asc\\](.*?)\\[/asc\\]#s', '');
		}
		else
		{
			$itpl->assign(array(
				'[asc]' => null,
				'[/asc]' => null,
			));
			$itpl->block('#\\[desc\\](.*?)\\[/desc\\]#s', '');
		}

		$itpl->assign(array(
			'{total}' => $total,
			'{order}' => $order,
			'{page}' => $_page,
		));

		($hook = get_hook('shoutbox_archive_end'))? eval($hook) : null;

		if (is_ajax())
		{
			define('no_template', true);
			$itpl->display();
		}
		else
		{
			if (!isset($file[2])) set_content('جعبه پیام', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
		}
		unset($itpl);
	}
}

?>