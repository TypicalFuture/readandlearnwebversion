<?php

require('connect.php');

/** Получаем наш ID статьи из запроса */

$id = $_POST['id'];

/** Если нам передали ID то обновляем */
if(!empty($id)){

	//извлекаем все записи из таблицы
	$query = $connection->query("SELECT * FROM `schedule` where schedule.place = '$id' ORDER BY `schedule`.`timeFrom` ASC");

	while($row = $query->fetch_assoc()){
		$info['timeFrom'][] = $row['timeFrom'];
		$info['timeTo'][] = $row['timeTo'];
		$info['place'][] = $row['place'];
		$info['teacher'][] = $row['teacher'];
		$info['day'][] = $row['day'];
	}

	$message = 'Все хорошо';

}else{
	$message = 'Не удалось записать и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'message' => $message,
	'info' => $info
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>