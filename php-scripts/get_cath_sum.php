<?php

require('../connect-base.php');

$sql = "SELECT * FROM `cathedr`";
$result = mysqli_query($connection, $sql);
$cath = mysqli_fetch_all($result, MYSQLI_ASSOC);

for ($i=0; $i <= count($cath); $i++) {

	$sql = "SELECT SUM(point * criteria_mult) as points FROM `points` WHERE cath_num = 1 AND section_num = 'Штрафные баллы'";
	$result = mysqli_query($connection, $sql);
	$sum_fail = mysqli_fetch_all($result, MYSQLI_ASSOC);
	$out = $sum_fail;

}

//var_dump($number);
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>