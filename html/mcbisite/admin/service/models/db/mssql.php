<?php

include_once('dbal.php');

/**
* MSSQL Database Abstraction Layer
* @package dbal
*/
class dbal_mssql extends dbal
{
	var $multi_insert = true;

	/**
	* Connect to server
	* @access public
	*/
	function sql_connect($sqlserver, $sqluser, $sqlpassword, $database, $port = false, $persistency = false, $new_link = false)
	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->server = $sqlserver . (($port) ? ':' . $port : '');
		$this->dbname = $database;

		$this->sql_layer = 'mssql';

		$this->db_connect_id = ($this->persistency) ? @mssql_pconnect($this->server, $this->user, $sqlpassword, $new_link) : @mssql_connect($this->server, $this->user, $sqlpassword, $new_link);

		if ($this->db_connect_id && $this->dbname != '')
		{
			//error_log("se realizó conección". $this->dbname);
			if (@mssql_select_db($this->dbname, $this->db_connect_id))
			{
				
				//error_log("se seleccionó base de datos");
				// Determine what version we are using and if it natively supports UNICODE
				if (version_compare($this->sql_server_info(true), '4.1.0', '>='))
				{
					@mssql_query("SET NAMES 'utf8'", $this->db_connect_id);

					// enforce strict mode on databases that support it
					if (version_compare($this->sql_server_info(true), '5.0.2', '>='))
					{
						$result = @mssql_query('SELECT @@session.sql_mode AS sql_mode', $this->db_connect_id);
						$row = @mssql_fetch_assoc($result);
						@mssql_free_result($result);
						$modes = array_map('trim', explode(',', $row['sql_mode']));

						// TRADITIONAL includes STRICT_ALL_TABLES and STRICT_TRANS_TABLES
						if (!in_array('TRADITIONAL', $modes))
						{
							if (!in_array('STRICT_ALL_TABLES', $modes))
							{
								$modes[] = 'STRICT_ALL_TABLES';
							}

							if (!in_array('STRICT_TRANS_TABLES', $modes))
							{
								$modes[] = 'STRICT_TRANS_TABLES';
							}
						}

						$mode = implode(',', $modes);
						@mssql_query("SET SESSION sql_mode='{$mode}'", $this->db_connect_id);
					}
				}
				else if (version_compare($this->sql_server_info(true), '4.0.0', '<'))
				{
					$this->sql_layer = 'mssql';
				}

				return $this->db_connect_id;
			}
		}

