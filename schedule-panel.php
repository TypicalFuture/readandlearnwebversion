<?php
session_start();

if (empty($_SESSION['user']) && (($_SESSION['user']['username'] != 'admin190') || ($_SESSION['user']['username'] != 'repadmin'))){
    header('location: index.php');
}

if ($_SERVER['REQUEST_METHOD']=="POST"){
header("location:{$_SERVER['PHP_SELF']}");
}

$fullname1 = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['user']['full_name']);

echo '
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Caomatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\schedule-panel.css">
    <link rel="stylesheet" type="text/css" href="css\sp.css">
    <script type="text/javascript" src="/js/jquery.js"></script>
    <link rel="shortcut icon" href="icons/icon.svg" />
    <title>Панель управления</title>
</head>
<body>';

require('php-scripts/writter.php');
require('functions.php');

echo '<div class="header">
    <div class="containerHeader">
        <div class="header_logo">
            <a href="https://mgok.mskobr.ru/" target="_blank"><img src="/img/MGOK.svg" alt="МГОК" title="МГОК" class="logo"/></a>
        </div>';
?>
        <button class="ex-btn" name="submit" onclick="document.location.replace('exit.php');">Выход</button>

<?php
        echo '<div class="user_name"><p>'.$fullname1.'</p></div>';

        if($_SESSION['user']['username'] != 'repadmin'){
        echo'
            <div class="headerAdd">
                <a class="add" href="add-panel.php">Дополнительное образование</a>
            </div>';
        
        }
        echo'
        <div class="headerSchedule">
            <a class="schedule" href="schedule-panel.php">Расписание</a>
        </div>
    </div>

</div>';

if($_SESSION['user']['username'] != 'repadmin'){
    
echo'

<details>
<summary><div class="import" id="im-data">
    <div class="content">
        <p class="imp-data">Импорт данных</p>
        <p class="upload-data">Загрузка файлов формата .csv в кодировке UTF-8</p></summary>
        <div class="chose">
            <div class="content">
                <p class="choose-t">Выберите таблицу для добавления данных:</p>
                
                <form id="form-entry" method="POST"  enctype="multipart/form-data">
                <select name="selector" id="select_" class="select-t">
                  <option value="schedule">Расписание занятий</option>
                  <option value="students">Ученики</option>
                  <option value="teachers">Преподаватели</option>
                </select>
                <input type="file" id="file" class="file" name="file">
                <label for="file" class="file-l">Добавить файл</label>
                <input type="submit" name="submit" onclick="return proverka();" class="upload" value="Загрузить">
                </form>

            </div>
        </div>
        </details>
    </div>
</div>';
}

echo '<details>
    <summary>
        <div class="querys">
            <div class="content">
                <div class="quer"><p class="quer-p t-swp">Замена преподавателя</p></div>
                <p class="upload-data">Назначить замену преподавателя</p></summary>
                <div class="quer-chs">
                    <div class="content" id="del-inps">
                        <input type="text" class="t-swap" placeholder="ФИО преподавателя">
                        <input type="submit" class="t-find" value="Поиск">
                        <div class="display" id="display"></div>';
                        

                    /*$get = get_teacher();

                    if (!empty($get)){

                        $get_t = $get[0];

                        foreach ($get as $get_t){

                        $nums += 1;
                        echo '<form method="POST" action="" class="del-frm" id="'.$get_t['id'].'">
                            <input type="text" class="qry-inp" placeholder="ФИО" value="'.$get_t['teacherName'].'">
                            </form>
                        '; 
                        };
                    };*/
              echo '</div>
                </div>
            </div> 
        </div>
</details>';



