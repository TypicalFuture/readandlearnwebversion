<?php

require('connect.php');
$query;
$fn = $_POST['fn'];
$group = $_POST['group'];
$sem = $_POST['sem'];
$message = '';

if(!empty($fn) && !empty($group) && !empty($sem)){

	$query = $connection->query("SELECT DISTINCT(lesson) as lesson FROM `module_first` WHERE stud_group = '$group' and semestr = '$sem'  ORDER BY `module_first`.`lesson` ASC");
	if (mysqli_num_rows($query) > 0){

		while($row = $query->fetch_assoc()){
			$lesson['lesson'][] = $row['lesson'];
		}

		$out['lesson'] = $lesson;


		for($i = 0; $i < count($out['lesson']['lesson']); $i++){
			$query = $connection->query("SELECT * FROM `module_first` WHERE student = '$fn' and semestr = '$sem' and lesson = '".$out['lesson']['lesson'][$i]."' ORDER BY `module_first`.`lesson` ASC");
		}

		//$row_cnt = $query->num_rows;

		//if($row_cnt > 0){

		for($i = 0; $i < count($out['lesson']['lesson']); $i++){
			$query = $connection->query("SELECT SUM(student_p) as sum_p, lesson FROM `module_first` WHERE student = '$fn' and semestr = '$sem' and lesson = '".$out['lesson']['lesson'][$i]."' ORDER BY `module_first`.`lesson` ASC");
			while ($row = $query->fetch_assoc()){
				$module_1['sum_p'][] = floatval($row['sum_p']);
			}
		}
		if(!empty($module_1)){
			$out['lesson']['stud_p'] = $module_1['sum_p'];
			$message = 'ok';
		}
		else{
			$message = 'no';
		}

		for($i = 0; $i < count($out['lesson']['lesson']); $i++){
			$query = $connection->query("SELECT SUM(student_p) as sum_p, lesson FROM `modules` WHERE student = '$fn' and semestr = '$sem' and lesson = '".$out['lesson']['lesson'][$i]."' and module_name = 'Модуль 2' ORDER BY `modules`.`lesson` ASC");
			while ($row = $query->fetch_assoc()){
				$module_2['sum_p'][] = floatval($row['sum_p']);
			}
		}
		if(!empty($module_2)){
			for ($i=0; $i < count($out['lesson']['stud_p']) ; $i++) { 
				$out['lesson']['stud_p'][$i] += $module_2['sum_p'][$i];	
			}
			$message = 'ok';
		}
		else{
			$message = 'no';
		}

		for($i = 0; $i < count($out['lesson']['lesson']); $i++){
			$query = $connection->query("SELECT SUM(student_p) as sum_p, lesson FROM `modules` WHERE student = '$fn' and semestr = '$sem' and lesson = '".$out['lesson']['lesson'][$i]."' and module_name = 'Модуль 3' ORDER BY `modules`.`lesson` ASC");
			while ($row = $query->fetch_assoc()){
				$module_3['sum_p'][] = floatval($row['sum_p']);
			}
		}
		if(!empty($module_3)){
			for ($i=0; $i < count($out['lesson']['stud_p']) ; $i++) { 
				$out['lesson']['stud_p'][$i] += $module_3['sum_p'][$i];	
			}
			$message = 'ok';
		}
		else{
			$message = 'no';
		}

		for($i = 0; $i < count($out['lesson']['lesson']); $i++){
			$query = $connection->query("SELECT SUM(student_p) as sum_p, lesson FROM `modules` WHERE student = '$fn' and semestr = '$sem' and lesson = '".$out['lesson']['lesson'][$i]."' and module_name = 'Модуль 4' ORDER BY `modules`.`lesson` ASC");
			while ($row = $query->fetch_assoc()){
				$module_4['sum_p'][] = floatval($row['sum_p']);
			}
		}
		if(!empty($module_4)){
			for ($i=0; $i < count($out['lesson']['stud_p']) ; $i++) { 
				$out['lesson']['stud_p'][$i] += $module_4['sum_p'][$i];	
			}
			$message = 'ok';
		}
		else{
			$message = 'no';
		}

		for($i = 0; $i < count($out['lesson']['lesson']); $i++){
			$query = $connection->query("SELECT SUM(student_p) as sum_p, lesson FROM `modules` WHERE student = '$fn' and semestr = '$sem' and lesson = '".$out['lesson']['lesson'][$i]."' and module_name = 'Модуль 5' ORDER BY `modules`.`lesson` ASC");
			while ($row = $query->fetch_assoc()){
				$module_5['sum_p'][] = floatval($row['sum_p']);
			}
		}
		if(!empty($module_5)){
			for ($i=0; $i < count($out['lesson']['stud_p']) ; $i++) { 
				$out['lesson']['stud_p'][$i] += $module_5['sum_p'][$i];	
			}
			$message = 'ok';
		}
		else{
			$message = 'no';
		}

		for($i = 0; $i < count($out['lesson']['lesson']); $i++){
			$query = $connection->query("SELECT SUM(student_p) as sum_p, lesson FROM `modules` WHERE student = '$fn' and semestr = '$sem' and lesson = '".$out['lesson']['lesson'][$i]."' and module_name = 'Модуль 6' ORDER BY `modules`.`lesson` ASC");
			while ($row = $query->fetch_assoc()){
				$module_6['sum_p'][] = floatval($row['sum_p']);
			}
		}
		if(!empty($module_6)){
			for ($i=0; $i < count($out['lesson']['stud_p']) ; $i++) { 
				$out['lesson']['stud_p'][$i] += $module_6['sum_p'][$i];	
			}
			$message = 'ok';
		}
		else{
			$message = 'no';
		}

		for($i = 0; $i < count($out['lesson']['lesson']); $i++){
			$query = $connection->query("SELECT SUM(student_p) as sum_p, lesson FROM `modules` WHERE student = '$fn' and semestr = '$sem' and lesson = '".$out['lesson']['lesson'][$i]."' and module_name = 'Модуль 7' ORDER BY `modules`.`lesson` ASC");
			while ($row = $query->fetch_assoc()){
				$module_7['sum_p'][] = floatval($row['sum_p']);
			}
		}
		if(!empty($module_7)){
			for ($i=0; $i < count($out['lesson']['stud_p']) ; $i++) { 
				$out['lesson']['stud_p'][$i] += $module_7['sum_p'][$i];	
			}
			$message = 'ok';
		}
		else{
			$message = 'no';
		}

	}
	else{
		$message = 'no';
	}
}
else{
	$message = 'no';
}

$out['lesson']['message'] = $message;

header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>