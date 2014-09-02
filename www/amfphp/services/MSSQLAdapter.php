<?php
include 'MSSQLAdapterException.php';

class MSSQLAdapter
{
	protected $_config = array();
	protected $_link;
	protected $_result;
	protected static $_instance;
	//public $con;

	/**
	 * Get the Singleton instance of the class
	 */
	public static function getInstance(array $config = array())
	{
		if (self::$_instance === null)
		{
			self::$_instance = new self($config);
		}
		return self::$_instance;
	}

	/**
	 * Class constructor
	 */
	protected function __construct(array $config)
	{
		if (count($config) !== 4)
		{
			throw new MSSQLAdapterException('Invalid number of connection parameters.');
		}
		$this->_config = $config;
	}

	/**
	 * Prevent cloning the instance of the class
	 */
	protected function __clone(){}

	/**
	 * Connect to MSSQL
	 */
	public function connect()
	{
		// connect only once
		if ($this->_link === null)
		{
			list($host, $user, $password, $database) = $this->_config;
			$this->_link =  mssql_connect($host, $user, $password);
			mssql_select_db($database, $this->_link);
			mssql_query("SET ANSI_NULLS ON", $this->_link);
			mssql_query("SET ANSI_WARNINGS ON", $this->_link);
			if (!$this->_link)
			{
				throw new MSSQLAdapterException('Error connecting to MSSQL');
			}
			unset($host, $user, $password, $database);
		}
	}

	/**
	 * Execute the specified query
	 */
	public function query($query)
	{
		if (!is_string($query) || empty($query))
		{
			echo $query;
			exit();
			//throw new MSSQLAdapterException('The specified query is not valid.');
		}
		// lazy connect to MSSQL
		$this->connect();
		//echo $query;
		$this->_result = mssql_query($query);
		/*
		if (!$this->_result = mssql_query(self::_link, $query))
		{
			//throw new MSSQLAdapterException('Error executing the specified query ' . $query . mssql_error($this->_link));
			throw new MSSQLAdapterException('Error executing the specified query ' . $query);
		}
		*/
	}

	/**
	 * Perform a SELECT statement
	 */
	public function select($table, $fields = '*', $where = '',  $order = '', $group = null, $top = null)
	{
		$query = 'SELECT '
			. (($top) ? ' TOP ' . $top : '')
			. $fields
			. ' FROM ' . $table
			. (($where) ? ' WHERE ' . $where : '')
			. (($group) ? ' GROUP BY ' . $group : '')
			. (($order) ? ' ORDER BY ' . $order : '');
		$this->query($query);
		return $this->countRows();
	}

	/**
	 * Perform an INSERT statement
	 */
	public function insert($table, array $data)
	{
		$fields = implode(',', array_keys($data));
		//$values = implode(',', array_map(array($this, 'quoteValue'), array_values($data)));
		$values = implode(',', array_map(array($this, 'noQuoteValue'), array_values($data)));
		$query = 'INSERT INTO ' . $table . '(' . $fields . ')' . ' VALUES (' . $values . ')';
		$this->query($query);
		//return $this->getInsertId($table);
	}

	public function noQuoteValue($value)
	{
		return $value;
	}

	/**
	 * Perform an UPDATE statement
	 */
	public function update($table, array $data, $where = '')
	{
		$set = array();
		/*
		foreach ($data as $field => $value)
		{
			$set[] = $field . '=' . $this->quoteValue($value);
		}
		*/
		foreach ($data as $field => $value)
		{
			$set[] = $field . '=' . $value;
		}
		$set = implode(',', $set);
		$query = 'UPDATE ' . $table . ' SET ' . $set
			. (($where) ? ' WHERE ' . $where : '');
		//echo $query;
		$this->query($query);
		return $this->getAffectedRows();
	}

	/**
	 * Perform a DELETE statement
	 */
	public function delete($table, $where = '')
	{
		$query = 'DELETE FROM ' . $table
			. (($where) ? ' WHERE ' . $where : '');
		$this->query($query);
		return $this->getAffectedRows();
	}

	/**
	 * Single quote the specified value
	 */
	public function quoteValue($value)
	{
		if ($value === null)
		{
			$value = 'NULL';
		}
		else if (!is_numeric($value))
		{
			//$value = "'" . mysqli_real_escape_string($this->_link, $value) . "'";
			$value = "'" . $this->mysql_escape_mimic($value) . "'";
		}
		return $value;
	}

