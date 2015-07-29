<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCms.ir. All rights reserved.
 * @Apadana CMS is a Free Software
**/

defined('security') or exit('Direct Access to this location is not allowed.');

class pagination 
{
	public $start; // start of selection 
	public $end; // end of selection 
	private $total; // number of totall content
	public $pages; // number of page after set category process
	private $per_page; // number of content per page
	private $page_number; // number of current page
	private	$buttons; // number of max buttons
	public $data;

	function __construct($total, $per_page = 10, $page_number = 1, $buttons = 9)
	{
		$this->total = $total;
		$this->per_page = $per_page;
		$this->pages = ceil($this->total/$this->per_page);
		$this->buttons = $buttons;
		$this->page_number = ($page_number < 1)? 1 : $page_number;
		$this->page_number = ($this->page_number > $this->pages)? $this->pages : $this->page_number;

		// give the page number and return start and end of selection
		$this->start = ($this->page_number-1)*$this->per_page;
		$this->start = $this->start <= 0? 0 : $this->start;
		$this->end = $this->per_page;
	}

	/**
	* Backward campatibility!!
	*
	* @since 1.1
	*/
	public function __get($name) {
		$name =strtolower($name);
		return $this->$name;
	}

	public function build($link, $return = false)
	{
		// if pages == 1 , no need to print pagination
		if ($this->pages <= 1 || $this->total <= 0)
		{
			return null;
		}

		$this->data = array();

		if ($this->pages > $this->buttons)
		{
			if ($this->page_number > 1) 
			{
				$this->data[] = array('url' => str_replace('{page}', 1, $link),'first' => 1,'off' => 0);
			}
			else
			{
				$this->data[] = array('url' => null,'first' => 1,'off' => 1);
			}
		}

		// pre button
		if ($this->page_number>1)
		{
			$this->data[] = array('url' => str_replace('{page}', ($this->page_number -1), $link), 'previous' => 1, 'off' => 0);
		}
		else 
		{
			$this->data[] = array('url' => null, 'previous' => 1, 'off' => 1);
		}

		// print button
		$this->buttons = (int) $this->buttons;
		$start_counter = $this->page_number - floor($this->buttons/2); // for normal mode
		$end_conter = $this->page_number + floor($this->buttons/2); // for normal mode

		// try to buttons exactly equal to $buttons
		if ($start_counter < 1)
		{
			$end_conter = $end_conter + abs($start_counter);
		}
		
		if ($end_conter > $this->pages)
		{
			$start_counter = $start_counter - ($end_conter - $this->pages);
		}
		
		if (($this->page_number - floor($this->buttons/2)) < 1)
		{
			$end_conter ++;
		}

		for ($i = $start_counter; $i <= $end_conter; $i++)
		{
			if ($i > $this->pages || $i < 1)
			{
				continue; // no print less than 1 value or grater than totall page
			}
				
			if ($i == $this->page_number)
			{
				$this->data[] = array('url' => null, 'page' => 1, 'active' => 1, 'number' => $i); // change current page' class
			}
			else
			{
				$this->data[] = array('url' => str_replace('{page}', $i, $link), 'page' => 1, 'active' => 0, 'number' => $i); // normal pages
			}
		}

		// next button
		if ($this->page_number < $this->pages)
		{
			$this->data[] = array('url' => str_replace('{page}', ($this->page_number+1), $link),'next' => 1,'off' => 0);
		}
		else
		{
			$this->data[] = array('url' => null,'next' => 1,'off' => 1);
		}

		if ($this->pages > $this->buttons)
		{
			if ($this->pages > $this->page_number)
			{
				$this->data[] = array('url' => str_replace('{page}', $this->pages, $link),'last' => 1,'off' => 0);
			}
			else
			{
				$this->data[] = array('url' => null, 'last' => 1, 'off' => 1);
			}
		}

		if ($return)
		{
			return $this->data;
		}

		global $tpl, $options;

		foreach ($this->data as $i)
		{
			if (isset($i['first']))
			{
				if ($i['off'] == 0)
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'[first]' => null,
						'[/first]' => null,
						'[on]' => null,
						'[/on]' => null,
						'replace' => array(
							'#\\[(pagination|off|previous|page|active|not-active|next|last)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
				else
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'[first]' => null,
						'[/first]' => null,
						'[off]' => null,
						'[/off]' => null,
						'replace' => array(
							'#\\[(pagination|on|previous|page|active|not-active|next|last)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
			}
			elseif (isset($i['previous']))
			{
				if ($i['off'] == 0)
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'[previous]' => null,
						'[/previous]' => null,
						'[on]' => null,
						'[/on]' => null,
						'replace' => array(
							'#\\[(pagination|off|first|page|active|not-active|next|last)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
				else 
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'[previous]' => null,
						'[/previous]' => null,
						'[off]' => null,
						'[/off]' => null,
						'replace' => array(
							'#\\[(pagination|on|first|page|active|not-active|next|last)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
			}
			elseif (isset($i['page']))
			{
				if ($i['active'] == 0)
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'{number}' => $i['number'],
						'[page]' => null,
						'[/page]' => null,
						'[not-active]' => null,
						'[/not-active]' => null,
						'replace' => array(
							'#\\[(pagination|off|on|first|previous|active|next|last)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
				else 
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'{number}' => $i['number'],
						'[page]' => null,
						'[/page]' => null,
						'[active]' => null,
						'[/active]' => null,
						'replace' => array(
							'#\\[(pagination|off|on|first|previous|not-active|next|last)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
			}
			elseif (isset($i['next']))
			{
				if ($i['off'] == 0)
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'[next]' => null,
						'[/next]' => null,
						'[on]' => null,
						'[/on]' => null,
						'replace' => array(
							'#\\[(pagination|off|first|page|active|not-active|previous|last)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
				else 
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'[next]' => null,
						'[/next]' => null,
						'[off]' => null,
						'[/off]' => null,
						'replace' => array(
							'#\\[(pagination|on|first|page|active|not-active|previous|last)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
			}
			elseif (isset($i['last']))
			{
				if ($i['off'] == 0)
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'[last]' => null,
						'[/last]' => null,
						'[on]' => null,
						'[/on]' => null,
						'replace' => array(
							'#\\[(pagination|off|first|page|active|not-active|previous|next)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
				else 
				{
					$tpl->add_for('pagination', array(
						'{url}' => $i['url'],
						'[last]' => null,
						'[/last]' => null,
						'[off]' => null,
						'[/off]' => null,
						'replace' => array(
							'#\\[(pagination|on|first|page|active|not-active|previous|next)\\](.*?)\\[/\\1\\]#s' => ''
						),
					));
				}
			}
		}
	}
}
