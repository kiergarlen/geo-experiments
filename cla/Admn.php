<?php
class Admn
{
	const DB_HOST = "mihudetec-s1";
	const DB_USER = "ctrldoc";
	const DB_PASSWORD = "ctrldoc123";
	const DB_DATA_BASE = "admn";
	const DB_TYPE = "sqlsrv";
	const DB_PORT = "1433";
	protected $_link;

	public function Admn()
	{
		$this->methodTable = array(
			"getValidUserById" => array(
				"arguments" => array("usr"),
				"access" => "remote"
			),
			"getValidUserId" => array(
				"arguments" => array("user", "pass"),
				"access" => "remote"
			),
			"getUserValidEnterprise" => array(
				"arguments" => array("iduser"),
				"access" => "remote"
			),
			"getEnterpriseById" => array(
				"arguments" => array("id_emp"),
				"access" => "remote"
			),
			"getEnterpriseByIdArray" => array(
				"arguments" => array("empresas"),
				"access" => "remote"
			),
			"getProjectTypes" => array(
				"arguments" => array("db"),
				"access" => "remote"
			),
			"getServiceTypes" => array(
				"arguments" => array("db"),
				"access" => "remote"
			),
			"getStatusMenu" => array(
				"arguments" => array("db"),
				"access" => "remote"
			),
			"getProjects" => array(
				"arguments" => array("db"),
				"access" => "remote"
			),
			"insertProject" => array(
				"arguments" => array("db", "data"),
				"access" => "remote"
			),
			"updateProject" => array(
				"arguments" => array("db", "projectData"),
				"access" => "remote"
			),
			"updateProjectArray" => array(
				"arguments" => array("db", "data", "itemId"),
				"access" => "remote"
			),
			"deleteProject" => array(
				"arguments" => array("db", "ids"),
				"access" => "remote"
			),
			"insertPhoto" => array(
				"arguments" => array("db", "photoData"),
				"access" => "remote"
			),
			"updatePhoto" => array(
				"arguments" => array("db", "photoData"),
				"access" => "remote"
			),
			"updatePhotoArray" => array(
				"arguments" => array("db", "photoData", "itemId"),
				"access" => "remote"
			),
			"deletePhoto" => array(
				"arguments" => array("db", "itemId"),
				"access" => "remote"
			),
			"getPhotos" => array(
				"arguments" => array("db", "itemId"),
				"access" => "remote"
			),
			"getPhotosEdit" => array(
				"arguments" => array("db", "id"),
				"access" => "remote"
			),
			"getPhotosViewDetail" => array(
				"arguments" => array("db", "id"),
				"access" => "remote"
			),
			"getPhotosDetail" => array(
				"arguments" => array("db", "itemId"),
				"access" => "remote"
			),
			"getPhotosDetailMap" => array(
				"arguments" => array("db", "id"),
				"access" => "remote"
			),
			"insertDoc" => array(
				"arguments" => array("db", "docData"),
				"access" => "remote"
			),
			"updateDoc" => array(
				"arguments" => array("db", "docData", "itemId"),
				"access" => "remote"
			),
			"deleteDoc" => array(
				"arguments" => array("db", "itemId"),
				"access" => "remote"
			),
			"getDocDetail" => array(
				"arguments" => array("db", "itemId"),
				"access" => "remote"
			),
			"getDocs" => array(
				"arguments" => array("db"),
				"access" => "remote"
			)
		);
	}

