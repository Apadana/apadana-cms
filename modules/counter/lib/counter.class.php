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

class counter
{
	static function analyse_user_agent($useragent = null)
	{
		require(root_dir.'modules/counter/lib/user_agents.lib.php');

		$useragent = empty($useragent)? $_SERVER['HTTP_USER_AGENT'] : $useragent;
		
		$ualib_browser =& $ualib_browsers;
		$ualib_robot =& $ualib_robots;
		$libs = array( 'browser', 'os', 'robot' );

		foreach( $libs as $libname )
		{
			// Wenn Browser und OS schon durchgelaufen und Browser erfolgreich ermittelt,
			// ist es kein Robot, und das Durchsuchen der Arrays kann beendet werden
			if(
				( isset( $browser ) && isset( $os ) )
				&&
				$browser != 'unknown'
			  )
			{
				break;
			}

			// jeweiliges Library-Array durchlaufen
			foreach( ${'ualib_'.$libname} as $name => $array )
			{
				if( $array['use_PCRE'] == 1 )
				{
					if( preg_match( $array['pattern'], $useragent, $match ) )
					{
						if( !empty ( $array['anti_pattern'] ) && preg_match( $array['anti_pattern'], $useragent ) )
						{
							continue;
						}
						${$libname} = $name;
					}
				}
				else
				{
					if( is_int( strpos( $useragent, $array['pattern'] ) ) )
					{
						if( !empty( $array['anti_pattern'] ) && is_int( strpos ( $useragent, $array['anti_pattern'] ) ) )
						{
							continue;
						}
						${$libname} = $name;
					}
				}

				// Wenn kein Treffer, Loop fortsetzen -> weitersuchen:
				if( !isset( ${$libname} ) )
				{
					continue;
				}

				// Ansonsten: Treffer

				// Icon
				${$libname.'_icon'} = $array['icon'];

				// nach Version suchen?
				if( $array['use_PCRE'] == 1 && ${'ualib_'.$libname}[${$libname}]['version'] != false ) // mit preg_match bereits Version ermittelt
				{
					if( !empty( $match[ (int) ${'ualib_'.$libname}[${$libname}]['version'] ] ) )
					{
						${$libname.'_version'} = $match[ (int) ${'ualib_'.$libname}[${$libname}]['version'] ];
					}
					else
					{
						${$libname.'_version'} = 'unknown';
					}
				}
				elseif( is_array ( ${'ualib_'.$libname}[${$libname}]['version'] ) )
				{
					foreach( ${'ualib_'.$libname}[${$libname}]['version'] as $pattern => $version )
					{
						if( is_int( strpos( $useragent, (string) $pattern ) ) )
						{
							${$libname.'_version'} = $version;
							break;
						}
					}
					if( !isset( ${$libname.'_version'} ) )
					{
						${$libname.'_version'} = 'unknown';
					}
				}

				// mit dem nächsten lib-Typ weiter
				continue 2;
			}
		}

		if( isset( $robot ) )
		{
			unset( $browser );
		}

		unset($ualib_robots, $ualib_browsers, $ualib_os);
		return array(
			'browser'		   => isset( $browser ) ? $browser : FALSE,
			'browser_version'  => isset($browser_version) ? $browser_version : FALSE,
			'browser_icon'	   =>  isset( $browser ) ? $browser_icon : FALSE,
			'os'			   => $os,
			'os_version'	   => isset($os_version) ? $os_version : FALSE,
			'os_icon'		   => $os_icon,
			'robot'			   => isset( $robot ) ? $robot : FALSE,
			'robot_version'	   => isset( $robot_version ) ? $robot_version : FALSE,
			'robot_icon'	   => isset( $robot_icon ) ? $robot_icon : FALSE
		);
	}

	function is_robot($user_agent = null)
	{
		$user_agent = empty($user_agent)? $_SERVER['HTTP_USER_AGENT'] : $user_agent;
		require_once(engine_dir.'modules/counter/lib/user_agents.lib.php');
		static $identified_robots = array();

		if( $robot = array_search( $user_agent, $identified_robots ) )
		{
			return $robot;
		}

		foreach( $ualib_robots as $name => $array )
		{
			if( $array['use_PCRE'] == 1 )
			{
				if( preg_match( $array['pattern'], $user_agent) )
				{
					if( ! ( !empty( $array['anti_pattern'] ) && preg_match( $array['anti_pattern'], $user_agent ) ) )
					{
						$identified_robots[$name] = $user_agent;
						return $name;
					}
				}
			}
			else
			{
				if( is_int( strpos( $user_agent, $array['pattern'] ) ) )
				{
					if( ! ( !empty ( $array['anti_pattern'] ) && is_int( strpos( $user_agent, $array['anti_pattern'] ) ) ) )
					{
						$identified_robots[$name] = $user_agent;
						return $name;
					}
				}
			}
		}
		unset($ualib_robots, $ualib_browsers);
		return FALSE;
	}

	static function valid_ip( $string )
	{
		$array = explode( '.', $string );
		$count = count( $array );
		
		if ( $count != 4 )
		{
			return FALSE;
		}

		for ( $i = 0; $i < $count; $i++ )
		{
			if(
				!preg_match( '/^[0-9]{1,3}$/', $array[$i] )
				|| $array[$i] > 255
			  )
			{
				return FALSE;
			}
		}

		// http://www.faqs.org/rfcs/rfc1918.html: 3. Private Address Space
		if(
			( $array[0] == 10 ) ||
			( $array[0] == 127 ) ||
			( $array[0] == 172 && $array[1] >= 16 && $array[1] <= 31 ) ||
			( $array[0] == 192 && $array[1] === 0 && $array[2] == 2 ) ||
			( $array[0] == 192 && $array[1] == 168 )
		  )
		{
			return FALSE;
		}
		
		return TRUE;
	}
}

?>