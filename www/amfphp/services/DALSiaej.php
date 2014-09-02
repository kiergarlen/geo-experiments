<?php
require_once('MySQLAdapter.php');

class DALSiaej
{
	protected static $_instance;
	const DB_HOST = "10.25.44.29";
	const DB_USER = "siaej";
	const DB_PASSWORD = "s1a3j";
	const DB_DATA_BASE = "siaej";

	public static function getInstance() {
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected function __construct() {
		MySQLAdapter::getInstance(array(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_DATA_BASE));
	}

	protected function __clone(){}

	public function getPtarStatus($idPtar) {
		$sql = "SELECT
				ptar_hsituacion.CVE_PTAR,
				ptar_hsituacion.PTAR_OP,
				ptar_situacion.SITUACION,
				ptar_hsituacion.FECHA
			FROM
				ptar_hsituacion
			LEFT JOIN
				ptar_situacion ON ptar_hsituacion.PTAR_OP = ptar_situacion.PTAR_OP
			WHERE
				ptar_hsituacion.CVE_PTAR = '" . $idPtar . "'
			ORDER BY
				ptar_hsituacion.CVE_PTAR, ptar_hsituacion.FECHA
			DESC
			LIMIT 1";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtars() {
		$sql = "SELECT
				planta_de_tratamiento.CVE_PTAR,
				planta_de_tratamiento.NOMBRE,
				planta_de_tratamiento.CVE_LOC_UNICA,
				planta_de_tratamiento.CVE_LOC_COMP,
				planta_de_tratamiento.COORD_FECHA,
				planta_de_tratamiento.ALTITUD,
				planta_de_tratamiento.COORD_METODO,
				planta_de_tratamiento.HAB_BEN,
				planta_de_tratamiento.GMD_LPS,
				planta_de_tratamiento.CPO_REC,
				planta_de_tratamiento.TIPO_CPO_REC,
				planta_de_tratamiento.POT_INST_HP,
				planta_de_tratamiento.DISP_LODOS,
				planta_de_tratamiento.DISP_AGUA,
				planta_de_tratamiento.ORG_OPERA,
				planta_de_tratamiento.FECHA_INI_OP,
				planta_de_tratamiento.ENCARGADO,
				planta_de_tratamiento.POT_OPER_HP,
				planta_de_tratamiento.M3_MES,
				planta_de_tratamiento.COSTOS_OM_MES,
				planta_de_tratamiento.COSTO_M3,
				planta_de_tratamiento.REQ_REHAB_AMP,
				planta_de_tratamiento.TIPO_PROC,
				planta_de_tratamiento.ACT_FRANCIA,
				planta_de_tratamiento.ACT_BRASILIA,
				planta_de_tratamiento.CVE_CARTA_TOP,
				planta_de_tratamiento.CED_CAP,
				planta_de_tratamiento.FECHA_CED_CAP,
				planta_de_tratamiento.IMAGEN,
				planta_de_tratamiento.IMG,
				planta_de_tratamiento.CRO_UBICA,
				planta_de_tratamiento.PTAR_OP,
				planta_de_tratamiento.IMG2,
				planta_de_tratamiento.COMENTARIO,
				planta_de_tratamiento.ID_PROGRAMA,
				planta_de_tratamiento.COSTO_INV,
				planta_de_tratamiento.ANIO_CONST,
				planta_de_tratamiento.NOM_OPERADOR,
				planta_de_tratamiento.LAT_GRA+(planta_de_tratamiento.LAT_MIN/60)+(planta_de_tratamiento.LAT_SEG/3600) AS lat,
				((planta_de_tratamiento.LON_GRA + (planta_de_tratamiento.LON_MIN/60) + (planta_de_tratamiento.LON_SEG/3600))*-1) AS lon
			FROM
				planta_de_tratamiento";
		//$sql = self::getPtarsWhere("");
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsLoc() {
		$sql = "SELECT
				planta_de_tratamiento.CVE_PTAR,
				planta_de_tratamiento.CVE_LOC_COMP,
				planta_de_tratamiento.NOMBRE,
				planta_de_tratamiento.GMD_LPS,
				planta_de_tratamiento.TIPO_PROC,
				planta_de_tratamiento.PTAR_OP,
				planta_de_tratamiento.LAT_GRA+(planta_de_tratamiento.LAT_MIN/60)+(planta_de_tratamiento.LAT_SEG/3600) AS lat,
				((planta_de_tratamiento.LON_GRA + (planta_de_tratamiento.LON_MIN/60) + (planta_de_tratamiento.LON_SEG/3600))*-1) AS lon,
				ptar_tipo_proc.DESCRIPCION AS proceso,
				cat_localidades.nombre AS localidad,
				ptar_situacion.SITUACION
			FROM
				planta_de_tratamiento
			INNER JOIN ptar_tipo_proc ON planta_de_tratamiento.TIPO_PROC = ptar_tipo_proc.ID_TIPO_PROC
			INNER JOIN cat_localidades ON planta_de_tratamiento.CVE_LOC_COMP = cat_localidades.cve_loc_comp
			INNER JOIN ptar_situacion ON planta_de_tratamiento.PTAR_OP = ptar_situacion.PTAR_OP";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsCities() {
		$sql = "SELECT
				planta_de_tratamiento.CVE_PTAR,
				planta_de_tratamiento.NOMBRE,
				planta_de_tratamiento.GMD_LPS,
				planta_de_tratamiento.PTAR_OP,
				cat_municipios.nombre AS municipio,
				cat_localidades.nombre AS localidad,
				ptar_situacion.SITUACION,
				pob_inegi_2010.POBTOT AS poblacion
			FROM
				planta_de_tratamiento
			INNER JOIN cat_localidades ON planta_de_tratamiento.CVE_LOC_COMP = cat_localidades.cve_loc_comp
			INNER JOIN cat_municipios ON LEFT(planta_de_tratamiento.CVE_PTAR, 5) = cat_municipios.cve_mun_comp
			INNER JOIN ptar_situacion ON planta_de_tratamiento.PTAR_OP = ptar_situacion.PTAR_OP
			INNER JOIN pob_inegi_2010 ON planta_de_tratamiento.CVE_LOC_COMP = pob_inegi_2010.CVE_LOC_COMP
			WHERE
				pob_inegi_2010.POBTOT > '2499'
				AND planta_de_tratamiento.PTAR_OP <> '8'
				AND planta_de_tratamiento.PTAR_OP <> '3'
				AND planta_de_tratamiento.PTAR_OP <> '2'";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getMunicipio($cve_mun_comp) {
		$sql = "SELECT
				cat_municipios.cve_mun_comp,
				cat_municipios.cve_edo,
				cat_municipios.cve_mun,
				cat_municipios.id_mun,
				cat_municipios.cve_reg_edo,
				cat_municipios.nombre
			FROM
				cat_municipios
			WHERE
				cat_municipios.cve_mun_comp = '" . $cve_mun_comp . "'
			LIMIT 1";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsWhere($where) {
		$sql = "SELECT
				planta_de_tratamiento.CVE_PTAR,
				planta_de_tratamiento.NOMBRE,
				planta_de_tratamiento.CVE_LOC_UNICA,
				planta_de_tratamiento.CVE_LOC_COMP,
				planta_de_tratamiento.COORD_FECHA,
				planta_de_tratamiento.ALTITUD,
				planta_de_tratamiento.COORD_METODO,
				planta_de_tratamiento.HAB_BEN,
				planta_de_tratamiento.GMD_LPS,
				planta_de_tratamiento.CPO_REC,
				planta_de_tratamiento.TIPO_CPO_REC,
				planta_de_tratamiento.POT_INST_HP,
				planta_de_tratamiento.DISP_LODOS,
				planta_de_tratamiento.DISP_AGUA,
				planta_de_tratamiento.ORG_OPERA,
				planta_de_tratamiento.FECHA_INI_OP,
				planta_de_tratamiento.ENCARGADO,
				planta_de_tratamiento.POT_OPER_HP,
				planta_de_tratamiento.M3_MES,
				planta_de_tratamiento.COSTOS_OM_MES,
				planta_de_tratamiento.COSTO_M3,
				planta_de_tratamiento.REQ_REHAB_AMP,
				planta_de_tratamiento.TIPO_PROC,
				planta_de_tratamiento.ACT_FRANCIA,
				planta_de_tratamiento.ACT_BRASILIA,
				planta_de_tratamiento.CVE_CARTA_TOP,
				planta_de_tratamiento.CED_CAP,
				planta_de_tratamiento.FECHA_CED_CAP,
				planta_de_tratamiento.IMAGEN,
				planta_de_tratamiento.IMG,
				planta_de_tratamiento.CRO_UBICA,
				planta_de_tratamiento.PTAR_OP,
				planta_de_tratamiento.IMG2,
				planta_de_tratamiento.COMENTARIO,
				planta_de_tratamiento.ID_PROGRAMA,
				planta_de_tratamiento.COSTO_INV,
				planta_de_tratamiento.ANIO_CONST,
				planta_de_tratamiento.NOM_OPERADOR,
				planta_de_tratamiento.LAT_GRA+(planta_de_tratamiento.LAT_MIN/60)+(planta_de_tratamiento.LAT_SEG/3600) AS lat,
				((planta_de_tratamiento.LON_GRA + (planta_de_tratamiento.LON_MIN/60) + (planta_de_tratamiento.LON_SEG/3600))*-1) AS lon
			FROM
				planta_de_tratamiento";
		if ($where !== "") {
			$sql .= "WHERE (" . $where . ")";
		}
		return $sql;
	}

	public function getPtarsSmall() {
		$sql = "SELECT
				planta_de_tratamiento.CVE_PTAR,
				planta_de_tratamiento.NOMBRE,
				planta_de_tratamiento.CVE_LOC_UNICA,
				planta_de_tratamiento.CVE_LOC_COMP,
				planta_de_tratamiento.COORD_FECHA,
				planta_de_tratamiento.LAT_GRA,
				planta_de_tratamiento.LAT_MIN,
				planta_de_tratamiento.LAT_SEG,
				planta_de_tratamiento.LON_GRA,
				planta_de_tratamiento.LON_MIN,
				planta_de_tratamiento.LON_SEG,
				planta_de_tratamiento.ALTITUD,
				planta_de_tratamiento.COORD_METODO,
				planta_de_tratamiento.HAB_BEN,
				planta_de_tratamiento.GMD_LPS,
				planta_de_tratamiento.CPO_REC,
				planta_de_tratamiento.TIPO_CPO_REC,
				planta_de_tratamiento.POT_INST_HP,
				planta_de_tratamiento.DISP_LODOS,
				planta_de_tratamiento.DISP_AGUA,
				planta_de_tratamiento.ORG_OPERA,
				planta_de_tratamiento.FECHA_INI_OP,
				planta_de_tratamiento.ENCARGADO,
				planta_de_tratamiento.POT_OPER_HP,
				planta_de_tratamiento.M3_MES,
				planta_de_tratamiento.COSTOS_OM_MES,
				planta_de_tratamiento.COSTO_M3,
				planta_de_tratamiento.REQ_REHAB_AMP,
				planta_de_tratamiento.TIPO_PROC,
				planta_de_tratamiento.ACT_FRANCIA,
				planta_de_tratamiento.ACT_BRASILIA,
				planta_de_tratamiento.CVE_CARTA_TOP,
				planta_de_tratamiento.CED_CAP,
				planta_de_tratamiento.FECHA_CED_CAP,
				planta_de_tratamiento.IMAGEN,
				planta_de_tratamiento.IMG,
				planta_de_tratamiento.CRO_UBICA,
				planta_de_tratamiento.PTAR_OP,
				planta_de_tratamiento.IMG2,
				planta_de_tratamiento.COMENTARIO,
				planta_de_tratamiento.ID_PROGRAMA,
				planta_de_tratamiento.COSTO_INV,
				planta_de_tratamiento.ANIO_CONST,
				planta_de_tratamiento.NOM_OPERADOR,
				planta_de_tratamiento.LAT_GRA+(planta_de_tratamiento.LAT_MIN/60)+(planta_de_tratamiento.LAT_SEG/3600) AS lat,
				((planta_de_tratamiento.LON_GRA + (planta_de_tratamiento.LON_MIN/60) + (planta_de_tratamiento.LON_SEG/3600))*-1) AS lon
			FROM
				planta_de_tratamiento
			WHERE
				(GMD_LPS < 11.99)";
		//$sql = self::getPtarsWhere("GMD_LPS < 11.99");
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsMedium() {
		$sql = "SELECT
				planta_de_tratamiento.CVE_PTAR,
				planta_de_tratamiento.NOMBRE,
				planta_de_tratamiento.CVE_LOC_UNICA,
				planta_de_tratamiento.CVE_LOC_COMP,
				planta_de_tratamiento.COORD_FECHA,
				planta_de_tratamiento.LAT_GRA,
				planta_de_tratamiento.LAT_MIN,
				planta_de_tratamiento.LAT_SEG,
				planta_de_tratamiento.LON_GRA,
				planta_de_tratamiento.LON_MIN,
				planta_de_tratamiento.LON_SEG,
				planta_de_tratamiento.ALTITUD,
				planta_de_tratamiento.COORD_METODO,
				planta_de_tratamiento.HAB_BEN,
				planta_de_tratamiento.GMD_LPS,
				planta_de_tratamiento.CPO_REC,
				planta_de_tratamiento.TIPO_CPO_REC,
				planta_de_tratamiento.POT_INST_HP,
				planta_de_tratamiento.DISP_LODOS,
				planta_de_tratamiento.DISP_AGUA,
				planta_de_tratamiento.ORG_OPERA,
				planta_de_tratamiento.FECHA_INI_OP,
				planta_de_tratamiento.ENCARGADO,
				planta_de_tratamiento.POT_OPER_HP,
				planta_de_tratamiento.M3_MES,
				planta_de_tratamiento.COSTOS_OM_MES,
				planta_de_tratamiento.COSTO_M3,
				planta_de_tratamiento.REQ_REHAB_AMP,
				planta_de_tratamiento.TIPO_PROC,
				planta_de_tratamiento.ACT_FRANCIA,
				planta_de_tratamiento.ACT_BRASILIA,
				planta_de_tratamiento.CVE_CARTA_TOP,
				planta_de_tratamiento.CED_CAP,
				planta_de_tratamiento.FECHA_CED_CAP,
				planta_de_tratamiento.IMAGEN,
				planta_de_tratamiento.IMG,
				planta_de_tratamiento.CRO_UBICA,
				planta_de_tratamiento.PTAR_OP,
				planta_de_tratamiento.IMG2,
				planta_de_tratamiento.COMENTARIO,
				planta_de_tratamiento.ID_PROGRAMA,
				planta_de_tratamiento.COSTO_INV,
				planta_de_tratamiento.ANIO_CONST,
				planta_de_tratamiento.NOM_OPERADOR,
				planta_de_tratamiento.LAT_GRA+(planta_de_tratamiento.LAT_MIN/60)+(planta_de_tratamiento.LAT_SEG/3600) AS lat,
				((planta_de_tratamiento.LON_GRA + (planta_de_tratamiento.LON_MIN/60) + (planta_de_tratamiento.LON_SEG/3600))*-1) AS lon
			FROM
				planta_de_tratamiento
			WHERE
				(GMD_LPS BETWEEN 12 AND 22.99)";
		//$sql = self::getPtarsWhere("GMD_LPS < 11.99");
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsLarge() {
		$sql = "SELECT
				planta_de_tratamiento.CVE_PTAR,
				planta_de_tratamiento.NOMBRE,
				planta_de_tratamiento.CVE_LOC_UNICA,
				planta_de_tratamiento.CVE_LOC_COMP,
				planta_de_tratamiento.COORD_FECHA,
				planta_de_tratamiento.LAT_GRA,
				planta_de_tratamiento.LAT_MIN,
				planta_de_tratamiento.LAT_SEG,
				planta_de_tratamiento.LON_GRA,
				planta_de_tratamiento.LON_MIN,
				planta_de_tratamiento.LON_SEG,
				planta_de_tratamiento.ALTITUD,
				planta_de_tratamiento.COORD_METODO,
				planta_de_tratamiento.HAB_BEN,
				planta_de_tratamiento.GMD_LPS,
				planta_de_tratamiento.CPO_REC,
				planta_de_tratamiento.TIPO_CPO_REC,
				planta_de_tratamiento.POT_INST_HP,
				planta_de_tratamiento.DISP_LODOS,
				planta_de_tratamiento.DISP_AGUA,
				planta_de_tratamiento.ORG_OPERA,
				planta_de_tratamiento.FECHA_INI_OP,
				planta_de_tratamiento.ENCARGADO,
				planta_de_tratamiento.POT_OPER_HP,
				planta_de_tratamiento.M3_MES,
				planta_de_tratamiento.COSTOS_OM_MES,
				planta_de_tratamiento.COSTO_M3,
				planta_de_tratamiento.REQ_REHAB_AMP,
				planta_de_tratamiento.TIPO_PROC,
				planta_de_tratamiento.ACT_FRANCIA,
				planta_de_tratamiento.ACT_BRASILIA,
				planta_de_tratamiento.CVE_CARTA_TOP,
				planta_de_tratamiento.CED_CAP,
				planta_de_tratamiento.FECHA_CED_CAP,
				planta_de_tratamiento.IMAGEN,
				planta_de_tratamiento.IMG,
				planta_de_tratamiento.CRO_UBICA,
				planta_de_tratamiento.PTAR_OP,
				planta_de_tratamiento.IMG2,
				planta_de_tratamiento.COMENTARIO,
				planta_de_tratamiento.ID_PROGRAMA,
				planta_de_tratamiento.COSTO_INV,
				planta_de_tratamiento.ANIO_CONST,
				planta_de_tratamiento.NOM_OPERADOR,
				planta_de_tratamiento.LAT_GRA+(planta_de_tratamiento.LAT_MIN/60)+(planta_de_tratamiento.LAT_SEG/3600) AS lat,
				((planta_de_tratamiento.LON_GRA + (planta_de_tratamiento.LON_MIN/60) + (planta_de_tratamiento.LON_SEG/3600))*-1) AS lon
			FROM
				planta_de_tratamiento
			WHERE
				(GMD_LPS > 23)";
		//$sql = self::getPtarsWhere("GMD_LPS > 23");
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPpots() {
		$sql = "SELECT
				planta_potabilizadora.CVE_P_POT,
				planta_potabilizadora.N_PLANTA,
				planta_potabilizadora.GASTO_M_DIS,
				planta_potabilizadora.GASTO_M_OPER,
				planta_potabilizadora.F_ABAST,
				planta_potabilizadora.T_PROCESO,
				planta_potabilizadora.U_TRATAMIENTO,
				planta_potabilizadora.IMAGEN,
				planta_potabilizadora.IMG_CR,
				planta_potabilizadora.M3_TRATADOS,
				planta_potabilizadora.IMG_CR_U,
				planta_potabilizadora.CVE_LOC_COMP,
				planta_potabilizadora.ESTATUS,
				planta_potabilizadora.ALTITUD,
				planta_potabilizadora.HAB_BEN,
				planta_potabilizadora.ORG_OPER,
				planta_potabilizadora.P_INSTALADA,
				planta_potabilizadora.P_OPERACION,
				planta_potabilizadora.COSTO_OPER_MANT,
				planta_potabilizadora.COSTO_M3_AGUA_TRA,
				planta_potabilizadora.REQ_REH_AMP,
				planta_potabilizadora.INICIO_OPER,
				planta_potabilizadora.RES_SIS,
				planta_potabilizadora.CED_CAP,
				planta_potabilizadora.FECHA_CED_CAP,
				planta_potabilizadora.OPER_RES,
				YEAR(planta_potabilizadora.INICIO_OPER) AS inicio,
				potabl_abast.DESCRIPCION AS fuenteAbastecimiento,
				cat_municipios.nombre AS municipio,
				cat_localidades.nombre AS localidad,
				potabl_tipo_proc.DESCRIPCION AS proceso,
				planta_potabilizadora.LAT_GRA+(planta_potabilizadora.LAT_MIN/60)+(planta_potabilizadora.LAT_SEG/3600) AS lat,
				((planta_potabilizadora.LON_GRA + (planta_potabilizadora.LON_MIN/60) + (planta_potabilizadora.LON_SEG/3600))*-1) AS lon
			FROM
				planta_potabilizadora
			LEFT JOIN potabl_abast ON planta_potabilizadora.F_ABAST = potabl_abast.ID_FTEAB
			LEFT JOIN cat_localidades ON planta_potabilizadora.CVE_LOC_COMP = cat_localidades.cve_loc_comp
			LEFT JOIN cat_municipios ON cat_localidades.cve_mun_comp = cat_municipios.cve_mun_comp
			LEFT JOIN potabl_tipo_proc ON planta_potabilizadora.T_PROCESO = potabl_tipo_proc.ID_TIPO_PROC";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

		public function getDamDetails($id_presa) {
		$id_presa = MySQLAdapter::getInstance()->mysql_escape_mimic($id_presa);
		$sql = "SELECT
				cat_presas.id_presa, cat_presas.cve_presa,
				cat_presas.nombre, cat_presas.cve_bandas,
				cat_presas.superf_ha, cat_presas.uso_principal,
				cat_presas.elev_name, cat_presas.elev_namo,
				cat_presas.elev_namin, cat_presas.cap_total,
				cat_presas.cap_total_namo, cat_presas.cve_edo,
				cat_presas.cve_mun_comp, cat_presas.anho_inicio,
				cat_presas.anho_termino,
				COALESCE(cat_presas.ppal_lerma, 0) * 1 AS ppal_lerma,
				COALESCE(cat_presas.ppal_jal, 0) * 1 AS ppal_jal,
				cat_presas.num_reporte_cna,
				COALESCE(cat_presas.act_semanal, 0) * 1 AS act_semanal,
				cat_presas.img,
				cat_presas.tabla_hist, cat_presas.columna,
				cat_presas.renglon, cat_presas.corriente,
				cat_presas.cve_corriente, cat_presas.cve_tipo_o,
				cat_presas.superf_ha, cat_presas.otros_usos,
				cat_presas.cve_reg_hid, cat_presas.cve_cuenca,
				cat_presas.cuenca_hid, cat_presas.cve_subcue,
				cat_presas.cve_tipo_cortina, cat_presas.cap_util,
				cat_presas.latitud AS lat, cat_presas.longitud AS lng,
				cat_municipios.nombre AS municipio
			FROM
				cat_presas
			INNER JOIN
				cat_municipios
				ON cat_presas.cve_mun_comp = cat_municipios.cve_mun_comp
			WHERE
				id_presa = '$id_presa'";
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getDamHistory($id, $yesterDate) {
		$id = MySQLAdapter::getInstance()->mysql_escape_mimic($id);
		$yesterDate = MySQLAdapter::getInstance()->mysql_escape_mimic($yesterDate);
		$sql = "SELECT
				volumen,
				fecha
			FROM
				presa$id
			WHERE
				fecha > '$yesterDate'
				AND	NOT (MONTH(fecha) = '2' AND DAY(fecha) = '29')
			ORDER BY
				fecha";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getDamVolLatest($id) {
		$id = MySQLAdapter::getInstance()->mysql_escape_mimic($id);
		$sql = "SELECT
				ROUND(COALESCE(volumen, -1), 3) AS volumen,
				ROUND(COALESCE(obra_toma, -1), 3) AS obra_toma,
				ROUND(COALESCE(vertedor, -1), 3) AS vertedor,
				fecha
			FROM
				presa$id
			ORDER BY
				fecha DESC
			LIMIT 1";
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getDamVolDate($id, $date) {
		$id = MySQLAdapter::getInstance()->mysql_escape_mimic($id);
		$date = MySQLAdapter::getInstance()->mysql_escape_mimic($date);
		$sql = "SELECT
				ROUND(COALESCE(volumen, -1), 3) AS volumen,
				ROUND(COALESCE(obra_toma, -1), 3) AS obra_toma,
				ROUND(COALESCE(vertedor, -1), 3) AS vertedor,
				fecha
			FROM
				presa$id
			WHERE
				fecha ='$date'
			ORDER BY
				fecha DESC
			LIMIT 1";
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getPresasJalisco() {
		$sql = "SELECT
				cat_presas.id_presa,
				cat_presas.cve_presa,
				cat_presas.nombre,
				cat_presas.cve_bandas,
				cat_presas.superf_ha,
				cat_presas.uso_principal,
				cat_presas.elev_name,
				cat_presas.elev_namo,
				cat_presas.elev_namin,
				cat_presas.cap_total,
				cat_presas.cap_total_namo,
				cat_presas.cve_edo,
				cat_presas.cve_mun_comp,
				cat_presas.anho_inicio,
				cat_presas.anho_termino,
				cat_presas.ppal_lerma,
				cat_presas.ppal_jal,
				cat_presas.num_reporte_cna,
				cat_presas.act_semanal,
				cat_presas.img,
				cat_presas.tabla_hist,
				cat_presas.columna,
				cat_presas.renglon,
				cat_presas.corriente,
				cat_presas.cve_corriente,
				cat_presas.cve_tipo_o,
				cat_presas.superf_ha,
				cat_presas.otros_usos,
				cat_presas.cve_reg_hid,
				cat_presas.cve_cuenca,
				cat_presas.cuenca_hid,
				cat_presas.cve_subcue,
				cat_presas.cve_tipo_cortina,
				cat_presas.cap_util,
				cat_presas.latitud AS lat,
				cat_presas.longitud AS lon,
				cat_municipios.nombre AS municipio
			FROM
				cat_presas
			INNER JOIN
				cat_municipios
			ON cat_presas.cve_mun_comp = cat_municipios.cve_mun_comp
			WHERE
				ppal_jal = 1 AND id_presa <> 974
			ORDER BY
				num_reporte_cna";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPresasJaliscoMapa() {
		$sql = "SELECT
				cat_presas.id_presa,
				cat_presas.cve_presa,
				cat_presas.nombre,
				COALESCE(cat_presas.cve_bandas, '') AS cve_bandas,
				COALESCE(cat_presas.superf_ha, 0) AS superf_ha,
				COALESCE(cat_presas.uso_principal, '') AS uso_principal,
				ROUND(COALESCE(cat_presas.elev_name, 0), 2) AS elev_name,
				ROUND(COALESCE(cat_presas.elev_namo, 0), 2) AS elev_namo,
				ROUND(COALESCE(cat_presas.elev_namin, 0), 2) AS elev_namin,
				ROUND(COALESCE(cat_presas.cap_total, 0), 3) AS cap_total,
				ROUND(COALESCE(cat_presas.cap_total_namo, 0), 3) AS cap_total_namo,
				cat_presas.cve_mun_comp,
				COALESCE(cat_presas.anho_termino, 0) AS anho_termino,
				cat_presas.ppal_lerma,
				cat_presas.ppal_jal,
				cat_presas.num_reporte_cna,
				COALESCE(cat_presas.act_semanal, 0) * 1 AS act_semanal,
				cat_presas.tabla_hist,
				COALESCE(cat_presas.superf_ha, 0) AS superf_ha,
				COALESCE(cat_presas.cap_util, 0) AS cap_util,
				cat_presas.latitud AS lat,
				cat_presas.longitud AS lon,
				cat_municipios.nombre AS municipio
			FROM
				cat_presas
			INNER JOIN
				cat_municipios
			ON cat_presas.cve_mun_comp = cat_municipios.cve_mun_comp
			WHERE
				ppal_jal = 1 AND id_presa <> 974
			ORDER BY
				cat_presas.nombre";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPresaMapa($id_presa) {
		$sql = "SELECT
				cat_presas.id_presa,
				cat_presas.cve_presa,
				cat_presas.nombre,
				COALESCE(cat_presas.cve_bandas, '') AS cve_bandas,
				COALESCE(cat_presas.superf_ha, 0) AS superf_ha,
				COALESCE(cat_presas.uso_principal, '') AS uso_principal,
				ROUND(COALESCE(cat_presas.elev_name, 0), 2) AS elev_name,
				ROUND(COALESCE(cat_presas.elev_namo, 0), 2) AS elev_namo,
				ROUND(COALESCE(cat_presas.elev_namin, 0), 2) AS elev_namin,
				ROUND(COALESCE(cat_presas.cap_total, 0), 3) AS cap_total,
				ROUND(COALESCE(cat_presas.cap_total_namo, 0), 3) AS cap_total_namo,
				cat_presas.cve_mun_comp,
				COALESCE(cat_presas.anho_termino, 0) AS anho_termino,
				cat_presas.ppal_lerma,
				cat_presas.ppal_jal,
				cat_presas.num_reporte_cna,
				COALESCE(cat_presas.act_semanal, 0) * 1 AS act_semanal,
				cat_presas.tabla_hist,
				COALESCE(cat_presas.superf_ha, 0) AS superf_ha,
				COALESCE(cat_presas.cap_util, 0) AS cap_util,
				cat_presas.latitud AS lat,
				cat_presas.longitud AS lon,
				cat_municipios.nombre AS municipio
			FROM
				cat_presas
			INNER JOIN
				cat_municipios
			ON cat_presas.cve_mun_comp = cat_municipios.cve_mun_comp
			WHERE
				id_presa = " . $id_presa . "
			ORDER BY
				cat_presas.nombre";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPresaVolActual($tabla_hist) {
		$sql = "SELECT
				ROUND(COALESCE(volumen, 0), 3) AS volumen,
				ROUND(COALESCE(obra_toma, 0), 3) AS obra_toma,
				ROUND(COALESCE(vertedor, 0), 3) AS vertedor,
				COALESCE(fecha, '') AS fecha,
				COALESCE(comentario, '') AS comentario,
				id_registro
			FROM
				" . $tabla_hist . "
			ORDER BY
				fecha DESC
			LIMIT 1";
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getPresaVolFecha($tabla_hist, $date) {
		$sql = "SELECT
				ROUND(COALESCE(volumen, 0), 3) AS volumen,
				ROUND(COALESCE(obra_toma, 0), 3) AS obra_toma,
				ROUND(COALESCE(vertedor, 0), 3) AS vertedor,
				COALESCE(fecha, '') AS fecha,
				COALESCE(comentario, '') AS comentario,
				id_registro
			FROM
				" . $tabla_hist . "
			WHERE fecha = '" . $date . "'
			LIMIT 1";
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getPresaVolMaxMin($tabla_hist, $year, $max = true) {
		$sql = "SELECT
				ROUND(COALESCE(volumen, 0), 3) AS volumen,
				ROUND(COALESCE(obra_toma, 0), 3) AS obra_toma,
				ROUND(COALESCE(vertedor, 0), 3) AS vertedor,
				COALESCE(fecha, '') AS fecha,
				COALESCE(comentario, '') AS comentario,
				id_registro
			FROM
				" . $tabla_hist . "
			WHERE
				YEAR(fecha) = '" . $year . "'";
		$sql .= ($max) ? " AND volumen <> '-1' ORDER BY volumen ASC LIMIT 1" : " ORDER BY volumen DESC LIMIT 1";
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getPresaTrendQuery($tabla_hist, $today) {
		$todayArray = explode("-", $today);
		$lastYearNumber = $todayArray[0] - 1;
		$thisYearNumber = $todayArray[0];
		$d = mktime(0, 0, 0, $todayArray[1], $todayArray[2], $todayArray[0]);
		$raind = mktime(0, 0, 0, 6, 15, $thisYearNumber);
		$dryd = mktime(0, 0, 0, 10, 31, $thisYearNumber);
		$sql = "SELECT
				volumen, fecha,
				YEAR(fecha) AS anio,
				MONTH(fecha) AS mes,
				DAY(fecha) AS dia
			FROM " . $tabla_hist;
		//rain season
		//$sql .=	" WHERE fecha > '" . $lastYearNumber . "-10-30' ";
		//$sql .= " AND fecha < '" . $thisYearNumber . "-11-01' ";
		//dry season
		$sql .= " WHERE fecha > '" . $thisYearNumber . "-11-01' ";
		return $sql;
	}

	public function getPresaTrend($tabla_hist, $today) {
		$sql = self::getPresaTrendQuery($tabla_hist, $today);
		$sql .= " ORDER BY fecha";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPresaTrendMonth($tabla_hist, $today) {
		$sql = self::getPresaTrendQuery($tabla_hist, $today);
		$todayArray = explode("-", $today);
		$sql .= " AND (DAY(fecha) = 1 OR DAY(fecha) = 2)
					OR (DAY(fecha) = " . $todayArray[2] . "
					AND MONTH(fecha) = " . $todayArray[1] . "
					AND YEAR(fecha) = " . $todayArray[0] . ")
				ORDER BY
					fecha";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPresaTrendYear($tabla_hist, $today) {
		$todayArray = explode("-", $today);
		$thisYearNumber = $todayArray[0];
		$sql = "SELECT volumen, fecha FROM " . $tabla_hist;
		$sql .=	" WHERE fecha > '" . ($thisYearNumber - 3) . "-10-30' ";
		$sql .= " ORDER BY fecha";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getEstacionesJalisco() {
		$sql = "SELECT
				cat_estaciones_hidro.id_estacion,
				cat_estaciones_hidro.nombre,
				cat_estaciones_hidro.cve_mun_comp,
				cat_estaciones_hidro.latitud AS lat,
				cat_estaciones_hidro.longitud AS lon,
				cat_estaciones_hidro.tabla_hist,
				cat_municipios.nombre AS municipio
			FROM
				cat_estaciones_hidro
			INNER JOIN
				cat_municipios ON cat_estaciones_hidro.cve_mun_comp = cat_municipios.cve_mun_comp
			WHERE
				cat_estaciones_hidro.jalisco = 1
				AND cat_estaciones_hidro.latitud <> 0";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getTendenciaEstacion($tabla_hist) {
		$sql = "SELECT
				id_registro, comentario, fecha, gasto_m3
			FROM
				" . $tabla_hist . "
			ORDER BY
				fecha DESC
			LIMIT 15";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getEstacionFecha($tabla_hist, $fecha) {
		$sql = "SELECT
				id_registro, comentario, fecha, gasto_m3
			FROM
				" . $tabla_hist . "
			WHERE
				(fecha = '" . $fecha . "')
			LIMIT 1";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getCotaToday() {
		$sql = "SELECT
				chapala_hist.cota,
				constante_cota_chapala.ALMACENAMIENTO AS vol,
				constante_cota_chapala.SUPERFICIE AS area,
				DATE_FORMAT(chapala_hist.fecha, '%Y-%m-%d') as fecha,
				chapala_hist.variacion,
				YEAR(chapala_hist.fecha) AS cotaYear,
				MONTH(chapala_hist.fecha) AS cotaMonth,
				DAY(chapala_hist.fecha) AS cotaDay
			FROM
				chapala_hist
			INNER JOIN
				constante_cota_chapala ON chapala_hist.cota = constante_cota_chapala.COTA
			ORDER BY
				chapala_hist.fecha DESC
			LIMIT 1";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getCotaDate($date) {
		$sql = "SELECT
				chapala_hist.cota,
				constante_cota_chapala.ALMACENAMIENTO AS vol,
				constante_cota_chapala.SUPERFICIE AS area,
				chapala_hist.variacion,
				DATE_FORMAT(chapala_hist.fecha, '%Y-%m-%d') as fecha,
				YEAR(chapala_hist.fecha) AS cotaYear,
				MONTH(chapala_hist.fecha) AS cotaMonth,
				DAY(chapala_hist.fecha) AS cotaDay
			FROM
				chapala_hist
			INNER JOIN
				constante_cota_chapala ON chapala_hist.cota = constante_cota_chapala.COTA
			WHERE
				chapala_hist.fecha = '$date'
			LIMIT 1";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getCotaRecent() {
		$sql = "SELECT
				cota, vol, area, variacion, fecha
			FROM
				v_cota_recent
			ORDER BY
				fecha";
		$sql = "SELECT cota,vol,area,variacion,fecha
			FROM (
				SELECT
					chapala_hist.cota AS cota,
					constante_cota_chapala.ALMACENAMIENTO AS vol,
					constante_cota_chapala.SUPERFICIE AS area,
					chapala_hist.variacion,
					DATE_FORMAT(chapala_hist.fecha, '%Y-%m-%d') as fecha
				FROM
					chapala_hist
				INNER JOIN
					constante_cota_chapala ON chapala_hist.cota = constante_cota_chapala.COTA
				ORDER BY
					chapala_hist.fecha DESC
				LIMIT 15
			) AS t
		ORDER BY fecha ASC";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getCotaWeekly() {
		$sql = "SELECT
			t.cota AS cotaYear,
			(
				SELECT
					cota
				FROM
					chapala_hist
				WHERE
					fecha = DATE_ADD(t.fecha,INTERVAL -1 year)
			) AS cotaLastYear,
			(
				SELECT
					cota
				FROM
					chapala_hist
				WHERE
					fecha = DATE_ADD(t.fecha,INTERVAL -2 year)
			) AS cotaYesterYear,
			DATE_FORMAT(t.fecha, '%Y-%m-%d') as fecha
		FROM
			chapala_hist AS t
		ORDER BY
			fecha DESC
		LIMIT 7";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getCotaMonthly($year) {
		$lastYear = ($year - 1) . "-12-31";
		$sql = "SELECT
				chapala_hist.cota,
				constante_cota_chapala.ALMACENAMIENTO AS vol,
				constante_cota_chapala.SUPERFICIE AS area,
				chapala_hist.variacion,
				DATE_FORMAT(chapala_hist.fecha, '%Y-%m-%d') as fecha,
				YEAR(chapala_hist.fecha) AS cotaYear,
				MONTH(chapala_hist.fecha) AS cotaMonth,
				DAY(chapala_hist.fecha) AS cotaDay
			FROM
				chapala_hist
			INNER JOIN
				constante_cota_chapala ON chapala_hist.cota = constante_cota_chapala.COTA
			WHERE
				fecha = LAST_DAY(fecha) AND fecha > '$lastYear'";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsStatusSum() {
		$sql = "SELECT
				COUNT(*) AS cant,
				SUM(COALESCE(GMD_LPS, 0)) AS gasto,
				SUM(v_ptar_hab.HAB_BEN) AS ben,
				planta_de_tratamiento.PTAR_OP AS ptar_op,
				ptar_situacion.SITUACION AS status
			FROM
				planta_de_tratamiento
			LEFT JOIN
				ptar_situacion ON planta_de_tratamiento.PTAR_OP = ptar_situacion.PTAR_OP
			LEFT JOIN
				v_ptar_hab ON planta_de_tratamiento.CVE_PTAR = v_ptar_hab.cve_ptar
			WHERE
				planta_de_tratamiento.PTAR_OP <> '2' AND
				planta_de_tratamiento.PTAR_OP <> '3' AND
				planta_de_tratamiento.PTAR_OP <> '8'
			GROUP BY
				planta_de_tratamiento.PTAR_OP";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsCoords() {
		$sql = "SELECT
				planta_de_tratamiento.CVE_PTAR,
				planta_de_tratamiento.NOMBRE,
				planta_de_tratamiento.LAT_GRA + (planta_de_tratamiento.LAT_MIN/60) + (planta_de_tratamiento.LAT_SEG/3600) AS lat,
				(planta_de_tratamiento.LON_GRA + (planta_de_tratamiento.LON_MIN/60) + (planta_de_tratamiento.LON_SEG/3600)) * -1 AS lng
			FROM
				planta_de_tratamiento";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsStartCountBeforeDate($date, $excludeStatus) {
		$sql = "SELECT
				COUNT(CVE_PTAR) AS cant
			FROM
				planta_de_tratamiento
			WHERE
				FECHA_INI_OP < '" . $date . "' ";
		if (isset($excludeStatus) && $excludeStatus != 0) {
			$sql .= " AND PTAR_OP <> 8 ";
			$sql .= " AND PTAR_OP <> 3 ";
			$sql .= " AND PTAR_OP <> 2 ";
		}
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getPtarsStartCountMonth($currentYear, $month, $excludeStatus) {
		$sql = "SELECT
				COUNT(CVE_PTAR) AS cant
			FROM
				planta_de_tratamiento
			WHERE
				MONTH(FECHA_INI_OP) = '" . $month . "'
				AND YEAR(FECHA_INI_OP) = '" . $currentYear . "'";
		if (isset($excludeStatus) && $excludeStatus != 0) {
			$sql .= " AND PTAR_OP <> 8 ";
			$sql .= " AND PTAR_OP <> 3 ";
			$sql .= " AND PTAR_OP <> 2 ";
		}
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getPtarsStatusCountBeforeDate($date, $statusId) {
		$sql = "SELECT
				COUNT(planta_de_tratamiento.CVE_PTAR) AS cant
			FROM
				planta_de_tratamiento
			LEFT JOIN
				ptar_hsituacion ON planta_de_tratamiento.CVE_PTAR = ptar_hsituacion.CVE_PTAR
			WHERE
				ptar_hsituacion.FECHA < '" . $date . "' ";
		if ($statusId == 1) {
			$sql .= " AND (ptar_hsituacion.PTAR_OP = '1') ";
		}
		if($statusId == 2) {
			$sql .= " AND (ptar_hsituacion.PTAR_OP = '1' OR ptar_hsituacion.PTAR_OP = '14') ";
		}
		$sql .= " AND ptar_hsituacion.PTAR_OP <> '8' ";
		$sql .= " AND ptar_hsituacion.PTAR_OP <> '3' ";
		$sql .= " AND ptar_hsituacion.PTAR_OP <> '2' ";
		$sql .= " ORDER BY ptar_hsituacion.FECHA, ptar_hsituacion.PTAR_OP";
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getPtarsStatusCountMonth($currentYear, $month, $statusId) {
		$sql = "SELECT
				COUNT(planta_de_tratamiento.CVE_PTAR) AS cant
			FROM
				planta_de_tratamiento
			LEFT JOIN
				ptar_hsituacion ON planta_de_tratamiento.CVE_PTAR = ptar_hsituacion.CVE_PTAR
			WHERE
				MONTH(ptar_hsituacion.FECHA) = '" . $month . "'
				AND YEAR(ptar_hsituacion.FECHA) = '" . $currentYear . "'";
		if ($statusId == 1) {
			$sql .= " AND (ptar_hsituacion.PTAR_OP = '1') ";
		}
		if($statusId == 2) {
			$sql .= " AND (ptar_hsituacion.PTAR_OP = '1' OR ptar_hsituacion.PTAR_OP = '14') ";
		}
		$sql .= " AND ptar_hsituacion.PTAR_OP <> '8' ";
		$sql .= " AND ptar_hsituacion.PTAR_OP <> '3' ";
		$sql .= " AND ptar_hsituacion.PTAR_OP <> '2' ";
		$sql .= " ORDER BY ptar_hsituacion.FECHA, ptar_hsituacion.PTAR_OP";
		return MySQLAdapter::getInstance()->getSingleRow($sql);
	}

	public function getPtarsOp() {
		$sql = "SELECT
				cant,
				CVE_PTAR,
				COALESCE(HAB_BEN, 0) AS HAB_BEN,
				GMD_LPS,
				PTAR_OP,
				operacion,
				municipio,
				localidad,
				NOMBRE,
				SITUACION,
				ptarStatus
			FROM
				v_ptar_status
			ORDER BY
				operacion, PTAR_OP";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsOpGroup() {
		$sql = "SELECT
				SUM(cant) AS summary,
				SUM(COALESCE(HAB_BEN, 0)) AS pop,
				SUM(GMD_LPS) AS flow,
				PTAR_OP AS id,
				operacion,
				SITUACION AS label,
				ptarStatus
			FROM
				v_ptar_status
			GROUP BY
				operacion, SITUACION, PTAR_OP";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsInventory() {
		$sql = "SELECT
				id_ptar_inventario,
				op + fuera + baja AS cant,
				op,
				norma,
				op-norma AS fuera_norma,
				fuera,
				baja,
				anio,
				mes,
				DATE_FORMAT(fecha, '%Y-%m-%d') as fecha
			FROM
				ptar_inventario
			WHERE
				YEAR(fecha) > '2006'";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsInventoryNorm() {
		$sql = "SELECT
				COUNT(CVE_PTAR) AS cant,
				PTAR_OP AS id,
				'Dentro de norma' AS label,
				DATE_FORMAT(FECHA, '%Y-%m-%d') AS fecha
			FROM
				ptar_situacion_hist
			WHERE
				PTAR_OP = '1'
			GROUP BY
				FECHA, PTAR_OP";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsInventoryOutNorm() {
		$sql = "SELECT
				COUNT(CVE_PTAR) AS cant,
				PTAR_OP AS id,
				'Fuera de norma' AS label,
				DATE_FORMAT(FECHA, '%Y-%m-%d') AS fecha
			FROM
				ptar_situacion_hist
			WHERE
				PTAR_OP = '14'
			GROUP BY
				FECHA, PTAR_OP";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLermaDamsYearly($id_presa) {
		$table = "presa" . $id_presa;
		//rain season
		//$year = date('Y') - 3;
		//dry season
		$year = date('Y') - 2;
		if ($id_presa == 0) {
			//rain season
			//$year = $year - 2;
			//dry season
			$year = $year - 1;
			$table = "presasLermaVolTotal";
		}
		if ($id_presa < 0) {
			//rain season
			//$year = $year - 2;
			//dry season
			$year = $year - 1;
			$table = "presasJalVolTotal";
		}
		$sql = "SELECT
				volumen AS vol,
				CASE
					WHEN
						MONTH(fecha) > 10
					THEN
				  		YEAR(fecha) + 1
					ELSE
				  		YEAR(fecha)
				END	AS series,
				CASE
					WHEN
						MONTH(fecha) > 10
					THEN
						CONCAT((DATE_FORMAT(NOW(), '%Y') - 1), DATE_FORMAT(fecha, '-%m'), DATE_FORMAT(fecha, '-%d'))
					ELSE
						CONCAT((DATE_FORMAT(NOW(), '%Y')), DATE_FORMAT(fecha, '-%m'), DATE_FORMAT(fecha, '-%d'))
				END AS date
			FROM
				" . $table . "
			WHERE
				fecha > '" . $year . "-10-31'
				AND	NOT (MONTH(fecha) = '2' AND DAY(fecha) = '29')
			ORDER BY
				fecha";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsChapala() {
		$sql = "SELECT
				LEFT(CVE_PTAR, 2) as CVE_EDO,
				COUNT(CVE_PTAR) AS ptar,
				CVE_LOC_COMP,
				CVE_PTAR,
				NOMBRE,
				GMD_LPS,
				planta_de_tratamiento.LAT_GRA + (planta_de_tratamiento.LAT_MIN/60) + (planta_de_tratamiento.LAT_SEG/3600) AS lat,
				(planta_de_tratamiento.LON_GRA + (planta_de_tratamiento.LON_MIN/60) + (planta_de_tratamiento.LON_SEG/3600)) * -1 AS lon
			FROM
				planta_de_tratamiento
			WHERE
				(CVE_LOC_COMP like '14030%'
				OR CVE_LOC_COMP like '14063%'
				OR CVE_LOC_COMP like '14066%'
				OR CVE_LOC_COMP like '14047%'
				OR CVE_LOC_COMP like '14050%'
				OR CVE_LOC_COMP like '14107%'
				OR CVE_LOC_COMP like '14096%')
			AND NOT
				(CVE_PTAR = '14050PTAR06'
				OR CVE_PTAR = '14050PTAR08'
				OR CVE_PTAR = '14066PTAR01'
				OR CVE_PTAR = '14063PTAR01'
				OR CVE_PTAR = '14066PTAR05'
				OR CVE_PTAR = '14063PTAR03'
				OR CVE_PTAR = '14063PTAR05'
				OR CVE_PTAR = '14063PTAR02'
				OR CVE_PTAR = '14066PTAR06'
				OR CVE_PTAR = '14063PTAR04')
			GROUP BY
				CVE_PTAR";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getVolumenPeriodo($fechaIni, $fechaFin) {
		$sql = "SELECT
					chapala_hist.cota,
					constante_cota_chapala.ALMACENAMIENTO AS vol,
					constante_cota_chapala.SUPERFICIE AS area,
					DATE_FORMAT(chapala_hist.fecha, '%Y-%m-%d') AS fecha,
					CONCAT('2012', DATE_FORMAT(chapala_hist.fecha, '-%m'), DATE_FORMAT(fecha, '-%d')) AS date,
					YEAR(chapala_hist.fecha) AS year
				FROM
					chapala_hist
				INNER JOIN
					constante_cota_chapala ON chapala_hist.cota = constante_cota_chapala.COTA
				WHERE
					chapala_hist.fecha > '" . $fechaIni . "'
					AND chapala_hist.fecha <= '" . $fechaFin . "'
					AND	NOT (MONTH(chapala_hist.fecha) = '2' AND DAY(chapala_hist.fecha) = '29')
				ORDER BY
					chapala_hist.fecha ASC";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getChapalaLevels() {
		$sql = "SELECT
				chapala_niveles.cota,
				chapala_niveles.fecha,
				chapala_niveles.maximo,
				constante_cota_chapala.ALMACENAMIENTO AS vol,
				constante_cota_chapala.SUPERFICIE AS area,
				YEAR(chapala_niveles.fecha) AS y,
				MONTH(chapala_niveles.fecha) - '1' AS m,
				DAY(chapala_niveles.fecha) AS d
			FROM
				chapala_niveles
			LEFT JOIN
				constante_cota_chapala ON chapala_niveles.cota = constante_cota_chapala.COTA
			ORDER BY
				chapala_niveles.fecha";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLastChapalaLevel() {
		$sql = "SELECT
				cota, fecha, maximo
			FROM
				chapala_niveles
			ORDER BY
				fecha DESC
			LIMIT 1";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	/*
	public function insertPtarStatus() {
		$data = array(
			array(
				CVE_PTAR => '14091PTAR01',
				PTAR_OP =>'14',
				FECHA => '2012-01-31'),
			array(
				CVE_PTAR => '14106PTAR01',
				PTAR_OP =>'14',
				FECHA => '2012-01-31')
		);

		foreach ($data as $row)
		{
			$sqlData = array(
				"CVE_PTAR" => "'" . $row['CVE_PTAR'] . "'",
				"PTAR_OP" => "'" . $row['PTAR_OP'] . "'",
				"FECHA"	=> "'" . $row['FECHA'] . "'"
			);
			MySQLAdapter::getInstance()->insert("ptar_situacion_hist", $sqlData);
		}
	}
	*/
	public function getPtarsInventorybyPtarOp($ptar_op) {
		$sql = "SELECT
				COUNT(CVE_PTAR) AS cant,
				ptar_situacion_hist.PTAR_OP AS id,
				ptar_situacion.SITUACION AS label,
				DATE_FORMAT(ptar_situacion_hist.FECHA, '%Y-%m-%d') AS fechaSituacion
			FROM
				ptar_situacion_hist
			LEFT Join
				ptar_situacion
				ON ptar_situacion_hist.PTAR_OP = ptar_situacion.PTAR_OP
			WHERE
				ptar_situacion_hist.PTAR_OP = '$ptar_op'
			GROUP BY
				ptar_situacion_hist.FECHA, ptar_situacion_hist.PTAR_OP";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getEvaporacion($year) {
		$lastYear = ($year - 1) . "-12-31";
		$sql = "SELECT
				SUM(precipitacion) AS precipitacion,
				SUM(evaporacion) AS evaporacion,
				DATE_FORMAT(fecha, '%Y-%m-%d') AS fecha,
				YEAR(fecha) as anio,
				MONTH(fecha) as idmes,
				variacion
			FROM
				chapala_hist
			WHERE
				fecha > '$lastYear'
			GROUP BY
				MONTH(fecha)
			ORDER BY
				fecha";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPresasDiario() {
		$sql = "SELECT
				id_presa,
				nombre,
				cve_mun_comp,
				ppal_lerma,
				ppal_jal,
				num_reporte_cna,
				act_semanal,
				tabla_hist,
				columna,
				renglon
			FROM
				cat_presas
			WHERE
				COALESCE(renglon, 0) > 0
				AND id_presa <> '977'";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function insertDamData($table, $damData) {
		$data = array(
			"volumen" => MySQLAdapter::getInstance()->mysql_escape_mimic($damData['volumen']),
			"obra_toma" => MySQLAdapter::getInstance()->mysql_escape_mimic($damData['obra_toma']),
			"vertedor" => MySQLAdapter::getInstance()->mysql_escape_mimic($damData['vertedor']),
			"fecha" => "'" . utf8_encode(MySQLAdapter::getInstance()->mysql_escape_mimic($damData['fecha'])) . "'"
		);
		//return MySQLAdapter::getInstance()->insertDebug($table, $data);
		MySQLAdapter::getInstance()->insertRecord($table, $data);
		return MySQLAdapter::getInstance()->getInsertedRowID($table);
	}

	public function insertDamTotalData($table, $damData) {
		$data = array(
			"volumen" => MySQLAdapter::getInstance()->mysql_escape_mimic($damData['volumen']),
			"fecha" => "'" . utf8_encode(MySQLAdapter::getInstance()->mysql_escape_mimic($damData['fecha'])) . "'"
		);
		//return MySQLAdapter::getInstance()->insertDebug($table, $data);
		MySQLAdapter::getInstance()->insertRecord($table, $data);
		return MySQLAdapter::getInstance()->getInsertedRowID($table);
	}

	public function getEstacionesDiario() {
		$sql = "SELECT
				id_estacion, nombre,
				cve_reg_hid, cve_cuenca,
				cuenca_hid, cve_subcue,
				cve_edo, cve_reg_edo,
				cve_mun_comp, latitud,
				longitud, ppal_lerma,
				ppal_jal, num_reporte_cna,
				lerma, jalisco,
				img, tabla_hist,
				columna, renglon,
				tipo
			FROM
				cat_estaciones_hidro
			WHERE
				COALESCE(renglon, 1) > 1";
		$sql = "SELECT id_estacion, nombre, columna, renglon FROM cat_estaciones_hidro WHERE COALESCE(renglon, 1) > '1'";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function insertStationData($table, $insertData) {
		$data = array(
			"gasto_m3" => MySQLAdapter::getInstance()->mysql_escape_mimic($insertData['gasto_m3']),
			"fecha" => "'" . utf8_encode(MySQLAdapter::getInstance()->mysql_escape_mimic($insertData['fecha'])) . "'",
			"comentario" => "'" . utf8_encode(MySQLAdapter::getInstance()->mysql_escape_mimic($insertData['comentario'])) . "'"
		);
		return MySQLAdapter::getInstance()->insertDebug($table, $data);
		MySQLAdapter::getInstance()->insertRecord($table, $data);
		return MySQLAdapter::getInstance()->getInsertedRowID($table);
	}

	public function insertChapalaData($insertData) {
		$data = array(
			"cota" => MySQLAdapter::getInstance()->mysql_escape_mimic($insertData['cota']),
			"precipitacion" => MySQLAdapter::getInstance()->mysql_escape_mimic($insertData['precipitacion']),
			"evaporacion" => MySQLAdapter::getInstance()->mysql_escape_mimic($insertData['evaporacion']),
			"variacion" => MySQLAdapter::getInstance()->mysql_escape_mimic($insertData['variacion']),
			"fecha" => "'" . utf8_encode(MySQLAdapter::getInstance()->mysql_escape_mimic($insertData['fecha'])) . "'"
		);
		//return MySQLAdapter::getInstance()->insertDebug("chapala_hist", $data);
		MySQLAdapter::getInstance()->insertRecord("chapala_hist", $data);
		return MySQLAdapter::getInstance()->getInsertedRowID("chapala_hist");
	}

	public function getPtarsOpKey() {
		$sql = "SELECT
				CVE_PTAR,
				pobtot,
				COALESCE(HAB_BEN, 0) AS HAB_BEN,
				GMD_LPS,
				PTAR_OP,
				operacion,
				municipio,
				localidad,
				NOMBRE,
				SITUACION,
				ptarStatus
			FROM
				v_ptar_status
			LEFT JOIN
				pob_mun_inegi_2010 ON LEFT(v_ptar_status.CVE_PTAR, 5) = pob_mun_inegi_2010.cve_mun_comp
			ORDER BY
				CVE_PTAR";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsOpKey2() {
		$sql = "SELECT
				pt.CVE_PTAR AS cv,
				COALESCE(pi.POBTOT, 0) AS pob,
				COALESCE(pt.HAB_BEN, 0) AS h,
				COALESCE(pt.GMD_LPS, 0) AS l,
				COALESCE(vps.PTAR_OP, 99) AS pO,
				COALESCE(vps.operacion, 99) AS o,
				cm.nombre AS m,
				cl.nombre AS loc,
				pt.NOMBRE AS n
			FROM
				planta_de_tratamiento AS pt
			LEFT JOIN
				cat_municipios AS cm ON LEFT(pt.CVE_PTAR, 5) = cm.cve_mun_comp
			LEFT JOIN
				cat_localidades AS cl ON pt.CVE_LOC_UNICA = cl.cve_loc_unica
			LEFT JOIN
				v_ptar_status AS vps ON pt.CVE_PTAR = vps.CVE_PTAR
			LEFT JOIN
				pob_inegi_2010 AS pi ON pt.CVE_LOC_UNICA = pi.CVE_LOC_UNICA";
		$sql = "SELECT
				pt.CVE_PTAR AS cv,
				COALESCE(pi.POBTOT, 0) AS pob,
				COALESCE(pt.HAB_BEN, 0) AS h,
				COALESCE(pt.GMD_LPS, 0) AS l,
				pt.PTAR_OP AS o
				cm.nombre AS m,
				cl.nombre AS loc,
				pt.NOMBRE AS n,
				cm.cve_reg_edo AS r
			FROM planta_de_tratamiento AS pt
			LEFT JOIN
				cat_municipios AS cm ON LEFT(pt.CVE_PTAR, 5) = cm.cve_mun_comp
			LEFT JOIN
				cat_localidades AS cl ON pt.CVE_LOC_UNICA = cl.cve_loc_unica
			LEFT JOIN
				pob_inegi_2010 AS pi ON pt.CVE_LOC_UNICA = pi.CVE_LOC_UNICA
			WHERE cm.censo2010 = '1'
			AND cl.censo2010 = '1'";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPtarsReport() {
		$sql = "SELECT
				REGION, MUNICIPIO,
				LOCALIDAD, NOMBRE_PLANTA,
				TIPO_PROCESO, GASTO_DIS_LPS,
				GASTO_M_OPERACION_LPS, HABITANTES_BENEFICIADOS,
				CONSTRUCCION, REGION_HIDROLOGICA,
				CUENCA, SUBCUENCA,
				SITUACION, ESTADO,
				CLAVE_EDO, CLAVE_MCPIO,
				CLAVE_LOC, CVE_LOC_UNICA,
				CLAVE_REGION_EDO, CLAVE_PTAR,
				CVE_LOC_COMP, FECHA_COORDENADAS,
				LAT_GRA, LAT_MIN,
				LAT_SEG, LON_GRA,
				LON_MIN, LON_SEG,
				ALTITUD, METODO_COORDENADAS,
				CUERPO_RECEPTOR, UTILIZACION_BIOSOLIDOS,
				USO_AGUA_TRATADA, ORGANISMO_OPERADOR,
				REQUERIMIENTOS_AMPLIACION_REHABILITACION, ANIO_OPERACIONALES,
				MES_OPERACIONALES, COSTO_OPERACION_$,
				COSTO_UNITARIOXm3_$, M3_AGUA_TRATADA,
				POTENCIA_INSTALADA_HP, POTENCIA_OPERACION_HP,
				SUELDOS_Y_SALARIOS_$, CONSUMIBLES_$,
				ENERGIA_ELECTRICA_$, SERVICIOS_GENERALES_$,
				HERARRAMIENTAS_Y_MAQUINARIA_$, COMENTARIO,
				PROGRAMA, COSTO_INV,
				INICIO_OPERACION, RESPONSABLE,
				NOMBRE_OPERADOR, CAPTURA,
				FECHA_CAPTURA, CVE_RH,
				CVE_CUENCA, CVE_SUBCUENCA,
				PTAR_OP
			FROM
				reporte_lalo_demi
			ORDER BY
				CLAVE_PTAR";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getPpotsReport() {
		$sql = "SELECT
				planta_potabilizadora.CVE_P_POT AS id,
				planta_potabilizadora.N_PLANTA AS name,
				planta_potabilizadora.CVE_LOC_COMP AS idl,
				planta_potabilizadora.F_ABAST AS idf,
				planta_potabilizadora.T_PROCESO AS idp,
				planta_potabilizadora.ESTATUS AS ids,
				planta_potabilizadora.GASTO_M_DIS AS g,
				cat_municipios.nombre AS mun,
				cat_localidades.nombre AS loc,
				potabl_abast.DESCRIPCION AS f,
				potabl_tipo_proc.DESCRIPCION AS p,
				potabl_situacion.ESTATUS AS s,
				ROUND((planta_potabilizadora.LAT_GRA+(planta_potabilizadora.LAT_MIN/60)
				+(planta_potabilizadora.LAT_SEG/3600)) * 1000000) / 1000000 AS lat,
				(ROUND((planta_potabilizadora.LON_GRA+(planta_potabilizadora.LON_MIN/60)
				+(planta_potabilizadora.LON_SEG/3600)) * 1000000) / 1000000) * -1 AS lng
			FROM
				planta_potabilizadora
				LEFT JOIN cat_localidades ON
				planta_potabilizadora.CVE_LOC_COMP = cat_localidades.cve_loc_comp
				LEFT JOIN cat_municipios ON
				cat_localidades.cve_mun_comp = cat_municipios.cve_mun_comp
				LEFT JOIN potabl_abast ON
				planta_potabilizadora.F_ABAST = potabl_abast.ID_FTEAB
				LEFT JOIN potabl_tipo_proc ON
				planta_potabilizadora.T_PROCESO = potabl_tipo_proc.ID_TIPO_PROC
				LEFT JOIN potabl_situacion ON
				planta_potabilizadora.ESTATUS = potabl_situacion.ID_ESTATUS
			ORDER BY
				CVE_P_POT";
		return MySQLAdapter::getInstance()->getAllRows($sql);
	}
}