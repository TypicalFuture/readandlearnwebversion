<?php

require('../connect-base.php');

$number = $_POST['num'];
$cathedras = $_POST['cathedras'];
$number = intval($number);
$month = date("m");
$year = date("Y");

for ($i=1; $i <= count($cathedras); $i++) {

	$query = $connection->query("SELECT `point` FROM `points` WHERE criteria_num = '$number' and cath_num = '$i' AND month(date) = '". $month ."' AND year(date) = '". $year ."'");
	$p = mysqli_fetch_all($query, MYSQLI_ASSOC);
	$point[$i - 1] = $p[0]['point'];
}

$out = $point;
//var_dump($number);
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);
//var_dump($_POST);

?>