<?php
session_start();

    if (!$_SESSION['user']) {
        header('location: index.php');
    }

global $chet;
global $tip;

require('php-scripts/connect.php');
require('functions.php');


$fullname = $_SESSION['user']['full_name'];
if($_SESSION['dostup'] == 2){
    $class = $_SESSION['user']['class'];
}
$tip = $_SESSION['dostup'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Caomatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/add.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script type="text/javascript" src="popup.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="js/jquery.maskedinput.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <title>Дополнительное образование</title>
</head>
<body>

<div class="header">
    <div class="containerHeader">
        <div class="header_logo">
            <a href="https://mgok.mskobr.ru/" target="_blank"><img src="/img/MGOK.svg" alt="МГОК" title="МГОК" class="logo"/></a>
        </div>

        <button class="ex-btn" name="submit" onclick="document.location.replace('exit.php');">Выход</button>


        <div class="user_name" id=""><p><?=preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['user']['full_name']);?></p></div>

        <?php
        if ($_SESSION['dostup'] == 2)
        {
        echo '<div class="headerSocial">
            <a class="social" href="rb-stud.php">Электронный журнал</a>
        </div>';
        }
        ?>

        <div class="headerSocial">
            <a class="social" href="online-reception.php">Онлайн-приемная</a>
        </div>

        <?php

        if ($_SESSION['dostup'] == 1)
        {
            echo '<div class="headerSupport">
            <a class="support" href="support.php">Техподдержка</a>
                </div>';
        }

        ?>
        <div class="header-load">
            <a class="load-c" href="load-cabs.php">Загруженность кабинетов</a>
        </div>

<?php
//
//        if ($_SESSION['dostup'] == 2)
//        {
//            echo '<div class="headerAdd">
//            <a class="add" href="add.php?postsNow=">Доп. образование</a>
//            </div>';
//        }
?>

        <div class="headerSchedule">
            <a class="schedule" href="schedule.php">Расписание</a>
        </div>
    </div>
</div>

<div class="underH">
        <div class="underContent">
            <div class="links">
                <div class="up-menu">
                    <input type="checkbox" id="menu_toggle" >
                    <label class="menu_btn" for="menu_toggle">
                        <span></span>
                    </label>
                    <ul class="menu_box">
                        <form method="GET" action="add.php">
                            <li><button name="postsNow" class="main" id="frst" type="submit">Текущие записи</button></li>
                            <li><button name="buttonC" class="main" id="fsn"  value="Физкультурно-спортивная направленность">Физкультурно-спортивная направленность</button></li>
                            <li><button name="buttonC" class="main" id="en" type="submit" value="Естественнонаучная направленность">Естественнонаучная направленность</button></li>
                            <li><button name="buttonC" class="main" id="spn" type="submit" value="Социально-педагогическая направленность">Социально-педагогическая направленность</button></li>
                            <li><button name="buttonC" class="main" id="tn" type="submit" value="Техническая направленность">Техническая направленность</button></li>
                            <li><button name="buttonC" class="main" id="hn" type="submit" value="Художественная направленность">Художественная направленность</button></li>
                            <li><button name="buttonC" class="main" id="tkn" type="submit" value="Туристско-краеведческая направленность">Туристско-краеведческая направленность</button></li>
                            <li><button name="buttonC" class="main" id="po" type="submit" value="Профессиональное образование">Профессиональное образование</button></li>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
</div>
<div class="overlay_popup"></div>

