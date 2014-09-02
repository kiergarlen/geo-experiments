<?php
require_once 'MSSQLAdapter.php';
//require_once('ConnectionConstantsInegi.php');

class DALInegi
{
	protected static $_instance;
	const DB_HOST = "DBSQL";
	const DB_USER = "sincej";
	const DB_PASSWORD = "sincej@12#";
	const DB_DATA_BASE = "INEGI";

	/**
	 * Get the Singleton instance of the class
	 */
	public static function getInstance() {
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Class constructor
	 */
	protected function __construct() {
		MSSQLAdapter::getInstance(array(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_DATA_BASE));
	}

	/**
	 * Prevent cloning the instance of the class
	 */
	protected function __clone(){}

	public function getMenuAnios($campoEnlace) {
		$sql = "SELECT DISTINCT
				anio
			FROM
				coberturas
			WHERE
				ISNULL(" .  $campoEnlace . ", 0) > 0
			ORDER BY
				anio
			DESC";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function insertMapaTematico($mapData) {
		MSSQLAdapter::getInstance()->insertRecord("geoMapaTematico", $mapData);
		return self::getInsertedRowID("geoMapaTematico");
	}

	public function insertActiveLayers($activeLayer) {
		MSSQLAdapter::getInstance()->insertRecord("geoActiveLayers", $activeLayer);
		return self::getInsertedRowID("geoActiveLayers");
	}

	public function insertFilterItems($filterItem) {
		MSSQLAdapter::getInstance()->insertRecord("geoFilterItems", $filterItem);
		return self::getInsertedRowID("geoFilterItems");
	}

	public function insertSymbologySource($symbologySource) {
		MSSQLAdapter::getInstance()->insertRecord("geoSymbologySources", $symbologySource);
		return self::getInsertedRowID("geoSymbologySources");
	}

	public function getMenuCuencas($id_cue_hid = 0) {
		$sql = "SELECT
				id_cue_hid, cve_reg_hid, cve_cue_hid, nom_cue_hid
			FROM
				cat_cuenca_hid";
		if (!is_null($id_cue_hid) && ($id_cue_hid > 0))
		{
			$sql .= " WHERE id_cue_hid = '" . $id_cue_hid . "'";
			//return self::getSingleRow($sql);
		}
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getEstados($id_estado = 0) {
		$sql= "SELECT
				cve_edo, nom_edo, cve_region,
				cve_capital, id_meso_reg
			FROM
				cat_estados";
		if (!is_null($id_estado) && ($id_estado > 0))
		{
			$sql .= " WHERE cve_edo = '" . $id_estado . "'";
		}
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getMunicipios($id_municipio = 0) {
		$sql= "SELECT
				id_municipio, cve_edo, nom_municipio,
				cve_mun_com, cve_reg_admva, distancia_km,
				id_tipo_vida, id_tipo_comision, idmuncabecera,
				zona, area, zona_conurbada,
				exentopago, nom_cab, siglas
			FROM
				cat_municipios";
		if (!is_null($id_municipio) && ($id_municipio > 0))
		{
			$sql .= " WHERE id_municipio = '" . $id_municipio . "'";
		}
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getRegionesAdministrativas($cve_reg_admva = 0) {
		$sql= "SELECT
				cve_reg_admva, cve_edo, nom_reg_admva
			FROM
				cat_reg_admva";
		if (!is_null($cve_reg_admva) && ($cve_reg_admva > 0))
		{
			$sql .= " WHERE cve_reg_admva = '" . $cve_reg_admva . "'";
		}
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getSubregionesAdministrativas($cve_subreg_admva = 0) {
		$sql= "SELECT
				cve_subreg_admva, cve_edo, nom_subreg_admva
			FROM
				cat_subreg_admva";
		if (!is_null($cve_subreg_admva) && ($cve_subreg_admva > 0))
		{
			$sql .= " WHERE cve_subreg_admva = '" . $cve_subreg_admva . "'";
		}
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLocServicesCensus($cve_mun_loc_com) {
		$cve_mun_loc_com = MSSQLAdapter::getInstance()->mysql_escape_mimic($cve_mun_loc_com);
		$sql = "SELECT
				coberturas.cve_mun_loc_com,
				coberturas.anio,
				coberturas.p_total,
				CAST((ISNULL(coberturas.pob_agua, 0) / ISNULL(coberturas.p_total, 0)) * 100 AS DECIMAL(38,2)) AS cob_agua,
				CAST((ISNULL(coberturas.pob_alc, 0) / ISNULL(coberturas.p_total, 0)) * 100 AS DECIMAL(38,2)) AS cob_alc,
				CAST((ISNULL(coberturas.pob_dren, 0) / ISNULL(coberturas.p_total, 0)) * 100 AS DECIMAL(38,2)) AS cob_dren,
				CAST((ISNULL(coberturas.pob_sanea, 0) / ISNULL(coberturas.p_total, 0)) * 100 AS DECIMAL(38,2)) AS cob_sanea,
				ISNULL(marginacion_loc.marginacion, '') AS marginacion
			FROM
				coberturas
			LEFT JOIN
				marginacion_loc ON coberturas.cve_mun_loc_com = marginacion_loc.cve_mun_loc_com
				AND coberturas.anio = marginacion_loc.anio
			WHERE
				coberturas.anio <> '2011'
				AND coberturas.cve_mun_loc_com = '$cve_mun_loc_com'
			ORDER BY
				coberturas.anio";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLocCube() {
		$sql = "SELECT
				top 4000
				cve_reg_admva,
				cve_mun_com,
				cve_loc,
				idreghid,
				idcuenca,
				idsubcuenca,
				 ";
				/*
		$sql.="	cve_region, cve_mun_loc_com, cve_edo, cve_reg_hid,
				cve_cue_hid,
				cve_sub_cue_hid,cve_mun, region, estado, regionAdmiva,municipio,
				regHidro,cuenca, subcuenca, alt,";
				*/
		$sql.="	localidad,
				lat,
				lng,
				p_total,
				pob_agua,
				pob_alc,
				pob_dren,
				pob_sanea
			FROM
				loc_coberturas
			WHERE
				anio = '2010'";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getRegionesHidrologicas($id_sub_cue_hid = 0) {
		$sql = "SELECT
				id_sub_cue_hid, id_reg_hid, cve_reg_hid,
				id_cue_hid, cve_cue_hid, cve_sub_cue_hid,
				nom_sub_cue_hid, anio, pob_total,
				pob_agua, pob_alc, pob_dren,
				pob_sanea, cob_agua, cob_alc,
				cob_dren, cob_sanea
			FROM
				cat_subcuenca_hid";
		if (!is_null($id_sub_cue_hid) && ($id_sub_cue_hid > 0))
		{
			$sql .= " WHERE id_sub_cue_hid = '" . $id_sub_cue_hid . "'";
		}
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLocIds($year) {
		$sql = "SELECT
				cve_mun_loc_com,
				idreghid,
				idcuenca,
				idsubcuenca,
				cve_reg_admva,
				cve_subreg_admva
			FROM
				coberturas
			WHERE
				(anio = '" . $year . "')";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getLocCobs($year) {
		$sql = "SELECT
				p_total,
				ISNULL(pob_agua, 0) AS pob_agua,
				ISNULL(pob_alc, 0) AS pob_alc,
				ISNULL(pob_dren, 0) AS pob_dren, ISNULL(pob_sanea, 0) AS pob_sanea
			FROM
				coberturas
			WHERE
				(anio = '" . $year . "')";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getLocLatLngs($year) {
		$sql = "SELECT
				lat,
				lng
			FROM
				coberturas
			WHERE
				(anio = '" . $year . "')";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getLocKeys($year) {
		$sql = "SELECT
				cve_region,
				cve_reg_hid,
				cve_cue_hid,
				cve_sub_cue_hid
			FROM
				coberturas
			WHERE
				(anio = '" . $year . "')";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getLocNames($year) {
		$sql = "SELECT
				coberturas.localidad
			FROM
				coberturas
			WHERE
				(coberturas.anio = '" . $year . "')";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getLocDistricts($year) {
		$sql = "SELECT
				cat_municipios.nom_municipio,
				cat_reg_admva.nom_reg_admva,
				cat_subreg_admva.nom_subreg_admva
			FROM
				coberturas
			INNER JOIN
				cat_municipios ON coberturas.cve_mun = cat_municipios.id_municipio
			INNER JOIN
				cat_reg_admva ON coberturas.cve_reg_admva = cat_reg_admva.cve_reg_admva
			INNER JOIN
				cat_subreg_admva ON coberturas.cve_subreg_admva = cat_subreg_admva.cve_subreg_admva
			WHERE
				(coberturas.anio = '" . $year . "')";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLocBasins($year) {
		$sql = "SELECT
				cat_cuenca_hid.nom_cue_hid,
				cat_reg_hid.nom_reg_hid,
				cat_subcuenca_hid.nom_sub_cue_hid
			FROM
				coberturas
			INNER JOIN
				cat_cuenca_hid ON coberturas.idcuenca = cat_cuenca_hid.id_cue_hid
			INNER JOIN
				cat_reg_hid ON cat_cuenca_hid.id_reg_hid = cat_reg_hid.id_reg_hid
			INNER JOIN
				cat_subcuenca_hid ON coberturas.idsubcuenca = cat_subcuenca_hid.id_sub_cue_hid
			WHERE
				(coberturas.anio = '" . $year . "')";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLocDetails($cve_mun_loc_com) {
		$sql = "SELECT
					coberturas.cve_mun_loc_com,
					coberturas.anio,
					coberturas.p_total AS ptotal, coberturas.pob_agua, coberturas.pob_alc, coberturas.pob_dren,
					coberturas.pob_sanea, coberturas.cve_edo, coberturas.cve_mun_com, coberturas.cve_mun, coberturas.cve_loc, coberturas.cve_reg_admva,
					coberturas.cve_region, coberturas.idreghid, coberturas.idcuenca, coberturas.idsubcuenca, coberturas.cve_reg_hid, coberturas.cve_cue_hid,
					coberturas.cve_sub_cue_hid, coberturas.region, coberturas.estado, coberturas.regionAdmiva, coberturas.municipio, coberturas.regHidro,
					coberturas.cuenca, coberturas.subcuenca,
					coberturas.localidad, coberturas.lat, coberturas.lng, coberturas.alt, coberturas.cob_aguah,
					coberturas.cob_alch, coberturas.cob_dreh,
					ISNULL(coberturas.cob_sanh, 0) AS cob_sanh,
					coberturas.cob_aguav, coberturas.cob_alcv, coberturas.cob_drev,
					coberturas.prom_ocup
				FROM
					coberturas
				WHERE
					(coberturas.cve_mun_loc_com = '" . $cve_mun_loc_com . "')
				ORDER BY
					anio ASC";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getMenuMunicipios() {
		$sql = "SELECT
				cve_mun_com AS data, nom_municipio AS label
			FROM
				cat_municipios
			WHERE cve_edo = '14'
			ORDER BY nom_municipio";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getMenuLocalidadesMun($cve_mun_com) {
		$sql = "SELECT
				cve_mun_loc_com AS data,
				localidad AS label
			FROM
				v_loc2010
			WHERE
				(cve_mun_com = '" . $cve_mun_com . "')
			ORDER BY
				localidad";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getProyeccionMunicipios() {
		$sql = "SELECT
				idproyeccion,
				cve_mun_com,
				CAST(anio AS VARCHAR(4))  + '-06-30' as anio,
				poblacion
			FROM
				proyecciones_mun";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getProyeccionEstado() {
		$sql = "SELECT
				idproyeccion,
				CAST(cve_edo AS VARCHAR(2))  + '000' as cve_mun_com,
				CAST(anio AS VARCHAR(4))  + '-06-30' as anio,
				poblacion
			FROM
				proyecciones_edo";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLocPop($year) {
		$sql = "SELECT
				p_total
			FROM
				coberturas
			WHERE
				(anio = '" . $year . "')";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getDatosLocalidad($cve_mun_loc_com) {
		$sql = "SELECT
				cve_mun_loc_com, p_total,
				cve_mun_com, cve_reg_admva,
				idreghid, idcuenca,
				idsubcuenca, cve_reg_hid,
				cve_cue_hid, cve_sub_cue_hid,
				regionAdmiva, municipio,
				regHidro, cuenca,
				subcuenca, localidad,
				lat, lng,
				alt
			FROM
				coberturas
			WHERE
				(cve_mun_loc_com = '" . $cve_mun_loc_com . "')
				 AND (anio = '2010')";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getRepdaIds() {
		$sql = "SELECT
				idRepda
			FROM
				Repda";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getRepdaLats() {
		$sql = "SELECT
				lat
			FROM
				Repda";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getRepdaLngs() {
		$sql = "SELECT
				lng
			FROM
				Repda";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getAcuifers() {
		$sql = "SELECT
				id_acuifero, cve_acuifero, cve_edo,
				id_reg_hid, cve_region, id_disp,
				sobreExp, areaKm,
				(das + deficit) AS disponibilidad,
				CONVERT(VARCHAR(10), fechaDofl, 126) AS fechaDofl,
				CONVERT(VARCHAR(10), fechaDofd, 126) AS fechaDofd,
				nom_region, acuifero, disponibilidad
			FROM
				cat_acuiferos";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getAcuifersMenu() {
		$sql = "SELECT
				id_acuifero AS data,
				acuifero AS label
			FROM
				cat_acuiferos
			ORDER BY acuifero";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getIds() {
		$sql = "SELECT
				cve_mun_loc_com
			FROM
				coberturas
			WHERE
				(anio = '2010')";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getRepdaDetail($itemId) {
		$itemId = MSSQLAdapter::getInstance()->mysql_escape_mimic($itemId);
		$sql = "SELECT
				idRepda,
				idTipoObra,
				cve_mun_com,
				cve_uso,
				lat,
				lng,
				prof,
				vol1,
				vol2,
				vol3,
				repdaVolumen,
				mun,
				repdaNombre,
				repdaTitulo,
				repdaDesc,
				reg_hidro,
				c_hidro,
				loc,
				acu_orig,
				acu,
				uso,
				uso1,
				uso2,
				uso3, acuifero,
				repdaFechaAct,
				repdaFechaTitulo
			FROM
				v_repda
			WHERE
				idRepda = '" . $itemId . "'";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getRepdaPublic() {
		$sql = "SELECT
				idRepda, lat, lng, repdaVolumen, repdaTitulo
			FROM
				v_repdaPublico";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getRepdaAgro() {
		$sql = "SELECT
				idRepda, lat, lng, repdaVolumen, repdaTitulo
			FROM
				v_repdaAgro";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getRepdaAgroIds() {
		$sql = "SELECT
				idRepda
			FROM
				v_repdaAgro";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getRepdaAgroLats() {
		$sql = "SELECT
				lat
			FROM
				v_repdaAgro";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getRepdaAgroLngs() {
		$sql = "SELECT
				lng
			FROM
				v_repdaAgro";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getRepdaAgroVols() {
		$sql = "SELECT
				repdaVolumen
			FROM
				v_repdaAgro";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getRepdaAgroTitles() {
		$sql = "SELECT
				repdaTitulo
			FROM
				v_repdaAgro";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getRepdaIndust() {
		$sql = "SELECT
				idRepda, lat, lng, repdaVolumen, repdaTitulo
			FROM
				v_repdaIndust";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getRepdaOthers() {
		$sql = "SELECT
				idRepda, lat, lng, repdaVolumen, repdaTitulo
			FROM
				v_repdaOtros";
		return MSSQLAdapter::getInstance()->getAllRowsArray($sql);
	}

	public function getHidroRegions() {
		$sql = "SELECT
				id_reg_hid,
				cve_reg_hid,
				nom_reg_hid
			FROM
				cat_reg_hid_jal
			ORDER BY
				id_reg_hid";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getHidroRegionBasins($id_reg_hid) {
		$id_reg_hid = MSSQLAdapter::getInstance()->mysql_escape_mimic($id_reg_hid);
		$sql = "SELECT
				id_cue_hid AS idcuenca,
				id_sub_cue_hid AS idsubcuenca,
				cve_sub_cue_hid,
				nom_sub_cue_hid AS subcuenca
			FROM
				cat_subcuenca_hid
			WHERE
				(id_reg_hid = '" . $id_reg_hid . "')
			ORDER BY
				id_reg_hid, id_cue_hid, id_sub_cue_hid";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getBasinSubBasins($idsubcuenca) {
		$idsubcuenca = MSSQLAdapter::getInstance()->mysql_escape_mimic($idsubcuenca);
		$sql = "SELECT
				id_sub_cue_hid AS idsubcuenca,
				id_cue_hid AS idcuenca,
				cve_sub_cue_hid AS cve_subcuenca,
				nom_sub_cue_hid AS subcuenca
			FROM
				cat_subcuenca_hid
			WHERE
				(id_sub_cue_hid = '$idsubcuenca')
			ORDER BY
				id_cue_hid,id_sub_cue_hid";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getDistrictsPresidents() {
		$sql = "SELECT
				presidentes_mun.cve_mun_com, presidentes_mun.partidos,
				presidentes_mun.nombre, presidentes_mun.paterno, presidentes_mun.materno,
				presidentes_mun.titulo, presidentes_mun.foto,
				presidentes_mun.nom_interino, presidentes_mun.pat_interino, presidentes_mun.mat_interino,
				presidentes_mun.tit_interino, presidentes_mun.fot_interino,
				ISNULL(presidentes_mun.id_partido, 0) AS id_partido,
				ISNULL(presidentes_mun.id_partido2, 0) AS id_partido2,
				presidentes_mun.email, presidentes_mun.web,
				presidentes_mun.lada, presidentes_mun.telefono, presidentes_mun.fax,
				presidentes_mun.domicilio_oficial,
				cat_municipios.nom_municipio, cat_reg_admva.nom_reg_admva,
				CONVERT(VARCHAR(10), presidentes_mun.fecha_ini, 103) AS fecha_ini,
				CONVERT(VARCHAR(10), presidentes_mun.fecha_fin, 103) AS fecha_fin
			FROM
				presidentes_mun INNER JOIN
				cat_municipios ON presidentes_mun.cve_mun_com = cat_municipios.cve_mun_com INNER JOIN
				cat_reg_admva ON cat_municipios.cve_reg_admva = cat_reg_admva.cve_reg_admva";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getDistrictsCapitals() {
		$sql = "SELECT
				cve_mun_com, cve_mun_loc_com, p_total,
				FLOOR(lat * 100000) / 100000 AS lat,
				FLOOR(lng * 100000) / 100000 AS lng,
				municipio, localidad
			FROM
				coberturas
			WHERE
				(anio = 2010) AND (cve_loc = '0001') AND (cve_edo = 14)
			ORDER BY
				localidad";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getLocsUrban() {
		$sql = "SELECT
				cve_mun_com, cve_mun_loc_com, p_total,
				lat, lng, municipio, localidad, nivel_pob
			FROM
				v_loc2010_pob
			WHERE
				(p_total > 2499)
			ORDER BY
				p_total";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getAdmRegionDetails($cve_reg_admva) {
		$cve_reg_admva = MSSQLAdapter::getInstance()->mysql_escape_mimic($cve_reg_admva);
		$sql = "SELECT
				cve_reg_admva, cve_edo,
				nom_reg_admva AS regionAdmiva
			FROM
				reg_admva
			WHERE
				anio = '2010'
				AND cve_reg_admva = '$cve_reg_admva'
			ORDER BY
				cve_reg_admva";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getDistrictDetails($cve_mun_com) {
		$cve_mun_com = MSSQLAdapter::getInstance()->mysql_escape_mimic($cve_mun_com);
		$sql = "SELECT
				cve_mun_com, cve_reg_admva, cve_subreg_admva,
				regionAdmiva, subregionAdmiva, municipio,
				localidad, p_total AS p_total_cab
			FROM
				coberturas
			WHERE
				anio = '2010'
				AND cve_loc = '0001'
				AND cve_mun_com = '$cve_mun_com'";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getDistrictServices($cve_mun_com) {
		$cve_mun_com = MSSQLAdapter::getInstance()->mysql_escape_mimic($cve_mun_com);
		$sql = "SELECT
				'' AS cve_mun_loc_com,
				v_CobMunAnio.cve_mun_com, v_CobMunAnio.anio,
				v_CobMunAnio.p_total,
				v_CobMunAnio.cob_agua, v_CobMunAnio.cob_alc,
				v_CobMunAnio.cob_dren, v_CobMunAnio.cob_sanea,
				ISNULL(marginacion.marginacion, '') AS marginacion
			FROM
				v_CobMunAnio
			LEFT JOIN
				marginacion ON v_CobMunAnio.cve_mun_com = marginacion.cve_mun_com
				AND v_CobMunAnio.anio = marginacion.anio
			WHERE
				(v_CobMunAnio.cve_mun_com = '$cve_mun_com')
			ORDER BY
				anio DESC";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getDistrictServicesCensus($cve_mun_com) {
		$cve_mun_com = MSSQLAdapter::getInstance()->mysql_escape_mimic($cve_mun_com);
		$sql = "SELECT
				v_CobMunAnio.cve_mun_com,
				v_CobMunAnio.anio,
				v_CobMunAnio.p_total,
				CAST(v_CobMunAnio.cob_agua AS DECIMAL(38,2)) AS cob_agua,
				CAST(v_CobMunAnio.cob_alc AS DECIMAL(38,2)) AS cob_alc,
				CAST(v_CobMunAnio.cob_dren AS DECIMAL(38,2)) AS cob_dren,
				CAST(v_CobMunAnio.cob_sanea AS DECIMAL(38,2)) AS cob_sanea,
				ISNULL(marginacion.marginacion, '') AS marginacion
			FROM
				v_CobMunAnio
			LEFT JOIN
				marginacion ON v_CobMunAnio.cve_mun_com = marginacion.cve_mun_com
				AND v_CobMunAnio.anio = marginacion.anio
			WHERE
				(v_CobMunAnio.anio <> '2011')
				AND (v_CobMunAnio.cve_mun_com = '$cve_mun_com')
			ORDER BY
				v_CobMunAnio.anio";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getCityServices($cve_mun_loc_com) {
		$cve_mun_loc_com = MSSQLAdapter::getInstance()->mysql_escape_mimic($cve_mun_loc_com);
		$sql = "SELECT
				v_CobLocAnio.cve_mun_loc_com,
				v_CobLocAnio.cve_mun_com, v_CobLocAnio.anio,
				v_CobLocAnio.p_total,
				v_CobLocAnio.cob_agua, v_CobLocAnio.cob_alc,
				v_CobLocAnio.cob_dren, v_CobLocAnio.cob_sanea,
				ISNULL(marginacion_loc.marginacion, '') AS marginacion
			FROM
				v_CobLocAnio
			LEFT JOIN marginacion_loc ON v_CobLocAnio.cve_mun_loc_com = marginacion_loc.cve_mun_loc_com
				AND v_CobLocAnio.anio = marginacion_loc.anio
			WHERE
				(v_CobLocAnio.cve_mun_loc_com = '$cve_mun_loc_com')
			ORDER BY
				v_CobLocAnio.anio DESC";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getDistrictsAdministrativeBasins() {
		$sql = "SELECT
				municipios.cve_mun_com,
				municipios.nom_municipio,
				municipios_cuenca_admva.id_cuenca_admva,
				cat_cuenca_admva.cuenca_admva
			FROM
				municipios
			LEFT JOIN
				municipios_cuenca_admva ON municipios.cve_mun_com = municipios_cuenca_admva.cve_mun_com
			LEFT JOIN
				cat_cuenca_admva ON municipios_cuenca_admva.id_cuenca_admva = cat_cuenca_admva.id
			WHERE
				(municipios.anio = '2010')
			ORDER BY
				nom_municipio";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getDistrictsVerde() {
		$sql = "SELECT
				cve_mun_loc_com AS c, p_total AS p, lat, lng, localidad AS l
			FROM
				INEGI.dbo.coberturas
			WHERE
				(anio = '2010')
				AND (cve_mun_com = '14064')
				-- AND (idcuenca = '16' OR idcuenca = '19')
			ORDER BY
				p_total DESC";
		/*

				AND (cve_mun_com = '14001' OR
				cve_mun_com = '14005' OR
				cve_mun_com = '14008' OR
				cve_mun_com = '14009' OR
				cve_mun_com = '14013' OR
				cve_mun_com = '14016' OR
				cve_mun_com = '14018' OR
				cve_mun_com = '14029' OR
				cve_mun_com = '14030' OR
				cve_mun_com = '14033' OR
				cve_mun_com = '14035' OR
				cve_mun_com = '14039' OR
				cve_mun_com = '14040' OR
				cve_mun_com = '14044' OR
				cve_mun_com = '14045' OR
				cve_mun_com = '14046' OR
				cve_mun_com = '14047' OR
				cve_mun_com = '14050' OR
				cve_mun_com = '14051' OR
				cve_mun_com = '14053' OR
				cve_mun_com = '14055' OR
				cve_mun_com = '14060' OR
				cve_mun_com = '14063' OR
				cve_mun_com = '14066' OR
				cve_mun_com = '14070' OR
				cve_mun_com = '14071' OR
				cve_mun_com = '14073' OR
				cve_mun_com = '14074' OR
				cve_mun_com = '14076' OR
				cve_mun_com = '14078' OR
				cve_mun_com = '14083' OR
				cve_mun_com = '14091' OR
				cve_mun_com = '14093' OR
				cve_mun_com = '14094' OR
				cve_mun_com = '14097' OR
				cve_mun_com = '14098' OR
				cve_mun_com = '14101' OR
				cve_mun_com = '14105' OR
				cve_mun_com = '14109' OR
				cve_mun_com = '14111' OR
				cve_mun_com = '14116' OR
				cve_mun_com = '14117' OR
				cve_mun_com = '14118' OR
				cve_mun_com = '14120' OR
				cve_mun_com = '14123' OR
				cve_mun_com = '14124' OR
				cve_mun_com = '14125')
			ORDER BY
		*/
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getAdmRegServicesCensus($cve_reg_admva) {
		$cve_reg_admva = MSSQLAdapter::getInstance()->mysql_escape_mimic($cve_reg_admva);
		$sql = "SELECT
				cve_reg_admva, anio, p_total,
				cob_agua, cob_alc, cob_dren, cob_sanea
			FROM
				v_CobRegAdmAnio
			WHERE
				anio <> '2011'
				AND cve_reg_admva = '$cve_reg_admva'
			ORDER BY
				v_CobRegAdmAnio.anio";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getAdmBasinDetails($id) {
		$id = MSSQLAdapter::getInstance()->mysql_escape_mimic($id);
		$sql = "SELECT
				id, cuenca_admva
			FROM
				cat_cuenca_admva
			WHERE
				id = '$id'
			ORDER BY
				id";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getAdmBasinServicesCensus($id_cuenca_admva) {
		$id_cuenca_admva = MSSQLAdapter::getInstance()->mysql_escape_mimic($id_cuenca_admva);
		$sql = "SELECT
				id_cuenca_admva,
				anio,
				p_total,
				cob_agua,
				cob_alc,
				cob_dren,
				cob_sanea
			FROM
				v_CobCueAdmAnio
			WHERE
				anio <> '2011'
				AND id_cuenca_admva = '$id_cuenca_admva'
			ORDER BY
				anio";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getBasinDetails($idcuenca) {
		$idcuenca = MSSQLAdapter::getInstance()->mysql_escape_mimic($idcuenca);
		$sql = "SELECT DISTINCT
				idcuenca, cuenca
			FROM
				coberturas
			WHERE
				anio = '2010'
				AND idcuenca = '$idcuenca'
			ORDER BY
				idcuenca";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getBasinServicesCensus($idcuenca) {
		$idcuenca = MSSQLAdapter::getInstance()->mysql_escape_mimic($idcuenca);
		$sql = "SELECT
				idcuenca, anio, p_total,
				cob_agua, cob_alc, cob_dren, cob_sanea
			FROM
				v_CobCueAnio
			WHERE
				anio <> '2011'
				AND idcuenca = '$idcuenca'
			ORDER BY
				anio";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}

	public function getSubBasinServicesCensus($idsubcuenca) {
		$idsubcuenca = MSSQLAdapter::getInstance()->mysql_escape_mimic($idsubcuenca);
		$sql = "SELECT
				idcuenca,
				idsubcuenca, anio, p_total,
				cob_agua, cob_alc, cob_dren, cob_sanea,
				cuenca, subcuenca
			FROM
				v_CobSubCueAnio
			WHERE
				anio <> '2011'
				AND idsubcuenca = '$idsubcuenca'
			ORDER BY
				idcuenca,
				idsubcuenca, anio";
		return MSSQLAdapter::getInstance()->getAllRows($sql);
	}
}