<?php

require('connect.php');

/** Получаем наш ID статьи из запроса */
$name_select = $_POST['name_select'];
$teacher = $_POST['teacher'];

/** Если нам передали ID то обновляем */
if(!empty($name_select) && !empty($teacher)){

    //извлекаем все записи из таблицы
    $query = $connection->query("SELECT distinct class FROM `schedule` WHERE teacher = '$teacher' and lesson = '$name_select' GROUP BY class");

    while($row = $query->fetch_assoc()){
        $class['class'][] = $row['class'];
    }
    $out['class'] = $class;
	$message = 'Все хорошо';
}

else{
    $message = 'Не удалось записать и извлечь данные';
}

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>