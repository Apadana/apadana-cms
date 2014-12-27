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

$search_engines = array(
	'Google' => array(
		'name'		=> 'Google',
		'needle'	=> 'google',
		'query_var'	=> 'q',
		'icon'		=> 'google.png'
	),
	'Yahoo!' => array(
		'name'		=> 'Yahoo!',
		'needle'	=> 'yahoo',
		'query_var'	=> 'p',
		'icon'		=> 'yahoo.png'
	),
	'HotBot' => array(
		'name'		=> 'HotBot',
		'needle'	=> 'hotbot.com',
		'query_var'	=> 'query',
		'icon'		=> 'hotbot.png'
	),
	'Lycos' => array(
		'name'		=> 'Lycos',
		'needle'	=> 'lycos',
		'query_var'	=> 'query',
		'icon'		=> 'lycos.png'
	),
	'AllTheWeb' => array(
		'name'		=> 'AllTheWeb',
		'needle'	=> 'alltheweb.com',
		'query_var'	=> 'q',
		'icon'		=> 'alltheweb.png'
	),
	'Altavista' => array(
		'name'		=> 'Altavista',
		'needle'	=> 'altavista',
		'query_var'	=> 'q',
		'icon'		=> 'altavista.png'
	),
	'Alexa' => array(
		'name'		=> 'Alexa',
		'needle'	=> 'alexa',
		'query_var'	=> 'q',
		'icon'		=> 'alexa.png'
	),
	'MSN' => array(
		'name'		=> 'MSN',
		'needle'	=> 'msn',
		'query_var'	=> 'q',
		'icon'		=> 'msn.png'
	),
	'Search.com' => array(
		'name'		=> 'Search.com',
		'needle'	=> 'search.com',
		'query_var'	=> 'q',
		'icon'		=> 'search.com.png'
	),
	'IlTrovatore' => array(
		'name'		=> 'IlTrovatore',
		'needle'	=> 'iltrovatore.it',
		'query_var'	=> 'q',
		'icon'		=> 'search_engine.png'
	),
	'Web.de' => array(
		'name'		=> 'Web.de',
		'needle'	=> 'suche.web.de',
		'query_var'	=> 'su',
		'icon'		=> 'web.de.png'
	),
	'T-Online' => array(
		'name'		=> 'T-Online',
		'needle'	=> 'brisbane.t-online.de',
		'query_var'	=> 'q',
		'icon'		=> 't-online.png'
	),
	'Fireball' => array(
		'name'		=> 'Fireball',
		'needle'	=> 'fireball.de',
		'query_var'	=> 'query',
		'icon'		=> 'fireball.png'
	),
	'Overture' => array(
		'name'		=> 'Overture',
		'needle'	=> 'overture.com',
		'query_var'	=> 'Keywords',
		'icon'		=> 'overture.png'
	),
	'Netscape (DE)' => array(
		'name'		=> 'Netscape',
		'needle'	=> 'search.netscape.de',
		'query_var'	=> 'q',
		'icon'		=> 'netscape.png'
	),
	'Netscape' => array(
		'name'		=> 'Netscape',
		'needle'	=> 'search.netscape.com',
		'query_var'	=> 'query',
		'icon'		=> 'netscape.png'
	),
	'AOL (DE)' => array(
		'name'		=> 'AOL',
		'needle'	=> 'suche.aol',
		'query_var'	=> 'q',
		'icon'		=> 'aol.png'
	),
	'AOL (DE Nr2)' => array(
		'name'		=> 'AOL',
		'needle'	=> 'sucheaol.aol',
		'query_var'	=> 'q',
		'icon'		=> 'aol.png'
	),
	'AOL' => array(
		'name'		=> 'AOL',
		'needle'	=> 'search.aol',
		'query_var'	=> 'query',
		'icon'		=> 'aol.png'
	),
	'Ask Jeeves' => array(
		'name'		=> 'Ask Jeeves',
		'needle'	=> 'ask.com',
		'query_var'	=> 'q',
		'icon'		=> 'askjeeves.png'
	),
	'Teoma' => array(
		'name'		=> 'Teoma',
		'needle'	=> 'teoma.com',
		'query_var'	=> 'q',
		'icon'		=> 'teoma.png'
	),
	'WiseNut' => array(
		'name'		=> 'WiseNut',
		'needle'	=> 'wisenut',
		'query_var'	=> 'q',
		'icon'		=> 'wisenut.png'
	),
	'Gigablast' => array(
		'name'		=> 'Gigablast',
		'needle'	=> 'gigablast.com',
		'query_var'	=> 'q',
		'icon'		=> 'gigablast.png'
	),
	'Quepasa' => array(
		'name'		=> 'Quepasa',
		'needle'	=> 'quepasa.com',
		'query_var'	=> 'q',
		'icon'		=> 'quepasa.png'
	),
	'LookSmart' => array(
		'name'		=> 'LookSmart',
		'needle'	=> 'search.looksmart.com',
		'query_var'	=> 'qt',
		'icon'		=> 'looksmart.png'
	),
	'My Web Search' => array(
		'name'		=> 'My Web Search',
		'needle'	=> 'mywebsearch.com',
		'query_var'	=> 'searchfor',
		'icon'		=> 'search_engine.png'
	),
	'Baidu' => array(
		'name'		=> 'Baidu',
		'needle'	=> 'baidu.com',
		'query_var'	=> 'word',
		'icon'		=> 'baidu.png'
	),
	'Walhello' => array(
		'name'		=> 'Walhello',
		'needle'	=> 'walhello',
		'query_var'	=> 'key',
		'icon'		=> 'search_engine.png'
	),
	'Naver' => array(
		'name'		=> 'Naver',
		'needle'	=> 'naver.co.jp',
		'query_var'	=> 'query',
		'icon'		=> 'search_engine.png'
	),
	'Exalead' => array(
		'name'		=> 'Exalead',
		'needle'	=> 'exalead',
		'query_var'	=> 'q',
		'icon'		=> 'exalead.png'
	),
	'dir.com' => array(
		'name'		=> 'dir.com',
		'needle'	=> 'dir.com',
		'query_var'	=> 'req',
		'icon'		=> 'search_engine.png'
	),
	'Google Cache' => array(
		'name'		=> 'Google',
		'needle'	=> '216.239.37.104',
		'query_var'	=> 'q',
		'icon'		=> 'google.png'
	),
	'Seekport' => array(
		'name'		=> 'Seekport',
		'needle'	=> 'seekport',
		'query_var'	=> 'query',
		'icon'		=> 'seekport.png'
	),
	'Freenet' => array(
		'name'		=> 'suche.freenet.de',
		'needle'	=> 'freenet',
		'query_var'	=> 'query',
		'icon'		=> 'freenet.png'
	),
	'Vienna Online: Finder' => array(
		'name'		=> 'Vienna Online: Finder',
		'needle'	=> 'finder.vienna.at',
		'query_var'	=> 'query',
		'icon'		=> 'search_engine.png'
	),
	'AT:Search' => array(
		'name'		=> 'AT:Search',
		'needle'	=> 'atsearch.at',
		'query_var'	=> 'qs',
		'icon'		=> 'search_engine.png'
	),
	'search.ch' => array(
		'name'		=> 'search.ch',
		'needle'	=> 'search.ch',
		'query_var'	=> 'q',
		'icon'		=> 'search.ch.png'
	),
	'Bluewin' => array(
		'name'		=> 'Bluewin',
		'needle'	=> 'search.bluewin.ch',
		'query_var'	=> 'qry',
		'icon'		=> 'search_engine.png'
	),
	'Seekport' => array(
		'name'		=> 'Seekport',
		'needle'	=> 'seekport',
		'query_var'	=> 'query',
		'icon'		=> 'seekport.png'
	),
	'Kvasir' => array(
		'name'		=> 'Kvasir',
		'needle'	=> 'kvasir.no',
		'query_var'	=> 'q',
		'icon'		=> 'search_engine.png'
	)
);

?>