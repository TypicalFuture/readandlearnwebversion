<?php

require('connect.php');

/** Получаем наш ID статьи из запроса */

$id = $_POST['id'];

/** Если нам передали ID то обновляем */
if(!empty($id)){

	//извлекаем все записи из таблицы
	$query = $connection->query("SELECT * FROM `posts` where posts.groupName = '$id'");

	while($row = $query->fetch_assoc()){
		$posts['email'][] = $row['email'];
		$posts['phone'][] = $row['phone'];
		$posts['studentName'][] = $row['studentName'];
		$posts['groupName'][] = $row['groupName'];
		$posts['id'][] = $row['id'];
	}

	$message = 'Все хорошо';

}else{
	$message = 'Не удалось записать и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'message' => $message,
	'posts' => $posts
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>