<?php

require('updater.php');

/** Получаем наш ID статьи из запроса */
$all = $_POST['all'];

if(!empty($all)){
	for($i = 0; $i < count($all); $i++){
		$student = $all[$i]["student"];
		$stud_group = $all[$i]["stud_group"];
		$teacher = $all[$i]["teacher"];
		$lesson = $all[$i]["lesson"];
		$name_cp = $all[$i]["name_cp"];
		$maxb_cp = floatval($all[$i]["maxb_cp"]);
		$student_p = floatval($all[$i]["student_p"]);
		$module_name = $all[$i]["module_name"];
		$semestr = intval($all[$i]["semestr"]);
		
		$query = "SELECT * FROM `module_first` WHERE student='$student' and stud_group='$stud_group' and teacher='$teacher' and lesson='$lesson' and name_cp='$name_cp' and maxb_cp='$maxb_cp' and student_p='$student_p' and module_name='$module_name' and semestr='$semestr'";
	    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	    if (mysqli_num_rows($result) == 0){

			$message_q[$i] = "Таких строк нет";
			$query2 = "SELECT * FROM `module_first` WHERE student='$student' and stud_group='$stud_group' and teacher='$teacher' and lesson='$lesson' and name_cp='$name_cp' and module_name='$module_name' and semestr='$semestr'";
			$result2 = mysqli_query($connection, $query2) or die(mysqli_error($connection));

	    	if (mysqli_num_rows($result2) > 0){
	    		$message_p[$i] = "Есть строка, но с другой оценкой";
	    		$query3 = "UPDATE `module_first` SET student_p = '$student_p' WHERE student='$student' and stud_group='$stud_group' and teacher='$teacher' and lesson='$lesson' and name_cp='$name_cp' and module_name='$module_name' and semestr='$semestr'";
	    		//$result3 =  mysqli_query($connection, $query3) or die(mysqli_error($connection));

	    		$query5 = "UPDATE `module_first` SET maxb_cp = '$maxb_cp' WHERE student='$student' and stud_group='$stud_group' and teacher='$teacher' and lesson='$lesson' and name_cp='$name_cp' and student_p='$student_p' and module_name='$module_name' and semestr='$semestr'";
	    		//$result5 =  mysqli_query($connection, $query3) or die(mysqli_error($connection));

	    		if ($query3)
		        {
		            mysqli_query($connection, $query3);
		        }
		        if ($query5){
		            mysqli_query($connection, $query5);
		        }
	    	}

	    	else{
	    		$query4 = "INSERT INTO `module_first`(`id`, `student`, `stud_group`, `teacher`, `lesson`, `name_cp`, `maxb_cp`, `student_p`, `module_name`, `semestr`) VALUES (null, '$student', '$stud_group', '$teacher', '$lesson', '$name_cp', '$maxb_cp', '$student_p', '$module_name', '$semestr')";
	    		$result4 = mysqli_query($connection, $query4) or die(mysqli_error($connection));
	    	}
		}

		else{
			$message[$i] = $all[$i]["lesson"]." есть в массиве";
		}
	}
}

$out = array(
	'message' => $message,
	'msg1' => $message_q,
	'msg2' => $message_p,
	'all' => $all,
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

//$query = "SELECT * FROM `module_first` WHERE student='$student' and stud_group='$stud_group' and teacher='$teacher' and lesson='$lesson' and name_cp='$name_cp' and maxb_cp='$maxb_cp' and student_p='$student_p' and module_name='$module_name' and semestr='$semestr'";

?>