<div class="container">

    <?php

    if(isset($_GET['buttonC']))
    {  
        $catAdd = $_GET['buttonC'];
        $table = get_addCheckMenu();
        

        $arr = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");

        if (!empty($table))
        {

            $query1 = "SELECT * FROM app_add WHERE studentName='$fullname'";
            $result1 = mysqli_query($connection, $query1) or die(mysqli_error($connection));
            if(mysqli_num_rows($result1) > 0){
                echo'<div class="bmsg"><p class="n-o">У вас уже есть заявка на рассмотрении</p></div>';
            }

            else{

            $ids = 0;
            $tabl = $table[0];
            foreach ($table as $tabl) :
                $groupName = $tabl['groupName'];
                $a = 0;
                $count = mt_rand(1000, 34000);
                $arra = $_SESSION['ar'];
                for($i = 0; $i < 7; $i++)
                {
                    $gdac = get_dayAddCheck();
                    
                    
                    if (!empty($gdac))
                    {
                        $gdacS = $gdac[0];
                        switch($gdacS['day']){
                        
                            case "Понедельник":
                                if($arra[0] >= (integer)str_replace(':','',substr($gdacS['time'], 0, 5))){
                                    $a++;
                                }
                                break;
                            case "Вторник":
                                if($arra[1] >= (integer)str_replace(':','',substr($gdacS['time'], 0, 5))){
                                    $a++;
                                }
                                break;
                            case "Среда":
                                if($arra[2] >= (integer)str_replace(':','',substr($gdacS['time'], 0, 5))){
                                    $a++;
                                }
                                break;
                            case "Четверг":
                                if($arra[3] >= (integer)str_replace(':','',substr($gdacS['time'], 0, 5))){
                                    $a++;
                                }
                                break;
                            case "Пятница":
                                if($arra[4] >= (integer)str_replace(':','',substr($gdacS['time'], 0, 5))){
                                    $a++;
                                }
                                break;
                        }
                    }
                }
                $id = 1;
                if($a == 0){
                    echo '

                    <div class="modal"  id="'.$count.'">
                        <p class="m-title">Отправка заявки</p>
                        <p class="m-par">Для отправления заявки на рассмотрение необходимо оставить контактный номер телефона и адрес электронной почты, чтобы мы могли связаться с Вами и подтвердить вашу заявку.</p>
                        <form class="buttonAdd" method="POST">
                            <p class="m-inf">Группа:</p>
                            <p class="p-gr">'.$tabl['groupName'].'</p>
                            <p class="m-inf">Номер телефона:</p>
                            <input name="online_phone" id="online_phone" class="online_phone'.$ids.'" name="phone" type="text" maxlength="50" value required placeholder="+79999999999">
                            <p class="m-inf">Почта:</p>
                            <input id="mail" class="mail'.$ids.'" type="text" placeholder="example@mail.ru" required>

                            <button type="submit" id="'.$ids.'" name="btn" value="'.$groupName.'" class="addString addS">Записаться</button>
                        </form>
                    </div>

                    <div class="popup" id="'.$tabl['id'].'">
                        <div class="object">
                            <p class="parTeacher">Наименование группы:</p>
                            <p class="teacher">'.$groupName.'</p>
                            <p class="parTeacher">Возрастная группа: </p>
                            <p class="teacher">'.$tabl['ageGroupFrom']."-".$tabl['ageGroupTo']." лет".'</p>
                            <p class="parTeacher">Описание: </p>
                            <p class="teacher">'.$tabl['about'].'</p>
                            
                        </div>
                    </div>';

                    
                    echo'
                    <div class="check">
                        <div class="parTitle"><p>'.$tabl['vid'].'</p></div>
                        <div class="name show_popup" rel="'.$tabl['id'].'"><a>'.$tabl['groupName'].'</a></div>

                        <p class="parTeacher">Педагог</p>

                        <div class="teacher"><p>'.$tabl['teacherName'].'</p></div>';

                        

                        echo'<p class="par">Место проведения</p>
                        <div class="address"><p>'.$tabl['address'].'</p></div>
                        
                        <p class="parDay">Расписание</p>';

                        for($i = 0; $i < 7; $i++)
                        {
                            $gdac = get_dayAddCheck();
                            
                            
                            if (!empty($gdac))
                            {
                                $gdacS = $gdac[0];
                                echo'<div class="day"><p>'.$gdacS['day'].'</p></div>
                                <div class="time"><p>'.substr($gdacS['time'], 0, 5)."-".substr($gdacS['timeTo'], 0, 5).'</p></div>';
                            }
                        }

                        $fio = $_SESSION['user']['full_name'];

                        $query1 = "SELECT * FROM app_add WHERE studentName='$fio' and groupName = '$groupName'";
                        $result1 = mysqli_query($connection, $query1) or die(mysqli_error($connection));

                        if(mysqli_num_rows($result1) == 0){
                            echo'<form class="buttonAdd" method="POST" >
                                <input type="button" class="snd-inp" rel="'.$count.'" value="Отправить заявку">
                                </form>';
                            $ids++;
                            $count++;
                        }
                        else{
                            echo '
                            <form class="buttonAdd" method="POST" >
                            <button type="button" name="btn" value="'.$groupName.'" class="wait-add" disabled>На рассмотрении</button>
                            </form>';
                        }
                        
                        
                    echo'</div>';
                    
                }
                $vid = $tabl['vid'];
                $name = $tabl['name'];
                $teacher = $tabl['teacherName'];
                $fio = $_SESSION['user']['full_name'];
                $class = $_SESSION['user']['class'];
                $dob = $_SESSION['user']['dob'];
            endforeach;
}
        }
        else{
            echo'<div class="bmsg"><p class="v-c">В категории</p><p class="ctg">"'.$catAdd.'"</p><p class="n-o">нет подходящих записей</p></div>';
        }
    }
    elseif(isset($_GET['postsNow']))
    {
        $arr = $_SESSION['days'];

        $sql = "SELECT * FROM `app_add` WHERE studentName = '$fullname'";

        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0){

            $user = mysqli_fetch_assoc($result);

            $w_add['w'] = ["groupName" => $user['groupName']];

            $w_grp = $w_add['w']['groupName'];
            
            $sql = "SELECT * FROM `infoadd` where groupName = '$w_grp' and groupName IN (SELECT groupName FROM app_add WHERE studentName = '$fullname')";

            $result = mysqli_query($connection, $sql);

            $table = mysqli_fetch_all($result, MYSQLI_ASSOC);

            if (!empty($table))
            {

                $tabl = $table[0];
                $ids = 0;
                foreach ($table as $tabl) : 

                    echo '<div class="popup" id="'.$tabl['id'].'">
                        <div class="object">
                            <p class="parTeacher">Наименование группы:</p>
                            <p class="teacher">'.$groupName.'</p>
                            <p class="parTeacher">Возрастная группа: </p>
                            <p class="teacher">'.$tabl['ageGroupFrom']."-".$tabl['ageGroupTo']." лет".'</p>
                            <p class="parTeacher">Описание: </p>
                            <p class="teacher">'.$tabl['about'].'</p>
                            
                        </div>
                    </div>';

                    $groupName = $tabl['groupName'];
                        echo'<div class="check w_add">
                        <div class="parTitle"><p>'.$tabl['vid'].'</p></div>
                        <div class="name show_popup" rel="'.$tabl['id'].'"><a>'.$tabl['groupName'].'</a></div>
                        <p class="parTeacher">Педагог</p>
                        <div class="teacher"><p>'.$tabl['teacherName'].'</p></div>
                        <p class="parDay">Расписание</p>';
                        for($i = 0; $i < 7; $i++)
                        {
                            $gdac = get_dayAddCheck();
                            
                            if (!empty($gdac))
                            {
                                $gdacS = $gdac[0];
                                echo'<div class="day"><p>'.$gdacS['day'].'</p></div>
                                <div class="time"><p>'.substr($gdacS['time'], 0, 5)."-".substr($gdacS['timeTo'], 0, 5).'</p></div>';
                            }
                        }
                        echo'<p class="par">Место проведения</p>
                        <div class="address"><p>'.$tabl['address'].'</p></div>
                        </div>';
                endforeach;
                        
            }
        }

        $table2 = get_addCheck();

        if (!empty($table2))
        {
            $tabl = $table2[0];
            $ids = 0;
            foreach ($table2 as $tabl) : 

                echo '<div class="popup" id="'.$tabl['id'].'">
                        <div class="object">
                            <p class="parTeacher">Наименование группы:</p>
                            <p class="teacher">'.$groupName.'</p>
                            <p class="parTeacher">Возрастная группа: </p>
                            <p class="teacher">'.$tabl['ageGroupFrom']."-".$tabl['ageGroupTo']." лет".'</p>
                            <p class="parTeacher">Описание: </p>
                            <p class="teacher">'.$tabl['about'].'</p>
                            
                        </div>
                    </div>';

                $groupName = $tabl['groupName'];
                    echo'<div class="check">
                    <div class="parTitle"><p>'.$tabl['vid'].'</p></div>
                    <div class="name show_popup" rel="'.$tabl['id'].'"><a>'.$tabl['groupName'].'</a></div>
                    <p class="parTeacher">Педагог</p>
                    <div class="teacher"><p>'.$tabl['teacherName'].'</p></div>
                    <p class="parDay">Расписание</p>';
                    for($i = 0; $i < 7; $i++)
                    {
                        $gdac = get_dayAddCheck();
                        
                        if (!empty($gdac))
                        {
                            $gdacS = $gdac[0];
                            echo'<div class="day"><p>'.$gdacS['day'].'</p></div>
                            <div class="time"><p>'.substr($gdacS['time'], 0, 5)."-".substr($gdacS['timeTo'], 0, 5).'</p></div>';
                        }
                    }
                    echo'<p class="par">Место проведения</p>
                    <div class="address"><p>'.$tabl['address'].'</p></div>
                    <form class="buttonAdd" method="POST" name="'.$groupName.'">';
                    $fio = $_SESSION['user']['full_name'];
                    $query1 = "SELECT * FROM unposts WHERE studentName='$fio' and groupName = '$groupName'";
                    $result1 = mysqli_query($connection, $query1) or die(mysqli_error($connection));
                    
                    if(mysqli_num_rows($result1) == 0){
                        echo '<button type="submit" name="btn" id="unpst'.$ids.'" value="'.$groupName.'" class="addString unps">Отписаться</button>';
                        $ids += 1;
                    }

                    else{
                        echo '<button type="button" name="btn" value="'.$groupName.'" class="wait-add" disabled>На рассмотрении</button>';
                    }

                    echo '</form>';

                    $vid = $tabl['vid'];
                    $name = $tabl['name'];
                    $teacher = $tabl['teacherName'];
                    $fio = $_SESSION['user']['full_name'];
                    $class = $_SESSION['user']['class'];
                    $dob = $_SESSION['user']['dob'];          
                    
                echo'</div>';
            endforeach;
                    

            /*
            if(isset($_POST['btn']))
            {  
                $gogo = $_POST['btn'];
                $query = "SELECT * FROM posts WHERE studentName='$fio' and groupName='$gogo'";
                $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
                if (mysqli_num_rows($result) > 0){
                    $query = "INSERT INTO `unposts` (`id`, `studentName`, `groupName`) VALUES (NULL, '$fio', '$gogo')";
                    if ($query)
                    {
                        mysqli_query($connection, $query);
                        echo '<script>alert("Ваша заявка на рассмотрении")</script>';
                        //echo '<script>window.location.reload();</script>';
                    }
                }
            }*/
        }
        else{
            if(empty($table) && empty($table2))
            echo'<div class="bmsg"><p class="n-o lk">Нет подходящих записей</p></div>';
        }
    }

