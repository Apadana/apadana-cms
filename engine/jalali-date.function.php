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

function Jdate($type, $maket = 'now', $transnumber = 0)
{
	#set 1 if you want translate number to persian or if you don't like set 0
	#$transnumber=1;

	#chosse your timezone
	$TZhours=0;
	$TZminute=0;
	$need='';
	$result1='';
	$result='';
	if ($maket=='now'){
		$year=date('Y');
		$month=date('m');
		$day=date('d');
		list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
		$maket=mktime(date('H')+$TZhours,date('i')+$TZminute,date('s'),date('m'),date('d'),date('Y'));
	} else {
		#$maket=0;
		$maket+=$TZhours*3600+$TZminute*60;
		$date=date('Y-m-d',$maket);
		list( $year, $month, $day ) = preg_split ( '/-/', $date );

		list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
	}

	$need= $maket;
	$year=date('Y',$need);
	$month=date('m',$need);
	$day=date('d',$need);
	$i=0;
	$subtype='';
	$subtypetemp='';
	list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
	while($i<strlen($type))
	{
		$subtype=substr($type,$i,1);
		if ($subtypetemp=='\\')
		{
			$result.=$subtype;
			$i++;
			continue;
		}

		switch ($subtype)
		{
			case 'A':
					$result1=date('a',$need);
					if ($result1=='pm') $result.= 'بعد از ظهر';
					else $result.='قبل ‏از ظهر';
					break;

			case 'a':
					$result1=date('a',$need);
					if ($result1=='pm') $result.= 'ب.ظ';
					else $result.='ق.ظ';
					break;
			case 'd':
					if ($jday<10)$result1='0'.$jday;
					else         $result1=$jday;
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'D':
					$result1=date('D',$need);
					if ($result1=='Thu') $result1='پ';
					else if ($result1=='Sat') $result1='ش';
					else if ($result1=='Sun') $result1='ى';
					else if ($result1=='Mon') $result1='د';
					else if ($result1=='Tue') $result1='س';
					else if ($result1=='Wed') $result1='چ';
					else if ($result1=='Thu') $result1='پ';
					else if ($result1=='Fri') $result1='ج';
					$result.=$result1;
					break;
			case'F':
					$result.=monthname($jmonth);
					break;
			case 'g':
					$result1=date('g',$need);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'G':
					$result1=date('G',$need);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
					case 'h':
					$result1=date('h',$need);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'H':
					$result1=date('H',$need);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'i':
					$result1=date('i',$need);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'j':
					$result1=$jday;
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'l':
					$result1=date('l',$need);
					if ($result1=='Saturday') $result1='شنبه';
					else if ($result1=='Sunday') $result1='یکشنبه';
					else if ($result1=='Monday') $result1='دوشنبه';
					else if ($result1=='Tuesday') $result1='سه شنبه';
					else if ($result1=='Wednesday') $result1='چهارشنبه';
					else if ($result1=='Thursday') $result1='پنجشنبه';
					else if ($result1=='Friday') $result1='جمعه';
					$result.=$result1;
					break;
			case 'm':
					if ($jmonth<10) $result1='0'.$jmonth;
					else        $result1=$jmonth;
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'M':
					$result.=short_monthname($jmonth);
					break;
			case 'n':
					$result1=$jmonth;
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 's':
					$result1=date('s',$need);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'S':
					$result.='&#1575;&#1605;';
					break;
			case 't':
					$result.=lastday($month,$day,$year);
					break;
			case 'w':
					$result1=date('w',$need);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'W':
					$result1=date('W',$need);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'y':
					$result1=substr($jyear,2,4);
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'Y':
					$result1=$jyear;
					if ($transnumber==1) $result.=number2persian($result1);
					else $result.=$result1;
					break;
			case 'U' :
					$result.=mktime();
					break;
			case 'z' :
					$result.=days_of_year($jmonth,$jday,$jyear);
					break;
			case 'L' :
					list( $tmp_year, $tmp_month, $tmp_day ) = jalali_to_gregorian(1384, 12, 1);
					echo $tmp_day;
					/*if (lastday($tmp_month,$tmp_day,$tmp_year)=='31')
							$result.='1';
					else
							$result.='0';
							*/
					break;
			default:
				$result.=$subtype;
		}
		$subtypetemp=substr($type,$i,1);
		$i++;
	}
	return $result;
}

