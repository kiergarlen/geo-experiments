<?php
$id = (isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] > 0  && $_GET["id"] < 9999) ? $_GET["id"] : 19;
$y = (isset($_GET["y"]) && is_numeric($_GET["y"]) && $_GET["y"] > 2000  && $_GET["y"] < 9999) ? $_GET["y"] : 2014;
$yesterDate = ($y - 3) . "-12-31";
include_once ("DALSiaej.php");
$data = DALSIaej::getInstance()->getDamHistory($id, $yesterDate);
$l = count($data);
if ($l > 0)
{
	echo '[';
	for ($i = 0; $i < $l; $i++) {
		echo "{";
		echo '"d":"' . $data[$i]['fecha'] . '",';
		echo '"v":' . $data[$i]['volumen'] . '}';
		if ($i < ($l - 1)) {
			echo ',';
		}
	}
	echo ']';
}