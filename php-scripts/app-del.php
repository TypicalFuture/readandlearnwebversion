<?php

require('deler.php');

global $nums;

/** Получаем наш ID статьи из запроса */

$id = intval($_POST['id']);
$fio = $_POST['fn'];
$group = $_POST['gp'];


/** Если нам передали ID то обновляем */
if(!empty($id && !empty($fio) && !empty($group))){


	//удаляем запись из БД
	
	$query = $connection->query("DELETE FROM `app_add` WHERE id = '$id'");
	
	$message = 'Все хорошо';

}else{
	$message = 'Не удалось удалить и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
	'fio' => $fio,
	'gr' => $group,
	'message' => $message,
	'users' => $query_d,
	'unp' => $query
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>