function Jmaketime($hour='',$minute='',$second='',$jmonth='',$jday='',$jyear='')
{
	if (!$hour && !$minute && !$second && !$jmonth && !$jmonth && !$jday && !$jyear)
		return mktime();
	list( $year, $month, $day ) = jalali_to_gregorian($jyear, $jmonth, $jday);
	$i=mktime($hour,$minute,$second,$month,$day,$year);
	return $i;
}

#Find num of Day Begining Of Month ( 0 for Sat & 6 for Sun)
function mstart($month,$day,$year)
{
	list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
	list( $year, $month, $day ) = jalali_to_gregorian($jyear, $jmonth, '1');
	$timestamp=mktime(0,0,0,$month,$day,$year);
	return date('w',$timestamp);
}

#Find Number Of Days In This Month
function lastday($month,$day,$year)
{
	$jday2='';
	$jdate2 ='';
	$lastdayen=date('d',mktime(0,0,0,$month+1,0,$year));
	list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
	$lastdatep=$jday;
	$jday=$jday2;
	while($jday2!='1')
	{
		if ($day<$lastdayen)
		{
			$day++;
			list( $jyear, $jmonth, $jday2 ) = gregorian_to_jalali($year, $month, $day);
			if ($jdate2=='1') break;
			if ($jdate2!='1') $lastdatep++;
		}
		else
		{
			$day=0;
			$month++;
			if ($month==13)
			{
				$month='1';
				$year++;
			}
		}

	}
	return $lastdatep-1;
}

#Find days in this year untile now
function days_of_year($jmonth,$jday,$jyear)
{
	$year='';
	$month='';
	$year='';
	$result='';
	if ($jmonth=='01')
		return $jday;
	for ($i=1;$i<$jmonth || $i==12;$i++)
	{
		list( $year, $month, $day ) = jalali_to_gregorian($jyear, $i, '1');
		$result+=lastday($month,$day,$year);
	}
	return $result+$jday;
}

#translate number of month to name of month
function monthname($month)
{
	if ($month=='01') return 'فروردین';

    if ($month=='02') return 'اردیبهشت';

    if ($month=='03') return 'خرداد';

    if ($month=='04') return 'تیر';

    if ($month=='05') return 'مرداد';

    if ($month=='06') return 'شهریور';

    if ($month=='07') return 'مهر';

    if ($month=='08') return 'آبان';

    if ($month=='09') return 'آذر';

    if ($month=='10') return 'دی';

    if ($month=='11') return 'بهمن';

    if ($month=='12') return 'اسفند';
}

function short_monthname($month)
{
    if ($month=='01') return 'فروردین';

    if ($month=='02') return 'اردیبهشت';

    if ($month=='03') return 'خرداد';

    if ($month=='04') return 'تیر';

    if ($month=='05') return 'مرداد';

    if ($month=='06') return 'شهریور';

    if ($month=='07') return 'مهر';

    if ($month=='08') return 'آبان';

    if ($month=='09') return 'آذر';

    if ($month=='10') return 'دی';

    if ($month=='11') return 'بهمن';

    if ($month=='12') return 'اسفند';
}

# here convert to number in persian
function number2persian($srting)
{
    $num0='۰';
    $num1='۱';
    $num2='۲';
    $num3='۳';
    $num4='۴';
    $num5='۵';
    $num6='۶';
    $num7='۷';
    $num8='۸';
    $num9='۹';
    $stringtemp='';
    $len=strlen($srting);
    for($sub=0;$sub<$len;$sub++)
    {
		if(substr($srting,$sub,1)=='0')$stringtemp.=$num0;
		elseif(substr($srting,$sub,1)=='1')$stringtemp.=$num1;
		elseif(substr($srting,$sub,1)=='2')$stringtemp.=$num2;
		elseif(substr($srting,$sub,1)=='3')$stringtemp.=$num3;
		elseif(substr($srting,$sub,1)=='4')$stringtemp.=$num4;
		elseif(substr($srting,$sub,1)=='5')$stringtemp.=$num5;
		elseif(substr($srting,$sub,1)=='6')$stringtemp.=$num6;
		elseif(substr($srting,$sub,1)=='7')$stringtemp.=$num7;
		elseif(substr($srting,$sub,1)=='8')$stringtemp.=$num8;
		elseif(substr($srting,$sub,1)=='9')$stringtemp.=$num9;
		else $stringtemp.=substr($srting,$sub,1);
    }
    return  $stringtemp;
}

