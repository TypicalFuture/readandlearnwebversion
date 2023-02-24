<?php

require('deleter.php');

/** Получаем наш ФИО и группу из запроса */

$fio = $_POST['fn'];
$group = $_POST['gp'];
$phone = $_POST['ph'];
$email = $_POST['em'];

/** Если нам передали ID то обновляем */
if(!empty($fio) && !empty($group) && !empty($phone) && !empty($email)){

    $query = "SELECT * FROM posts WHERE studentName='$fio' and groupName='$group'";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    if (mysqli_num_rows($result) == 0){
        $query = "INSERT INTO `posts` (`id`, `studentName`, `groupName`, `phone`, `email`) VALUES (NULL, '$fio', '$group', '$phone', '$email')";
        if ($query)
        {
            mysqli_query($connection, $query);
        }
    }

    $query1 = $connection->query("DELETE FROM `app_add` WHERE studentName = '$fio' and groupName = '$group' and phone = '$phone' and email = '$email'");

    $message = 'Все хорошо';

}else{
    $message = 'Не удалось записать и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
    'message' => $message,
    'fio' => $fio,
    'group' => $group,
    'phone' => $phone,
    'email' => $email,
    'query' => $query,
    'query1' => $query1
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>