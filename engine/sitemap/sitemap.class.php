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

class sitemap
{
	public $data = array();
	public $date = 'c';
	public $header = null;
	public $baseDir = null;
	public $generator = null;
	public $stylesheet = null;
	public $encoding = 'UTF-8';
	public $result = null;
	public $cache = false;
	public $cacheLife = null;
	public $cacheFile = null;
	public $n = "\n";
	
	function __construct()
	{
		$this->header = 'Content-type: application/xml; charset='.$this->encoding;
		$this->generator = 'Apadana Cms Copyright © '.date('Y').' by Iman Moodi (www.apadanacms.ir)';
		$this->stylesheet = $this->baseDir . 'engine/sitemap/sitemap.xsl';
	}
	
	function addItem($loc, $lastmod, $changefreq, $priority = '0.5')
	{
		$loc = str_replace(array('&', '&amp;amp;'), '&amp;', $loc);
		$lastmod = intval($lastmod) <= 0? time() : intval($lastmod);

		$this->data[] = array(
			'loc' => trim($loc),
			'lastmod' => $lastmod,
			'changefreq' => trim($changefreq),
			'priority' => trim($priority),
		);
	}
	
	function is_cache()
	{
		if ($this->cache === true && file_exists($this->cacheFile) && is_writable($this->cacheFile))
		{
			$this->cacheLife = intval($this->cacheLife) <= 60? 14400 : intval($this->cacheLife); // 14400 = 4 Hour
			$cacheFileLife = filemtime($this->cacheFile);
			
			if (($cacheFileLife+$this->cacheLife) > time())
			{
				return true;
			}
		}
		return false;
	}
	
	function get_var()
	{
		$this->build();
		return $this->result;
	}
	
	function display()
	{
		@Header($this->header);
		exit($this->get_var());
	}
	
	function build()
	{
		if ($this->cache === true)
		{
			if ($this->is_cache())
			{
				$this->result = file_get_contents($this->cacheFile);
			}
			else
			{
				$this->_build();
				@file_put_contents($this->cacheFile, $this->result);		
			}
		}
		else
		{
			$this->_build();
		}
	}
	
	function _build()
	{
		if (empty($this->result))
		{
			$this->result  = '<?xml version="1.0" encoding="'.$this->encoding.'"?>'.$this->n;
			$this->result .= '<?xml-stylesheet type="text/xsl" href="'.$this->stylesheet.'"?>'.$this->n;
			$this->result .= '<!-- generator="'.$this->generator.'" -->'.$this->n;
			$this->result .= '<!-- generated-on="'.date($this->date).'" -->'.$this->n;
			$this->result .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$this->n;
			if (is_array($this->data) && count($this->data))
			{
				foreach($this->data as $map)
				{
					#$map['lastmod'] = date_default_timezone_get() == 'Asia/Tehran'? $map['lastmod']-12600 : $map['lastmod']; // GMT
					$this->result .= '<url>'.$this->n;
					$this->result .= '	 <loc>'.$map['loc'].'</loc>'.$this->n;
					$this->result .= '	 <lastmod>'.date($this->date, $map['lastmod']).'</lastmod>'.$this->n;
					$this->result .= '	 <changefreq>'.$map['changefreq'].'</changefreq>'.$this->n;
					$this->result .= '	 <priority>'.$map['priority'].'</priority>'.$this->n;
					$this->result .= '</url>'.$this->n;
				};
			};
			$this->result .= '</urlset>';
		}
	}
	
	function reset()
	{
		$this->data = array();
		$this->date = 'c';
		$this->header = 'Content-type: application/xml; charset=utf-8';
		$this->generator = null;
		$this->stylesheet = null;
		$this->result = null;
		$this->encoding = 'UTF-8';
		$this->cache = false;
		$this->cacheLife = null;
		$this->cacheFile = null;
		$this->n = "\n";
	}
}

?>