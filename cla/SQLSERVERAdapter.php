<?php
include 'SQLServerAdapterException.php';

class SQLServerAdapter
{
    protected $_config = array();
    protected $_link;
    protected $_result;
    protected static $_instance;
   // public $con;

    /**
     * Get the Singleton instance of the class
     */
    public static function getInstance(array $config = array())
    {
        if (self::$_instance === null) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }

    /**
     * Class constructor
     */
    protected function __construct(array $config)
    {
        if (count($config) !== 4) {
            throw new SQLServerAdapterException('Invalid number of connection parameters.');
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
        if ($this->_link === null) {
            list($host, $user, $password, $database) = $this->_config;
/*
			$connectionInfo = array(
				"Database"=>$database,
				"UID"=>$user,
				"PWD"=>$password
			);
*/
	$serverName = "localhost"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"Ctrldoc", "UID"=>"ctrldoc", "PWD"=>"ctrldoc123");
	$this->_link= sqlsrv_connect( $serverName, $connectionInfo);

			//sqlsrv_query($this->_link, "SET ANSI_NULLS ON");
			//sqlsrv_query($this->_link, "SET ANSI_WARNINGS ON");
			if (!$this->_link) {
				throw new SQLServerAdapterException('Error connecting to MSSQL');
			}

            //unset($host, $user, $password, $database);
        }
    }
	
    /**
     * Execute the specified query
     */
    public function query($query)
    {
        if (!is_string($query) || empty($query)) {
        	echo $query;
        	exit();
            //throw new SQLServerAdapterException('The specified query is not valid.');
        }
        // lazy connect to MSSQL
        $this->connect();

        //echo $query;
        $this->_result = sqlsrv_query($this->_link, $query);
        /*
		if (!$this->_result = sqlsrv_query(self::_link, $query))
		{
            //throw new SQLServerAdapterException('Error executing the specified query ' . $query . mssql_error($this->_link));
            throw new SQLServerAdapterException('Error executing the specified query ' . $query);
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
        foreach ($data as $field => $value) {
            $set[] = $field . '=' . $this->quoteValue($value);
        }
        */
        foreach ($data as $field => $value) {
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
        if ($value === null) {
            $value = 'NULL';
        }
        else if (!is_numeric($value)) {

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
        if ($this->_result !== null) {
            if ((!$row = sqlsrv_fetch_array($this->_result, SQLSRV_FETCH_ASSOC))) {

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
    	if ($this->_result !== null) {
    		if ((!$row = sqlsrv_fetch_array($this->_result, SQLSRV_FETCH_NUMERIC))) {

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
        //$this->_result = sqlsrv_query($query);
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
               sqlsrv_num_rows($this->_result) :
               0;
    }

    /**
     * Get the number of affected rows
     */
    public function getAffectedRows()
    {
        return $this->_link !== null ? sqlsrv_rows_affected($this->_link) : 0;
    }

    /**
     * Free up the current result set
     */
    public function freeResult()
    {
        if ($this->_result !== null) {
           // sqlsrv_free_stmt($this->_result);
        }
    }

    /**
     * Close explicitly the database connection
     */
    public function disconnect()
    {
        if ($this->_link !== null) {
            sqlsrv_close($this->_link);
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
}