	/**
	 * Fetch a single row from the current result set (as an associative array)
	 */
	public function fetch()
	{
		if ($this->_result !== null)
		{
			if ((!$row = mssql_fetch_array($this->_result, MSSQL_ASSOC)))
			{
				$this->freeResult();
				return false;
			}
			return $row;
		}
	}

	/**
	* Fetch a single row from the current result set (as an array)
	*/
	public function fetchArray()
	{
		if ($this->_result !== null)
		{
			if ((!$row = mssql_fetch_array($this->_result, MSSQL_NUM)))
			{
				$this->freeResult();
				return false;
			}
			return $row;
		}
	}

	/**
	 * Get the insertion ID
	 */
	public function getInsertId($table)
	{
		//return $this->_link !== null ?  $this->query("SELECT scope_identity() as id from $table") : null;
		$this->connect();
		$query = "SELECT scope_identity() as id from $table";
		//$this->_result = mssql_query($query);
		$this->query($query);
		return $this->fetch();
		//return $this->query("SELECT scope_identity() as id from $table");
	}

	/**
	 * Get the number of rows returned by the current result set
	 */
	public function countRows()
	{
		return $this->_result !== null ?
			mssql_num_rows($this->_result) :
			0;
	}

	/**
	 * Get the number of affected rows
	 */
	public function getAffectedRows()
	{
		return $this->_link !== null ? mssql_rows_affected($this->_link) : 0;
	}

	/**
	 * Free up the current result set
	 */
	public function freeResult()
	{
		if ($this->_result !== null)
		{
			// mssql_free_result($this->_result);
		}
	}

	/**
	 * Close explicitly the database connection
	 */
	public function disconnect()
	{
		if ($this->_link !== null)
		{
			mssql_close($this->_link);
			$this->_link = null;
		}
	}

	/**
	 * Close automatically the database connection when the instance of the class is destroyed
	 */
	public function __destruct()
	{
		$this->disconnect();
	}

	/**
	 * Mimic mysql_real_escape_string() behavior, without an active connection
	 */
	public function mysql_escape_mimic($inp)
	{
		if(is_array($inp))
			return array_map(__METHOD__, $inp);
		if(!empty($inp) && is_string($inp))
		{
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
		}

		return $inp;
	}

	/**
	 * Executes the SQL query <code>$sql</code> and returns a single row from the result's associative array, if <code>$debug</code> is set to true, echoes the query and returns and empty array.
	 * @param String $sql
	 * @param Boolean $debug
	 * @return Array $row
	 */
	public function getSingleRow($sql)
	{
		$result = self::getInstance()->query($sql);
		$row = self::getInstance()->fetch();
		self::getInstance()->freeResult();
		if (isset($row))
		{
			return $row;
		}
		return array();
	}

	/**
	 * Executes the SQL query <code>$sql</code> and returns all rows from the result's associative array, if <code>$debug</code> is set to true, echoes the query and returns and empty array.
	 * @param String $sql
	 * @param Boolean $debug
	 * @return Array $ret
	 */
	public function getAllRows($sql)
	{
		$result = self::getInstance()->query($sql);
		while ($row = self::getInstance()->fetch())
		{
			$ret[] = $row;
		}
		self::getInstance()->freeResult();
		if (isset($ret))
		{
			return $ret;
		}
		return array();
	}

	/**
	* Executes the SQL query <code>$sql</code> and returns a all rows from the result's array, if <code>$debug</code> is set to true, echoes the query and returns and empty array.
	* @param String $sql
	* @param Boolean $debug
	* @return Array $ret
	*/
	public function getAllRowsArray($sql)
	{
		$result = self::getInstance()->query($sql);
		while ($row = self::getInstance()->fetchArray())
		{
			$ret[] = $row;
		}
		self::getInstance()->freeResult();
		if (isset($ret))
		{
			return $ret;
		}
		return array();
	}

	public function insertRecord($table, array $data)
	{
		self::getInstance()->insert($table, $data);
	}

	public function deleteRecord($table, $where)
	{
		return self::getInstance()->delete($table, $where);
	}

	public function getInsertedRowID($table)
	{
		return self::getInstance()->getInsertId($table);
	}

	public function updateTable($table, array $data, $where = '')
	{
		return self::getInstance()->update($table, $data, $where);
	}
}