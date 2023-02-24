<?php

require('updater.php');

/** Получаем наш ID статьи из запроса */

$teacher = $_POST['teacher'];
$timeF = $_POST['timeF'];
$timeT = $_POST['timeT'];
$lessn = $_POST['less'];
$group = $_POST['grp'];
$fio = $_POST['fio'];
$dateF = $_POST['dateF'];
$dateT = $_POST['dateT'];
$day = $_POST['day'];

//var_dump($teacher, $timeF, $timeT, $lessn, $fio, $dateF, $dateT);

/** Если нам передали ID то обновляем */
if(!empty($teacher) && !empty($timeF) && !empty($timeT) && !empty($lessn) && !empty($group) && !empty($fio) && !empty($dateF) && !empty($dateT) && !empty($day)){

	//извлекаем все записи из таблицы
	$query = "SELECT * FROM `replacements` WHERE teacher='$teacher' and timeF='$timeF' and timeT='$timeT' and less='$lessn' and class='$group' and fio='$fio' and dateF='$dateF' and dateT='dateT'";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    if (mysqli_num_rows($result) == 0){
        $query1 = "INSERT INTO `replacements` (`id`, `teacher`, `timeF`, `timeT`, `less`, `class`, `fio`, `dateF`, `dateT`) VALUES (NULL, '$teacher', '$timeF', '$timeT', '$lessn', '$group', '$fio', '$dateF', '$dateT')";
        $query2 = "UPDATE schedule SET replacement = '$fio' WHERE teacher = '$teacher' AND class = '$group' AND timeFrom = '$timeF' AND timeTo = '$timeT' AND day = '$day' AND lesson = '$lessn'";
        $query3 = "UPDATE schedule SET rep_date = '$dateT' WHERE teacher = '$teacher' AND class = '$group' AND timeFrom = '$timeF' AND timeTo = '$timeT' AND day = '$day' AND lesson = '$lessn'";
        if ($query1)
        {
            mysqli_query($connection, $query1);
        }
        if ($query2){
            mysqli_query($connection, $query2);
        }
        if ($query3){
            mysqli_query($connection, $query3);
        }
    }

	$message = 'Все хорошо';

}else{
	$message = 'Не удалось записать и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'message' => $message,
	'query' => $query,
	'query1' => $query1,
    'query2' => $query2
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>