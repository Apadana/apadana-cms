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

function _browser()
{
	global $tpl, $page, $d, $options;
	_menu();
	require(root_dir.'modules/counter/lib/user_agents.lib.php');

	$ualib_browsers['Bot'] = array();
	$ualib_browsers['Bot']['icon'] = 'robot.png';
	
	set_title('آمار مرورگرها');
	set_canonical(url('counter/browser'));

	$Browser = array();
	$result = $d->query("SELECT * from #__counter WHERE counter_name LIKE '%Browser-%' AND counter_version='' order by counter_name desc");
	while($row = $d->fetch($result))
	{
		$Browser[ $row['counter_name'] ] = $row;
	}
	$d->freeResult($result);

    $counts = 0;
	foreach($Browser as $row)
	{
		$counts += $row['counter_value'];
	}
	unset($result, $row);
	
	$file = get_tpl(root_dir.'modules/counter/html/||browser.tpl', template_dir.'||counter/browser.tpl');
	$itpl = new template($file[1], $file[0]);
	
    $i = 1;
	$progress = 1;
	foreach($Browser as $row)
	{
		$percent = $row['counter_value'] * 100;

		if ($counts!=0)
			$percent /= $counts;
		else
			$percent = 0;

		$percent = floor($percent);
		$percent = $percent>100? 100 : $percent;
		$name = str_replace('Browser-', '', $row['counter_name']);
		
		$itpl->add_for('browser', array(
			'{odd-even}' => odd_even(),
			'{number}' => $i,
			'{percent}' => $percent,
			'{progress}' => $progress,
			'{icon}' => $ualib_browsers[$name]['icon'],
			'{name}' => $name,
			'{count}' => $row['counter_value'],
		));
		
        $i++;
		$progress++;
		if ($progress>5) $progress = 1;
	}
	
	$itpl->assign(array(
		'[not-version]' => null,
		'[/not-version]' => null,
	));
	
	$itpl->block('#\\[version\\](.*?)\\[/version\\]#s', '');
	if (!isset($file[2])) set_content('آمار مرورگرها', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($ualib_robots, $ualib_browsers, $ualib_os, $itpl);
}

function _browser_version()
{
	global $tpl, $page, $d, $options;
	_menu();
	require(root_dir.'modules/counter/lib/user_agents.lib.php');

	$ualib_browsers['Bot'] = array();
	$ualib_browsers['Bot']['icon'] = 'robot.png';

	set_title('آمار مرورگرها');
	$_GET['c'] = get_param($_GET, 'c');
	$_GET['c'] = urldecode($_GET['c']);
	$_GET['c'] = nohtml($_GET['c']);
	
	if (empty($_GET['c']) || !isset($ualib_browsers[$_GET['c']]))
	{
		module_error_run('404');
	}
	else
	{
		$_GET['c'] = $d->escapeString($_GET['c']);
		set_title($_GET['c']);
		set_canonical(url('counter/browser/'.$_GET['c']));

		$Browser = array();
		$result = $d->query("SELECT * from #__counter WHERE counter_name='Browser-".$_GET['c']."' order by counter_name,counter_version desc");
		while($row = $d->fetch($result))
		{
			$Browser[] = $row;
		}
		$d->freeResult($result);

		$counts = 0;
		foreach($Browser as $row)
		{
			if (empty($row['counter_version'])) continue;
			$counts += $row['counter_value'];
		}
		unset($result, $row);
		
		$file = get_tpl(root_dir.'modules/counter/html/||browser.tpl', template_dir.'||counter/browser.tpl');
		$itpl = new template($file[1], $file[0]);
		
		$list = null;
		$i = 1;
		$progress = 1;
		foreach($Browser as $row)
		{
			$percent = $row['counter_value'] * 100;

			if ($counts!=0)
				$percent /= $counts;
			else
				$percent = 0;

			$percent = floor($percent);
			$percent = $percent>100? 100 : $percent;
			$name = str_replace('Browser-', '', $row['counter_name']);

			$array = array(
				'{odd-even}' => odd_even(),
				'{number}' => $i,
				'{percent}' => $percent,
				'{progress}' => $progress,
				'{icon}' => $ualib_browsers[$name]['icon'],
				'{name}' => $name,
				'{count}' => $row['counter_value'],
				'{version}' => empty($row['counter_version'])? '--' : nohtml($row['counter_version']),
			);
			
			if (empty($row['counter_version']))
			{
				$array['[all]'] = null;
				$array['[/all]'] = null;
				$array['replace'] = array(
					'#\\[not-all\\](.*?)\\[/not-all\\]#s' => ''
				);
			}
			else
			{
				$array['[not-all]'] = null;
				$array['[/not-all]'] = null;
				$array['replace'] = array(
					'#\\[all\\](.*?)\\[/all\\]#s' => ''
				);
			}
			
			$itpl->add_for('browser', $array);

			$i++;
			$progress++;
			if ($progress>5) $progress = 1;
		}
		
		$itpl->assign(array(
			'{browser}' => $_GET['c'],
			'[version]' => null,
			'[/version]' => null,
		));
		
		$itpl->block('#\\[not-version\\](.*?)\\[/not-version\\]#s', '');		
		if (!isset($file[2])) set_content('آمار مرورگر '.$_GET['c'], $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
		unset($ualib_robots, $ualib_browsers, $ualib_os, $itpl);
	}
}

function _os()
{
	global $tpl, $page, $d, $options;
	_menu();
	require(root_dir.'modules/counter/lib/user_agents.lib.php');
	
	set_title('سیستم عامل ها');
	set_canonical(url('counter/os'));

	$OS = array();
	$result = $d->query("SELECT * from #__counter WHERE counter_name LIKE '%OS-%' AND counter_version='' order by counter_name desc");
	while($row = $d->fetch($result))
	{
		$OS[ $row['counter_name'] ] = $row;
	}
	$d->freeResult($result);

    $counts = 0;
	foreach($OS as $row)
	{
		$counts += $row['counter_value'];
	}
	unset($result, $row);
	
	$file = get_tpl(root_dir.'modules/counter/html/||os.tpl', template_dir.'||counter/os.tpl');
	$itpl = new template($file[1], $file[0]);
	
    $i = 1;
	$progress = 1;
	foreach($OS as $row)
	{
		$percent = $row['counter_value'] * 100;

		if ($counts!=0)
			$percent /= $counts;
		else
			$percent = 0;

		$percent = floor($percent);
		$percent = $percent>100? 100 : $percent;
		$name = str_replace('OS-', '', $row['counter_name']);
		
		$itpl->add_for('os', array(
			'{odd-even}' => odd_even(),
			'{number}' => $i,
			'{percent}' => $percent,
			'{progress}' => $progress,
			'{icon}' => $ualib_os[$name]['icon'],
			'{name}' => $name,
			'{count}' => $row['counter_value'],
		));
        $i++;
		$progress++;
		if ($progress>5) $progress = 1;
	}
	
	$itpl->assign(array(
		'[not-version]' => null,
		'[/not-version]' => null,
	));
	
	$itpl->block('#\\[version\\](.*?)\\[/version\\]#s', '');		
	if (!isset($file[2])) set_content('سیستم عامل ها', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($ualib_robots, $ualib_browsers, $ualib_os, $itpl);
}

function _os_version()
{
	global $tpl, $page, $d, $options;
	_menu();
	require(root_dir.'modules/counter/lib/user_agents.lib.php');

	set_title('سیستم عامل ها');
	$_GET['c'] = get_param($_GET, 'c');
	$_GET['c'] = urldecode($_GET['c']);
	$_GET['c'] = nohtml($_GET['c']);
	
	if (empty($_GET['c']) || !isset($ualib_os[$_GET['c']]))
	{
		module_error_run('404');
	}
	else
	{
		$_GET['c'] = $d->escapeString($_GET['c']);
		set_title($_GET['c']);
		set_canonical(url('counter/os/'.$_GET['c']));

		$OS = array();
		$result = $d->query("SELECT * from #__counter WHERE counter_name='OS-".$_GET['c']."' order by counter_name,counter_version desc");
		while($row = $d->fetch($result))
		{
			$OS[] = $row;
		}
		$d->freeResult($result);

		$counts = 0;
		foreach($OS as $row)
		{
			if (empty($row['counter_version'])) continue;
			$counts += $row['counter_value'];
		}
		unset($result, $row);
		
		$file = get_tpl(root_dir.'modules/counter/html/||os.tpl', template_dir.'||counter/os.tpl');
		$itpl = new template($file[1], $file[0]);

		$i = 1;
		$progress = 1;
		foreach($OS as $row)
		{
			$percent = $row['counter_value'] * 100;

			if ($counts!=0)
				$percent /= $counts;
			else
				$percent = 0;

			$percent = floor($percent);
			$percent = $percent>100? 100 : $percent;
			$name = str_replace('OS-', '', $row['counter_name']);

			$array = array(
				'{odd-even}' => odd_even(),
				'{number}' => $i,
				'{percent}' => $percent,
				'{progress}' => $progress,
				'{icon}' => $ualib_os[$name]['icon'],
				'{name}' => $name,
				'{count}' => $row['counter_value'],
				'{version}' => empty($row['counter_version'])? '--' : nohtml($row['counter_version']),
			);
			
			if (empty($row['counter_version']))
			{
				$array['[all]'] = null;
				$array['[/all]'] = null;
				$array['replace'] = array(
					'#\\[not-all\\](.*?)\\[/not-all\\]#s' => ''
				);
			}
			else
			{
				$array['[not-all]'] = null;
				$array['[/not-all]'] = null;
				$array['replace'] = array(
					'#\\[all\\](.*?)\\[/all\\]#s' => ''
				);
			}

			$itpl->add_for('os', $array);

			$i++;
			$progress++;
			if ($progress>5) $progress = 1;
		}
		
		$itpl->assign(array(
			'{os}' => $_GET['c'],
			'[version]' => null,
			'[/version]' => null,
		));
		
		$itpl->block('#\\[not-version\\](.*?)\\[/not-version\\]#s', '');		
		if (!isset($file[2])) set_content('سیستم عامل '.$_GET['c'], $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());
		unset($ualib_robots, $ualib_browsers, $ualib_os, $itpl);
	}
}

function _robots()
{
	global $tpl, $page, $d, $options;
	_menu();
	require(root_dir.'modules/counter/lib/user_agents.lib.php');

	$ualib_browsers['Bot'] = array();
	$ualib_browsers['Bot']['icon'] = 'robot.png';
	
	set_title('موتورهای جستجو');
	set_canonical(url('counter/robots'));

	$Robot = array();
	$result = $d->query("SELECT * from #__counter WHERE counter_name LIKE '%Robot-%' AND counter_version='' order by counter_name desc");
	while($row = $d->fetch($result))
	{
		$Robot[ $row['counter_name'] ] = $row;
	}
	$d->freeResult($result);

    $counts = 0;
	foreach($Robot as $row)
	{
		$counts += $row['counter_value'];
	}
	unset($result, $row);
	
	$file = get_tpl(root_dir.'modules/counter/html/||robots.tpl', template_dir.'||counter/robots.tpl');
	$itpl = new template($file[1], $file[0]);
	
    $i = 1;
	$progress = 1;
	foreach($Robot as $row)
	{
		$percent = $row['counter_value'] * 100;

		if ($counts!=0)
			$percent /= $counts;
		else
			$percent = 0;

		$percent = floor($percent);
		$percent = $percent>100? 100 : $percent;
		$name = str_replace('Robot-', '', $row['counter_name']);
		
		$itpl->add_for('robot', array(
			'{odd-even}' => odd_even(),
			'{number}' => $i,
			'{percent}' => $percent,
			'{progress}' => $progress,
			'{icon}' => $ualib_robots[$name]['icon'],
			'{name}' => $name,
			'{count}' => $row['counter_value'],
		));
        $i++;
		$progress++;
		if ($progress>5) $progress = 1;
	}

	if (!isset($file[2])) set_content('موتورهای جستجو', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($ualib_robots, $ualib_browsers, $ualib_os, $itpl);
}

function _year()
{
	global $tpl, $page, $d, $options;
	_menu();
	
	set_title('بازدیدهای سالیانه');
	set_canonical(url('counter/year'));

	$Year = array();
	$result = $d->query("SELECT * from #__counter WHERE counter_name LIKE '%Year-%' AND counter_version='' order by counter_name desc");
	while($row = $d->fetch($result))
	{
		$Year[ $row['counter_name'] ] = $row;
	}
	$d->freeResult($result);

    $counts = 0;
	foreach($Year as $row)
	{
		$counts += $row['counter_value'];
	}
	unset($result, $row);
	
	$file = get_tpl(root_dir.'modules/counter/html/||year.tpl', template_dir.'||counter/year.tpl');
	$itpl = new template($file[1], $file[0]);
	
    $i = 1;
	$progress = 1;
	foreach($Year as $row)
	{
		$percent = $row['counter_value'] * 100;

		if ($counts!=0)
			$percent /= $counts;
		else
			$percent = 0;

		$percent = floor($percent);
		$percent = $percent>100? 100 : $percent;
		$name = str_replace('Year-', '', $row['counter_name']);
		
		$itpl->add_for('year', array(
			'{odd-even}' => odd_even(),
			'{number}' => $i,
			'{percent}' => $percent,
			'{progress}' => $progress,
			'{name}' => $name,
			'{count}' => $row['counter_value'],
		));
        $i++;
		$progress++;
		if ($progress>5) $progress = 1;
	}
	
	if (!isset($file[2])) set_content('بازدیدهای سالیانه', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
	unset($itpl);
}

function _month()
{
	global $tpl, $page, $d, $options;
	_menu();
	
    if (!isset($_GET['c']) || !isnum($_GET['c']) || $_GET['c']>jdate('Y') || strlen($_GET['c'])<4 || strlen($_GET['c'])>4)
	{
		module_error_run('404');
	}
	else
	{
		$result = $d->query("SELECT * from #__counter WHERE counter_name LIKE '%Month-".$_GET['c']."-%' AND counter_version='' order by counter_name desc");
		if ($d->numRows($result)<=0)
		{
			module_error_run('404');
		}
		else
		{
			set_title('بازدیدهای ماهیانه سال '.number2persian($_GET['c']));
			set_canonical(url('counter/month/'.$_GET['c']));

			$Month = array();
			while($row = $d->fetch($result))
			{
				$Month[ $row['counter_name'] ] = $row;
			}
			$d->freeResult($result);

			$counts = 0;
			foreach($Month as $row)
			{
				$counts += $row['counter_value'];
			}
			unset($result, $row);
			
			$file = get_tpl(root_dir.'modules/counter/html/||month.tpl', template_dir.'||counter/month.tpl');
			$itpl = new template($file[1], $file[0]);
			
			$i = 1;
			$progress = 1;
			foreach($Month as $row)
			{
				$percent = $row['counter_value'] * 100;

				if ($counts!=0)
					$percent /= $counts;
				else
					$percent = 0;

				$percent = floor($percent);
				$percent = $percent>100? 100 : $percent;
				$name = str_replace('Month-', '', $row['counter_name']);
				$name = explode('-', $name);
				
				$itpl->add_for('month', array(
					'{odd-even}' => odd_even(),
					'{number}' => $i,
					'{percent}' => $percent,
					'{progress}' => $progress,
					'{name}' => short_monthname($name[1]).' ماه سال '.number2persian($name[0]),
					'{year}' => $name[0],
					'{month}' => $name[1],
					'{count}' => $row['counter_value'],
				));

				$i++;
				$progress++;
				if ($progress>5) $progress = 1;
			}
			
			$itpl->assign('year', number2persian($_GET['c']));
			if (!isset($file[2])) set_content('بازدیدهای ماهیانه سال '.number2persian($_GET['c']), $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
			unset($itpl);
		}
	}
}

function _day()
{
	global $tpl, $page, $d, $options;
	_menu();

    if (!isset($_GET['c']) || !isset($_GET['d']) || !isnum($_GET['c']) || $_GET['c']>jdate('Y') || strlen($_GET['c'])<4 || strlen($_GET['c'])>4 || !isnum($_GET['d']) || $_GET['d']>12 || strlen($_GET['d'])<2 || strlen($_GET['d'])>2)
	{
		module_error_run('404');
	}
	else
	{
		$result = $d->query("SELECT * from #__counter WHERE counter_name LIKE '%Day-".$_GET['c'].'-'.$_GET['d']."-%' AND counter_version='' order by counter_name desc");
		if ($d->numRows($result) <= 0)
		{
			module_error_run('404');
		}
		else
		{
			set_title('آمار روزانه '.short_monthname($_GET['d']).' ماه سال '.number2persian($_GET['c']));
			set_canonical(url('counter/day/'.$_GET['c'].'/'.$_GET['d']));

			$Day = array();
			while($row = $d->fetch($result))
			{
				$Day[ $row['counter_name'] ] = $row;
			}
			$d->freeResult($result);

			$counts = 0;
			foreach($Day as $row)
			{
				$counts += $row['counter_value'];
			}
			unset($result, $row);
			
			$file = get_tpl(root_dir.'modules/counter/html/||day.tpl', template_dir.'||counter/day.tpl');
			$itpl = new template($file[1], $file[0]);
			
			$i = 1;
			$progress = 1;
			foreach($Day as $row)
			{
				$percent = $row['counter_value'] * 100;

				if ($counts!=0)
					$percent /= $counts;
				else
					$percent = 0;

				$percent = floor($percent);
				$percent = $percent>100? 100 : $percent;
				$name = str_replace('Day-', '', $row['counter_name']);
				$name = explode('-', $name);
				
				$itpl->add_for('day', array(
					'{odd-even}' => odd_even(),
					'{number}' => $i,
					'{percent}' => $percent,
					'{progress}' => $progress,
					'{name}' => 'روز '.number2persian($name[2]).' '.short_monthname($name[1]).' ماه سال '.number2persian($name[0]),
					'{count}' => $row['counter_value'],
					'[not-all]' => null,
					'[/not-all]' => null,
					'replace' => array(
						'#\\[all\\](.*?)\\[/all\\]#s' => ''
					)
				));

				$i++;
				$progress++;
				if ($progress>5) $progress = 1;
			}
			
			$itpl->add_for('day', array(
				'{odd-even}' => odd_even(),
				'{number}' => $i,
				'{name}' => 'بازدید کل '.short_monthname($name[1]).' ماه سال '.number2persian($name[0]),
				'{count}' => $counts,
				'[all]' => null,
				'[/all]' => null,
				'replace' => array(
					'#\\[not-all\\](.*?)\\[/not-all\\]#s' => ''
				)
			));
			
			$itpl->assign('month', short_monthname($_GET['d']).' ماه سال '.number2persian($_GET['c']));
			if (!isset($file[2])) set_content('آمار روزانه '.short_monthname($_GET['d']).' ماه سال '.number2persian($_GET['c']), $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());	
			unset($itpl);
		}
	}
}

function _menu()
{
	$file = get_tpl(root_dir.'modules/counter/html/||menu.tpl', template_dir.'||counter/menu.tpl');
	$itpl = new template($file[1], $file[0]);
	if (!isset($file[2])) set_content('دسترسی سریع', $itpl->get_var()); else $tpl->assign('{content}', $itpl->get_var());
}

?>