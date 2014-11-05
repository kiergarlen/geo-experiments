<?php
require_once('SQLServerAdapter.php');

class DALCtrlDoc
{
	protected static $_instance;
	const DB_HOST = "localhost";
	const DB_USER = "ctrldoc";
	const DB_PASSWORD = "ctrldoc123";
	const DB_DATA_BASE = "ctrldoc";

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

	public function getEjercicios()
	{
		$sql = "SELECT
				idejercicio, ejercicio
			FROM
				ejercicios";
		return self::getAllRows($sql);
	}
	
	public function getProgramas()
	{
		$sql = "SELECT
				idprograma, idejercicio, programa, descripcion
			FROM
				programas
			ORDER BY
				idejercicio";
		return self::getAllRows($sql);
	}
	
	public function getProgramasStatus()
	{
		$sql = "SELECT
				v_count_ejer_prog.idejercicio,
				v_count_ejer_prog.idprograma,
				v_count_ejer_prog.programa,
				v_count_ejer_prog.countprog,
				v_count_ejer_prog.countcont,
				v_count_ejer_prog.countdoc,
				v_count_ejer_prog.countarch,
				v_count_ejer_prog.countdoc - v_count_ejer_prog.countarch AS docfal,
				SUM(DISTINCT ISNULL(v_count_status2.countsta2, 0)) AS docsr,
				SUM(DISTINCT ISNULL(v_count_status3.countsta3, 0)) AS docv,
				SUM(DISTINCT ISNULL(v_count_status4.countsta4, 0)) AS docna
			FROM
				v_count_ejer_prog
			LEFT OUTER JOIN
				v_count_status4 ON v_count_ejer_prog.idprograma = v_count_status4.idprograma
			LEFT OUTER JOIN
				v_count_status3 ON v_count_ejer_prog.idprograma = v_count_status3.idprograma
			LEFT OUTER JOIN
				v_count_status2 ON v_count_ejer_prog.idprograma = v_count_status2.idprograma
			GROUP BY
				v_count_ejer_prog.idejercicio,
				v_count_ejer_prog.idprograma,
				v_count_ejer_prog.programa,
				v_count_ejer_prog.countprog,
				v_count_ejer_prog.countcont,
				v_count_ejer_prog.countdoc,
				v_count_ejer_prog.countarch,
				v_count_ejer_prog.countdoc - v_count_ejer_prog.countarch
			ORDER BY
				idejercicio, idprograma";
		return self::getAllRows($sql);
	}

	public function getContratosStatus()
	{
		$sql = "SELECT
				contratos.idcontrato,
				contratos.idprograma,
				contratos.idtpocontratacion,
				programas.idejercicio,
				programas.programa,
				contratos.contrato,
				ISNULL(v_count_doc.countdoc, 0) + ISNULL(v_count_doc_cont.countdoc, 0) AS countdoc,
				ISNULL(v_count_arch.countarch, 0) AS countarch, 
				ISNULL(v_count_doc.countdoc, 0) + ISNULL(v_count_doc_cont.countdoc, 0) - ISNULL(v_count_arch.countarch, 0) AS docfal,
				ISNULL(v_count_status2.countsta2, 0) AS docsr, 
				ISNULL(v_count_status3.countsta3, 0) AS docv,
				ISNULL(v_count_status4.countsta4, 0) AS docna
			FROM
				contratos
			INNER JOIN
				programas ON contratos.idprograma = programas.idprograma
			LEFT OUTER JOIN
				v_count_status4 ON contratos.idcontrato = v_count_status4.idcontrato
			LEFT OUTER JOIN
				v_count_status3 ON contratos.idcontrato = v_count_status3.idcontrato
			LEFT OUTER JOIN
				v_count_status2 ON contratos.idcontrato = v_count_status2.idcontrato
			LEFT OUTER JOIN
				v_count_arch ON contratos.idcontrato = v_count_arch.idcontrato
			LEFT OUTER JOIN
				v_count_doc_cont ON contratos.idcontrato = v_count_doc_cont.idcontrato
			LEFT OUTER JOIN
				v_count_doc ON contratos.idprograma = v_count_doc.idprograma AND contratos.idtpocontratacion = v_count_doc.idtpocontratacion
			GROUP BY
				contratos.idcontrato,
				contratos.idprograma,
				contratos.idtpocontratacion,
				contratos.contrato,
				ISNULL(v_count_doc.countdoc, 0)  + ISNULL(v_count_doc_cont.countdoc, 0),
				ISNULL(v_count_arch.countarch, 0), ISNULL(v_count_status2.countsta2, 0),
				ISNULL(v_count_status3.countsta3, 0), ISNULL(v_count_status4.countsta4, 0),
				ISNULL(v_count_doc.countdoc, 0) + ISNULL(v_count_doc_cont.countdoc, 0) - ISNULL(v_count_arch.countarch, 0),
				programas.idejercicio,
				programas.programa
			ORDER BY
				contratos.idcontrato";
		return self::getAllRows($sql);
	}

	public function getEmpleadosDocumentosTotal()
	{
		$sql = "SELECT
				programas.idejercicio,
				COUNT(DISTINCT archivos.idarchivo) AS doc,
				COUNT(DISTINCT archivos.id_usr) AS emp,
				CONVERT(char(10), archivos.fecha_alta, 126) AS fecha
			FROM
				archivos
			INNER JOIN
				contratos ON archivos.idcontrato = contratos.idcontrato
			INNER JOIN
				programas ON contratos.idprograma = programas.idprograma
			GROUP BY
				CONVERT(char(10), archivos.fecha_alta, 126),
				programas.idejercicio
			ORDER BY
				CONVERT(char(10), archivos.fecha_alta, 126),
				programas.idejercicio";
		return self::getAllRows($sql);
	}

	public function getEmpleadosDocumentosProg()
	{
		$sql = "SELECT
				programas.idejercicio,
				contratos.idprograma,
				COUNT(DISTINCT archivos.idarchivo) AS doc,
				COUNT(DISTINCT archivos.id_usr) AS emp,
				CONVERT(char(10), archivos.fecha_alta, 126) AS fecha
			FROM
				archivos
			INNER JOIN
				contratos ON archivos.idcontrato = contratos.idcontrato
			INNER JOIN
				programas ON contratos.idprograma = programas.idprograma
			GROUP BY
				CONVERT(char(10), archivos.fecha_alta, 126),
				contratos.idprograma,
				programas.idejercicio
			ORDER BY
				CONVERT(char(10), archivos.fecha_alta, 126),
				programas.idejercicio,
				contratos.idprograma";
		return self::getAllRows($sql);
	}
}