<?php

require('../connect-base.php');

$cathedras = $_POST['cathedras'];

$sql1 = "SELECT * FROM blocks ORDER BY `blocks`.`block_num` ASC";
$result1 = mysqli_query($connection, $sql1);
$blocks = mysqli_fetch_all($result1, MYSQLI_ASSOC);
$month = date("m");
$year = date("Y");


for ($i=0; $i < count($cathedras); $i++) {

	$poin = [];

	for ($j=0; $j < count($blocks); $j++) { 
		
		$poin[$j] = 0;
		$sql = $connection->query("SELECT * FROM points WHERE block_num = '". $blocks[$j]['block_num'] ."' AND cath_num = '". ($i +1) ."' AND month(date) = '". $month ."' AND year(date) = '". $year ."'");

		if (mysqli_num_rows($sql) > 0) {
			$point = mysqli_fetch_all($sql, MYSQLI_ASSOC);
		    for ($l=0; $l < count($point); $l++) { 
		    	if ($point[$l]['section_num'] == 'Штрафные баллы') {
		    		$poin[$j] -= $point[$l]['point'] * $point[$l]['criteria_mult'];
		    	}
		    	else
	    			$poin[$j] += $point[$l]['point'] * $point[$l]['criteria_mult'];
	    	}

		}

	}	

	$points[$i] = $poin;
}

$out[0] = $points;
$out[1] = $blocks;
//var_dump($number);
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>