echo '<details>
    <summary>
        <div class="querys">
            <div class="content">
                <div class="quer"><p class="quer-p swp-check">Активные замены</p></div>
                <p class="upload-data">Проверить наличие замен преподавателей</p></summary>
                <div class="quer-chs">
                    <div class="content" id="del-inps">';
                    $get = get_swaps();

                    if (!empty($get)){

                        $swp = $get[0];

                        foreach ($get as $swp){

                        echo '<div class="table-s table-s'.$swp["id"].'"><input class="swap" id="tch'.$swp["id"].'" type="text" value="'.$swp["teacher"].'" disabled><input class="t-time" id="tf'.$swp["id"].'"" type="text" disabled value="'.substr($swp["timeF"], 0, 5).'"><input class="t-time" id="tt'.$swp["id"].'" type="text" disabled value="'.substr($swp["timeT"], 0, 5).'"><input class="t-less" id="ls'.$swp["id"].'" type="text" disabled value="'.$swp["less"].'"><input class="grp" id="gr'.$swp["id"].'" type="text"" disabled value="'.$swp["class"].'"><input class="swap" id="sw'.$swp["id"].'" type="text" value="'.$swp["fio"].'" disabled><input class="date" id="dt'.$swp["id"].'" type="date" value="'.$swp["dateT"].'" disabled><input id="'.$swp["id"].'" class="a-swap del" type="submit" value="Удалить"></div>
                        '; 
                        };
                    }
                    else{
                        echo '<p>Нет активных замен</p>';
                    }
               echo'</div>
                </div>
            </div> 
        </div>
</details>';

if (isset($_POST['submit'])) {
    var_dump($_FILES);
    var_dump($_POST['selector']);
    
    $selector = $_POST['selector'];
    
    echo $_FILES['file']['name'];
    
    move_uploaded_file($_FILES['file']['tmp_name'], "tmp/".$_FILES['file']['name']);
            
    $file = fopen('tmp/'.$_FILES['file']['name'].'', 'r');

    while (!feof($file)) {
        $massiv = fgetcsv($file, 1024, ';');
        var_dump($massiv);
        $j = count($massiv);
        if ($j > 1) {

            switch ($selector) {
                case 'schedule':
                    $check = "SELECT * FROM schedule where lesson = '$massiv[1]' and timeFrom = '$massiv[2]' and place = '$massiv[4]' and teacher = '$massiv[5]' and day = '$massiv[6]'";
                    $result = mysqli_query($connection, $check) or die(mysqli_error($connection));
                    if (mysqli_num_rows($result) == 0 && $massiv[0] != NULL && $massiv[1] != NULL && $massiv[2] != NULL && $massiv[3] != NULL && $massiv[4] != NULL && $massiv[5] != NULL && $massiv[6] != NULL && $massiv[7] != NULL){
                        $query = "INSERT INTO `schedule` (`id`, `number`, `lesson`, `timeFrom`, `timeTo`, `place`, `teacher`, `day`, `class`, `parity`) VALUES (NULL, '".$massiv[0]."', '".$massiv[1]."', '".$massiv[2]."', '".$massiv[3]."', '".$massiv[4]."', '".$massiv[5]."', '".$massiv[6]."', '".$massiv[7]."', '".$massiv[8]."')";
                        if ($query){
                             mysqli_query($connection, $query) or die("Ошибка ".mysqli_error($connection));
                        }
                    }
                    break;
                
                case 'students':
                    $check = "SELECT * FROM students where full_name = '$massiv[0]' and date_of_birth = '$massiv[1]'";
                    $result = mysqli_query($connection, $check) or die(mysqli_error($connection));
                    if (mysqli_num_rows($result) == 0 && $massiv[0] != NULL && $massiv[1] != NULL && $massiv[2] != NULL && $massiv[3] != NULL && $massiv[4] != NULL){
                        $query = "INSERT INTO `students` (`id`, `full_name`, `date_of_birth`, `class`, `username`, `password`) VALUES (NULL, '".$massiv[0]."', '".$massiv[1]."', '".$massiv[2]."', '".$massiv[3]."', '".$massiv[4]."')";
                        if ($query){
                             mysqli_query($connection, $query) or die("Ошибка ".mysqli_error($connection));
                         }
                    }
                    break;

                case 'teachers':
                    $check = "SELECT * FROM teachers where teacherName = '$massiv[0]'";
                    $result = mysqli_query($connection, $check) or die(mysqli_error($connection));
                    if (mysqli_num_rows($result) == 0 && $massiv[0] != NULL && $massiv[1] != NULL && $massiv[2] != NULL ){
                        $query = "INSERT INTO `teachers` (`teacherName`, `username`, `password`) VALUES ('".$massiv[0]."', '".$massiv[1]."', '".$massiv[2]."')";
                        if ($query){
                             mysqli_query($connection, $query) or die("Ошибка ".mysqli_error($connection));
                         }
                     }
                    break;
            }

            
        }
    }

    fclose($file);

    unlink("tmp/".$_FILES['file']['name']);

    unset ($_POST['submit']);
}

