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

function module_files_run()
{
	global $d, $tpl, $options;
	
	if ($options['rewrite'] == 1)
	{
		$slug = isset($_GET['b'])? urlencode($_GET['b']) : null;
	}
	else
	{
		$slug = isset($_GET['b'])? intval($_GET['b']) : 0;
	}

	if ($slug == '' || ($options['rewrite'] != 1 && is_numeric($slug) && $slug <= 0))
	{
		module_error_run('404');
	}
	else
	{
		($hook = get_hook('module_files_start'))? eval($hook) : null;

		$query = $d->query("SELECT * FROM `#__files` WHERE `file_".($options['rewrite'] == 1? 'slug' : 'id')."` = '".$d->escapeString($slug)."' LIMIT 1");
		if ($d->numRows($query) <= 0)
		{
			if ($options['rewrite'] == 1 && isnum($slug))
			{
				$query = $d->query("SELECT * FROM `#__files` WHERE `file_id`='".intval($slug)."' LIMIT 1");
			}
			else
			{
				module_error_run('404');
			}
		}
		if ($d->numRows($query) <= 0)
		{
			module_error_run('404');
		}
		else
		{
			$row = $d->fetch();
			set_title('دانلود فایل');
			set_meta('robots', 'noindex, nofollow');
			set_canonical(url('files/'.($options['rewrite'] == 1? $row['file_slug'] : $row['file_id'])));

			if ($row['file_access'] == 2 && !member) $access = message('فقط کاربران عضو سایت می تواننداین فایل را دانلود کنند!', 'error');
			elseif ($row['file_access'] == 3 && member) $access = message('فقط کاربران مهمان می تواننداین فایل را دانلود کنند!', 'error');
			elseif ($row['file_access'] == 4 && !group_admin) $access = message('فقط مدیران می تواننداین فایل را دانلود کنند!', 'error');
			elseif ($row['file_access'] == 5 && !group_super_admin) $access = message('فقط مدیران کل سایت می توانند این فایل را دانلود کنند!', 'error');
			
			if (isset($access))
			{
				set_content('دانلود فایل '.basename($row['file_url']), $access);
			}
			else
			{
				require_once(engine_dir.'captcha.function.php');
				$html = null;

				if (isset($_POST['captcha']) || isset($_SESSION['file-checked'][$row['file_id']]))
				{
					if (!isset($_SESSION['file-checked'][$row['file_id']]) && !validate_captcha('file', $_POST['captcha']))
					{
						$html = message('کد امنیتی را صحیح وارد نکرده اید!', 'error');
					}
					else
					{
						if (file_exists(root_dir.$row['file_url']) && is_readable(root_dir.$row['file_url']))
						{
							$_SESSION['file-checked'][$row['file_id']] = true;
							require_once(engine_dir.'httpdownload.class.php');
							remove_captcha('file');

							if (member == 1)
							{
								$row['file_members'] = explode(',', $row['file_members']);
								if (!in_array(member_name, $row['file_members']))
								{
									$row['file_members'][] = member_name;
								}
								$members = implode(',', $row['file_members']);
							}
							else
							{
								$members = $row['file_members'];
							}

							$d->update('files', array(
								'file_members' => $members,
								'file_count_downloads' => $row['file_count_downloads']+1,
							), "file_id='".$row['file_id']."'", 1);

							unset($members, $row['file_members']);

							($hook = get_hook('module_files_download'))? eval($hook) : null;

							$object = new httpdownload;
							$object->set_byfile(root_dir.$row['file_url']);
							$object->use_resume = false;
							$object->download();
							exit;
						}
						else
						{
							$html = message('فایل یافت نشد، لطفا مسئله را به مدیران گزارش دهید!', 'error');
						}
					}
				}
				else
				{
					$html = message('برای دانلود لطفا کد امنیتی زیر را وارد کنید.', 'info');
				}

				$html .= '<form action="'.url('files/'.($options['rewrite'] == 1? $row['file_slug'] : $row['file_id'])).'" method="post"><center>';
				$html .= 'کد امنیتی:&nbsp;<input name="captcha" style="width:50px; text-align:center;direction:ltr" />&nbsp;';
				$html .= create_captcha('file');
				$html .= '<br/><input type="submit" value="دانلود فایل" />';
				$html .= '</center></form>';

				($hook = get_hook('module_files'))? eval($hook) : null;

				set_content('دانلود فایل '.basename($row['file_url']), $html);
			}
		}

		($hook = get_hook('module_files_end'))? eval($hook) : null;
	}
}

?>