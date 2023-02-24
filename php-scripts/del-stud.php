<?php

require('deler.php');

/** Получаем наш ID статьи из запроса */

$id = intval($_POST['id']);

/** Если нам передали ID то обновляем */
if(!empty($id)){
	//вставляем запись в БД
	$query = $connection->query("DELETE FROM `posts` WHERE id = '$id'");
	
	$message = 'Все хорошо';

}else{
	$message = 'Не удалось удалить данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'message' => $message,
	'users' => $users
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>