?>

<script>
    $(document).ready(function() {
 
    // Обработчик события keyup, сработает после того как пользователь отпустит кнопку, после ввода чего-либо в поле поиска.
    // Поле поиска из файла 'index.php' имеет id='search'
    $(".t-swap").keyup(function() {
 
        // Присваиваем значение из поля поиска, переменной 'name'.
        var name = $('.t-swap').val();
 
        // Проверяем если значение переменной 'name' является пустым
        if (name === "") {
 
            // Если переменная 'name' имеет пустое значение, то очищаем блок div с id = 'display'
            $("#display").html("");
 
        }
        else {
            // Иначе, если переменная 'name' не пустая, то вызываем ajax функцию.
 
            $.ajax({
 
                type: "POST", // Указываем что будем обращатся к серверу через метод 'POST'
                url: "/php-scripts/t-search.php", // Указываем путь к обработчику. То есть указывем куда будем отправлять данные на сервере.
                data: {
                    // В этом объекте, добавляем данные, которые хотим отправить на сервер
                    search: name // Присваиваем значение переменной 'name', свойству 'search'.
                },
                success: function(response) {
                    // Если ajax запрос выполнен успешно, то, добавляем результат внутри div, у которого id = 'display'.
                    $("#display").html(response).show();
                }
 
            });
 
        }
 
    });
 
});
 
function fill(Value) {
    // Функция 'fill', является обработчиком события 'click'.
    // Она вызывается, когда пользователь кликает по элементу из результата поиска.
 
    $('.t-swap').val(Value); // Берем значение элемента из результата поиска и добавляем его в значение поля поиска
 
    $('#display').hide(); // Скрываем результаты поиска
 
}

