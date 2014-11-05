<?php
class PDOAdapter
{
	protected $_config = array();
	protected $_link;
	protected $_result;
	protected static $_instance;

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
			throw new Exception('Invalid number of connection parameters.');
		}
		$this->_config = $config;

		if ($this->_link === null)
		{
			list($host, $user, $password, $database) = $this->_config;


			try {
				$dsn = "sqlsrv:server=" . $host . ";Database=" . $database;
				$pdo = new PDO($dsn, $user, $password);
				//Desarrollo
				//$pdo = setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_SYSTEM);
				$this->_link = $pdo;
			} catch (PDOException $e) {
				echo "Error de conexion: " . $e->getMessage();
			}

			unset($host, $user, $password, $database);
		}
	}

	/**
	 * Prevent cloning the instance of the class
	 */
	protected function __clone(){}

	public function getSingleRow($sql)
	{
		return $this->_link->query($sql)->fetch(PDO::FETCH_OBJ);
	}

	public function getAllRows($sql)
	{
		return $this->_link->query($sql)->fetchAll(PDO::FETCH_OBJ);
	}

	public function getAllRowsArray($sql)
	{
		return $this->_link->query($sql)->fetchAll(PDO::FETCH_NUM);
	}

	/**
	* Mimic mysql_real_escape_string() behavior, without an active MySQL connection
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

	public function noQuoteValue($value)
	{
		return mysql_escape_mimic($value);
	}

	public function insertRecord($table, $idField, array $data)
	{
		$fields = implode(',', array_keys($data));
		$values = implode(',', array_map(array($this, 'noQuoteValue'), array_values($data)));
		$sql = 'INSERT INTO ' . $table . '(' . $fields . ')' . ' VALUES (' . $values . ')';
		$this->_link->exec($sql);
		return $this->getInsertedRowID($table, $idField);
	}

	public function updateTable($table, array $data, $where = '')
	{
		$set = array();
		foreach ($data as $field => $value) {
			$set[] = $field . '=' . $value;
		}

		$set = implode(',', $set);
		$sql = 'UPDATE ' . $table . ' SET ' . $set . (($where) ? ' WHERE ' . $where : '');
		return $this->_link->exec($sql);
	}

	public function deleteRecord($table, $where)
	{
		$sql = 'DELETE FROM ' . $table . (($where) ? ' WHERE ' . $where : '');
		return $this->_link->exec($sql);
	}

	public function getInsertedRowID($table, $idField = "id")
	{
		$sql = "SELECT TOP 1 $idField as id from $table ORDER BY $idField DESC";
		return $this->_link->query($sql)->fetchAll();
	}
}