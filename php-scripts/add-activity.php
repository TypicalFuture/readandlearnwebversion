<?php

require_once('writter.php');

/** Получаем наш ФИО и группу из запроса */

$fio = $_POST['fio'];
$group = $_POST['group'];
$phone = trim($_POST['phone']);
$mail = trim($_POST['mail']);


//$pos_ph = strripos($phone, " ");
$pos_em = strripos($mail, " ");

$len_ph = strlen($phone);
$len_pas = strlen($mail);

if((($len_ph <= 17)) && (($pos_em === false))){
    //$pos_ph = strripos($phone, "-");
    $pos_em = strripos($mail, "-");
    $message = 'Прошли 1 проверку';
    if(($pos_em === false)){
        /** Если нам передали ID то обновляем */
        if(!empty($fio) & !empty($group)){
        
            $query = $connection->query("INSERT INTO `app_add` (`id`, `studentName`, `groupName`, `phone`, `email`) VALUES (NULL, '$fio', '$group', '$phone', '$mail')");
            
            $message = 'Все хорошо';
        
        }
    }
}
else{
    $message = 'Не удалось записать и извлечь данные';
}


/** Возвращаем ответ скрипту */

// Формируем масив данных для отправки
$out = array(
    'message' => $message,
    'query' => $query,
    'fio' => $fio,
    'phone' => $phone,
    'email' => $mail,
    'group' => $group
);

// Устанавливаем заголовот ответа в формате json
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>