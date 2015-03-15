<?php
/**
 * @In the name of God!
 * @author: Iman Moodi (Iman92)
 * @email: info@apadanacms.ir
 * @link: http://www.apadanacms.ir
 * @license: http://www.gnu.org/licenses/
 * @copyright: Copyright © 2012-2015 ApadanaCMS.ir. All rights reserved.
 * @Apadana CMS is a Free Software
 */

defined('security') or exit('Direct Access to this location is not allowed.');

class database
{
	private $mysqli = false;
	public $connect = null;
	public $num_queries = 0;
	public $query_time = 0;
	public $debug = false;
	public $prefix = null;
	public $result = null;
	public $charset = null;
	public $save_queries = false;
	public $queries = array();

	public function connect($db) 
	{		
		$this->mysqli = extension_loaded('mysqli')? true : false;
		$this->debug = debug_system;
		$this->save_queries = defined('database_save_queries') && database_save_queries? true : false;
		$this->prefix = $db['prefix'];
		$this->charset = $db['charset'];

		if ($this->mysqli)
		{
			$this->connect = mysqli_connect($db['host'], $db['user'], $db['password']);
		}
		else
		{
			$this->connect = mysql_connect($db['host'], $db['user'], $db['password'], true);
		}

		if (!$this->connect) 
		{
			return false;
		}

		return $this->select_db($db['name']);
	}

	public function select_db($name) 
	{
		if ($this->mysqli)
		{
			if (!mysqli_select_db($this->connect, $name)) 
			{
				return false;
			}

			mysqli_query($this->connect, 'SET CHARACTER SET '.$this->charset.';');
			mysqli_query($this->connect, 'SET SESSION collation_connection="'.$this->charset.'_general_ci"');		
			mysqli_set_charset($this->connect, 'utf8');
		}
		else
		{
			if (!mysql_select_db($name, $this->connect)) 
			{
				return false;
			}

			mysql_query('SET CHARACTER SET '.$this->charset.';', $this->connect);
			mysql_query('SET SESSION collation_connection="'.$this->charset.'_general_ci"', $this->connect);		
			mysql_set_charset('utf8', $this->connect);
		}
		return true;
	}	

	protected function replace_prefix($sql, $prefix = '#__') 
	{
		$done = null;
		$single = false;
		$double = false;
		$found = false;
		$i = 0;
		while (strlen($sql) > 0)
		{
			if ($sql[$i] == null) return $done.$sql;
			if (($sql[$i] == "'") && $sql[$i-1] !='\\')
				$single = !$single;
			if (($sql[$i] == '"') && $sql[$i-1] !='\\')
				$double = !$double;

			if ($sql[$i] == $prefix[0] && !$single && !$double)
			{
				$found = true;
				for ($j=0; $j < strlen($prefix); $j++)
				{
					if ($sql[$i+$j] != $prefix[$j])
					{
						$found = false;
						break;
					}
				}
			}
			if ($found)
			{
				$done .= substr($sql, 0, $i).$this->prefix;
				$sql = substr($sql, $i+$j);
				$found = false;
				$i = 0;
			}
			else
				$i++;
			if ($i >= strlen($sql)) return $done.$sql;
		}
		return $done;
	}	

	public function query($string = null, $hide_errors = true, $prefix = '#__')
	{
		unset($this->result);

		if ($string != '')
		{
			$this->get_execution_time();
			
			if ($this->mysqli)
			{
				$this->result = @mysqli_query($this->connect, $this->replace_prefix($string, $prefix));
			}
			else
			{
				$this->result = @mysql_query($this->replace_prefix($string, $prefix), $this->connect);
			}

			if ($this->error_number() && ($this->debug || !$hide_errors))
			{
				$this->error($string);
			}

			$query_time = $this->get_execution_time();

			if ($this->save_queries)
			{
				$this->queries[] = array(
					'query' => preg_replace('/([0-9a-f]){32}/', '********************************', $string), /* Hides all hashes */
					'result' => $this->result,
					'execution_time' => $query_time,
					'error_number' => $this->error_number(),
					'error_string' => $this->error_string()
				);
			}

			$this->query_time += $query_time;
			$this->num_queries++;
		}

		if ($this->result)
		{
			return $this->result;
		}

		return false;
	}

