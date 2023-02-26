<?php

	require('connect.php');

/** Получаем наш ID статьи из запроса */

$id = $_POST['id'];

/** Если нам передали ID то обновляем */
if(!empty($id)){

	//извлекаем все записи из таблицы
	$query = $connection->query("SELECT * FROM `infoadd` where infoadd.groupName = '$id'");

	while($row = $query->fetch_assoc()){
		$info['vid'][] = $row['vid'];
		$info['name'][] = $row['name'];
		$info['address'][] = $row['address'];
		$info['teacherName'][] = $row['teacherName'];
	}

	$query1 = $connection->query("SELECT * FROM `addschedule` where addschedule.groupName = '$id'");

	while($row1 = $query1->fetch_assoc()){
		$sche['day'][] = $row1['day'];
		$sche['time'][] = $row1['time'];
		$sche['timeTo'][] = $row1['timeTo'];
	}
	$message = 'Все хорошо';

}else{
	$message = 'Не удалось записать и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'message' => $message,
	'info' => $info,
	'sche' => $sche
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>