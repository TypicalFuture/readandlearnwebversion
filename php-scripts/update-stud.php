<?php

require('connect.php');

/** Получаем наш ID статьи из запроса */

$id = intval($_POST['id']);
$fio = $_POST['fio'];
$ph = $_POST['phone'];
$em = $_POST['email'];

/** Если нам передали ID то обновляем */
if(!empty($id)){
	//вставляем запись в БД
	
	//обновляем поле ФИО
	if(!empty($fio))
		$query_fio = $connection->query("UPDATE `posts` SET studentName = '$fio' WHERE id = '$id'");
	
	//обновляем поле телефон
	if(!empty($ph))
		$query_phone = $connection->query("UPDATE `posts` SET phone = '$ph' WHERE id = '$id'");
	
	//обновляем поле email
	if(!empty($em))
		$query_email = $connection->query("UPDATE `posts` SET email = '$em' WHERE id = '$id'");
	
	
	$message = 'Изменения внесены';

}else{
	$message = 'Не удалось внести изменения';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'message' => $message
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>