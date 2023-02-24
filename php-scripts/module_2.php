<?php

require('connect.php');

/** Получаем наш ID статьи из запроса */
$modules = $_POST['modules'];
$lesson = $_POST['lesson'];
$group = $_POST['group'];
$semestr = $_POST['semestr'];
$teacher = $_POST['teacher'];

/** Если нам передали ID то обновляем */
if(!empty($lesson) && !empty($group) && !empty($semestr) && !empty($teacher)){

    //извлекаем все записи из таблицы
    $query = $connection->query("SELECT * FROM `students` where students.class = '$group' ORDER BY students.full_name ASC");

    while($row = $query->fetch_assoc()){
        $stud['full_name'][] = $row['full_name'];
    }
    $out['stud'] = $stud;
    

    $query1 = $connection->query("SELECT * FROM `modules` where modules.lesson = '$lesson' and modules.stud_group = '$group' and modules.teacher = '$teacher' and modules.module_name = '$modules' and modules.semestr = '$semestr'");
    if (mysqli_num_rows($query1) > 0){
        while($row1 = $query1->fetch_assoc()) {
            $m['student'][] = $row1['student'];
            $m['name_cp'][] = $row1['name_cp'];
            $m['maxb_cp'][] = $row1['maxb_cp'];
            $m['student_p'][] = $row1['student_p'];
        }
        $out += ['m' => $m];
    }

    $query2 = $connection->query("SELECT COUNT(full_name) FROM `students` WHERE class = '$group'");
    if (mysqli_num_rows($query2) > 0){
        while($row2 = $query2->fetch_assoc()){
            $count = $row2['COUNT(full_name)'];
        }
        $out += ['count' => $count];
    }
    
    $out += ['modules' => $modules];
    $message = 'Все хорошо';

}else{
    $message = 'Не удалось записать и извлечь данные';
}

// Устанавливаем заголовот ответа в формате json
//header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>