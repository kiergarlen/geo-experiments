<?php
header('Content-type:text/html; charset=utf-8');
$id=2;
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0  && $_GET['id'] < 999) {
	$id= $_GET['id'];
}
include_once ("../../amfphp/services/DALInegi.php");
$servicesData = DALInegi::getInstance()->getSubBasinServicesCensus($id);
$i = count($servicesData) - 1;
$latestServices = $servicesData[$i];
?>
<!doctype html>
<html lang="es">
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
	<meta charset="utf-8"/>
	<title>Subcuenca hidrológica</title>
	<link href="favicon.ico" rel="shortcut icon"/>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="../../css/bootstrap3.min.css"/>
</head>
<body>
<div class="container">
	<small class="text-muted">Subcuenca hidrológica</small>
	<small class="label label-default"><?php echo $id;?></small><br>
	<h3 class="page-header"><?php echo utf8_encode($latestServices['subcuenca']);?>
	<small><?php echo utf8_encode($latestServices['cuenca']);?></small></h3>
	Habitantes: <strong>
	<?php echo number_format($latestServices['p_total'], 0);?>
	</strong><br>
	<h4>Coberturas de servicios</h4>
	<div class="row">
		<div class="pull-left col-xs-2">Agua</div>
		<div class="col-xs-10">
			<div class="progress">
				<div class="progress-bar progress-bar-info"
					role="progressbar"
					aria-valuenow="<?php echo $servicesData[$i]['cob_agua'];?>"
					aria-valuemin="0"
					aria-valuemax="100"
					style="width:<?php echo $servicesData[$i]['cob_agua'];?>%;">
					<?php echo $servicesData[$i]['cob_agua'];?>%
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
					aria-valuenow="<?php echo $servicesData[$i]['cob_alc'];?>"
					aria-valuemin="0"
					aria-valuemax="100"
					style="width:<?php echo $servicesData[$i]['cob_alc'];?>%;">
					<?php echo $servicesData[$i]['cob_alc'];?>%
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
					aria-valuenow="<?php echo $servicesData[$i]['cob_sanea'];?>"
					aria-valuemin="0"
					aria-valuemax="100"
					style="width:<?php echo $servicesData[$i]['cob_sanea'];?>%;">
					<?php echo $servicesData[$i]['cob_sanea'];?>%
				</div>
			</div>
		</div>
	</div>
	<table class="table table-bordered table-hover table-condensed">
		<tr>
			<th>Año</th>
			<th>Habitantes</th>
			<th>Agua (%)</th>
			<th>Alcantarillado (%)</th>
			<th>Saneamiento (%)</th>
		</tr>
		<tbody>
		<?php for($i = 0; $i < count($servicesData); $i++) {?>
			<tr>
				<td><?php echo $servicesData[$i]['anio'];?></td>
				<td><?php echo number_format($servicesData[$i]['p_total'], 0);?></td>
				<td><?php echo $servicesData[$i]['cob_agua'];?></td>
				<td><?php echo $servicesData[$i]['cob_alc'];?></td>
				<td><?php echo $servicesData[$i]['cob_sanea'];?></td>
			</tr>
		<?php }?>
		</tbody>
	</table>
	<small class="text-muted">Fuente: Datos estadísticos de INEGI <?php
	echo $latestServices['anio'];?> con interpretación de CEA Jalisco</small>
</div>
</body>
</html>