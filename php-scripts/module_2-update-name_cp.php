<?php

require('updater.php');

/** Получаем наш ID статьи из запроса */
$old = $_POST['old'];
$change = $_POST['change'];
$stud_group = $_POST['stud_group'];
$teacher = $_POST['teacher'];
$lesson = $_POST['lesson'];
$module_name = $_POST['module_name'];
$semestr = $_POST['semestr'];

if(!empty($old) && !empty($change) && !empty($stud_group) && !empty($teacher) && !empty($lesson) && !empty($module_name) && !empty($semestr)){
	
	$query = "UPDATE `modules` SET name_cp = '$change' WHERE stud_group = '$stud_group' and teacher = '$teacher' and lesson = '$lesson' and name_cp = '$old' and module_name = '$module_name' and semestr = '$semestr'";
	if($query)
		$result =  mysqli_query($connection, $query) or die(mysqli_error($connection));

	if($result)
		$message = "Контрольная точка изменена";
	else{
		$message = "Контрольная точка не изменилась";
	}

}

$out = array(
	'message' => $message,
	'result' => $result,
	'query' => $query,
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

//$query = "SELECT * FROM `module_first` WHERE student='$student' and stud_group='$stud_group' and teacher='$teacher' and lesson='$lesson' and name_cp='$name_cp' and maxb_cp='$maxb_cp' and student_p='$student_p' and module_name='$module_name' and semestr='$semestr'";

?>


