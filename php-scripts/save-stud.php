<?php

require('updater.php');

/** Получаем наш ID статьи из запроса */

$gr = $_POST['gr'];
$fio = $_POST['fio'];
$ph = $_POST['phone'];
$email = $_POST['email'];

/** Если нам передали ID то обновляем */
if(!empty($gr) && !empty($fio) && !empty($ph) && !empty($email)){

	//извлекаем все записи из таблицы
	$query1 = "SELECT * FROM posts WHERE studentName='$fio' and groupName='$gr'";
    $result = mysqli_query($connection, $query1) or die(mysqli_error($connection));
    if (mysqli_num_rows($result) == 0){

		$query = $connection->query("INSERT INTO `posts`(`id`, `studentName`, `groupName`, `phone`, `email`) VALUES (NULL, '$fio', '$gr', '$ph', '$email')");

		$message = 'Все хорошо';

	}
	else{
		$message = 'Попытка записать уже существующие данные';
	}

}else{
	$message = 'Не удалось записать и извлечь данные';
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