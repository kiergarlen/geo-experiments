<?php
header("Content-type:text/html; charset=utf-8");
$id = (isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] > 0  && $_GET["id"] < 9999) ? $_GET["id"] : 19;

include_once ("../../amfphp/services/DALSiaej.php");
$details = DALSiaej::getInstance()->getDamDetails($id);
$name =  ($details["act_semanal"] < 1) ? $details["nombre"] : $details["nombre"] . " *";
$maxVol = (isset($details["cap_total_namo"]) && $details["cap_total_namo"] > 0) ? $details["cap_total_namo"] : 1;
$maxVolStr = number_format($maxVol, 3);
$district = $details["municipio"];
$elevNamo = number_format($details["elev_namo"], 2);
$elevName = number_format($details["elev_name"], 2);
$elevNamin = number_format($details["elev_namin"], 2);
$area = number_format($details["superf_ha"], 2);
$latLngStr = $details["lat"] . "&deg;, " . $details["lng"] . "&deg;";


$today = DALSiaej::getInstance()->getDamVolLatest($id);
$todayVol = (isset($today["volumen"]) && $today["volumen"] > 0) ? $today["volumen"] :0;
$todayVolStr = number_format($todaVol, 3);
$todayPercent = ($todayVol > 0) ? ($todayVol / $maxVol) * 100 : 0;
$todayPercentStr = number_format($todayPercent);

$take = (isset($today["obra_toma"]) && $today["obra_toma"] > -1) ? number_format($today["obra_toma"], 3) : " [Sin dato] ";
$vert = (isset($today["vertedor"]) && $today["vertedor"] > -1) ? number_format($today["vertedor"], 3) : " [Sin dato] ";
$bandas = (isset($today["cve_bandas"]) && strlen($today["cve_bandas"]) > 0) ? $today["cve_bandas"] : " [Sin dato] ";
$use = (isset($today["uso_principal"]) && strlen($today["uso_principal"]) > 0) ? $today["uso_principal"] : " [Sin dato] ";

if (isset($today["fecha"])) {
	$date = $today["fecha"];
	$todayArray = explode("-", $date);
	$lastYearDate = $todayArray[0] -1 . "-" . $todayArray[1] . "-" . $todayArray[2];
	$lastYearPercent = 0;
	$volLastYear = DALSiaej::getInstance()->getDamVolDate($id, $lastYearDate);
	if (isset($volLastYear)) {
		$lastYear = $volLastYear[0];
		$lastVol = (isset($lastYear["volumen"]) && $lastYear["volumen"] > 0) ? $lastYear["volumen"] : 0;
		$lastVolStr = number_format($lastVol, 3);
		$lastYearPercent = ($lastVol > 0) ? ($lastVol / $maxVol) * 100 : 0;
		$lastYearPercentStr = number_format($lastYearPercent, 3);

		$result = array(
			array(
				'id' => $id,
				'label' => $todayArray[0] -1,
				'v' => $lastYearPercent,
				'vMax' => $maxVol,
				'dif' => ($lastYearPercent < 100) ? 100 - $lastYearPercent : 0,
				'd' => $lastYearDate
			),
			array(
				'id' => $id,
				'label' => $todayArray[0],
				'v' => $todayPercent,
				'vMax' => $maxVol,
				'dif' => ($todayPercent < 100) ? 100 - $todayPercent : 0,
				'd' => $date
			)
		);
		$levelsJson = json_encode($result);
	}
}
?>
<!doctype html>
<html lang="es">
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes"/>
	<meta charset="utf-8"/>
	<title>Presa</title>
	<link href="favicon.ico" rel="shortcut icon" />
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="../../css/bootstrap3.min.css"/>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<small class="text-muted">Presa</small>
			<small class="label label-default"><?php echo $id;?></small><br>
			<h3 class="page-header"><?php echo $name;?>
			<small><?php echo $district;?></small></h3>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-5">
			<h5>Volumen y datos operacionales</h5>
			Volumen máximo: <strong>
			<?php echo $maxVolStr;?>
			</strong> Mm3 (100%) <sub class="text-muted">NAMO</sub><br>
			Vol. actual: <strong>
			<?php echo $todayVolStr;?>
			</strong> Mm3
			(<?php echo $todayPercentStr;?>%)
			<span class="text-muted"><?php echo $date;?></span><br>
			Vol. año anterior: <strong>
			<?php echo $lastVolStr;?>
			</strong> Mm3
			(<?php echo $lastYearPercentStr;?>%)
			<span class="text-muted"><?php echo $lastYearDate;?></span><br>
			Obra de toma: <strong>
			<?php echo $take;?>
			</strong> m3/s<br>
			Vertedor: <strong>
			<?php echo $vert;?>
			</strong> m3/s<br>
		</div>
		<div class="col-xs-12 col-sm-7">
			<h5>Elevaciones y área inundada</h5>
			<abbr title="Nivel de Aguas Máximas Ordinarias">NAMO</abbr>: <strong>
			<?php echo $elevNamo;?>
			</strong> msnm<br>
			<abbr title="Nivel de Aguas Máximas Extraordinarias">NAME</abbr>: <strong>
			<?php echo $elevName;?>
			</strong> msnm<br>
			<abbr title="Nivel de Aguas Mínimo">NAMIN</abbr>: <strong>
			<?php echo $elevNamin;?>
			</strong> msnm<br>
			Área inundada: <strong>
			<?php echo $area;?>
			</strong> ha<br>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-5">
			<h5>Ubicación</h5>
			Municipio: <strong>
			<?php echo $district;?>
			</strong><br>
			Ubicación: <strong>
			<?php echo $latLngStr;?>
			</strong><br>
		</div>
		<div class="col-xs-12 col-sm-7">
			<h5>Otros</h5>
			Clave <abbr title="Banco Nacional de Datos de Aguas Superficiales">BANDAS</abbr>: <strong>
			<?php echo $bandas;?>
			</strong><br>
			Uso principal: <strong>
			<?php echo $use;?>
			</strong><br>
			Fecha actualización: <strong>
			<?php echo $date;?>
			</strong><br>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<small class="text-muted">* Indica actualización semanal</small>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12" id ="levelChart">
		</div>
	</div>
	<br><br>
	<div class="row">
		<div class="col-xs-12" id ="historyChart">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<small class="text-muted">Fuente: CONAGUA, con análisis de CEA Jalisco.<br></small>
		</div>
	</div>
</div>
	<script src="../../js/jquery-1.9.1.js"></script>
	<script src="../../js/highcharts.js"></script>
	<script src="../../js/highcharts_lightgray.js"></script>
	<script>
		var levelsData = <?php echo $levelsJson;?>;
	</script>
	<script src="../../js/chart/damLevels.js"></script>
</body>
</html>