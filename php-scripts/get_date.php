<?php

require('../connect-base.php');

$month = intval($_POST['month']);
$year = intval($_POST['year']);

$sql = "SELECT month(MIN(date)) as min_month, year(MIN(date)) as min_year FROM points";
$result = mysqli_query($connection, $sql);
$date1 = mysqli_fetch_all($result, MYSQLI_ASSOC);

$date = $date1[0];

$i = 0;
$m[$i] = intval($date['min_month']);
$y[$i] = intval($date['min_year']);


$i++;

while (($m[$i-1] != $month)||($y[$i-1] != $year)){

	if ($m[$i-1] != 12) {

		$m[$i] = $m[$i-1]+1;
		$y[$i] = $y[$i-1];
	}
	else{
		$m[$i] = 1;
		$y[$i] = $y[$i-1]+1;
	}

	$i++;
}



$mnt = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];

for($j = 0; $j < $i; $j++){
	$m[$j] = $mnt[$m[$j]-1];
}
//var_dump($m);

$out[0] = $m;
$out[1] = $y;

header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>