<?php

require_once('connect.php');
/** Получаем наш ФИО и группу из запроса */

$fio = $_POST['fio'];
$group = $_POST['group'];

/** Если нам передали ID то обновляем */
if(!empty($fio)){

	$query = "SELECT * FROM posts WHERE studentName='$fio' and groupName='$group'";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    if (mysqli_num_rows($result) > 0){
        $query = "INSERT INTO unposts (`studentName`, `groupName`, `phone`, `email`) SELECT `studentName`, `groupName`, `phone`, `email` FROM posts WHERE `studentName` = '$fio' and `groupName` = '$group';";
        if ($query)
        {
            mysqli_query($connection, $query);
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
	'fio' => $fio,
	'group' => $group
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>