	public function fetch($query_id = false, $fetch = 'assoc', $set_query = false)
	{
		if (!($fetch == 'array' || $fetch == 'assoc' || $fetch == 'object' || $fetch == 'row'))
		{
			echo '<b>Fetch type must be one of: array, assoc, object, row</b>';
			return false;
		}

		$fetch = 'mysql'.($this->mysqli? 'i' : null).'_fetch_'.$fetch;

		if (!$query_id)
		{
			$query_id = $this->result;
		}

		if ($set_query)
		{
			$query_id = $this->query($query_id);
		}

		if ($query_id)
		{
			return $fetch($query_id);
		}
		else
		{
			return false;
		}
	}

	public function get_row($query, $fetch = 'assoc', $index = false)
	{
		$this->query($query);

		if ($this->num_rows() <= 0)
		{
			return false;
		}

		$rows = array();
		while ($row = $this->fetch($this->result, $fetch))
		{
			if (!empty($index) && (isset($row->$index) || isset($row[$index])))
			{
				switch ($fetch)
				{
					case 'object':
					$rows[$row->$index] = $row;
					break;

					default:
					$rows[$row[$index]] = $row;
					break;
				}
			}
			else
			{
				$rows[] = $row;
			}
		}

		$this->free_result();
		return $rows;
	}

