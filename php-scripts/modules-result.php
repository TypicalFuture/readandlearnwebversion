<?php

require('connect.php');

$lesson = $_POST['lesson'];
$group = $_POST['group'];
$semestr = $_POST['semestr'];
$teacher = $_POST['teacher'];

if(!empty($lesson) && !empty($group) && !empty($semestr) && !empty($teacher)){

	$query = $connection->query("SELECT * FROM `students` where students.class = '$group' ORDER BY students.full_name ASC");

	while($row = $query->fetch_assoc()){
		$stud['full_name'][] = $row['full_name'];
	}

	$out['stud'] = $stud;

	for($i = 0; $i < count($stud['full_name']); $i++){
		$query = $connection->query("SELECT student, SUM(student_p) as sum_p FROM `module_first` WHERE student = '".$stud['full_name'][$i]."'");
		while ($row = $query->fetch_assoc()){
			$module_1['sum_p'][] = floatval($row['sum_p']);
		}
	}
	if(!empty($module_1)){
		$out['stud_p'] = $module_1['sum_p'];
	}

	for($i = 0; $i < count($stud['full_name']); $i++){
		$query = $connection->query("SELECT student, SUM(student_p) as sum_p FROM `modules` WHERE student = '".$stud['full_name'][$i]."' and module_name = 'Модуль 2'");
		while ($row = $query->fetch_assoc()){
			$module_2['sum_p'][] = floatval($row['sum_p']);
		}
	}
	if(!empty($module_2)){
		$out['stud_p2'] = $module_2['sum_p'];
	}

	for($i = 0; $i < count($stud['full_name']); $i++){
		$query = $connection->query("SELECT student, SUM(student_p) as sum_p FROM `modules` WHERE student = '".$stud['full_name'][$i]."' and module_name = 'Модуль 3'");
		while ($row = $query->fetch_assoc()){
			$module_3['sum_p'][] = floatval($row['sum_p']);
		}
	}
	if(!empty($module_3)){
		$out['stud_p3'] = $module_3['sum_p'];
	}

	for($i = 0; $i < count($stud['full_name']); $i++){
		$query = $connection->query("SELECT student, SUM(student_p) as sum_p FROM `modules` WHERE student = '".$stud['full_name'][$i]."' and module_name = 'Модуль 4'");
		while ($row = $query->fetch_assoc()){
			$module_4['sum_p'][] = floatval($row['sum_p']);
		}
	}
	if(!empty($module_4)){
		$out['stud_p4'] = $module_4['sum_p'];
	}

	for($i = 0; $i < count($stud['full_name']); $i++){
		$query = $connection->query("SELECT student, SUM(student_p) as sum_p FROM `modules` WHERE student = '".$stud['full_name'][$i]."' and module_name = 'Модуль 5'");
		while ($row = $query->fetch_assoc()){
			$module_5['sum_p'][] = floatval($row['sum_p']);
		}
	}
	if(!empty($module_5)){
		$out['stud_p5'] = $module_5['sum_p'];
	}

	for($i = 0; $i < count($stud['full_name']); $i++){
		$query = $connection->query("SELECT student, SUM(student_p) as sum_p FROM `modules` WHERE student = '".$stud['full_name'][$i]."' and module_name = 'Модуль 6'");
		while ($row = $query->fetch_assoc()){
			$module_6['sum_p'][] = floatval($row['sum_p']);
		}
	}
	if(!empty($module_6)){
		$out['stud_p6'] = $module_6['sum_p'];
	}

	for($i = 0; $i < count($stud['full_name']); $i++){
		$query = $connection->query("SELECT student, SUM(student_p) as sum_p FROM `modules` WHERE student = '".$stud['full_name'][$i]."' and module_name = 'Модуль 7'");
		while ($row = $query->fetch_assoc()){
			$module_7['sum_p'][] = floatval($row['sum_p']);
		}
	}
	if(!empty($module_7)){
		$out['stud_p7'] = $module_7['sum_p'];
	}
}

header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>