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

$ualib_browsers = array(
	'Internet Explorer' => array(
		'icon' => 'ie.png',
		'use_PCRE' => 1,
		'pattern' => '#MSIE ?([0-9.]+)?#',
		'version' => 1,
		'anti_pattern' => '/(Opera)/i'
	),
	'Firefox' => array(
		'icon' => 'firefox.png',
		'use_PCRE' => 1,
		'pattern' => '#(Firefox|Firebird|Phoenix)/([0-9.]+)#i',
		'version' => 2,
		'anti_pattern' => ''
	),
	'Mozilla' => array(
		'icon' => 'mozilla.png',
		'use_PCRE' => 1,
		'pattern' => '#Mozilla/5.0(.*); rv:([0-1]{1}\.[0-9]{1})(.*)Gecko#',
		'version' => 2,
		'anti_pattern' => '/(Netscape|Galeon|K-Meleon|Epiphany|Camino|Chimera|Firefox|Firebird|Phoenix|Beonex)/i'
	),
	'Opera' => array(
		'icon' => 'opera.png',
		'use_PCRE' => 1,
		'pattern' => '#Opera(/| )?([0-9.]+)?#',
		'version' => 2,
		'anti_pattern' => ''
	),
	'Netscape' => array(
		'icon' => 'netscape.png',
		'use_PCRE' => 1,
		'pattern' => '#Netscape(/?)([0-9.]+)?#',
		'version' => 2,
		'anti_pattern' => '#(Konqueror|Opera)#'
	),
	'Chrome' => array(
		'icon' => 'chrome.png',
		'use_PCRE' => 1,
		'pattern' => '#Chrome/([0-9.]+)?#',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Konqueror' => array(
		'icon' => 'konqueror.png',
		'use_PCRE' => 1,
		'pattern' => '#Konqueror/?([0-9.]+)?#',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Safari' => array(
		'icon' => 'safari.png',
		'use_PCRE' => 1,
		'pattern' => '/Safari\/([0-9.]+)/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Galeon' => array(
		'icon' => 'galeon.png',
		'use_PCRE' => 1,
		'pattern' => '/Galeon(\/([0-9.]+))?/',
		'version' => 2,
		'anti_pattern' => ''
	),
	'Epiphany' => array(
		'icon' => 'epiphany.png',
		'use_PCRE' => 1,
		'pattern' => '/Epiphany\/([0-9.]+)/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'K-Meleon' => array(
		'icon' => 'k-meleon.png',
		'use_PCRE' => 1,
		'pattern' => '/K-Meleon[ \/]([0-9.]+)/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'IBrowse' => array(
		'icon' => 'ibrowse.png',
		'use_PCRE' => 1,
		'pattern' => '/IBrowse\/([0-9.]+)/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Camino' => array(
		'icon' => 'camino.png',
		'use_PCRE' => 1,
		'pattern' => '/(Camino|Chimera)\/?([0-9.]+)?/i',
		'version' => 2,
		'anti_pattern' => ''
	),
	'iCab' => array(
		'icon' => 'icab.png',
		'use_PCRE' => 1,
		'pattern' => '#iCab[ \/]+([0-9.]+)?#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'OmniWeb' => array(
		'icon' => 'omniweb.png',
		'use_PCRE' => 1,
		'pattern' => '/OmniWeb(\/([0-9.]+))?/',
		'version' => 2,
		'anti_pattern' => ''
	),
	'w3m' => array(
		'icon' => 'w3m.png',
		'use_PCRE' => 1,
		'pattern' => '#w3m/([0-9.]+)?#',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Lynx' => array(
		'icon' => 'lynx.png',
		'use_PCRE' => 1,
		'pattern' => '#Lynx((/| )?([\d.]+))?#i',
		'version' => 3,
		'anti_pattern' => ''
	),
	'Links' => array(
		'icon' => 'links.png',
		'use_PCRE' => 1,
		'pattern' => '#Links[ \/]*\(([0-9.]+)?#',
		'version' => 1,
		'anti_pattern' => ''
	),
	'ELinks' => array(
		'icon' => 'elinks.png',
		'use_PCRE' => 1,
		'pattern' => '#ELinks[ \/]*\(([0-9.]+)?#',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Curl' => array(
		'icon' => 'curl.png',
		'use_PCRE' => 1,
		'pattern' => '#curl/(\d\.\d((\d){0,}\.(\d){0,}){0,})#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Voyager' => array(
		'icon' => 'voyager.png',
		'use_PCRE' => 1,
		'pattern' => '/Voyager(\/([0-9.]+))?/',
		'version' => 2,
		'anti_pattern' => ''
	),
	'Amaya' => array(
		'icon' => 'blank.png',
		'use_PCRE' => 1,
		'pattern' => '/amaya\/([0-9.]+)?/i',
		'version' => 1,
		'anti_pattern' => ''
	),	
	'unknown' => array(
		'icon' => 'unknown.png',
		'use_PCRE' => 1,
		'pattern' => '/.*/',
		'version' => false,
		'anti_pattern' => ''
	)
);

$ualib_os = array(
	'Windows' => array(
		'icon' => 'windows.png',
		'use_PCRE' => 0,
		'pattern' => 'Win',
		'version' => array(
			'NT 6.2' => '8',
			'NT 6.1' => '7',
			'NT 6.0' => 'Vista',
			'NT 5.2' => '2003 Server',
			'NT 5.1' => 'XP',
			'NT 5.0' => '2000',
			'NT' => 'NT',
			'ME' => 'ME',
			'Win 9x 4.90' => 'ME',
			'98' => '98',
			'95' => '95',
			'CE' => 'CE',
			'Windows 3.1' => 'ME',
			'XP' => 'XP',
			'2000' => '2000'
		),
		'anti_pattern' => ''
	),
	'Linux' => array(
		'icon' => 'linux.png',
		'use_PCRE' => 0,
		'pattern' => 'Linux',
		'version' => false,
		'anti_pattern' => ''
	),
	'Android' => array(
		'icon' => 'android.png',
		'use_PCRE' => 1,
		'pattern' => '/Android ?([0-9.]+)?/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'iPhone' => array(
		'icon' => 'iphone.png',
		'use_PCRE' => 0,
		'pattern' => 'iPhone',
		'version' => false,
		'anti_pattern' => ''
	),
	'iPod' => array(
		'icon' => 'iphone.png',
		'use_PCRE' => 0,
		'pattern' => 'iPod',
		'version' => false,
		'anti_pattern' => ''
	),
	'Mac OS' => array(
		'icon' => 'mac_os.png',
		'use_PCRE' => 1,
		'pattern' => '/(Macintosh|Mac_PowerPC|PPC Mac)(.+(OS X))?/i',
		'version' => 3,
		'anti_pattern' => ''
	),
	'SunOS' => array(
		'icon' => 'sunos.png',
		'use_PCRE' => 1,
		'pattern' => '/SunOS ?([0-9.]+)?/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'FreeBSD' => array(
		'icon' => 'freebsd.png',
		'use_PCRE' => 0,
		'pattern' => 'FreeBSD',
		'version' => false,
		'anti_pattern' => ''
	),
	'NetBSD' => array(
		'icon' => 'netbsd.png',
		'use_PCRE' => 0,
		'pattern' => 'NetBSD',
		'version' => false,
		'anti_pattern' => ''
	),
	'OpenBSD' => array(
		'icon' => 'openbsd.png',
		'use_PCRE' => 0,
		'pattern' => 'OpenBSD',
		'version' => false,
		'anti_pattern' => ''
	),
	'IRIX' => array(
		'icon' => 'irix.png',
		'use_PCRE' => 0,
		'pattern' => 'IRIX',
		'version' => false,
		'anti_pattern' => ''
	),
	'BeOS' => array(
		'icon' => 'beos.png',
		'use_PCRE' => 0,
		'pattern' => 'BeOS',
		'version' => false,
		'anti_pattern' => ''
	),
	'OS/2' => array(
		'icon' => 'os2.png',
		'use_PCRE' => 0,
		'pattern' => 'OS/2',
		'version' => false,
		'anti_pattern' => ''
	),
	'AIX' => array(
		'icon' => 'aix.png',
		'use_PCRE' => 0,
		'pattern' => 'AIX',
		'version' => false,
		'anti_pattern' => ''
	),
	'Amiga' => array(
		'icon' => 'amiga_os.png',
		'use_PCRE' => 1,
		'pattern' => '/AmigaOS ?([0-9.]+)?/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Darwin' => array(
		'icon' => 'darwin.png',
		'use_PCRE' => 1,
		'pattern' => '/Darwin/i',
		'version' => false,
		'anti_pattern' => ''
	),
	'HP-UX' => array(
		'icon' => 'hp-ux.png',
		'use_PCRE' => 0,
		'pattern' => 'HP-UX',
		'version' => false,
		'anti_pattern' => ''
	),
	'QNX' => array(
		'icon' => 'blank.png',
		'use_PCRE' => 0,
		'pattern' => 'QNX',
		'version' => false,
		'anti_pattern' => ''
	),
	'unknown' => array(
		'icon' => 'unknown.png',
		'use_PCRE' => 1,
		'pattern' => '/.*/',
		'version' => false,
		'anti_pattern' => ''
	)
);

$ualib_robots = array(
	'Googlebot-Image' => array(
		'icon' => 'google.png',
		'use_PCRE' => 1,
		'pattern' => '/Googlebot-Image\/([0-9.]+)/i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Mediapartners-Google' => array(
		'icon' => 'google.png',
		'use_PCRE' => 1,
		'pattern' => '#Mediapartners-Google/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Googlebot' => array(
		'icon' => 'google.png',
		'use_PCRE' => 1,
		'pattern' => '/Googl(e|ebot)[ \/]([0-9.]+|Test)?;? ?(\(?\+http:\/\/www\.google(bot)?\.com\/bot\.html\)?)?/i',
		'version' => 2,
		'anti_pattern' => ''
	),
	'Altavista' => array(
		'icon' => 'altavista.png',
		'use_PCRE' => 1,
		'pattern' => '/Scooter\/([0-9.]+)/i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Inktomi' => array(
		'icon' => 'inktomi.png',
		'use_PCRE' => 0,
		'pattern' => 'inktomi.com',
		'version' => false,
		'anti_pattern' => ''
	),
	'Yahoo!' => array(
		'icon' => 'yahoo.png',
		'use_PCRE' => 0,
		'pattern' => 'Yahoo! Slurp',
		'version' => false,
		'anti_pattern' => ''
	),
	'Infoseek' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 0,
		'pattern' => 'Infoseek',
		'version' => false,
		'anti_pattern' => ''
	),
	'Nutch' => array(
		'icon' => 'nutch.png',
		'use_PCRE' => 1,
		'pattern' => '/Nutch(Org|CVS)?\/?([0-9.]+)?/i',
		'version' => 2,
		'anti_pattern' => ''
	),
	'Fireball' => array(
		'icon' => 'fireball.png',
		'use_PCRE' => 0,
		'pattern' => 'Fireball',
		'version' => false,
		'anti_pattern' => ''
	),
	'AlltheWeb' => array(
		'icon' => 'alltheweb.png',
		'use_PCRE' => 1,
		'pattern' => '#((FAST[ \-]*WebCrawler[ \/]+([0-9.]+)?)|crawler@fast\.no)#i',  //TODO testen
		'version' => 3,
		'anti_pattern' => ''
	),
	'Alexa (web.archive.org)' => array(
		'icon' => 'alexa.png',
		'use_PCRE' => 0,
		'pattern' => 'ia_archiver-web.archive.org',
		'version' => false,
		'anti_pattern' => ''
	),
	'Alexa' => array(
		'icon' => 'alexa.png',
		'use_PCRE' => 0,
		'pattern' => 'ia_archiver',
		'version' => false,
		'anti_pattern' => ''
	),
	'WiseNutBot' => array(
		'icon' => 'wisenutbot.png',
		'use_PCRE' => 1,
		'pattern' => '/Zyborg[ \/]?([0-9.]+)/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'W3C Validator' => array(
		'icon' => 'w3c.png',
		'use_PCRE' => 0,
		'pattern' => 'W3C_Validator',
		'version' => false,
		'anti_pattern' => ''
	),
	'W3C CSS Validator' => array(
		'icon' => 'w3c.png',
		'use_PCRE' => 0,
		'pattern' => 'W3C_CSS_Validator',
		'version' => false,
		'anti_pattern' => ''
	),
	'SurveyBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#SurveyBot/([0-9.]+)?#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'QuepasaCreep' => array(
		'icon' => 'quepasa.png',
		'use_PCRE' => 0,
		'pattern' => 'QuepasaCreep',
		'version' => false,
		'anti_pattern' => ''
	),
	'PHP' => array(
		'icon' => 'php.png',
		'use_PCRE' => 1,
		'pattern' => '#PHP[ \/]+([0-9.]+)?#',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Java' => array(
		'icon' => 'java.png',
		'use_PCRE' => 1,
		'pattern' => '/^Java\/([0-9.]+)?/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Overture' => array(
		'icon' => 'overture.png',
		'use_PCRE' => 1,
		'pattern' => '#Overture[ \-]*WebCrawler#',
		'version' => false,
		'anti_pattern' => ''
	),
	'MSNBot' => array(
		'icon' => 'msn.png',
		'use_PCRE' => 1,
		'pattern' => '#msnbot[/ ]+([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Claymont' => array(
		'icon' => 'claymont.png',
		'use_PCRE' => 0,
		'pattern' => 'Claymont',
		'version' => false,
		'anti_pattern' => ''
	),
	'Baiduspider' => array(
		'icon' => 'baidu.png',
		'use_PCRE' => 0,
		'pattern' => 'Baiduspider',
		'version' => false,
		'anti_pattern' => ''
	),
	'Almaden' => array(
		'icon' => 'ibm.png',
		'use_PCRE' => 0,
		'pattern' => 'http://www.almaden.ibm.com/cs/crawler',
		'version' => false,
		'anti_pattern' => ''
	),
	'Il Trovatore' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/Iltrovatore-Setaccio\/([0-9.]+)/i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Teoma' => array(
		'icon' => 'teoma.png',
		'use_PCRE' => 0,
		'pattern' => 'Ask Jeeves/Teoma',
		'version' => false,
		'anti_pattern' => ''
	),
	'Gigabot' => array(
		'icon' => 'gigabot.png',
		'use_PCRE' => 1,
		'pattern' => '#Gigabot/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Girafabot' => array(
		'icon' => 'girafabot.png',
		'use_PCRE' => 0,
		'pattern' => 'http://www.girafa.com',
		'version' => false,
		'anti_pattern' => ''
	),
	'WebCopier' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 0,
		'pattern' => 'WebCopier',
		'version' => false,
		'anti_pattern' => ''
	),
	'HTTrack' => array(
		'icon' => 'httrack.png',
		'use_PCRE' => 1,
		'pattern' => '/HTTrack ?([0-9.x]+)?/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'WGet' => array(
		'icon' => 'wget.png',
		'use_PCRE' => 1,
		'pattern' => '#Wget/([0-9.]+)?#',
		'version' => 1,
		'anti_pattern' => ''
	),
	'lwp-request' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#lwp(:|-)+(trivial|request|Simple)/([0-9.]+)#i',
		'version' => 3,
		'anti_pattern' => ''
	),
	'JetBot' => array(
		'icon' => 'jetbot.png',
		'use_PCRE' => 1,
		'pattern' => '/Jetbot\/([0-9.]+)?/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'NaverBot' => array(
		'icon' => 'naver.png',
		'use_PCRE' => 1,
		'pattern' => '#NaverBot-([0-9.]+)? \(NHN Corp/#',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Larbin' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/larbin([ \/-_])?([0-9.]+)?/i',
		'version' => 2,
		'anti_pattern' => ''
	),
	'ObjectsSearch' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/ObjectsSearch\/([0-9.]+)?/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Robozilla' => array(
		'icon' => 'robozilla.png',
		'use_PCRE' => 1,
		'pattern' => '/Robozilla\/?(\d(\.\d){0,})?/',
		'version' => false,
		'anti_pattern' => ''
	),
	'Walhello appie' => array(
		'icon' => 'walhello_appie.png',
		'use_PCRE' => 1,
		'pattern' => '/appie[ \/]([0-9.]+)?.*\(www\.walhello\.com\)/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Grub' => array(
		'icon' => 'grub.png',
		'use_PCRE' => 1,
		'pattern' => '/grub-client-([0-9.]+)?;/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Gaisbot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/Gaisbot\/([0-9.]+)/i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'mozDex' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/mozDex\/([0-9.]+).+\(mozDex;/i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'GeonaBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/GeonaBot\/([0-9.]+)/i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Openbot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/Openbot\/([0-9.]+)/i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Boitho' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/boitho\.com(-\w+)?\/([0-9.]+)/i',
		'version' => 2,
		'anti_pattern' => ''
	),
	'Pompos' => array(
		'icon' => 'pompos.png',
		'use_PCRE' => 1,
		'pattern' => '/Pompos\/([0-9.]+)/i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Exabot' => array(
		'icon' => 'exabot.png',
		'use_PCRE' => 1,
		'pattern' => '/^NG\/([0-9.]+)$/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Xenu Link Sleuth' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '/Xenu Link Sleuth ?([0-9.]+)?/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'W3C-checklink' => array(
		'icon' => 'w3c.png',
		'use_PCRE' => 1,
		'pattern' => '/W3C-checklink\/([0-9.]+)/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'W3C-checklink' => array(
		'icon' => 'w3c.png',
		'use_PCRE' => 1,
		'pattern' => '/W3C-checklink\/([0-9.]+)/',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Versus' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#versus ?([0-9.]+) ?\(\+http://versus\.integis\.ch\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'FindLinks' => array(
		'icon' => 'findlinks.png',
		'use_PCRE' => 1,
		'pattern' => '#findlinks/?([0-9.]+)? \(\+http://wortschatz\.uni-leipzig\.de/findlinks/\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'wwwster' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#wwwster/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Steeler' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Steeler/([0-9.]+) \(http://www\.tkl\.iis\.u-tokyo\.ac\.jp/~crawler/\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Ocelli' => array(
		'icon' => 'ocelli.png',
		'use_PCRE' => 1,
		'pattern' => '#Ocelli/([0-9.]+) \(http://www\.globalspec\.com/Ocelli\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'BecomeBot' => array(
		'icon' => 'becomebot.png',
		'use_PCRE' => 1,
		'pattern' => '#Mozilla/5\.0 \(compatible; BecomeBot/([0-9.]+); \+http://www\.become\.com/(webmasters|site_owners)\.html\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Seekbot' => array(
		'icon' => 'seekbot.png',
		'use_PCRE' => 1,
		'pattern' => '#Seekbot/([0-9.]+) \(http://www\.seekbot.net/bot\.html\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Psbot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#psbot/([0-9.]+) \(\+http://www\.picsearch\.com/bot\.html\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'IRLbot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#IRLbot/([0-9.]+) \(\+http://irl\.cs\.tamu\.edu/crawler\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'PhpDig' => array(
		'icon' => 'phpdig.png',
		'use_PCRE' => 1,
		'pattern' => '#PhpDig/([0-9.]+) \(\+http://www\.phpdig\.net/robot\.php\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'gazz' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#gazz/([0-9.]+) \(gazz@nttr\.co\.jp\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'MJ12bot' => array(
		'icon' => 'mj12bot.png',
		'use_PCRE' => 1,
		'pattern' => '#MJ12bot/v([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'getRAX Crawler' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#getRAX/getRAX Crawler ([0-9.]+) \(\+http://www\.getRAX\.com\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Amfibibot' => array(
		'icon' => 'amfibibot.png',
		'use_PCRE' => 1,
		'pattern' => '#Amfibibot/([0-9.]+) \(Amfibi Robot; http://www\.amfibi\.com; agent@amfibi\.com\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'GigabotSiteSearch' => array(
		'icon' => 'gigablast.png',
		'use_PCRE' => 1,
		'pattern' => '#GigabotSiteSearch/([0-9.]+) \(sitesearch\.gigablast\.com\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'pipeLiner' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#pipeLiner/([0-9.]+) \(PipeLine Spider; http://www\.pipeline-search\.com/webmaster\.html\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'ZipppBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#ZipppBot/([0-9.]+) \(ZipppBot; http://www\.zippp\.net; webmaster@zippp\.net\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'TurnitinBot' => array(
		'icon' => 'turnitinbot.png',
		'use_PCRE' => 1,
		'pattern' => '#TurnitinBot/([0-9.]+) \(http://www\.turnitin\.com/robot/crawlerinfo\.html\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),	
	'KazoomBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#KazoomBot/([0-9.dev]+) \(Kazoom; http://www\.kazoom\.ca/bot\.html; kazoombot@kazoom\.ca\)#i',
		'version' => 1,
		'anti_pattern' => ''
	), 
	'NetResearchServer' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#NetResearchServer/([0-9.]+)\(loopimprovements\.com/robot\.html\)#i',
		'version' => 1,
		'anti_pattern' => ''
	), 
	'gamekitbot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#gamekitbot/([0-9.]+) \(\+http://www\.uchoose\.de/crawler/gamekitbot/\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),	
	'Vagabondo' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Vagabondo/([0-9.]+) MT#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Vagabondo' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Vagabondo/([0-9.]+) MT#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'TheSuBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#TheSuBot/([0-9.]+) \(www\.thesubot\.de\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'NPBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#NP/([0-9.]+) \(NP; http://www.nameprotect\.com; npbot@nameprotect\.com\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Cerberian' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Mozilla/4\.0 \(compatible; Cerberian Drtrs Version-([0-9.]+).*\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'ConveraCrawler' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#ConveraCrawler/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'search.ch' => array(
		'icon' => 'search.ch.png',
		'use_PCRE' => 1,
		'pattern' => '#search.ch V([0-9.]+) \(spiderman@search\.ch; http://www\.search\.ch\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'ichiro' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#ichiro/([0-9.]+) \(ichiro@nttr\.co\.jp\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'CydralSpider' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#CydralSpider/([0-9.]+) \(Cydral Web Image Search; http://www\.cydral\.com\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Szukacz' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Szukacz/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Patwebbot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 0,
		'pattern' => 'Patwebbot (http://www.herz-power.de/technik.html)',
		'version' => FALSE,
		'anti_pattern' => ''
	), 
	'SpeedySpider' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Speedy ?Spider.*/([0-9.]+).*entireweb\.com#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Mackster' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 0,
		'pattern' => 'Mackster( http://www.ukwizz.com )',
		'version' => FALSE,
		'anti_pattern' => ''
	),
	'thumbshots-de-Bot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#thumbshots-de-Bot \(Version: ([0-9.]+), powered by www\.thumbshots\.de\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),	
	'Digger' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Digger/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	), 
	
	'Zao' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Zao/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Tutorial Crawler' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Tutorial Crawler ([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'InelaBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#InelaBot/([0-9.]+) \( ?http://inelegant\.org/bot\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'ASPseek' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#ASPseek/([0-9.pre]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	), 
	'Francis' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Francis/([0-9.]+) \(francis@neomo\.de http://www\.neomo\.de/\)#i',
		'version' => 1,
		'anti_pattern' => ''
	), 
	'TutorGigBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#TutorGigBot/([0-9.]+) \( \+http://www\.tutorgig\.info \)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'CipinetBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 0,
		'pattern' => '#CipinetBot (http://www.cipinet.com/bot.html)#i',
		'version' => FALSE,
		'anti_pattern' => ''
	),	
	'ES.NET_Crawler' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#ES\.NET_Crawler/([0-9.]+) \(http://www\.innerprise\.net/\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'eventax' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#eventax/([0-9.]+) \(eventax; http://www\.eventax\.de/; info@eventax\.de\)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'stat' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#stat \(?statcrawler@gmail.com\)?#i',
		'version' => FALSE,
		'anti_pattern' => ''
	),	
	'Xaldon WebSpider' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Xaldon WebSpider ([0-9.b]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'Faxobot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Faxobot/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	), 
	'Sherlock' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#sherlock/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	), 
	'Holmes' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#Holmes/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	), 
	'lmspider' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 0,
		'pattern' => 'lmspider (lmspider@scansoft.com)',
		'version' => FALSE,
		'anti_pattern' => ''
	),
	'SeznamBot' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#SeznamBot/([0-9.]+)#i',
		'version' => 1,
		'anti_pattern' => ''
	),
	'other' => array(
		'icon' => 'robot.png',
		'use_PCRE' => 1,
		'pattern' => '#(Spider|(Ro)?bot|Crawler|Nutch)#i',
		'version' => 1,
		'anti_pattern' => ''
	)
);

?>