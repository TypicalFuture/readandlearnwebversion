<?php

//require('deleter.php');
require('connect.php');
/** Получаем наш ФИО и группу из запроса */

$timeFrom = trim($_POST['timeFrom']);
$timeTo = trim($_POST['timeTo']);
$name = trim($_POST['name']);
$teacher = trim($_POST['teacher']);
$place = trim($_POST['place']);
$username = trim($_POST['username']);
$class = trim($_POST['group']);
$day = trim($_POST['day']);
if(isset($_POST['date']))
    $date = trim($_POST['date']);
else
    $date = '0000-00-00';
$message = '';

if(strtotime($timeFrom) >= strtotime($timeTo)){
    $message = 'timeError';
    header('Content-Type: text/json; charset=utf-8');
    echo json_encode($message);
}
else{
    /** Если нам передали ID то обновляем */
    if(isset($_POST['timeFrom']) && isset($_POST['timeTo']) && isset($_POST['name']) && isset($_POST['day'])){

        $query = "SELECT * FROM events WHERE username='$username' and class='$class' and ((timeFrom < '$timeFrom' and timeTo > '$timeTo') or (timeFrom >= '$timeFrom' and timeTo <= '$timeTo') or (timeFrom >= '$timeFrom' and timeTo > '$timeTo' and timeFrom < '$timeTo') or (timeFrom < '$timeFrom' and timeTo <= '$timeTo' and timeTo > '$timeFrom')) and day = '$day'";
        $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
        if (mysqli_num_rows($result) == 0){
            $query = "SELECT * FROM schedule WHERE class = '$class' and timeFrom < '$timeTo' and day = '$day' ORDER BY timeFrom ASC LIMIT 1";
            $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
            if(mysqli_num_rows($result) == 0){
                $query = "INSERT INTO `events` (`id`, `username`, `class`, `timeFrom`, `timeTo`, `name`, `teacher`, `place`, `day`, `date`) VALUES (NULL, '$username', '$class', '$timeFrom', '$timeTo', '$name', '$teacher', '$place', '$day', '$date')";
                $res = mysqli_query($connection, $query) or die(mysqli_error($connection));
                if ($res)
                {
                    //mysqli_query($connection, $query);
                    $message = '2';
                }
            }
            else{
                $query1 = "SELECT * FROM schedule WHERE class = '$class' and timeTo > '$timeFrom' and day = '$day' ORDER BY timeFrom DESC LIMIT 1";
                $result1 = mysqli_query($connection, $query1) or die(mysqli_error($connection));
                if(mysqli_num_rows($result1) > 0){
                    $message = '1';
                }
                else{
                    $query = "INSERT INTO `events` (`id`, `username`, `class`, `timeFrom`, `timeTo`, `name`, `teacher`, `place`, `day`, `date`) VALUES (NULL, '$username', '$class', '$timeFrom', '$timeTo', '$name', '$teacher', '$place', '$day', '$date')";
                    $res = mysqli_query($connection, $query) or die(mysqli_error($connection));
                    if ($res)
                    {
                        //mysqli_query($connection, $query);
                        $message = '2';
                    }
                    else
                        $message = '3';
                }
            }
            
        }
        else{
            $message = '4';
        }
        

    }else{
        $message = 'Не удалось записать и извлечь данные';
    }
    /** Возвращаем ответ скрипту */

    // Формируем масив данных для отправки
    $out = array(
        'message' => $message,
        'username' => $username,
        'class' => $class,
        'timeFrom' => $timeFrom,
        'timeTo' => $timeTo,
        'name' => $name,
        'teacher' => $teacher,
        'place' => $place,
        'day' => $day
    );

    // Устанавливаем заголовот ответа в формате json
    header('Content-Type: text/json; charset=utf-8');

    // Кодируем данные в формат json и отправляем
    echo json_encode($out);
}
?>