		return $this->sql_error('');
	}

	/**
	* Version information about used database
	* @param bool $raw if true, only return the fetched sql_server_version
	* @return string sql server version
	*/
	function sql_server_info($raw = false)
	{
		global $cache;

		if (empty($cache) || ($this->sql_server_version = $cache->get('mssql_version')) === false)
		{
			$result = @mssql_query('SELECT VERSION() AS version', $this->db_connect_id);
			$row = @mssql_fetch_assoc($result);
			@mssql_free_result($result);

			$this->sql_server_version = $row['version'];

			if (!empty($cache))
			{
				$cache->put('mssql_version', $this->sql_server_version);
			}
		}

		return ($raw) ? $this->sql_server_version : 'MSSQL ' . $this->sql_server_version;
	}

	/**
	* SQL Transaction
	* @access private
	*/
	function _sql_transaction($status = 'begin')
	{
		switch ($status)
		{
			case 'begin':
				return @mssql_query('BEGIN', $this->db_connect_id);
			break;

			case 'commit':
				return @mssql_query('COMMIT', $this->db_connect_id);
			break;

			case 'rollback':
				return @mssql_query('ROLLBACK', $this->db_connect_id);
			break;
		}

		return true;
	}

	/**
	* Base query method
	*
	* @param	string	$query		Contains the SQL query which shall be executed
	* @param	int		$cache_ttl	Either 0 to avoid caching or the time in seconds which the result shall be kept in cache
	* @return	mixed				When casted to bool the returned value returns true on success and false on failure
	*
	* @access	public
	*/
	function sql_query($query = '', $cache_ttl = 0)
	{
		if ($query != '')
		{
			global $cache;

			// EXPLAIN only in extra debug mode
			if (defined('DEBUG_EXTRA'))
			{
				$this->sql_report('start', $query);
			}

			$this->query_result = ($cache_ttl && method_exists($cache, 'sql_load')) ? $cache->sql_load($query) : false;
			$this->sql_add_num_queries($this->query_result);

			
			if ($this->query_result === false)
			{
				if (($this->query_result = @mssql_query($query, $this->db_connect_id)) === false)
				{
					error_log("sql_error :: " . json_encode($this->sql_error($query)) . $query);
					$this->sql_error($query);
				}else{
					//error_log("sql_query::OK::". $query);
					//error_log("sql :: " .json_encode($this->db_connect_id));
				}

				if (defined('DEBUG_EXTRA'))
				{
					$this->sql_report('stop', $query);
				}

				if ($cache_ttl && method_exists($cache, 'sql_save'))
				{
					$this->open_queries[(int) $this->query_result] = $this->query_result;
					$cache->sql_save($query, $this->query_result, $cache_ttl);
				}
				else if (strpos($query, 'SELECT') === 0 && $this->query_result)
				{
					$this->open_queries[(int) $this->query_result] = $this->query_result;
				}
			}
			else if (defined('DEBUG_EXTRA'))
			{
				$this->sql_report('fromcache', $query);
			}
		}
		else
		{
			return false;
		}

		return $this->query_result;
	}

	/**
	* Build LIMIT query
	*/
	function _sql_query_limit($query, $total, $offset = 0, $cache_ttl = 0)
	{
		$this->query_result = false;

		// if $total is set to 0 we do not want to limit the number of rows
		if ($total == 0)
		{
			// Having a value of -1 was always a bug
			$total = '18446744073709551615';
		}

		$query .= "\n LIMIT " . ((!empty($offset)) ? $offset . ', ' . $total : $total);

		return $this->sql_query($query, $cache_ttl);
	}

	/**
	* Return number of affected rows
	*/
	function sql_affectedrows()
	{
		return ($this->db_connect_id) ? @mssql_rows_affected($this->db_connect_id) : false;
	}

	/**
	* Fetch current row assoc format
	*/
	function sql_fetchrow($query_id = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_fetchrow($query_id);
		}
		

		$row =  ($query_id !== false) ? @mssql_fetch_assoc($query_id) : false;
		
		if($row){
			foreach ($row as $key => $val) {
				//$row[$key] = utf8_encode(htmlentities($val,ENT_COMPAT,'utf-8'));
				//$row[$key] = htmlentities($val,ENT_COMPAT,'utf-8');
				//$row[$key] = mb_convert_encoding($val, "UTF-8", mb_detect_encoding($val, "UTF-8, ISO-8859-1, ISO-8859-15", true));
				$row[$key] = mb_convert_encoding($val, "UTF-8", mb_detect_encoding($val, "UTF-8, ISO-8859-1, ISO-8859-15", false));
			}
		}
		
		return $row;
	}

	/**
	* Fetch current row array format
	*/
	function sql_fetchrow_array($query_id = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_fetchrow_array($query_id);
		}

		return ($query_id !== false) ? @mssql_fetch_array($query_id) : false;
	}

	/**
	* Seek to given row number
	* rownum is zero-based
	*/
	function sql_rowseek($rownum, &$query_id)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_rowseek($rownum, $query_id);
		}

		return ($query_id !== false) ? @mssql_data_seek($query_id, $rownum) : false;
	}

	/**
	* Get last inserted id after insert statement
	*/
	function sql_nextid()
	{
		global $db, $emailActivation, $websiteUrl, $db_table_prefix;
		$sql = "SELECT SCOPE_IDENTITY() last_intertion";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
	    return $row['last_intertion'];
	}

	/**
	* Free sql result
	*/
	function sql_freeresult($query_id = false)
	{
		global $cache;

		if ($query_id === false)
		{
			$query_id = $this->query_result;
		}

		if (isset($cache->sql_rowset[$query_id]))
		{
			return $cache->sql_freeresult($query_id);
		}

		if (isset($this->open_queries[(int) $query_id]))
		{
			unset($this->open_queries[(int) $query_id]);
			return @mssql_free_result($query_id);
		}

		return false;
	}

	/**
	* Escape string used in sql query
	*/
	function sql_escape($data)
	{
		/*
		if(is_numeric($data))
			return $data;
		$unpacked = unpack('H*hex', $data);
		return '0x' . $unpacked['hex'];
		*/
		if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "''", $data );
	
		return $data;
	}
	
	
	function mssql_escape($data) {
		/*
		if(is_numeric($data))
			return $data;
		$unpacked = unpack('H*hex', $data);
		return '0x' . $unpacked['hex'];
		*/
		if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "''", $data );
	
		return $data;
	}

	/**
	* Build LIKE expression
	* @access private
	*/
	function _sql_like_expression($expression)
	{
		return $expression;
	}

	/**
	* Build db-specific query data
	* @access private
	*/
	function _sql_custom_build($stage, $data)
	{
		switch ($stage)
		{
			case 'FROM':
				$data = '(' . $data . ')';
			break;
		}

		return $data;
	}

	/**
	* return sql error array
	* @access private
	*/
	function _sql_error()
	{
		error_log("ERROR MSSQL: ".json_encode(@mssql_get_last_message()));
		if (!$this->db_connect_id)
		{
			return array(
				'message'	=> @mssql_get_last_message(),
				'code'		=> @mssql_get_last_message()
			);
		}

		return array(
			'message'	=> @mssql_get_last_message($this->db_connect_id),
			'code'		=> @mssql_get_last_message($this->db_connect_id)
		);
	}

	/**
	* Close sql connection
	* @access private
	*/
	function _sql_close()
	{
		return @mssql_close($this->db_connect_id);
	}
	
	
	function sql_report($mode, $query = ''){
		error_log("MODE: ".$mode. " SQL: ".$query);
	}

	/**
	* Build db-specific report
	* @access private
	*/
	function _sql_report($mode, $query = '')
	{
		static $test_prof;

		// current detection method, might just switch to see the existance of INFORMATION_SCHEMA.PROFILING
		if ($test_prof === null)
		{
			$test_prof = false;
			if (version_compare($this->sql_server_info(true), '5.0.37', '>=') && version_compare($this->sql_server_info(true), '5.1', '<'))
			{
				$test_prof = true;
			}
		}

		switch ($mode)
		{
			case 'start':

				$explain_query = $query;
				if (preg_match('/UPDATE ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}
				else if (preg_match('/DELETE FROM ([a-z0-9_]+).*?WHERE(.*)/s', $query, $m))
				{
					$explain_query = 'SELECT * FROM ' . $m[1] . ' WHERE ' . $m[2];
				}

				if (preg_match('/^SELECT/', $explain_query))
				{
					$html_table = false;

					// begin profiling
					if ($test_prof)
					{
						@mssql_query('SET profiling = 1;', $this->db_connect_id);
					}

					if ($result = @mssql_query("EXPLAIN $explain_query", $this->db_connect_id))
					{
						while ($row = @mssql_fetch_assoc($result))
						{
							$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
						}
					}
					@mssql_free_result($result);

					if ($html_table)
					{
						$this->html_hold .= '</table>';
					}

					if ($test_prof)
					{
						$html_table = false;

						// get the last profile
						if ($result = @mssql_query('SHOW PROFILE ALL;', $this->db_connect_id))
						{
							$this->html_hold .= '<br />';
							while ($row = @mssql_fetch_assoc($result))
							{
								// make <unknown> HTML safe
								if (!empty($row['Source_function']))
								{
									$row['Source_function'] = str_replace(array('<', '>'), array('&lt;', '&gt;'), $row['Source_function']);
								}

								// remove unsupported features
								foreach ($row as $key => $val)
								{
									if ($val === null)
									{
										unset($row[$key]);
									}
								}
								$html_table = $this->sql_report('add_select_row', $query, $html_table, $row);
							}
						}
						@mssql_free_result($result);

						if ($html_table)
						{
							$this->html_hold .= '</table>';
						}

						@mssql_query('SET profiling = 0;', $this->db_connect_id);
					}
				}

			break;

			case 'fromcache':
				$endtime = explode(' ', microtime());
				$endtime = $endtime[0] + $endtime[1];

				$result = @mssql_query($query, $this->db_connect_id);
				while ($void = @mssql_fetch_assoc($result))
				{
					// Take the time spent on parsing rows into account
				}
				@mssql_free_result($result);

				$splittime = explode(' ', microtime());
				$splittime = $splittime[0] + $splittime[1];

				$this->sql_report('record_fromcache', $query, $endtime, $splittime);

			break;
		}
	}
}

?>