	public function insert($table, $array, $escape = true)
	{
		if (!is_array($array))
		{
			return false;
		}

		if ($escape)
		{
			$array = $this->escape_string($array);
		}

		$fields = "`".implode("`,`", array_keys($array))."`";
		$values = implode("','", $array);
		$this->query("
			INSERT
			INTO {$this->prefix}{$table} (".$fields.")
			VALUES ('".$values."')
		");

		return $this->insert_id();
	}

	public function update($table, $array, $where = null, $limit = null, $escape = true, $no_quote = false)
	{
		if (!is_array($array))
		{
			return false;
		}

		if ($escape)
		{
			$array = $this->escape_string($array);
		}

		$comma = null;
		$query = null;
		$quote = "'";

		if ($no_quote == true)
		{
			$quote = null;
		}

		foreach ($array as $field => $value)
		{
			$query .= $comma."`".$field."`={$quote}{$value}{$quote}";
			$comma = ', ';
		}

		if (!empty($where))
		{
			$query .= " WHERE {$where}";
		}

		if (!empty($limit))
		{
			$query .= " LIMIT {$limit}";
		}

		return $this->query("
			UPDATE {$this->prefix}{$table}
			SET {$query}
		");
	}

	public function delete($table, $where = null, $limit = null)
	{
		$query = null;

		if (!empty($where))
		{
			$query .= ' WHERE '.$where;
		}

		if (!empty($limit))
		{
			$query .= 'LIMIT '.$limit;
		}

		return $this->query("
			DELETE
			FROM {$this->prefix}{$table}
			{$query}
		");
	}

	public function num_rows($query_id = false, $set_query = false)
	{
		if (!$query_id)
		{
			$query_id = $this->result;
		}

		if ($set_query)
		{
			$query_id = $this->query($query_id);
		}

		if ($query_id)
		{
			if ($this->mysqli)
			{
				return mysqli_num_rows($query_id);
			}
			else
			{
				return mysql_num_rows($query_id);
			}
		}
		else
		{
			return false;
		}
	}

	# this will be removed in the following version
	public function numRows($query_id = false, $set_query = false)
	{
		trigger_error('Function '.__CLASS__.'->'.__FUNCTION__.'() is deprecated', E_USER_DEPRECATED);

		return $this->num_rows($query_id, $set_query);
	}

	public function affected_rows()
	{
		if ($this->connect)
		{
			if ($this->mysqli)
			{
				return mysqli_affected_rows($this->connect);
			}
			else
			{
				return mysql_affected_rows($this->connect);
			}
		}
		else
		{
			return false;
		}
	}

	# this will be removed in the following version
	public function affectedRows()
	{
		trigger_error('Function '.__CLASS__.'->'.__FUNCTION__.'() is deprecated', E_USER_DEPRECATED);

		return $this->affected_rows();
	}

	public function free_result($query_id = false)
	{
		if (!$query_id)
		{
			$query_id = $this->result;
		}

		if ($query_id)
		{
			if ($this->mysqli)
			{
				return mysqli_free_result($query_id);
			}
			else
			{
				return mysql_free_result($query_id);
			}
		}
		else
		{
			return false;
		}
	}

	# this will be removed in the following version
	public function freeResult($query_id = false)
	{
		trigger_error('Function '.__CLASS__.'->'.__FUNCTION__.'() is deprecated', E_USER_DEPRECATED);

		return $this->free_result($query_id);
	}

	public function insert_id()
	{
		if ($this->connect)
		{
			if ($this->mysqli)
			{
				return mysqli_insert_id($this->connect);
			}
			else
			{
				return mysql_insert_id($this->connect);
			}
		}
		else
		{
			return false;
		}
	}	

	# this will be removed in the following version
	public function insertID()
	{
		trigger_error('Function '.__CLASS__.'->'.__FUNCTION__.'() is deprecated', E_USER_DEPRECATED);

		return $this->insert_id();
	}

	public function escape_string($string)
	{
		if (is_array($string))
		{
			foreach ($string as $key => $str)
			{
				$string[$key] = $this->escape_string($str);
			}
		}
		elseif (is_object($string))
		{
			foreach ($string as $key => $str)
			{
				$string->{$key} = $this->escape_string($str);
			}
		}
		else
		{
			/*if (get_magic_quotes_gpc())
			{
				$string = stripslashes($string);
			}*/

			//check if this function exists
			if ($this->mysqli && function_exists('mysqli_real_escape_string') && $this->connect)
			{
				$string = mysqli_real_escape_string($this->connect, $string);
			}
			elseif (function_exists('mysql_real_escape_string') && $this->connect)
			{
				$string = mysql_real_escape_string($string, $this->connect);
			}
			//for PHP version < 4.3.0 use addslashes
			else
			{
				$string = addslashes($string);
			}
		}

		return $string;		
	}	

	# this will be removed in the following version
	public function escapeString($string)
	{
		trigger_error('Function '.__CLASS__.'->'.__FUNCTION__.'() is deprecated', E_USER_DEPRECATED);

		return $this->escape_string($string);
	}

	public function get_execution_time()
	{
		static $time_start;

		$time = microtime(true);

		// Just starting timer, init and return
		if (!$time_start)
		{
			$time_start = $time;
			return 0;
		}
		// Timer has run, return execution time
		else
		{
			$total = $time-$time_start;
			$time_start = 0;
			return $total < 0? 0 : $total;
		}
	}	

	public function error_number()
	{
		if ($this->connect)
		{
			if ($this->mysqli)
			{
				return mysqli_errno($this->connect);			
			}
			else
			{
				return mysql_errno($this->connect);
			}
		}
	}

	public function error_string()
	{
		if ($this->connect)
		{
			if ($this->mysqli)
			{
				return mysqli_error($this->connect);			
			}
			else
			{
				return mysql_error($this->connect);
			}
		}
	}

	public function error($string = '')
	{
		if ($this->debug)
		{
			$string = trim($string);
			$string = explode("\n", $string);
			$string = array_map('trim', $string);
			$string = implode("\n", $string);
			echo("\n<!-- ERROR SQL! -->\n<div style='background:#FFCCCC;border:#FF6A6A 1px solid;padding:5px;margin:5px;direction:ltr;text-align:left'>\n<strong>[SQL] [".$this->error_number()."] ".$this->error_string()."</strong><br />\n<b>STRING:</b><pre style=\"direction:ltr;text-align:left;overflow-x:auto;\">{$string}</pre>\n</div>\n<!-- www.ApadanaCMS.ir -->\n\n");
		}
		else
		{
			return false;
		}
	}

	public function version() 
	{
		return preg_replace('/[^0-9.].*/', '', $this->mysqli? mysqli_get_server_info($this->connect) : mysql_get_server_info($this->connect));
	}

	public function __destruct()
	{
		return $this->close();
	}

	public function close()
	{
		if ($this->connect)
		{
			if ($this->mysqli)
			{
				$result = mysqli_close($this->connect);			
			}
			else
			{
				$result = mysql_close($this->connect);			
			}

			$this->connect = false;			
			return $result;			
		}
		else
		{
			return false;
		}
	}

}
