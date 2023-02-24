<?php

require('connect.php');

/** Получаем наш ID статьи из запроса */
$lesson = $_POST['lesson'];
$group = $_POST['group'];
$semestr = $_POST['semestr'];
$teacher = $_POST['teacher'];

/** Если нам передали ID то обновляем */
if(!empty($lesson) && !empty($group) && !empty($semestr) && !empty($teacher)){

	//извлекаем все записи из таблицы
	$query = $connection->query("SELECT * FROM `students` where students.class = '$group' ORDER BY students.full_name ASC");

	while($row = $query->fetch_assoc()){
		$stud['full_name'][] = $row['full_name'];
	}
	$out['stud'] = $stud;

	$query1 = $connection->query("SELECT * FROM `module_first` where module_first.lesson = '$lesson' and module_first.stud_group = '$group' and module_first.teacher = '$teacher' and module_first.module_name = 'Входной (стартовый) контроль' and module_first.semestr = '$semestr'");
	if (mysqli_num_rows($query1) > 0){
    	while($row1 = $query1->fetch_assoc()) {
    		$entry['student'][] = $row1['student'];
    		$entry['name_cp'][] = $row1['name_cp'];
    		$entry['maxb_cp'][] = $row1['maxb_cp'];
    		$entry['student_p'][] = $row1['student_p'];
    	}
    	$out += ['entry' => $entry];
	}

	$query2 = $connection->query("SELECT COUNT(full_name) FROM `students` WHERE class = '$group'");
	if (mysqli_num_rows($query2) > 0){
    	while($row2 = $query2->fetch_assoc()){
    		$count['count'][] = $row2['COUNT(full_name)'];
    	}
    	$out += ['count' => $count];
	}

	/*$query3 = $connection->query("SELECT row_number() over(ORDER BY full_name) num, full_name, class FROM students WHERE class = '$group'");
	if (mysqli_num_rows($query3) > 0){
    	while ($row3 = $query3->fetch_assoc()) {
    		$number['number'][] = $row3['num'];
    		$number['student'][] = $row3['full_name'];
    		$number['group'][] = $row3['class'];
    	}
	}*/

	$query4 = $connection->query("SELECT * FROM `module_first` where module_first.lesson = '$lesson' and module_first.stud_group = '$group' and module_first.teacher = '$teacher' and module_first.module_name = 'Текущий контроль' and module_first.semestr = '$semestr'");
	if (mysqli_num_rows($query4) > 0){
    	while($row4 = $query4->fetch_assoc()) {
    		$current['student'][] = $row4['student'];
    		$current['name_cp'][] = $row4['name_cp'];
    		$current['maxb_cp'][] = $row4['maxb_cp'];
    		$current['student_p'][] = $row4['student_p'];
    	}
    	$out += ['current' => $current];
	}

	$query5 = $connection->query("SELECT * FROM `module_first` where module_first.lesson = '$lesson' and module_first.stud_group = '$group' and module_first.teacher = '$teacher' and module_first.module_name = 'Рубежный контроль' and module_first.semestr = '$semestr'");
	if (mysqli_num_rows($query5) > 0){
    	while($row5 = $query5->fetch_assoc()) {
    		$control['student'][] = $row5['student'];
    		$control['name_cp'][] = $row5['name_cp'];
    		$control['maxb_cp'][] = $row5['maxb_cp'];
    		$control['student_p'][] = $row5['student_p'];
    	}
    	$out += ['control' => $control];
	}

	$query6 = $connection->query("SELECT * FROM `module_first` where module_first.lesson = '$lesson' and module_first.stud_group = '$group' and module_first.teacher = '$teacher' and module_first.module_name = 'Промежуточная аттестация' and module_first.semestr = '$semestr'");
	if (mysqli_num_rows($query6) > 0){
    	while($row6 = $query6->fetch_assoc()) {
    		$att['student'][] = $row6['student'];
    		$att['name_cp'][] = $row6['name_cp'];
    		$att['maxb_cp'][] = $row6['maxb_cp'];
    		$att['student_p'][] = $row6['student_p'];
    	}
    	$out += ['att' => $att];
    }
    
	$message = 'Все хорошо';

}else{
	$message = 'Не удалось записать и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
/*$out = array(
	'message' => $message,
	'stud' => $stud,
	'entry' => $entry,
	'current' => $current,
	'control' => $control,
	'att' => $att,
	'count' => $count,
	'number' => $number
);*/

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>