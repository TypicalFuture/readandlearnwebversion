<?php

require_once('deler.php');


global $nums;

/** Получаем наш ID статьи из запроса */

$id = $_POST['name'];
$class = $_POST['group'];

/** Если нам передали ID то обновляем */
if(!empty($id)){

	//удаляем запись из БД
	
	$query = $connection->query("DELETE FROM `events` WHERE name = '$name' and class = '$class'");
	
	if($query)
	    $message = 'Все хорошо';
	else
	    $message = 'Не удалось подключиться к БД';

}else{
	$message = 'Не удалось удалить и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'user' => $username,
	'class' => $class,
	'message' => $message,
	'query' => $query
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>