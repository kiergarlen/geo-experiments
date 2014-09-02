<?php
header('Content-type:text/html; charset=utf-8');
$id=14039;
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0  && $_GET['id'] < 14999) {
	$id= $_GET['id'];
}
include_once ("../../amfphp/services/DALInegi.php");
$data = DALInegi::getInstance()->getDistrictDetails($id);
$servicesData = DALInegi::getInstance()->getDistrictServicesCensus($id);
$details = $data[0];
$i = count($servicesData) - 1;
$latestServices = $servicesData[$i];
?>
<!doctype html>
<html lang="es">
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes"/>
	<meta charset="utf-8"/>
	<title>Municipio</title>
	<link href="favicon.ico" rel="shortcut icon" />
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="../../css/bootstrap3.min.css"/>
</head>
<body>
<div class="container">
	<small class="text-muted">Municipio</small>
	<small class="label label-default"><?php echo $id;?></small><br>
	<h3 class="page-header"><?php echo utf8_encode($details['municipio']);?>
	<small>Jalisco</small></h3>
	Habitantes: <strong>
	<?php echo number_format($latestServices['p_total'], 0);?>
	</strong><br>
	Región administrativa: <strong>
	<?php echo utf8_encode($details['regionAdmiva']);?>
	</strong><br>
	Región hidrológica-administrativa: <strong>
	<?php echo utf8_encode($details['subregionAdmiva']);?>
	</strong><br>
	Cabecera: <strong>
	<?php echo utf8_encode($details['localidad']);?>
	</strong><br>
	Habitantes cabecera: <strong>
	<?php echo number_format($details['p_total_cab'], 0);
	$popPercentCapital = round(($details['p_total_cab'] / $latestServices['p_total']) * 100);
	?>
	</strong><br>
	<!--
	Habitantes del mpio. en la cabecera:<br>
	<div class="progress">
		<div class="progress-bar
		<?php
		if($popPercentCapital < 51) {
			echo ' progress-bar-success ';
		} else if($popPercentCapital < 80) {
			echo ' progress-bar-warning ';
		} else if($popPercentCapital > 80) {
			echo ' progress-bar-danger ';
		}
		?>
		" role="progressbar"
			aria-valuenow="<?php echo $popPercentCapital; ?>"
			aria-valuemin="0"
			aria-valuemax="100"
			style="width:<?php echo $popPercentCapital; ?>%;">
			<?php echo $popPercentCapital; ?>%
		</div>
	</div>
	-->
	<h4>Coberturas de servicios</h4>
	<div class="row">
		<div class="pull-left col-xs-2">Agua</div>
		<div class="col-xs-10">
			<div class="progress">
				<div class="progress-bar progress-bar-info"
					role="progressbar"
					aria-valuenow="<?php echo $servicesData[$i]['cob_agua']; ?>"
					aria-valuemin="0"
					aria-valuemax="100"
					style="width:<?php echo $servicesData[$i]['cob_agua']; ?>%;">
					<?php echo $servicesData[$i]['cob_agua']; ?>%
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="pull-left col-xs-2">Alcantarillado</div>
		<div class="col-xs-10">
			<div class="progress">
				<div class="progress-bar progress-bar-danger"
					role="progressbar"
					aria-valuenow="<?php echo $servicesData[$i]['cob_alc']; ?>"
					aria-valuemin="0"
					aria-valuemax="100"
					style="width:<?php echo $servicesData[$i]['cob_alc']; ?>%;">
					<?php echo $servicesData[$i]['cob_alc']; ?>%
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="pull-left col-xs-2">Saneamiento</div>
		<div class="col-xs-10">
			<div class="progress">
				<div class="progress-bar progress-bar-warning"
					role="progressbar"
					aria-valuenow="<?php echo $servicesData[$i]['cob_sanea']; ?>"
					aria-valuemin="0"
					aria-valuemax="100"
					style="width:<?php echo $servicesData[$i]['cob_sanea']; ?>%;">
					<?php echo $servicesData[$i]['cob_sanea']; ?>%
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<table class="table table-bordered table-hover table-condensed">
			<tr>
				<th>Año</th>
				<th>Habitantes</th>
				<th>Agua (%)</th>
				<th>Alcantarillado (%)</th>
				<th>Saneamiento (%)</th>
				<th>Marginación</th>
			</tr>
			<tbody>
			<?php for($i = 0; $i < count($servicesData); $i++) { ?>
				<tr>
					<td><?php echo $servicesData[$i]['anio']; ?></td>
					<td><?php echo number_format($servicesData[$i]['p_total'], 0); ?></td>
					<td><?php echo $servicesData[$i]['cob_agua']; ?></td>
					<td><?php echo $servicesData[$i]['cob_alc']; ?></td>
					<td><?php echo $servicesData[$i]['cob_sanea']; ?></td>
					<td><?php echo utf8_encode($servicesData[$i]['marginacion']); ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<small class="text-muted">Fuente: Datos estadísticos de INEGI <?php
	echo $latestServices['anio'];?> con interpretación de CEA Jalisco</small>
</div>
</body>
</html>