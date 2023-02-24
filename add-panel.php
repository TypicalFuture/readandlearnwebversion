<?php
session_start();

if (empty($_SESSION['user']) || (($_SESSION['user']['username'] != 'admin190') && ($_SESSION['user']['username'] != 'addadmin'))){
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
    <link rel="stylesheet" type="text/css" href="css\add.css">
    <link rel="stylesheet" type="text/css" href="css\add-panel.css">
    <link rel="stylesheet" type="text/css" href="css\sp.css">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <script type="text/javascript" src="/js/jquery.js"></script>
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
        echo '<div class="user_name"><p>'.$fullname1.'</p></div>

        <div class="headerAdd">
            <a class="add" href="add-panel.php">Дополнительное образование</a>
        </div>';
if($_SESSION['user']['username'] != 'addadmin'){
        echo '
        <div class="headerSchedule">
            <a class="schedule" href="schedule-panel.php">Расписание</a>
        </div>';
}
echo'
    </div>

</div>';

if($_SESSION['user']['username'] != 'addadmin'){

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
                  <option value="infoadd">Сведения о дополнительном образовании</option>
                  <option value="addschedule">Расписание дополнительного образования</option>
                  <option value="posts">Запись учеников</option>
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

$nums = 0;
$nump = 0;

$get = get_unposts();
$getp = get_app_add();

if (!empty($get)){

    $get_unp = $get[0];

    foreach ($get as $get_unp){

        $nums += 1;
    };
};

if (!empty($getp)){

    $get_app = $getp[0];

    foreach ($getp as $get_app){

        $nump += 1;
    };
};

if(isset($_POST['del-btn'])){
    $nums -= 1;
}

echo '<details>
    <summary>
        <div class="querys">
            <div class="content">
                <div class="quer"><p class="quer-p">Запросы на удаление</p><p class="nums">'.$nums.'</p></div>
                <p class="upload-data">Подтвердите действия учеников, желающих покинуть <br> группу дополнительного образования</p></summary>
                <div class="quer-chs">
                    <div class="content" id="del-inps">';

                    $get = get_unposts();

                    if (!empty($get)){

                        $get_unp = $get[0];

                        foreach ($get as $get_unp){

                        $nums += 1;
                        echo '
                        <details id="'.$get_unp['id'].'">
                            <summary class="qr-sum '.$get_unp['id'].'">'.$get_unp['studentName'].'<hr></summary>
                            <form method="POST" action="" class="del-frm qr-frm">
                                <h3>ФИО: </h3><input type="text" id="fio'.$get_unp['id'].'" class="qry-inp i-fr" placeholder="ФИО" value="'.$get_unp['studentName'].'" disabled>
                                <h3>Группа: </h3><input type="text" id="grp'.$get_unp['id'].'" class="qry-inp i-fr" placeholder="Группа" value="'.$get_unp['groupName'].'" disabled>
                                <h3>Телефон: </h3><input type="text" id="phone'.$get_unp['id'].'" class="qry-inp i-fr" placeholder="Номер телефона" value="'.$get_unp['phone'].'" disabled>
                                <h3>Почта: </h3><input type="text" id="mail'.$get_unp['id'].'" class="qry-inp i-fr" placeholder="Почта" value="'.$get_unp['email'].'" disabled><br>
                                <button name="del-btn" type="submit" id="'.$get_unp['id'].'" class="qry-acpt" value="'.$get_unp['id'].'">Подтвердить</button>
                            </form>
                        </details>
                        '; 
                        };
                    };
              echo '</div>
                </div>
            </div> 
        </div>
</details>';

echo '<details>
    <summary>
        <div class="querys">
            <div class="content">
                <div class="quer"><p class="quer-pa">Запросы на добавление</p><p class="nums">'.$nump.'</p></div>
                <p class="upload-data">Подтвердите действия учеников, желающих присоедениться <br> к группе дополнительного образования</p></summary>
                <div class="quer-chs">
                    <div class="content" id="del-inps">';

                    $get = get_app_add();

                    if (!empty($get)){

                        $get_app = $get[0];

                        foreach ($get as $get_app){

                        $nump += 1;
                        echo '
                        <details id="'.$get_app['id'].'">
                            <summary class="qr-sum '.$get_app['id'].'">'.$get_app['studentName'].'<hr></summary>
                            <form method="POST" action="" class="del-frm qr-frm">
                                <h3>ФИО: </h3><input type="text" id="fio'.$get_app['id'].'" class="qry-inp i-fr" placeholder="ФИО" value="'.$get_app['studentName'].'" disabled>
                                <h3>Группа: </h3><input type="text" id="grp'.$get_app['id'].'" class="qry-inp i-fr" placeholder="Группа" value="'.$get_app['groupName'].'" disabled>
                                <h3>Телефон: </h3><input type="text" id="phone'.$get_app['id'].'" class="qry-inp i-fr" placeholder="Номер телефона" value="'.$get_app['phone'].'" disabled>
                                <h3>Почта: </h3><input type="text" id="mail'.$get_app['id'].'" class="qry-inp i-fr" placeholder="Почта" value="'.$get_app['email'].'" disabled><br>
                                <button name="del-btn" type="submit" id="'.$get_app['id'].'" class="qry-acpt-p b-fr" value="'.$get_app['id'].'">Подтвердить</button>
                                <button name="del-btn" type="submit" id="'.$get_app['id'].'" class="qry-acpt-d b-fr" value="'.$get_app['id'].'">Отклонить</button>
                            </form>
                        </details>
                        '; 
                        };
                    };
              echo '</div>
                </div>
            </div> 
        </div>
</details>';

echo '<details>
    <summary>
        <div class="info-gr">
            <div class="content">
                <p class="sche-grp">Расписание групп</p>
                <p class="inf-grp">Ознакомьтесь с расписанием групп дополнительнго образования</p></summary>
                <div class="quer-chs">
                    <div class=content>
                        <form method="POST" action="">
                            <input class="srch-grp" type="text" placeholder="Введите название группы" required>
                            <input class="search-g" type="submit" value="Поиск"></button>
                        </form>
                        <div class="grp-info">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </details>';

echo '
    <div class="edit-r">
        <div class="content" id="ed-id">
            <p class="edb-p">Редактирование записей</p>
            <p class="eds-p">Данные об учениках, записанных в группы дополнительного образования</p>
            <input class="ed-gn" id="ed-gn" type="text" placeholder="Наименование группы" autocomplete="off">
            <input class="ed-ac" type="submit" value="Подтвердить">
            <div class="display" id="display"></div>
        </div>
    </div>
';


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
                case 'infoadd':
                    $check = "SELECT groupName FROM infoadd where groupName = '$massiv[2]'";
                    $result = mysqli_query($connection, $check) or die(mysqli_error($connection));
                    if (mysqli_num_rows($result) == 0 && $massiv[11] != NULL){

                        $query = "INSERT INTO `infoadd` (`vid`, `name`, `groupName`, `category`, `ageGroupFrom`, `ageGroupTo`, `address`, `teacherName`, `free`, `trainingPeriod`, `lessonType`, `categoryAdd`) VALUES ('".$massiv[0]."', '".$massiv[1]."', '".$massiv[2]."', '".$massiv[3]."', '".$massiv[4]."', '".$massiv[5]."', '".$massiv[6]."', '".$massiv[7]."', '".$massiv[8]."', '".$massiv[9]."', '".$massiv[10]."', '".$massiv[11]."')";
                        if ($query){
                             mysqli_query($connection, $query) or die("Ошибка ".mysqli_error($connection));
                        }
                    }
                    break;
                
                case 'addschedule':
                    $check = "SELECT * FROM addschedule where groupName = '$massiv[0]' AND teacherName = '$massiv[1]' and day = '$massiv[2]' and (time = '$massiv[3]' OR timeTo = '$massiv[4]')";
                    $result = mysqli_query($connection, $check) or die(mysqli_error($connection));
                    if (mysqli_num_rows($result) == 0 && $massiv[11] == NULL && $massiv[4] != NULL){
                        $query = "INSERT INTO `addschedule` (`id`, `groupName`, `teacherName`, `day`, `time`, `timeTo`) VALUES (NULL, '".$massiv[0]."', '".$massiv[1]."', '".$massiv[2]."', '".$massiv[3]."', '".$massiv[4]."')";
                        if ($query){
                             mysqli_query($connection, $query) or die("Ошибка ".mysqli_error($connection));
                         }
                    }
                    break;

                case 'posts':
                    $check = "SELECT * FROM posts where studentName = '$massiv[1]' AND groupName = '$massiv[2]'";
                    $result = mysqli_query($connection, $check) or die(mysqli_error($connection));
                    if (mysqli_num_rows($result) == 0 && $massiv[2] == NULL ){
                        $query = "INSERT INTO `posts` (`studentName`, `groupName`) VALUES ('".$massiv[0]."', '".$massiv[1]."')";
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
    function proverka() {
    if (confirm("Отправить файл?")) {
        return true;
    } else {
        return false;
    }
}
</script>
</script>

<?php

echo '<!--<div class="menu">
    <div class="content">
        <form method="POST">
            <input type="search" name="search" placeholder="Введите Ф.И.О. учащегося..." class="inp-stud">
            <button type="submit" class="search-btn" name="search-btn">Поиск</button>
            <a href="group-panel.php"class="ads"><p>Дополнительное образование</p></a>
            <a href="#"class="stud"><p>Учащийся</p></a>
        </form>
    </div>
</div>

 <div class="content">
    <div class="addBlock">
        <p class="p-und-l">Добавление</p>
        <div class="empty"></div>
        <form method="POST" action="">
            <input autocomplete="off" type="text" name="searchAdd" class="searchAdd" placeholder="Введите Ф.И.О. учащегося для записи..." required>
            <input type="number" min="1" max="10" class="searchAdd" id="number" placeholder="Кол-во строк" required name="number">
            <button type="" name="accept-btn" class="accept-1">Подтвердить</button>
            <div class="empty2"></div>
        </form> -->';

        

        echo'<form method="POST">';

                if(isset($_POST['accept-btn']) and isset($_POST['searchAdd']))
                {
                    $number = $_POST['number'];
                    $fullname = $_POST['searchAdd'];
                    $_SESSION['admin'] = $_POST['searchAdd'];
                    $query = "SELECT * FROM students WHERE full_name = '$fullname'";
                    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

                    if (mysqli_num_rows($result) > 0){

                        for ($i=0; $i < $number; $i++) {

                        echo '<input type="text" placeholder="'.$_POST['searchAdd'].'" id="first" name="fio['.$i.']" class="add-str" value="" readonly>
                        <input type="text" placeholder="Введите наименование группы дополнительного образования" id="second" name="group['.$i.']" value="" class="add-str">';
                        }
                    }

                    else {
                        echo "Ученик не найден!";
                    }
                    echo '<div class="empty4"></div>
                    <button class="accept-str" type="submit" name="accept-2">Сохранить</button>';
                }
            
            echo '</form>';

            if(isset($_POST['accept-2'])){
                
                $a = 0;

                $fullname = $_SESSION['admin'];
                
                foreach ($_POST['group'] as $l => $group_value) {
                    
                    $group[$a] = $group_value;

                    $a = $a + 1;
                }

                for ($i=0; $i <= $l; $i++) { 

                    $query = "SELECT groupName FROM infoadd WHERE groupName = '$group[$i]'";
                    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

                    if (mysqli_num_rows($result) > 0){
                        
                        $query = "SELECT * FROM posts WHERE studentName='$fullname' and groupName='$group[$i]'";
                        $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
                        
                        if (mysqli_num_rows($result) <= 0){
                            
                            $query1 = "INSERT INTO posts (studentName, groupName) VALUES ('$fullname','$group[$i]')";

                            if ($query1){

                                mysqli_query($connection, $query1);

                                echo '<script>window.location.reload();</script>';

                            }
                        }
                    }
                }
            }
    echo '</div>';

        if(isset($_POST['search-btn']))
        {
            $_SESSION['adm'] = $_POST['search'];

            $fullname = $_POST['search'];

            $query = "SELECT * FROM posts WHERE studentName = '$fullname'";
            $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

            if (mysqli_num_rows($result) > 0){

                $students = get_students();
                $k = $students[0];

                echo '<p class="p-und-l" id="und-2">Просмотр</p><div class="empty3"></div>';
                if (!empty($k))
                {
                    foreach ($students as $k) :
                        $groupName = $k['groupName'];
                        echo '<div>
                            <input type="text" class="delete-str" name="fio" value="'.$fullname.'" readonly>
                            <input type="text" class="delete-str" id="sec-del" name="group" value="'.$groupName.'" readonly>
                            <form class="" method="POST" name="'.$groupName.'">
                                <button type="submit" name="delbtn" value="'.$groupName.'" class="delete-btn">Удалить</button>
                            </form>
                        </div>';

                    endforeach;
                }
            }
        }
        echo '</div></body></html>';
?>

<script>
jQuery(document).ready(function() {
    jQuery(".qry-acpt").bind("click", function() {

        var id = jQuery(this).val();
        var fn = jQuery("#fio" + id).val();
        var gp = jQuery("#grp" + id).val();
        var ph = jQuery("#phone" + id).val();
        var em = jQuery("#mail" + id).val();

        jQuery.ajax({
            url: "/php-scripts/del-unp.php",
            type: "POST",
            data: {id:id, fn:fn, gp:gp, ph:ph, em:em}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
                    $( "#" + id).remove();

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

jQuery(document).ready(function() {
    jQuery(".qry-acpt-p").bind("click", function() {

        var id = jQuery(this).val();
        var fn = jQuery("#fio" + id).val();
        var gp = jQuery("#grp" + id).val();
        var ph = jQuery("#phone" + id).val();
        var em = jQuery("#mail" + id).val();

        jQuery.ajax({
            url: "/php-scripts/app-add.php",
            type: "POST",
            data: {id:id, fn:fn, gp:gp, ph:ph, em:em}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
                    $( "#" + id).remove();

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

jQuery(document).ready(function() {
    jQuery(".qry-acpt-d").bind("click", function() {

        var id = jQuery(this).val();
        var fn = jQuery("#fio" + id).val();
        var gp = jQuery("#grp" + id).val();

        jQuery.ajax({
            url: "/php-scripts/app-del.php",
            type: "POST",
            data: {id:id, fn:fn, gp:gp}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
                    $( "#" + id).remove();
                    $( "." + id).remove();

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

jQuery(document).ready(function() {
    jQuery(".search-g").bind("click", function() {

        var id = jQuery(".srch-grp").val();

        jQuery.ajax({
            url: "/php-scripts/srch-grp.php",
            type: "POST",
            data: {id:id}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
               if (result){ 
                    jQuery('.col-3').remove();

                    //alert(result.info.address);
                    jQuery('.grp-info').append(function(){
                        var res = '';
                        for(var i = 0; i < result.info.teacherName.length; i++){
                            res += '<div class="col-3"><p class="big-p">' + result.info.vid[i] + '</p><p class="sml-p">' + result.info.name[i] + '</p></div><div class="col-3"><p class="sml-p">Педагог</p><p class="p-tch">' + result.info.teacherName[i] + '</p><p class="sml-p">Место проведения</p><p class="p-place">' + result.info.address[i] + '</p></div><div class="col-3"><p class="sml-p">Расписание</p>';
                            for(var j = 0; j < result.sche.time.length; j++){
                                res += '<div class="days"><p class="day-p">' + result.sche.day[j] + '</p><p class="time-p">' + result.sche.time[j].substring(0,5) +'-' + result.sche.timeTo[j].substring(0,5) + '</p></div>'
                            }
                        }
                            res += '</div>';
                            return res;
                    });
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

jQuery(document).ready(function() {
    jQuery(".ed-ac").bind("click", function() {

        var id = jQuery(".ed-gn").val();
        
        jQuery.ajax({
            url: "/php-scripts/edit-add.php",
            type: "POST",
            data: {id:id}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
                    jQuery('.edit-add').remove();
                    jQuery('.edit-look').remove();

                    //alert(result.posts.studentName);

                    //вывод в просмотр/удаление
                    jQuery('#ed-id').append(function(){
                        var res = '';

                        res += '<div class="edit-add"><p class="edta-p">Добавление</p><label class="eds-p">Выберите количество строк:</label><input class="ed-numb" type="number" min="1" max="10"><input class="numb-ac" type="submit" value="Подтвердить" onclick="add_str();"><div class="add-blk"></div></div><div class="edit-look"><p class="el-p">Просмотр/удаление</p><div class="ed-lk"></div></div>';

                            return res;
                    });

                    jQuery('.ed-lk').append(function(){
                        var rst = '';



                        for(var i = 0; i < result.posts.studentName.length; i++){
                                rst += '<div id="'+ result.posts.id[i] + '"><input style="display: none" class="lk-inp inp-gr" id="grnm" name="gr-nm[]" type="text" value="' + result.posts.groupName[i] + '" disabled><input class="lk-inp-fio" id="fio'+ result.posts.id[i] + '" type="text" value="' + result.posts.studentName[i] + '" placeholder="ФИО учащегося..."><input class="lk-inp-phone" id="phone'+ result.posts.id[i] + '" type="text" value="' + result.posts.phone[i] + '" placeholder="Номер телефона..." maxlength="11"><input class="lk-inp-email" id="email'+ result.posts.id[i] + '" type="text" value="' + result.posts.email[i] + '" placeholder="Email..."><input class="qry-delete" type="submit" id="'+ result.posts.id[i] + '" value="Удалить"><input class="qry-save" type="submit" id="'+ result.posts.id[i] + '" value="Сохранить"></div>';
                            }

                            jQuery(document).ready(function() {
                                jQuery(".qry-delete").bind("click", function() {

                                    var id = jQuery(this).attr("id");
                                    

                                    alert
                                    
                                    jQuery.ajax({
                                        url: "/php-scripts/del-stud.php",
                                        type: "POST",
                                        data: {id:id}, // Передаем данные для записи
                                        dataType: "text",
                                        success: function(result) {
                                            if (result){ 
                                                $( "#" + id ).remove();
                                            }else{
                                                alert(result.message);
                                            }
                                            return false;
                                        }
                                    });
                                return false;
                                });
                            });

                            jQuery(document).ready(function() {
                                jQuery(".qry-save").bind("click", function() {

                                    var id = jQuery(this).attr("id");
                                    var fio = jQuery("#fio"+id).val();
                                    var phone = jQuery("#phone"+id).val();
                                    var email = jQuery("#email"+id).val();
                                    //alert(id + " " + fio + " " + phone + " " + email);
                                    //return false;
                                    
                                    jQuery.ajax({
                                        url: "/php-scripts/update-stud.php",
                                        type: "POST",
                                        data: {id:id, fio:fio, phone:phone, email:email}, // Передаем данные для записи
                                        dataType: "text",
                                        success: function(result) {
                                            if (result){ 
                                                alert(result.out['message']);
                                            }else{
                                                alert(result.out.message);
                                            }
                                            return false;
                                        }
                                    });
                                return false;
                                });
                            });

                        return rst;
                    });

                }else{
                    alert(result.message);
                }
                return false;
            }
        });
    return false;
    });
});

function add_str(){
    var numb = jQuery(".ed-numb").val();

    var grup = jQuery("#ed-gn").val();

    jQuery.ajax({

    success: function(result) {
        if (result){ 
            jQuery( ".add-blk").remove();

            jQuery('.edit-add').append(function(){

                var rest = '<div class="add-blk">';

                for (var i = 0; i < numb; i++) {

                    rest += '<form method="POST" action="" class="del-frm qr-frm ed-frm"><h3>ФИО: </h3><input type="text" id="blk-fn' + i + '" class="qry-inp i-fr" placeholder="Введите ФИО ученика..."><h3>Группа: </h3><input type="text" id="blk-gr' + i + '" class="qry-inp i-fr ed-gr" value="' + grup + '" disabled><h3>Телефон: </h3><input type="text" id="blk-phone' + i + '" class="qry-inp i-fr ed-phone" placeholder="Номер телефона"><h3>Почта: </h3><input type="text" id="blk-mail' + i + '" class="qry-inp i-fr ed-mail" placeholder="Email"><br><input class="blk-ac" id="' + i + '" type="submit" value="Сохранить"></form>';

                }
                jQuery(document).ready(function() {
                    jQuery(".blk-ac").bind("click", function() {
                        var id = $(this).attr("id");
                        var gr = $("#blk-gr"+id).val();
                        var fio = $("#blk-fn"+id).val();
                        var phone = $("#blk-phone"+id).val();
                        var email = $("#blk-mail"+id).val();

                        //alert(id + " " + gr + " " + fio + " " + phone + " " + email);
                        //return false;

                        $.ajax({
                            url: "/php-scripts/save-stud.php",
                            type: "POST",
                            data: {gr:gr, fio:fio, phone:phone, email:email}, // Передаем данные для записи
                            dataType: "json",
                            success: function(result){
                                if(result){
                                    alert(result.message);
                                }
                                return false;
                            }
                            
                        });
                        return false;
                    });
                });

                return rest;

                });
            }
            return false;
        }
    });
    return false;
};



    
$(document).ready(function() {
 
    // Обработчик события keyup, сработает после того как пользователь отпустит кнопку, после ввода чего-либо в поле поиска.
    // Поле поиска из файла 'index.php' имеет id='search'
    $(".ed-gn").keyup(function() {
 
        // Присваиваем значение из поля поиска, переменной 'name'.
        var name = $('.ed-gn').val();
 
        // Проверяем если значение переменной 'name' является пустым
        if (name === "") {
 
            // Если переменная 'name' имеет пустое значение, то очищаем блок div с id = 'display'
            $("#display").html("");
 
        }
        else {
            // Иначе, если переменная 'name' не пустая, то вызываем ajax функцию.
 
            $.ajax({
 
                type: "POST", // Указываем что будем обращатся к серверу через метод 'POST'
                url: "/php-scripts/group-search.php", // Указываем путь к обработчику. То есть указывем куда будем отправлять данные на сервере.
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
 
    $('.ed-gn').val(Value); // Берем значение элемента из результата поиска и добавляем его в значение поля поиска
 
    $('#display').hide(); // Скрываем результаты поиска
 
}



</script>

<!--<div class="edit-add">
    <p class="edta-p">Добавление</p>
    <label class="eds-p">Выберите количество строк:</label>
    <input class="ed-numb" type="number" min="1" max="10">
    <input class="numb-ac" type="submit" value="Подтвердить">
    <div class="add-blk">
        <input class="blk-inp" type="text" value="Группа" disabled>
        <input class="blk-inp" id="blk-fn" type="text" value="ФИО">
        <input class="blk-ac" type="submit" value="Подтвердить">
    </div>
</div>

<div class="edit-look">
                <p class="el-p">Просмотр/удаление</p>
                <div class="ed-lk">
                    <input class="qry-inp" type="text" value="Группа" disabled>
                    <input class="qry-inp" type="text" value="ФИО" disabled>
                    <input class="qry-acpt" type="submit" value="Удалить">
                    <input class="qry-inp" type="text" value="Группа" disabled>
                    <input class="qry-inp" type="text" value="ФИО" disabled>
                    <input class="qry-acpt" type="submit" value="Удалить">
                </div>
            </div> -->