echo '</div>';
require_once('footer.php');
echo'

</body>
</html>';
?>
<script>
    jQuery(document).ready(function() {
        jQuery(".unps").click(function() {
            var group = jQuery(this).val();
            var fio = '<?php echo $fio?>';
            var id = this.id;
            //alert(fio + " | " + group + id);
            //event.preventDefault();

            jQuery.ajax({
            url: "/php-scripts/unpost.php",
            type: "POST",
            data: {group:group, fio:fio}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){
                    jQuery("#" + id).html("На рассмотрении");
                    jQuery("#" + id).removeClass('addString unps');
                    jQuery("#" + id).addClass('wait-add');
                    jQuery("#" + id).attr('disabled', true);

                 }
                 console.log(result);
                }
            });
            return false
        });
    })

$('.show_popup').click(function() {
    var popup_id = $('#' + $(this).attr("rel"));
    $(popup_id).show();

    $('.overlay_popup').show();
})
$('.overlay_popup').click(function() {
    $('.overlay_popup, .popup').hide();
})

$('.snd-inp').click(function() {
    var modal_id = $('#' + $(this).attr("rel"));
    $(modal_id).show();

    $('.overlay_popup').show();
})
$('.overlay_popup').click(function() {
    $('.overlay_popup, .modal').hide();
})