function is_kabise($year)
{
	if ($year%4==0 && $year%100!=0)
		return true;
	return false;
}

function Jcheckdate($month,$day,$year)
{
	$Jdays_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
	if ($month<=12 && $month>0)
	{
		if ($Jdays_in_month[$month-1]>=$day && $day>0)
			return 1;
		if (is_kabise($year))
			echo 'Asdsd';
		if (is_kabise($year) && $Jdays_in_month[$month-1]==31)
			return 1;
	}
	return 0;
}

function Jgetdate($timestamp='')
{
	if ($timestamp=='')
		$timestamp=mktime();

	return array(
		0=>$timestamp,
		'seconds'=>jdate('s',$timestamp, 0),
		'minutes'=>jdate('i',$timestamp, 0),
		'hours'=>jdate('G',$timestamp, 0),
		'mday'=>jdate('j',$timestamp, 0),
		'wday'=>jdate('w',$timestamp, 0),
		'mon'=>jdate('n',$timestamp, 0),
		'year'=>jdate('Y',$timestamp, 0),
		'yday'=>days_of_year(jdate('m',$timestamp, 0),jdate('d',$timestamp, 0),jdate('Y',$timestamp, 0)),
		'weekday'=>jdate('l',$timestamp, 0),
		'month'=>jdate('F',$timestamp, 0),
	);
}

function date_div($a,$b)
{
    return (int) ($a / $b);
}

function gregorian_to_jalali($g_y, $g_m, $g_d)
{
	$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	$Jdays_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

	$gy = $g_y-1600;
	$gm = $g_m-1;
	$gd = $g_d-1;

	$g_day_no = 365*$gy+date_div($gy+3,4)-date_div($gy+99,100)+date_div($gy+399,400);

	for ($i=0; $i < $gm; ++$i)
	$g_day_no += $g_days_in_month[$i];
	if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
	  /* leap and after Feb */
		$g_day_no++;
	$g_day_no += $gd;

	$Jday_no = $g_day_no-79;

	$Jnp = date_div($Jday_no, 12053); /* 12053 = 365*33 + 32/4 */
	$Jday_no = $Jday_no % 12053;

	$jy = 979+33*$Jnp+4*date_div($Jday_no,1461); /* 1461 = 365*4 + 4/4 */

	$Jday_no %= 1461;

	if ($Jday_no >= 366) {
		$jy += date_div($Jday_no-1, 365);
		$Jday_no = ($Jday_no-1)%365;
	}

	for ($i = 0; $i < 11 && $Jday_no >= $Jdays_in_month[$i]; ++$i)
		$Jday_no -= $Jdays_in_month[$i];
	$jm = $i+1;
	$jd = $Jday_no+1;

	return array($jy, $jm, $jd);
}

function Jalali_to_gregorian($Jy, $Jm, $Jd)
{
	$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	$Jdays_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

	$jy = $Jy-979;
	$jm = $Jm-1;
	$jd = $Jd-1;

	$Jday_no = 365*$jy + date_div($jy, 33)*8 + date_div($jy%33+3, 4);
	for ($i=0; $i < $jm; ++$i)
		$Jday_no += $Jdays_in_month[$i];

	$Jday_no += $jd;

	$g_day_no = $Jday_no+79;

   $gy = 1600 + 400*date_div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
   $g_day_no = $g_day_no % 146097;

	$leap = true;
	if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
	{
		$g_day_no--;
		$gy += 100*date_div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */
		$g_day_no = $g_day_no % 36524;

		if ($g_day_no >= 365)
			$g_day_no++;
		else
			$leap = false;
	}

	$gy += 4*date_div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
	$g_day_no %= 1461;

	if ($g_day_no >= 366) {
		$leap = false;

		$g_day_no--;
		$gy += date_div($g_day_no, 365);
		$g_day_no = $g_day_no % 365;
	}

	for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
		$g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
	$gm = $i+1;
	$gd = $g_day_no+1;

	return array($gy, $gm, $gd);
}

?>