jQuery(document).ready(function() {
    jQuery(".t-find").bind("click", function() {

        var name = $(".t-swap").val();

        jQuery.ajax({
            url: "/php-scripts/t-lesn.php",
            type: "POST",
            data: {name:name}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
                    $(".table-s").remove();
                    $(".days").remove()
                    $("#del-inps").append(function(){
                        var res = '<div class="days" id="p"><p>Понедельник</p></div><div class="days" id="v"><p>Вторник</p></div><div class="days" id="s"><p>Среда</p></div><div class="days" id="ch"><p>Четверг</p></div><div class="days" id="pt"><p>Пятница</p></div>';
                        return res;
                    });



                        for(var i = 0; i <= result.info.day.length; i++){
                            var res = '';
                            var day = result.info.day[i];
                            switch(day){
                                case "Понедельник":
                                    $("#p").append(function(){
                                        res +='<div class="table-s"><input class="t-day" id="t-day' + i + '" type="text" disabled value="' + result.info.day[i] + '"><input class="t-time" type="text" id="timeF' + i + '" disabled value="' + result.info.timeFrom[i].substring(0,5) + '"><input class="t-time" id="timeT' + i + '" type="text" disabled value="' + result.info.timeTo[i].substring(0,5) +'"><input class="t-less" id="t-less' + i + '" type="text" disabled value="' + result.info.lesson[i] + '"><input class="grp" type="text" id="grp' + i + '" disabled value="' + result.info.class[i] + '"><input placeholder="ФИО заменяющего" class="swap" id="swap' + i + '" type="text" required="required"><input class="date" id="dateF' + i + '" type="date" required="required"><input class="date" id="dateT' + i + '" type="date" required="required"><input id="' + i + '" class="a-swap" type="submit" value="Подтвердить замену"></div>';
                                        return res;
                                    });
                                    break;
                                case "Вторник":
                                    $("#v").append(function(){
                                        res +='<div class="table-s"><input class="t-day" id="t-day' + i + '" type="text" disabled value="' + result.info.day[i] + '"><input class="t-time" type="text" id="timeF' + i + '" disabled value="' + result.info.timeFrom[i].substring(0,5) + '"><input class="t-time" id="timeT' + i + '" type="text" disabled value="' + result.info.timeTo[i].substring(0,5) +'"><input class="t-less" id="t-less' + i + '" type="text" disabled value="' + result.info.lesson[i] + '"><input class="grp" type="text" id="grp' + i + '" disabled value="' + result.info.class[i] + '"><input placeholder="ФИО заменяющего" class="swap" id="swap' + i + '" type="text" required="required"><input class="date" id="dateF' + i + '" type="date" required="required"><input class="date" id="dateT' + i + '" type="date" required="required"><input id="' + i + '" class="a-swap" type="submit" value="Подтвердить замену"></div>';
                                        return res;
                                    });
                                    break;
                                case "Среда":
                                    $("#s").append(function(){
                                        res +='<div class="table-s"><input class="t-day" id="t-day' + i + '" type="text" disabled value="' + result.info.day[i] + '"><input class="t-time" type="text" id="timeF' + i + '" disabled value="' + result.info.timeFrom[i].substring(0,5) + '"><input class="t-time" id="timeT' + i + '" type="text" disabled value="' + result.info.timeTo[i].substring(0,5) +'"><input class="t-less" id="t-less' + i + '" type="text" disabled value="' + result.info.lesson[i] + '"><input class="grp" type="text" id="grp' + i + '" disabled value="' + result.info.class[i] + '"><input placeholder="ФИО заменяющего" class="swap" id="swap' + i + '" type="text" required="required"><input class="date" id="dateF' + i + '" type="date" required="required"><input class="date" id="dateT' + i + '" type="date" required="required"><input id="' + i + '" class="a-swap" type="submit" value="Подтвердить замену"></div>';
                                        return res;
                                    });
                                    break;
                                case "Четверг":
                                    $("#ch").append(function(){
                                        res +='<div class="table-s"><input class="t-day" id="t-day' + i + '" type="text" disabled value="' + result.info.day[i] + '"><input class="t-time" type="text" id="timeF' + i + '" disabled value="' + result.info.timeFrom[i].substring(0,5) + '"><input class="t-time" id="timeT' + i + '" type="text" disabled value="' + result.info.timeTo[i].substring(0,5) +'"><input class="t-less" id="t-less' + i + '" type="text" disabled value="' + result.info.lesson[i] + '"><input class="grp" type="text" id="grp' + i + '" disabled value="' + result.info.class[i] + '"><input placeholder="ФИО заменяющего" class="swap" id="swap' + i + '" type="text" required="required"><input class="date" id="dateF' + i + '" type="date" required="required"><input class="date" id="dateT' + i + '" type="date" required="required"><input id="' + i + '" class="a-swap" type="submit" value="Подтвердить замену"></div>';
                                        return res;
                                    });
                                    break;
                                case "Пятница":
                                    $("#pt").append(function(){
                                        res +='<div class="table-s"><input class="t-day" id="t-day' + i + '" type="text" disabled value="' + result.info.day[i] + '"><input class="t-time" type="text" id="timeF' + i + '" disabled value="' + result.info.timeFrom[i].substring(0,5) + '"><input class="t-time" id="timeT' + i + '" type="text" disabled value="' + result.info.timeTo[i].substring(0,5) +'"><input class="t-less" id="t-less' + i + '" type="text" disabled value="' + result.info.lesson[i] + '"><input class="grp" type="text" id="grp' + i + '" disabled value="' + result.info.class[i] + '"><input placeholder="ФИО заменяющего" class="swap" id="swap' + i + '" type="text" required="required"><input class="date" id="dateF' + i + '" type="date" required="required"><input class="date" id="dateT' + i + '" type="date" required="required"><input id="' + i + '" class="a-swap" type="submit" value="Подтвердить замену"></div>';
                                        return res;
                                    });
                                    break;
                            };
                            /*res += '<div class="table-s"><form method="post" action=""><input class="t-day" type="text" value="' + result.info.day[i] + '" disabled><input class="t-time" type="text" disabled><input class="t-less" type="text" disabled><input placeholder="ФИО заменяющего" class="swap" type="text" required="required"><input class="date" type="date" required="required"><input class="date" type="date" required="required"></form></div>';*/
                        };
                            jQuery(".a-swap").click(function() {
                                var id = jQuery(this).attr("id");
                                var day = jQuery("#t-day"+id).val();
                                var teacher = jQuery(".t-swap").val();
                                var timeF = jQuery("#timeF"+id).val();
                                var timeT = jQuery("#timeT"+id).val();
                                var less = jQuery("#t-less"+id).val();
                                var grp = jQuery("#grp"+id).val();
                                var fio = jQuery("#swap"+id).val();
                                var dateF = jQuery("#dateF"+id).val();
                                var dateT = jQuery("#dateT"+id).val();
                                if(fio == "" && dateF == "" && dateT == ""){
                                    alert("заполните все поля в строке");
                                    return false;
                                }
                                else{
                                    //alert(id + " " + timeF + " " + timeT + " " + less + " " + fio + " " + dateF + " " + dateT);
                                    //return false;
                                    //Доделать. Ошибка в том, что в БД записывается сразу несколько строчек, не понятно почему. UPD: ОШИБКА БЫЛА УСТРАНЕНА ПУТЕМ УДАЛЕНИЯ $.document ready function из начала
                                    jQuery.ajax({
                                        url: "/php-scripts/add-swap.php",
                                        type: "POST",
                                        data: {teacher:teacher, timeF:timeF, timeT:timeT, less:less, grp:grp, fio:fio, dateF:dateF, dateT:dateT, day:day}, // Передаем данные для записи
                                        dataType: "json",
                                        success: function(result) {
                                            if (result){ 
                                                //alert(result.message);
                                                console.log(result.message);
                                            }else{
                                                alert(result.message);
                                            }
                                            return false;
                                        }
                                    });
                                    return false;
                                }
                                return false;
                            });

                    console.log(result);
                    
                }
                else{
                    alert(result.message);
                }

                return false;
            }
        });
    return false;
    });
});

jQuery(document).ready(function() {
    jQuery(".del").bind("click", function() {

        var id = jQuery(this).attr("id");
        var teacher = jQuery("#tch"+id).val();
        var timeF = jQuery("#tf"+id).val();
        var timeT = jQuery("#tt"+id).val();
        var less = jQuery("#ls"+id).val();
        var grp = jQuery("#gr"+id).val();
        var fio = jQuery("#sw"+id).val();
        var dateF = jQuery("#df"+id).val();
        var dateT = jQuery("#dt"+id).val();

        jQuery.ajax({
            url: "/php-scripts/del-swp.php",
            type: "POST",
            data: {id:id}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
                    $( ".table-s" + id).remove();

                    console.log(result);
                }else{
                    alert(result.message);
                }
                return false;
            }
        });
    return false;
    });
});

</script>