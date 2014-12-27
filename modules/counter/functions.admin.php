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

function _default()
{
	global $tpl, $d;

	member::check_admin_page_access('counter') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');
	
	$chartcolor[0] = '90ee90';
	$chartcolor[1] = '666600';
	$chartcolor[2] = '008080';
	$chartcolor[3] = 'FFCC00';
	$chartcolor[4] = 'FF0000';
	$chartcolor[5] = '006699';
	$chartcolor[6] = '666699';
	$chartcolor[7] = '000000';
	$chartcolor[8] = '99CC99';
	$chartcolor[9] = '0000ff';
	$chartcolor[10] = '8a2be2';
	$chartcolor[11] = 'EAAAAA';
	$chartcolor[12] = 'deb887';
	$chartcolor[13] = '5f9ea0';
	$chartcolor[14] = 'c0c0c0';
	$chartcolor[15] = 'd2691e';
	$chartcolor[16] = '00E8E8';
	$chartcolor[17] = '9999CC';
	$chartcolor[18] = '33CCCC';
	$chartcolor[19] = 'FF99FF';
	$chartcolor[20] = '660099';
	$chartcolor[21] = '666699';
	$chartcolor[22] = '800000';
	$chartcolor[23] = '339999';
	
	if (!is_ajax() || (isset($_GET['chart']) && $_GET['chart'] == 1))
	{
		$result = $d->query("
			SELECT (SELECT `counter_value` FROM #__counter WHERE counter_name='OS-Windows' AND counter_version='') Windows,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-SunOS' AND counter_version='') SunOS,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-QNX' AND counter_version='') QNX,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-OS/2' AND counter_version='') OS2,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-OpenBSD' AND counter_version='') OpenBSD,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-NetBSD' AND counter_version='') NetBSD,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-Mac OS' AND counter_version='') MacOS,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-Linux' AND counter_version='') Linux,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-IRIX' AND counter_version='') IRIX,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-iPod' AND counter_version='') iPod,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-iPhone' AND counter_version='') iPhone,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-HP-UX' AND counter_version='') HPUX,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-FreeBSD' AND counter_version='') FreeBSD,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-Darwin' AND counter_version='') Darwin,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-BeOS' AND counter_version='') BeOS,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-Amiga' AND counter_version='') Amiga,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-AIX' AND counter_version='') AIX,
			(SELECT counter_value FROM #__counter WHERE counter_name='OS-unknown' AND counter_version='') unknown;
		");

		$countData = $d->fetch($result);
		$d->freeResult($result);

		$chartValue[0] = intval($countData['Windows']);
		$chartValue[1] = intval($countData['SunOS']);
		$chartValue[2] = intval($countData['QNX']);
		$chartValue[3] = intval($countData['OS2']);
		$chartValue[4] = intval($countData['OpenBSD']);
		$chartValue[5] = intval($countData['NetBSD']);
		$chartValue[6] = intval($countData['MacOS']);
		$chartValue[7] = intval($countData['Linux']);
		$chartValue[8] = intval($countData['IRIX']);
		$chartValue[9] = intval($countData['iPod']);
		$chartValue[10] = intval($countData['iPhone']);
		$chartValue[11] = intval($countData['HPUX']);
		$chartValue[12] = intval($countData['FreeBSD']);
		$chartValue[13] = intval($countData['Darwin']);
		$chartValue[14] = intval($countData['BeOS']);
		$chartValue[15] = intval($countData['Amiga']);
		$chartValue[16] = intval($countData['AIX']);																														
		$chartValue[17] = intval($countData['unknown']);
	}
	
	if (!is_ajax() || (isset($_GET['chart']) && $_GET['chart'] == 2))
	{
		$result = $d->query("
			SELECT (SELECT `counter_value` FROM #__counter WHERE counter_name='Browser-unknown' AND counter_version='') unknown,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-w3m' AND counter_version='') w3m,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Voyager' AND counter_version='') Voyager,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Safari' AND counter_version='') Safari,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Opera' AND counter_version='') Opera,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-OmniWeb' AND counter_version='') OmniWeb,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Netscape' AND counter_version='') Netscape,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Mozilla' AND counter_version='') Mozilla,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Lynx' AND counter_version='') Lynx,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Links' AND counter_version='') Links,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Konqueror' AND counter_version='') Konqueror,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-K-Meleon' AND counter_version='') KMeleon,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Internet Explorer' AND counter_version='') IE,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-iCab' AND counter_version='') iCab,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-IBrowse' AND counter_version='') IBrowse,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Galeon' AND counter_version='') Galeon,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Firefox' AND counter_version='') Firefox,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Epiphany' AND counter_version='') Epiphany,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-ELinks' AND counter_version='') ELinks,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Curl' AND counter_version='') Curl,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Camino' AND counter_version='') Camino,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Bot' AND counter_version='') Bot,
			(SELECT counter_value FROM #__counter WHERE counter_name='Browser-Amaya' AND counter_version='') Amaya;
		");

		$countData = $d->fetch($result);
		$d->freeResult($result);

		$chartValue2[0] = intval($countData['w3m']);
		$chartValue2[1] = intval($countData['Voyager']);
		$chartValue2[2] = intval($countData['Safari']);
		$chartValue2[3] = intval($countData['Opera']);
		$chartValue2[4] = intval($countData['OmniWeb']);
		$chartValue2[5] = intval($countData['Netscape']);
		$chartValue2[6] = intval($countData['Mozilla']);
		$chartValue2[7] = intval($countData['Lynx']);
		$chartValue2[8] = intval($countData['Links']);
		$chartValue2[9] = intval($countData['Konqueror']);
		$chartValue2[10] = intval($countData['KMeleon']);
		$chartValue2[11] = intval($countData['IE']);
		$chartValue2[12] = intval($countData['iCab']);
		$chartValue2[13] = intval($countData['IBrowse']);
		$chartValue2[14] = intval($countData['Galeon']);
		$chartValue2[15] = intval($countData['Firefox']);
		$chartValue2[16] = intval($countData['Epiphany']);
		$chartValue2[17] = intval($countData['ELinks']);
		$chartValue2[18] = intval($countData['Curl']);
		$chartValue2[19] = intval($countData['Camino']);
		$chartValue2[20] = intval($countData['Bot']);
		$chartValue2[21] = intval($countData['Amaya']);
		$chartValue2[22] = intval($countData['unknown']);
	}
	
	if (!is_ajax() || (isset($_GET['chart']) && $_GET['chart'] == 3))
	{
		$result = $d->query("
			SELECT (SELECT `counter_value` FROM #__counter WHERE counter_name='Robot-other' AND counter_version='') other,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-Yahoo!' AND counter_version='') Yahoo,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-W3C-checklink' AND counter_version='') W3Cchecklink,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-W3C Validator' AND counter_version='') W3CValidator,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-W3C CSS Validator' AND counter_version='') W3CCSSValidator,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-PHP' AND counter_version='') PHP,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-MSNBot' AND counter_version='') MSNBot,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-Mediapartners-Google' AND counter_version='') MediapartnersGoogle,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-Googlebot-Image' AND counter_version='') GooglebotImage,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-Googlebot' AND counter_version='') Googlebot,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-BecomeBot' AND counter_version='') BecomeBot,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-Baiduspider' AND counter_version='') Baiduspider,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-Alexa (web.archive.org)' AND counter_version='') Alexaarchive,
			(SELECT counter_value FROM #__counter WHERE counter_name='Robot-Alexa' AND counter_version='') Alexa;
		");

		$countData = $d->fetch($result);
		$d->freeResult($result);

		$chartValue3[0] = intval($countData['Yahoo']);
		$chartValue3[1] = intval($countData['W3Cchecklink']);
		$chartValue3[2] = intval($countData['W3CValidator']);
		$chartValue3[3] = intval($countData['W3CCSSValidator']);
		$chartValue3[4] = intval($countData['PHP']);
		$chartValue3[5] = intval($countData['MSNBot']);
		$chartValue3[6] = intval($countData['MediapartnersGoogle']);
		$chartValue3[7] = intval($countData['GooglebotImage']);
		$chartValue3[8] = intval($countData['Googlebot']);
		$chartValue3[9] = intval($countData['BecomeBot']);
		$chartValue3[10] = intval($countData['Baiduspider']);
		$chartValue3[11] = intval($countData['Alexaarchive']);
		$chartValue3[12] = intval($countData['Alexa']);
		$chartValue3[13] = intval($countData['other']);
	}
	
	if (!is_ajax() || (isset($_GET['chart']) && $_GET['chart'] == 4))
	{
		$start = date('Y-m-d',strtotime(date('Y-m-d').' -9 days'));
		$time = array();
		for ($i=0; $i<=9; $i++)
		{
			$time[$i] = $start.' +'.$i.' days';
			$time[$i] = jdate('Y-m-d', strtotime($time[$i]));
		}

		$result = $d->query("
			SELECT (SELECT `counter_value` FROM #__counter WHERE counter_name='Day-".$time[0]."' AND counter_version='') value0,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[1]."' AND counter_version='') value1,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[2]."' AND counter_version='') value2,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[3]."' AND counter_version='') value3,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[4]."' AND counter_version='') value4,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[5]."' AND counter_version='') value5,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[6]."' AND counter_version='') value6,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[7]."' AND counter_version='') value7,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[8]."' AND counter_version='') value8,
			(SELECT counter_value FROM #__counter WHERE counter_name='Day-".$time[9]."' AND counter_version='') value9;
		");

		$countData = $d->fetch($result);
		$d->freeResult($result);

		$chartValue4[0] = intval($countData['value0']);
		$chartValue4[1] = intval($countData['value1']);
		$chartValue4[2] = intval($countData['value2']);
		$chartValue4[3] = intval($countData['value3']);
		$chartValue4[4] = intval($countData['value4']);
		$chartValue4[5] = intval($countData['value5']);
		$chartValue4[6] = intval($countData['value6']);
		$chartValue4[7] = intval($countData['value7']);
		$chartValue4[8] = intval($countData['value8']);
		$chartValue4[9] = intval($countData['value9']);
		unset($countData);
	}

	if (isset($_GET['chart']) && $_GET['chart'] == 1)
	{
		_chart('pie', 'OS', $chartValue, $chartcolor);
	}
	elseif (isset($_GET['chart']) && $_GET['chart'] == 2)
	{
		_chart('pie', 'Browsers', $chartValue2, $chartcolor);
	}
	elseif (isset($_GET['chart']) && $_GET['chart'] == 3)
	{
		_chart('bar', 'Robots', $chartValue3, array(), max($chartValue3) <= 0? 100 : max($chartValue3));
	}
	elseif (isset($_GET['chart']) && $_GET['chart'] == 4)
	{
		_chart('line', 'Today '.jdate('Y/m/d'), $chartValue4, array(), max($chartValue4) <= 0? 100 : max($chartValue4));
	}

	set_title('آمارگیر');
	set_head('<script type="text/javascript" src="'.url.'engine/openChart/js/swfobject.js"></script>');
	set_head('<style type="text/css">.break{margin-right:6px;color:#3E6D8E;background-color:#E0EAF1;border-bottom:1px solid #3E6D8E;border-right:1px solid #7F9FB6;padding:3px 4px 3px 4px;margin:2px 2px 2px 0;text-decoration:none;font-size:90%;line-height:2.4;white-space:nowrap;cursor:default}</style>');

	$itpl = new template('modules/counter/html/admin/index.tpl');

	foreach($chartcolor as $i => $c)
	{
		$itpl->assign('{chart-color-'.$i.'}', $c);
	}
	
	foreach($chartValue as $i => $c)
	{
		$itpl->assign('{chart-1-value-'.$i.'}', $c);
	}
	
	foreach($chartValue2 as $i => $c)
	{
		$itpl->assign('{chart-2-value-'.$i.'}', $c);
	}
	
	foreach($chartValue3 as $i => $c)
	{
		$itpl->assign('{chart-3-value-'.$i.'}', $c);
	}
	
	foreach($chartValue4 as $i => $c)
	{
		$itpl->assign(array(
			'{chart-4-value-'.$i.'}' => $c,
			'{chart-dete-'.$i.'}' => $time[$i]
		));
	}

	$itpl->assign(array(
		'{chart-url-1}' => urlencode(admin_page.'&module=counter&chart=1'),
		'{chart-url-2}' => urlencode(admin_page.'&module=counter&chart=2'),
		'{chart-url-3}' => urlencode(admin_page.'&module=counter&chart=3'),
		'{chart-url-4}' => urlencode(admin_page.'&module=counter&chart=4'),
	));

	$tpl->assign('{content}', $itpl->get_var());
}

function _chart($chartType, $chartTitle, $chartValue, $chartColor, $chartRange = 1000)
{
	member::check_admin_page_access('counter') or warning('عدم دسترسی!', 'شما دسترسی لازم برای مشاهده این بخش را ندارید!');

	require_once(root_dir .'engine/openChart/data.php');

	$chartTitle = urldecode('Apadana CMS is a Free Software - '.$chartTitle);

	switch ($chartType)
	{
		case 'bar':
		// ----- BAR CHART ----------
		#$bar = new bar_value(5);
		#$bar->set_colour( '#900000' );
		#$bar->set_tooltip('Hello<br>#val#');
		$title = new title($chartTitle );
		$bar = new bar_3d();
		$bar->set_values( $chartValue );
		$bar->colour = '#0080c0';
		$x_axis = new x_axis();
		$x_axis->set_3d( 5 );
		$x_axis->colour = '#909090';
		$x_axis->set_grid_colour( '#d5f1ff' );
		$y = new y_axis();
		$y->set_range( 0, $chartRange);
		$y->set_grid_colour( '#d5f1ff' );
		$steps = $chartRange > 10? round($chartRange/count($chartValue)) : 1;
		$y->set_steps( $steps );
		$chart = new open_flash_chart();
		$chart->set_y_axis( $y );
		$chart->set_title( $title );
		$chart->add_element( $bar );
		$chart->set_x_axis( $x_axis );
		$chart->set_bg_colour( '#ffffff' );
		echo $chart->toPrettyString();
		break;

		case 'pie':
		$pie = new pie();
		$pie->start_angle(35)
		->add_animation( new pie_fade() )
		->add_animation( new pie_bounce(10) )
		->label_colour('#000033') // <-- uncomment to see all labels set to blue
		->gradient_fill()
		->tooltip('#val# از #total#<br>#percent#')
		->colours($chartColor);
		$pie->set_values( $chartValue );
		$chart = new open_flash_chart();
		$chart->add_element( $pie );
		$chart->set_bg_colour( '#ffffff' );
		echo $chart->toPrettyString();
		break;

		case 'line':
		$default_dot = new dot();
		$default_dot->size(5)->colour('#d92b56');
		$line_dot = new line();
		$line_dot->set_default_dot_style($default_dot);
		$line_dot->set_width( 4 );
		$line_dot->set_colour( '#0080c0' );
		$line_dot->set_values( $chartValue );
		$line_dot->set_key( $chartTitle, 10 );
		$x = new x_axis();
		$x->set_grid_colour( '#d5f1ff' );
		$y = new y_axis();
		$y->set_range( 0, $chartRange);
		$steps = $chartRange > 10? round($chartRange/count($chartValue)) : 1;
		$y->set_steps( $steps );
		$y->set_grid_colour( '#d5f1ff' );
		$chart = new open_flash_chart();
		$chart->set_title( new title( $chartTitle ) );
		$chart->set_x_axis( $x );
		$chart->set_y_axis( $y );
		$chart->set_bg_colour( '#ffffff' );
		$chart->add_element( $line_dot );
		echo $chart->toPrettyString();
		break;
	}
	exit;
}

?>