	public function __construct()
	{
		try {
			$dsn = "sqlsrv:server=" . self::DB_HOST . ";Database=" . self::DB_DATA_BASE;
			$pdo = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);
			//Desarrollo
			//$pdo = setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_SYSTEM);
			$this->_link = $pdo;
		} catch (PDOException $e) {
			echo "Error de conexion: " . $e->getMessage();
		}
	}

	private function getSingleRow($sql)
	{
		return $this->_link->query($sql)->fetch(PDO::FETCH_OBJ);
	}

	private function getAllRows($sql)
	{
		return $this->_link->query($sql)->fetchAll(PDO::FETCH_OBJ);
	}

	private function getAllRowsArray($sql)
	{
		return $this->_link->query($sql)->fetchAll(PDO::FETCH_NUM);
	}

	/**
	* Mimic mysql_real_escape_string() behavior, without an active MySQL connection
	*/
	private function mysql_escape_mimic($inp)
	{
		if(is_array($inp))
			return array_map(__METHOD__, $inp);
		if(!empty($inp) && is_string($inp))
		{
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
		}
		return $inp;
	}

	private function noQuoteValue($value)
	{
		return mysql_escape_mimic($value);
	}

	private function insertRecord($table, $idField, array $data)
	{
		$fields = implode(',', array_keys($data));
		$values = implode(',', array_map(array($this, 'noQuoteValue'), array_values($data)));
		$sql = 'INSERT INTO ' . $table . '(' . $fields . ')' . ' VALUES (' . $values . ')';
		$this->_link->exec($sql);
		return $this->getInsertedRowID($table, $idField);
	}

	private function updateTable($table, array $data, $where = '')
	{
		$set = array();
		foreach ($data as $field => $value) {
			$set[] = $field . '=' . $value;
		}

		$set = implode(',', $set);
		$sql = 'UPDATE ' . $table . ' SET ' . $set . (($where) ? ' WHERE ' . $where : '');
		return $this->_link->exec($sql);
	}

	private function deleteRecord($table, $where)
	{
		$sql = 'DELETE FROM ' . $table . (($where) ? ' WHERE ' . $where : '');
		return $this->_link->exec($sql);
	}

	private function getInsertedRowID($table, $idField = "id")
	{
		$sql = "SELECT TOP 1 $idField as id from $table ORDER BY $idField DESC";
		return $this->_link->query($sql)->fetchAll();
	}

	/**
	* @access remote
	*/
	public function getValidUserById($usr)
	{
		$usr = $this->mysql_escape_mimic($usr);
		$sql = "SELECT
				id_usr,
				idorganismo,
				idperfil,
				username,
				passwd,
				clave,
				nom,
				ap_pat,
				ap_mat,
				status,
				correo,
				fecha,
				nom + ' ' + ap_pat + ' ' + ap_mat AS nombreUsuario
			FROM
				adm_usuario
			WHERE
				(id_usr = N'$usr')";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getValidUserId($user, $pass)
	{
		$usr = $this->mysql_escape_mimic($user);
		$psw = $this->mysql_escape_mimic($pass);
		$sql = "SELECT
				id_usr,
				idorganismo,
				idperfil,
				username,
				passwd,
				clave,
				nom,
				ap_pat,
				ap_mat,
				status,
				correo,
				fecha,
				nom + ' ' + ap_pat + ' ' + ap_mat AS nombreUsuario
			FROM
				adm_usuario
			WHERE
				(username = N'$usr')
				AND (passwd = N'$psw')
				AND (status = 1)";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getUserValidEnterprise($iduser)
	{
		$iduser = $this->mysql_escape_mimic($iduser);
		$sql = "SELECT
				empresas
			FROM
				adm_usuario
			WHERE
				(id_usr = '$iduser')";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getEnterpriseById($id_emp)
	{
		$id_emp = $this->mysql_escape_mimic($id_emp);
		$sql = "SELECT
				id_empresa,
				empresa,
				img
			FROM
				adm_empresa
			WHERE
				(id_empresa = '$id_emp')";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getEnterpriseByIdArray($empresas)
	{
		$condition = "";
		$empresasArr = explode(",", $empresas);
		$l = count($empresasArr);
		if ($l > 0)
		{
			$condition = " WHERE ";
		}

		for ($i=0; $i < $l; $i++) {
			$condition .= "id_empresa = '" . $empresasArr[$i] ."' "; 
			if ($l > 1 && $i < ($l - 1))
			{
				$condition .= " OR ";
			}
		}

		$sql = "SELECT
				id_empresa,
				empresa,
				img
			FROM
				adm_empresa";
		$sql .= $condition;
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getProjectTypes()
	{
		$db = "estudios";
		$sql= "SELECT
			idtipo, tipo
			FROM " . $db . ".dbo.tipos_proy";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getServiceTypes()
	{
		$db = "estudios";
		$sql = "SELECT
				idcomponente, componente
			FROM " . $db . ".dbo.componentes";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getStatusMenu()
	{
		$db = "estudios";
		$sql = "SELECT
				idstatus AS data,
				status AS label
			FROM
				" . $db . ".dbo.status_proy";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getProjects()
	{
		$db = "estudios";
		$sql = "SELECT
			" . $db . ".dbo.proyectos.idproy,
			" . $db . ".dbo.proyectos.idtipo,
			" . $db . ".dbo.proyectos.idcontrato,
			" . $db . ".dbo.proyectos.idcontratista,
			" . $db . ".dbo.proyectos.idcomponente,
			" . $db . ".dbo.proyectos.idstatus,
			" . $db . ".dbo.proyectos.cve_reg_admva,
			" . $db . ".dbo.proyectos.cve_mun_com,
			" . $db . ".dbo.proyectos.cve_mun_loc_com,
			" . $db . ".dbo.proyectos.infra,
			ISNULL(" . $db . ".dbo.proyectos.costo_proy, 0) AS costo_proy,
			ISNULL(" . $db . ".dbo.proyectos.costo_obra, 0) AS costo_obra,
			ISNULL(" . $db . ".dbo.proyectos.lat, 0) AS lat,
			ISNULL(" . $db . ".dbo.proyectos.lng, 0) AS lng,
			ISNULL(" . $db . ".dbo.proyectos.alt, 0) AS alt,
			" . $db . ".dbo.proyectos.nombre,
			" . $db . ".dbo.proyectos.descripcion,
			" . $db . ".dbo.proyectos.comentarios,
			" . $db . ".dbo.proyectos.ubicacion,
			" . $db . ".dbo.proyectos.ubicacion_exp,
			" . $db . ".dbo.proyectos.contrato,
			" . $db . ".dbo.proyectos.orden_trabajo,
			" . $db . ".dbo.proyectos.user_updt,
			" . $db . ".dbo.proyectos.activo,
			" . $db . ".dbo.proyectos.poblacion,
			" . $db . ".dbo.proyectos.poblacion_bene,
			" . $db . ".dbo.proyectos.idejercicio,
			" . $db . ".dbo.tipos_proy.tipo,
			" . $db . ".dbo.proyectos.contratista,
			" . $db . ".dbo.componentes.componente,
			" . $db . ".dbo.status_proy.status,
			admnCtrlDoc.dbo.adm_usuario.nom + ' ' +  admnCtrlDoc.dbo.adm_usuario.ap_pat + ' ' +  admnCtrlDoc.dbo.adm_usuario.ap_mat AS nombreUsuario,
			CONVERT(VARCHAR(10), " . $db . ".dbo.proyectos.fecha_updt, 126) AS fecha_updt,
			CONVERT(VARCHAR(10), " . $db . ".dbo.proyectos.fecha_updt, 126) AS fechaElaboracion,
			CASE WHEN " . $db . ".dbo.proyectos.infra = 1 THEN 'SI' ELSE 'NO' END AS esInfraestructuraText,
			INEGI.dbo.cat_reg_admva.nom_reg_admva AS regionAdmiva,
			INEGI.dbo.cat_municipios.nom_municipio AS municipio,
			INEGI.dbo.cat_localidades.nom_loc AS localidad
		FROM
			" . $db . ".dbo.proyectos
		LEFT JOIN " . $db . ".dbo.tipos_proy
		ON  " . $db . ".dbo.proyectos.idtipo =  " . $db . ".dbo.tipos_proy.idtipo
		LEFT JOIN " . $db . ".dbo.componentes
		ON  " . $db . ".dbo.proyectos.idcomponente =  " . $db . ".dbo.componentes.idcomponente
		LEFT JOIN " . $db . ".dbo.status_proy
		ON  " . $db . ".dbo.proyectos.idstatus =  " . $db . ".dbo.status_proy.idstatus
		LEFT JOIN admnCtrlDoc.dbo.adm_usuario
		ON  " . $db . ".dbo.proyectos.user_updt =  admnCtrlDoc.dbo.adm_usuario.id_usr
		LEFT JOIN INEGI.dbo.cat_reg_admva
		ON  " . $db . ".dbo.proyectos.cve_reg_admva =  INEGI.dbo.cat_reg_admva.cve_reg_admva
		LEFT JOIN INEGI.dbo.cat_municipios
		ON  " . $db . ".dbo.proyectos.cve_mun_com =  INEGI.dbo.cat_municipios.cve_mun_com
		LEFT JOIN INEGI.dbo.cat_localidades
		ON  " . $db . ".dbo.proyectos.cve_mun_loc_com =  INEGI.dbo.cat_localidades.cve_mun_loc_com
		WHERE " . $db . ".dbo.proyectos.activo = '1'";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function insertProject($data)
	{
		$db = "estudios";
		$formattedData = array(
			"idtipo" => "'" . $data['idtipo'] . "'",
			"idcontrato" => "'" . $data['idcontrato'] . "'",
			"idcontratista" => "'" . $data['idcontratista'] . "'",
			"idcomponente" => "'" . $data['idcomponente'] . "'",
			"idstatus" => "'" . $data['idstatus'] . "'",
			"cve_reg_admva" => "'" . $data['cve_reg_admva'] . "'",
			"cve_mun_com" => "'" . $data['cve_mun_com'] . "'",
			"cve_mun_loc_com" => "'" . $data['cve_mun_loc_com'] . "'",
			"infra" => "'" . $data['infra'] . "'",
			"costo_proy" => "'" . $data['costo_proy'] . "'",
			"costo_obra" => "'" . $data['costo_obra'] . "'",
			"lat" => "'" . $data['lat'] . "'",
			"lng" => "'" . $data['lng'] . "'",
			"alt" => "'" . $data['alt'] . "'",
			"nombre" => "'" . utf8_decode($data['nombre']) . "'",
			"descripcion" => "'" . utf8_decode($data['descripcion']) . "'",
			"comentarios" => "'" . utf8_decode($data['comentarios']) . "'",
			"ubicacion" => "'" . utf8_decode($data['ubicacion']) . "'",
			"ubicacion_exp" => "'" . utf8_decode($data['ubicacion_exp']) . "'",
			"contrato" => "'" . utf8_decode($data['contrato']) . "'",
			"contratista" => "'" . utf8_decode($data['contratista']) . "'",
			"orden_trabajo" => "'" . utf8_decode($data['orden_trabajo']) . "'",
			"user_updt" => "'" . $data['user_updt'] . "'",
			"poblacion" => "'" . $data['poblacion'] . "'",
			"poblacion_bene" => "'" . $data['poblacion_bene'] . "'",
			"idejercicio" => "'" . $data['idejercicio'] . "'",
			"fecha" => "'" . $data['fechaElaboracion'] . "'",
			"activo" => "'1'",
			"fecha_updt" => "{fn NOW()}"
		);

		$table = $db . ".dbo.proyectos";
		$idField = "idproy";
		return $this->insertRecord($table, $idField, $formattedData);
	}

	/**
	* @access remote
	*/
	public function updateProject($projectData)
	{
		$db = "estudios";
		return $this->updateProjectArray($db, $projectData, $projectData['idproy']);
	}

	/**
	* @access remote
	*/
	public function updateProjectArray($data, $itemId)
	{
		$db = "estudios";
		$formattedData = array(
			"idtipo" => "'" . $data['idtipo'] . "'",
			"idcontrato" => "'" . $data['idcontrato'] . "'",
			"idcontratista" => "'" . $data['idcontratista'] . "'",
			"idcomponente" => "'" . $data['idcomponente'] . "'",
			"idstatus" => "'" . $data['idstatus'] . "'",
			"cve_reg_admva" => "'" . $data['cve_reg_admva'] . "'",
			"cve_mun_com" => "'" . $data['cve_mun_com'] . "'",
			"cve_mun_loc_com" => "'" . $data['cve_mun_loc_com'] . "'",
			"infra" => "'" . $data['infra'] . "'",
			"costo_proy" => "'" . $data['costo_proy'] . "'",
			"costo_obra" => "'" . $data['costo_obra'] . "'",
			"lat" => "'" . $data['lat'] . "'",
			"lng" => "'" . $data['lng'] . "'",
			"alt" => "'" . $data['alt'] . "'",
			"nombre" => "'" . utf8_decode($data['nombre']) . "'",
			"descripcion" => "'" . utf8_decode($data['descripcion']) . "'",
			"comentarios" => "'" . utf8_decode($data['comentarios']) . "'",
			"ubicacion" => "'" . utf8_decode($data['ubicacion']) . "'",
			"ubicacion_exp" => "'" . utf8_decode($data['ubicacion_exp']) . "'",
			"contrato" => "'" . utf8_decode($data['contrato']) . "'",
			"contratista" => "'" . utf8_decode($data['contratista']) . "'",
			"orden_trabajo" => "'" . utf8_decode($data['orden_trabajo']) . "'",
			"user_updt" => "'" . $data['user_updt'] . "'",
			"poblacion" => "'" . $data['poblacion'] . "'",
			"poblacion_bene" => "'" . $data['poblacion_bene'] . "'",
			"idejercicio" => "'" . $data['idejercicio'] . "'",
			"fecha" => "'" . $data['fechaElaboracion'] . "'",
			"activo" => "'" . $data['activo'] . "'",
			"fecha_updt" => "{fn NOW()}"
		);

		$table = $db . ".dbo.proyectos";
		$itemId = $this->mysql_escape_mimic($itemId);
		$where = " (idproy = '". $itemId . "') ";

		return $this->updateTable($table, $formattedData, $where);
	}

	/**
	* @access remote
	*/
	public function deleteProject($ids)
	{
		$db = "estudios";
		for ($i = 0; $i < count($ids); $i++)
		{
			$data = array("activo" => "'0'");
			$this->updateProjectArray($db, $data, $ids[$i]);
		}
		return count($ids);
	}

	/**
	* @access remote
	*/
	public function insertPhoto($photoData)
	{
		$db = "estudios";
		$data = array(
			"idproy"		=> "'" . $photoData['idproy'] . "'",
			"foto"			=> "'" . $photoData['foto'] . "'",
			"url"			=> "'" . $photoData['url'] . "'",
			"descripcion"	=> "'" . $photoData['descripcion'] . "'",
			"activo"		=> "'" . $photoData['activo'] . "'",
			"id_usr"		=> "'" . $photoData['id_usr'] . "'",
			"fecha_updt"	=> "{fn NOW()}"
		);
		$table = $db . ".dbo.fotos_proy";
		$idField = "idfoto";
		return $this->insertRecord($table, $idField, $data);
	}

	/**
	* @access remote
	*/
	public function updatePhoto($photoData)
	{
		$db = "estudios";
		return $this->updatePhotoArray($db, $photoData, $photoData['idFoto']);
	}

	/**
	* @access remote
	*/
	public function updatePhotoArray($photoData, $itemId)
	{
		$data = array(
			"idproy"		=> "'" . $photoData['idproy'] . "'",
			"foto"			=> "'" . $photoData['foto'] . "'",
			"url"			=> "'" . $photoData['url'] . "'",
			"descripcion"	=> "'" . $photoData['descripcion'] . "'",
			"activo"		=> "'" . $photoData['activo'] . "'",
			"id_usr"		=> "'" . $photoData['id_usr'] . "'",
			"fecha_updt"	=> "{fn NOW()}"
		);
		$table = $db . ".dbo.fotos_proy";
		$itemId = $this->mysql_escape_mimic($itemId);
		$where = " (idfoto = '". $itemId . "') ";
		return $this->updateTable($table, $data, $where);
	}

	/**
	* @access remote
	*/
	public function deletePhoto($itemId)
	{
		$db = "estudios";
		$table = $db . ".dbo.fotos_proy";
		$itemId = $this->mysql_escape_mimic($itemId);
		$where = " (idfoto = '". $itemId . "') ";
		return $this->deleteRecord($table, $where);
	}

	/**
	* @access remote
	*/
	public function getPhotos($itemId)
	{
		$db = "estudios";
		$itemId = $this->mysql_escape_mimic($itemId);
		$sql= "SELECT
				idfoto,
				foto,
				url,
				idproy,
				activo,
				id_usr,
				CONVERT(TEXT, descripcion) AS descripcion,
				CONVERT(VARCHAR(10), fecha_updt, 126) AS fecha_updt
			FROM
				" . $db . ".dbo.fotos_proy
			WHERE (activo = '1')
			AND (idproy = '" . $itemId . "')";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getPhotosEdit($id)
	{
		$db = "estudios";
		return $this->getPhotos($db, $id);
	}

	/**
	* @access remote
	*/
	public function getPhotosViewDetail($id)
	{
		$db = "estudios";
		return $this->getPhotos($db, $id);
	}

	/**
	* @access remote
	*/
	public function getPhotosDetail($itemId)
	{
		$db = "estudios";
		$itemId = $this->mysql_escape_mimic($itemId);
		$sql= "SELECT
				idfoto,
				foto,
				url,
				idproy,
				activo,
				id_usr,
				CONVERT(TEXT, descripcion) AS descripcion,
				CONVERT(VARCHAR(10), fecha_updt, 126) AS fecha_updt
			FROM
				" . $db . ".dbo.fotos_proy
			WHERE (activo = '1')
			AND (idfoto = '" . $itemId . "')";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getPhotosDetailMap($id)
	{
		$db = "estudios";
		return $this->getPhotosDetail($db, $id);
	}

	/**
	* @access remote
	*/
	public function insertDoc($docData)
	{
		$db = "estudios";
		$data = array(
			"idproy"		=> "'" . $docData['idproy'] . "'",
			"doc"			=> "'" . $docData['doc'] . "'",
			"url"			=> "'" . $docData['url'] . "'",
			"descripcion"	=> "'" . $docData['descripcion'] . "'",
			"activo"		=> "'" . $docData['activo'] . "'",
			"id_usr"		=> "'" . $docData['id_usr'] . "'",
			"fecha_updt"	=> "{fn NOW()}"
		);
		$table = $db . ".dbo.docs_proy";
		$idField = "iddoc";
		return $this->insertRecord($table, $idField, $data);
	}

	/**
	* @access remote
	*/
	public function updateDoc($docData, $itemId)
	{
		$db = "estudios";
		$data = array(
			"idproy"		=> "'" . $docData['idproy'] . "'",
			"doc"			=> "'" . $docData['doc'] . "'",
			"url"			=> "'" . $docData['url'] . "'",
			"descripcion"	=> "'" . $docData['descripcion'] . "'",
			"activo"		=> "'" . $docData['activo'] . "'",
			"id_usr"		=> "'" . $docData['id_usr'] . "'",
			"fecha_updt"	=> "{fn NOW()}"
		);
		$table = $db . ".dbo.docs_proy";
		$itemId = $this->mysql_escape_mimic($itemId);
		$where = " (iddoc = '". $itemId . "') ";
		return $this->updateTable($table, $data, $where);
	}

	/**
	* @access remote
	*/
	public function deleteDoc($itemId)
	{
		$db = "estudios";
		$data = array(
			"activo"		=> "'1'",
			"fecha_updt"	=> "{fn NOW()}"
		);
		$table = $db . ".dbo.docs_proy";
		$itemId = $this->mysql_escape_mimic($itemId);
		$where = " (iddoc = '". $itemId . "') ";
		return $this->updateTable($table, $data, $where);
	}

	/**
	* @access remote
	*/
	public function getDocDetail($itemId)
	{
		$db = "estudios";
		$itemId = $this->mysql_escape_mimic($itemId);
		$sql= "SELECT
				iddoc,
				doc,
				url,
				idproy,
				activo,
				id_usr,
				CONVERT(TEXT, descripcion) AS descripcion,
				CONVERT(VARCHAR(10), fecha_updt, 126) AS fecha_updt
			FROM
				" . $db . ".dbo.docs_proy
			WHERE (activo = '1')
			AND (idproy = '" . $itemId . "')";
		return $this->getAllRows($sql);
	}

	/**
	* @access remote
	*/
	public function getDocs($itemId)
	{
		$db = "estudios";
		$itemId = $this->mysql_escape_mimic($itemId);
		$sql= "SELECT
				iddoc,
				doc,
				url,
				idproy,
				activo,
				id_usr,
				CONVERT(TEXT, descripcion) AS descripcion,
				CONVERT(VARCHAR(10), fecha_updt, 126) AS fecha_updt
			FROM
				" . $db . ".dbo.docs_proy
			WHERE (activo = '1')
			AND (idproy = '" . $itemId . "')";
		return $this->getAllRows($sql);
	}
}