<?php
require_once('SQLServerAdapter.php');

class DALBancoProy
{
	protected static $_instance;
	const DB_HOST = "localhost";
	const DB_USER = "ctrldoc";
	const DB_PASSWORD = "ctrldoc123";

	const DB_DATA_BASE = "admnCtrlDoc";

	/**
	 * Get the Singleton instance of the class
	 */
	public static function getInstance()
	{
		if (self::$_instance === null)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Class constructor
	 */
	protected function __construct()
	{
		SQLServerAdapter::getInstance(array(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_DATA_BASE));
	}

	/**
	 * Prevent cloning the instance of the class
	 */
	protected function __clone(){}

	/**
	 * Executes the SQL query <code>$sql</code> and returns a single row from the result's associative array, if <code>$debug</code> is set to true, echoes the query and returns and empty array.
	 * @param String $sql
	 * @param Boolean $debug
	 * @return Array $row
	 */
	public function getSingleRow($sql, $debug = false)
	{
		if ($debug)
		{
			echo $sql;
			return array();
		}
		else
		{
			$result = SQLServerAdapter::getInstance()->query($sql);
			$row = SQLServerAdapter::getInstance()->fetch();
			SQLServerAdapter::getInstance()->freeResult();
			if (isset($row))
			{
				return $row;
			}
			return array();
		}
	}

	/**
	 * Executes the SQL query <code>$sql</code> and returns all rows from the result's associative array, if <code>$debug</code> is set to true, echoes the query and returns and empty array.
	 * @param String $sql
	 * @param Boolean $debug
	 * @return Array $ret
	 */
	public function getAllRows($sql, $debug = false)
	{
		if ($debug)
		{
			echo $sql;
			return array();
		}
		else
		{
			$result = SQLServerAdapter::getInstance()->query($sql);
			while ($row = SQLServerAdapter::getInstance()->fetch())
			{
				$ret[] = $row;
			}
			SQLServerAdapter::getInstance()->freeResult();
			if (isset($ret))
			{
				return $ret;
			}
			return array();
		}
	}

	/**
	* Executes the SQL query <code>$sql</code> and returns a all rows from the result's array, if <code>$debug</code> is set to true, echoes the query and returns and empty array.
	* @param String $sql
	* @param Boolean $debug
	* @return Array $ret
	*/
	public function getAllRowsArray($sql, $debug = false)
	{
		if ($debug)
		{
			echo $sql;
			return array();
		}
		else
		{
			$result = SQLServerAdapter::getInstance()->query($sql);
			while ($row = SQLServerAdapter::getInstance()->fetchArray())
			{
				$ret[] = $row;
			}
			SQLServerAdapter::getInstance()->freeResult();
			if (isset($ret))
			{
				return $ret;
			}
			return array();
		}
	}

	public function insertRecord($table, array $data)
	{
		SQLServerAdapter::getInstance()->insert($table, $data);
	}

	public function deleteRecord($table, $where)
	{
		return SQLServerAdapter::getInstance()->delete($table, $where);
	}

	public function getInsertedRowID($table)
	{
		return SQLServerAdapter::getInstance()->getInsertId($table);
	}

	public function updateTable($table, array $data, $where = '')
	{
		return SQLServerAdapter::getInstance()->update($table, $data, $where);
	}

	public function getValidUserId($user, $pass)
	{
		$usr = SQLServerAdapter::getInstance()->mysql_escape_mimic($user);
		$psw = SQLServerAdapter::getInstance()->mysql_escape_mimic($pass);
		$sql = "SELECT
				id_usr
			FROM
				adm_usuario
			WHERE
				(username = N'$usr')
				AND (passwd = N'$psw')
				AND (status = 1)";
		return self::getAllRows($sql);
	}

	public function getUserValidEnterprise($iduser)
	{
		$iduser = SQLServerAdapter::getInstance()->mysql_escape_mimic($iduser);
		$sql = "SELECT
				empresas
			FROM
				adm_usuario
			WHERE
				(id_usr = '$iduser')";
		return self::getAllRows($sql);
	}

	public function getEnterpriseById($id_emp)
	{
		$id_emp = SQLServerAdapter::getInstance()->mysql_escape_mimic($id_emp);
		$sql = "SELECT
				id_empresa,
				bd,
				img
			FROM
				adm_empresas
			WHERE
				(id_empresa = '$id_emp')";
		return self::getAllRows($sql);
	}

	public function getUserEnterpriseMenu($usr, $id_emp)
	{
		$usr = SQLServerAdapter::getInstance()->mysql_escape_mimic($usr);
		$id_emp = SQLServerAdapter::getInstance()->mysql_escape_mimic($id_emp);
		$sql = "SELECT distinct a.id_menu, a.nom_menu, a.tab
					 FROM adm_menu a, adm_opcion b, adm_permisos c
					 WHERE (a.id_menu = b.id_menu and
					 b.id_opc = c.id_opc and
					 c.id_usr = '$usr' AND c.id_empresa = '$id_emp') ORDER BY a.tab";
		return self::getAllRows($sql);
	}

	public function getUserEnterpriseSubMenu($usr, $id_emp, $id_menu)
	{
		$usr = SQLServerAdapter::getInstance()->mysql_escape_mimic($usr);
		$id_emp = SQLServerAdapter::getInstance()->mysql_escape_mimic($id_emp);
		$id_emp = SQLServerAdapter::getInstance()->mysql_escape_mimic($id_menu);
		$sql = "SELECT adm_item.id_item, adm_item.nom_item, adm_item.url 
						FROM adm_item INNER JOIN 
                        adm_opcion ON adm_item.id_item = adm_opcion.id_item INNER JOIN 
                        adm_permisos ON adm_opcion.id_opc = adm_permisos.id_opc 
						WHERE (adm_opcion.id_menu = '$id_menu' and adm_permisos.id_usr = '$usr'  
						AND adm_permisos.id_empresa = '$id_emp')";
		return self::getAllRows($sql);
	}
}