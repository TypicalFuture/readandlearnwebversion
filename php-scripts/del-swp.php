<?php

require('deler.php');

global $nums;

/** Получаем наш ID статьи из запроса */

$id = intval($_POST['id']);

/** Если нам передали ID то обновляем */
if(!empty($id)){


	//удаляем запись из БД
	
	$query = $connection->query("DELETE FROM `replacements` WHERE id = '$id'");
	
	$message = 'Все хорошо';

}else{
	$message = 'Не удалось удалить или извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'message' => $message,
	'del' => $query
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>