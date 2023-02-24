<?php

require('../connect-base.php');

$mass = $_POST['mass'];
$month = date("m");
$year = date("Y");

if (!empty($mass)) {
	for($i = 0; $i < count($mass); $i++){
		
		$cath_num = $mass[$i]['cath_num'];
		$block_num = $mass[$i]['block_num'];
		$section_num = $mass[$i]['section_num'];
		$criteria_num = $mass[$i]['criteria_num'];
		$criteria_mult = $mass[$i]['criteria_mult'];
		$point = $mass[$i]['point'];
		$now = date('Y-m-d');

		$query = "SELECT * FROM `points` WHERE cath_num='$cath_num' and block_num='$block_num' and section_num='$section_num' and criteria_num='$criteria_num' and point='$point' AND month(date) = $month AND year(date) = $year";
	    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	    if (mysqli_num_rows($result) == 0){

			$message_q[$i] = "Таких строк нет";
			$query2 = "SELECT * FROM `points` WHERE cath_num='$cath_num' and block_num='$block_num' and section_num='$section_num' and criteria_num='$criteria_num' AND month(date) = $month AND year(date) = $year";
			$result2 = mysqli_query($connection, $query2) or die(mysqli_error($connection));

	    	if (mysqli_num_rows($result2) > 0){
	    		$message_p[$i] = "Есть строка, но с другой оценкой";
	    		$query3 = "UPDATE `points` SET point = '$point', date = '$now' WHERE cath_num='$cath_num' and block_num='$block_num' and section_num='$section_num' and criteria_num='$criteria_num' AND month(date) = $month AND year(date) = $year";

	    		if ($query3)
		        {
		            mysqli_query($connection, $query3);
		            $message_p[$i] = 'Обновлено';    
		        }
	    	}

	    	else{
	    		$query4 = "INSERT INTO `points`(`id`, `cath_num`, `block_num`, `section_num`, `criteria_num`, `criteria_mult`, `point`, `date`) VALUES (NULL , '$cath_num', '$block_num', '$section_num', '$criteria_num', '$criteria_mult', '$point', '$now')";
	    		$result4 = mysqli_query($connection, $query4) or die(mysqli_error($connection));
	    	}
		}

		else{
			$message[$i] = $mass[$i]["point"]." есть в массиве";
		}
	}
}

$out = array(
	'message' => $message,
	'msg1' => $message_q,
	'msg2' => $message_p,
	'mass' => $mass,
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>