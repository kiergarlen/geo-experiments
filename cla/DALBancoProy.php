<?php
require_once('SQLServerAdapter.php');

class DALBancoProy
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

	public function getProjectTypes()
	{
		$sql= "SELECT
				idTipoProyEje, tipo
			FROM
				TipoProyEje";
		return self::getAllRows($sql);
	}

	public function getServiceTypes()
	{
		$sql= "SELECT
				idcobertura, cobertura
			FROM
				Cobertura";
		return self::getAllRows($sql);
	}

	public function insertProject($data)
	{
		self::insertRecord("ProyectosEjecutivos", $data);
		return self::getInsertedRowID("ProyectosEjecutivos");
	}

	public function updateProject($data, $id)
	{
		$where = " (idProyectoEjecutivo = '".  $id . "') ";
		return self::updateTable("ProyectosEjecutivos", $data, $where);
	}

	public function getProjects()
	{
		$sql = "SELECT
			dbo.ProyectosEjecutivos.idProyectoEjecutivo,
			dbo.ProyectosEjecutivos.idTipoProyEje,
			dbo.ProyectosEjecutivos.idaccion,
			dbo.ProyectosEjecutivos.idcobertura,
			dbo.ProyectosEjecutivos.idtipoaccion,
			dbo.ProyectosEjecutivos.idpertenece,
			dbo.ProyectosEjecutivos.cve_mun_com,
			dbo.ProyectosEjecutivos.cve_mun_loc_com,
			ISNULL(dbo.ProyectosEjecutivos.poblacionbene, 0) AS poblacionbene,
			ISNULL(dbo.ProyectosEjecutivos.hab_bene, 0) AS hab_bene,
			ISNULL(dbo.ProyectosEjecutivos.gasto_disenio, 0) AS gasto_disenio,
			ISNULL(dbo.ProyectosEjecutivos.techoFinanciero, 0) AS techoFinanciero,
			ISNULL(dbo.ProyectosEjecutivos.costoObra, 0) AS costoObra,
			ISNULL(dbo.ProyectosEjecutivos.costoProyecto, 0) AS costoProyecto,
			dbo.ProyectosEjecutivos.lat,
			dbo.ProyectosEjecutivos.lng,
			CONVERT(TEXT, dbo.ProyectosEjecutivos.concepto) AS concepto,
			CONVERT(TEXT, dbo.ProyectosEjecutivos.comentarios) AS comentarios,
			CONVERT(TEXT, dbo.ProyectosEjecutivos.ubicacion) AS ubicacion,
			dbo.ProyectosEjecutivos.user_updt,
			CONVERT(VARCHAR(10), dbo.ProyectosEjecutivos.fecha_updt, 126) AS fecha_updt,
			CONVERT(VARCHAR(10), dbo.ProyectosEjecutivos.fechaElaboracion, 126) AS fechaElaboracion,
			dbo.ProyectosEjecutivos.esInfraestructura,
			CASE WHEN ProyectosEjecutivos.esInfraestructura = 1 THEN 'SI' ELSE 'NO' END AS esInfraestructuraText,
			dbo.ProyectosEjecutivos.estaConstruido,
			CASE WHEN ProyectosEjecutivos.estaConstruido = 1 THEN 'SI' ELSE 'NO' END AS estaConstruidoText,
			dbo.TipoProyEje.tipo,
			dbo.Cobertura.cobertura,
			INEGI.dbo.cat_municipios.municipio AS municipio,
			INEGI.dbo.cat_localidades.localidad AS localidad,
			admnCtrlDoc.dbo.adm_usuario.nombre + ' ' + admnCtrlDoc.dbo.adm_usuario.apellidoPaterno + ' ' + admnCtrlDoc.dbo.adm_usuario.apellidoMaterno AS nombreUsuario,
			INEGI.dbo.coberturas.regionAdmiva,
			INEGI.dbo.coberturas.p_total,
			dbo.SicpedSituacion.idSituacionProyEje,
			dbo.SicpedSituacion.situacion,
			dbo.SicpedPlazo.idPlazo,
			dbo.SicpedPlazo.plazo,
			dbo.Pertenece.descripcion AS area,
			dbo.contratistas.contratista AS contratistaSica,
			dbo.contratos.contrato AS contratoSica
		FROM
			dbo.contratos
		INNER JOIN
			dbo.contratos ON dbo.contratos.idcontrato = dbo.ProyectosEjecutivos.idcontrato
		INNER JOIN
			dbo.contratistas ON dbo.contratistas.idcontratista = dbo.ProyectosEjecutivos.idcontratista
		RIGHT OUTER JOIN
			dbo.ProyectosEjecutivos
		INNER JOIN
			dbo.Cobertura ON dbo.ProyectosEjecutivos.idcobertura = dbo.Cobertura.idcobertura
		INNER JOIN
			dbo.TipoProyEje ON dbo.ProyectosEjecutivos.idTipoProyEje = dbo.TipoProyEje.idTipoProyEje
		INNER JOIN
			dbo.SicpedUsuarios ON dbo.ProyectosEjecutivos.user_updt = dbo.SicpedUsuarios.idUsuario
		INNER JOIN
			INEGI.dbo.cat_localidades ON INEGI.dbo.cat_localidades.cve_mun_loc_com = dbo.ProyectosEjecutivos.cve_mun_loc_com
		INNER JOIN
			dbo.SicpedSituacion ON dbo.ProyectosEjecutivos.idSituacionProyEje = dbo.SicpedSituacion.idSituacionProyEje
		INNER JOIN
			dbo.Pertenece ON dbo.ProyectosEjecutivos.idpertenece = dbo.Pertenece.idpertenece
		LEFT OUTER JOIN
			dbo.SicpedPlazo ON dbo.ProyectosEjecutivos.idPlazo = dbo.SicpedPlazo.idPlazo
		WHERE
			(dbo.ProyectosEjecutivos.activo = '1') AND (dbo.ejercicios.ejercicio = '2012')";


		$sql = "SELECT
				idProyectoEjecutivo, idTipoProyEje, idaccion, idcobertura,
				idSituacionProyEje, idPlazo,
				idtipoaccion, id_dir_siafi, idpertenece, cve_mun_com,
				cve_mun_loc_com, poblacionbene, hab_bene, gasto_disenio,
				techoFinanciero, costoObra, costoProyecto, lat,
				lng, concepto, comentarios, ubicacion,
				user_updt, fecha_updt, fechaElaboracion, esInfraestructura,
				esInfraestructuraText, estaConstruido, estaConstruidoText, tipo,
				cobertura, cuenca, municipio, localidad,
				nombreUsuario, regionAdmiva, p_total,
				situacion, plazo, area,
				contratistaSica, contratoSica
			FROM
				v_proyectosDetalle";
		return self::getAllRows($sql);
	}

	public function getProjectsByDistrict()
	{
		$sql = "SELECT
				SUM(ProyectosEjecutivos.activo) AS cant,
				ProyectosEjecutivos.cve_mun_com,
				municipio.nom_municipio,
				ProyectosEjecutivos.idTipoProyEje
			FROM
				ProyectosEjecutivos
				INNER JOIN
				municipio ON ProyectosEjecutivos.cve_mun_com = municipio.id_inegi
			WHERE
				(ProyectosEjecutivos.idTipoProyEje = '1')
			GROUP BY
				ProyectosEjecutivos.cve_mun_com, municipio.nom_municipio,
				ProyectosEjecutivos.idTipoProyEje
			ORDER BY
				ProyectosEjecutivos.idTipoProyEje, municipio.nom_municipio";
		return self::getAllRows($sql);
	}

	public function getStatusMenu()
	{
		$sql = "SELECT
				idSituacionProyEje AS data,
				situacion AS label
			FROM
				SicpedSituacion";
		return self::getAllRows($sql);
	}

	public function getTermsMenu()
	{
		$sql = "SELECT
				idPlazo AS data,
				plazo AS label
			FROM
				SicpedPlazo";
		return self::getAllRows($sql);
	}

	public function getAreasMenu()
	{
		$sql = "SELECT
				idpertenece AS data,
				descripcion AS label,
				pertenece,
				idtutela, idpertenecepadre
			FROM
				Pertenece";
		return self::getAllRows($sql);
	}

	public function getContract($idaccion)
	{
		$sql = "SELECT
				CTRLOBRA.dbo.ContratoporAccion.idaccion,
				CTRLOBRA.dbo.Contratista.razonsocial AS contratistaSica,
				CTRLOBRA.dbo.Contratos.nocontrato AS contratoSica
			FROM
				CTRLOBRA.dbo.Contratos
			INNER JOIN
				CTRLOBRA.dbo.ContratoporAccion ON CTRLOBRA.dbo.Contratos.idcontrato = CTRLOBRA.dbo.ContratoporAccion.idcontrato
			INNER JOIN
				CTRLOBRA.dbo.Contratista ON CTRLOBRA.dbo.Contratos.idcontratista = CTRLOBRA.dbo.Contratista.idcontratista
			WHERE
				(CTRLOBRA.dbo.ContratoporAccion.idaccion = '" . $idaccion . "')";
		return self::getAllRows($sql);
	}
}