$(function(){
  $("input[name='online_phone']").mask("+7(999) 999-99-99");
});


jQuery(document).ready(function() {
    jQuery(".addS").click(function() {

        var group = jQuery(this).val();
        var fio = '<?php echo $fio?>';
        var id = this.id;
        var phone = jQuery('.online_phone' + id).val().trim();
        var mail = jQuery('.mail' + id).val().trim();

        if (phone == "" || mail == "") {
            if(phone == "")
                $('.online_phone' + id).css('border-color', 'red');
            if(mail == "")
                $('.mail' + id).css('border-color', 'red');
           alert('Пожалуйста, заполните все поля');
        }

        else{

        
            //alert(fio + " | " + group + " | " + phone + " | " + mail);
            //event.preventDefault();
            $('.online_phone' + id).css('border-color', 'black');
            $('.mail' + id).css('border-color', 'black');
            
                jQuery.ajax({
                url: "/php-scripts/add-str.php",
                type: "POST",
                data: {group:group, fio:fio, phone:phone, mail:mail}, // Передаем данные для записи
                dataType: "json",
                success: function(result) {
                    if (result){
                        if(result.message == "Все хорошо"){
                            jQuery("#" + id).html("На рассмотрении");
                            jQuery("#" + id).removeClass('addString addS');
                            jQuery("#" + id).addClass('wait-add');
                            jQuery("#" + id).attr('disabled', true);
                        }
                        else{
                            $('.online_phone' + id).css('border-color', 'red');
                            $('.mail' + id).css('border-color', 'red');
                            alert("Введены неправильные данные!");
                        }
    
                     }
                     console.log(result);
                    }
                });
        }
        return false
    });
})
</script>