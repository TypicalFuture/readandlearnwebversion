<?php
session_start();

/*if (empty($_SESSION['user'])) {
    header('location: index.php');
}

if ($_SESSION['dostup'] == 2){
    header('location: schedule.php');
}
*/

if (empty($_SESSION['user'])) {
    $btex = 'Вход';
    $empt = 1;
}

$fullname1 = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['user']['full_name']);

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
    <link rel="stylesheet" type="text/css" href="css/online-reception.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <script src="js/jquery.js"></script>
    <title>Онлайн-приемная</title>
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
            <a class="social" href="rb-stud.php">БРС</a>
        </div>';
        }
        ?>
        
        <?php
        if ($_SESSION['dostup'] == 1)
        {
        echo '<div class="headerSocial">
            <a class="social" href="record-book.php">БРС</a>
        </div>';
        }
        ?>
        
        <div class="header-reception">
            <a class="reception" href="online-reception.php">Онлайн-приемная</a>
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

        if ($_SESSION['dostup'] == 2)
        {
            echo '<div class="headerAdd">
            <a class="add" href="add.php?postsNow=">Доп. образование</a>
            </div>';
        } 
?>

        <div class="headerSchedule">
            <a class="schedule" href="schedule.php">Расписание</a>
        </div>
    </div>
</div>

<div class="container">

    <div class="block leader"><a href="#"><p>Подать обращение<br>к руководителю</p></a></div>

    <div class="block"><a target="_blank" href="https://forms.gle/vrenBhkyqwhAKPfR6"><p>Записаться<br>к учителю предметнику</p></a></div>

    <div class="block"><a target="_blank" href="https://forms.gle/C33njeVDaEGt3mEg8"><p>Записаться<br>к социальному педагогу</p></a></div>

    <div class="block"><a target="_blank" href="https://forms.gle/vNj2rVdTWTGrKSg86"><p>Записаться<br>к психологу</p></a></div>

    <div class="block"><a target="_blank" href="https://forms.gle/euxU12anUC2auSWGA"><p>Подать заявку<br>на получение книг<br>в библиотеке</p></a></div>

    <div class="block"><a target="_blank" href="https://forms.gle/1fjNRMC71ryDMNQ76"><p>Информировать<br>об отсутствии</p></a></div>

    <div class="block"><a target="_blank" href="https://forms.gle/bVbuHAw69LFccc9G6"><p>Заказать справку<br>об обучении</p></a></div>

    <div class="block report"><a target="_blank" href="https://forms.gle/XnC99UbjMuUWR8vQ9"><p>Пожаловаться</p></a></div>

</div>


<script>
    $(document).ready(function() {
        $(".leader").bind("click", function() {

            var res ="";

            $(".block").remove();

            $(".container").append(function(){
                res += '<a class="back-a" href="online-reception.php"><p class="back">Назад</p></a><p class="p-inf">Обращение к директору</p><div class="lead-block"><div class="info-card"><img class="img-lead" src="img/l-1.png"><div class="info-place"><p class="p-1">Артемьев Игорь Анатольевич</p><p class="p-2">Директор</p></div><a class="link-a" href="https://forms.gle/AhWHFcz1j1yFEa9v8" target="_blank"><div class="link"><p>Обратиться</p></div></a></div></div><p class="u-p-inf">Обращение к заместителю директора</p><div class="u-lead-block"><div class="info-card"><img class="img-lead" src="img/l-2.png"><div class="info-place"><p class="p-1">Голубкова Елена Аркадьевна</p><p class="p-2">Заместитель директора по учебно-воспитательной работе</p></div><a class="link-a" href="https://forms.gle/wMXsNx4TrEVZSv9y6" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="info-card"><img class="img-lead" src="img/l-3.png"><div class="info-place"><p class="p-1">Петрова Ирина Викторовна</p><p class="p-2">Заместитель директора по учебной работе</p></div><a class="link-a" href="https://forms.gle/zQzniqvtKD4JdvqD9" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="info-card"><img class="img-lead" src="img/l-4.png"><div class="info-place"><p class="p-1">Шишкина Анна Александровна</p><p class="p-2">Заметитель директора</p></div><a class="link-a" href="https://forms.gle/4jar6s4Rvaz8G4C88" target="_blank"><div class="link"><p>Обратиться</p></div></a></div></div><p class="z-p-inf">Обращение к заведующему кафедрой</p><div class="z-lead-block"><div class="z-info-card"><img class="img-lead" src="img/unnamed.png"><div class="info-place"><p class="p-1">Сивова Ирина Станиславовна</p><p class="p-2">Заведующий кафедрой дизайна и рекламы</p></div><a class="link-a" href="https://forms.gle/NngwGukttJD2uPd38" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-2.png"><div class="info-place"><p class="p-1">Котова Оксана Петровна</p><p class="p-2">Заведующий кафедрой здоровьесберегающих дисциплин</p></div><a class="link-a" href="https://forms.gle/zRLM6A12DhiHEvAk9" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-3.png"><div class="info-place"><p class="p-1">Патеева Руфина Тахировна</p><p class="p-2">Заведующий кафедрой земельно-имущественных отношений</p></div><a class="link-a" href="https://forms.gle/JnqNZGLQVowKVbH56" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-4.png"><div class="info-place"><p class="p-1">Зигфрид Дмитрий Леонидович</p><p class="p-2">Заведующий кафедрой иностранных языков</p></div><a class="link-a" href="https://forms.gle/N3M7pBMKhEHRJZV49" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-5.png"><div class="info-place"><p class="p-1">Полубабкин Виталий Петрович</p><p class="p-2">Заведующий кафедрой информационных технологий</p></div><a class="link-a" href="https://forms.gle/RafXaiXr3pC3dX176" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-6.png"><div class="info-place"><p class="p-1">Акопян Светлана Владимировна</p><p class="p-2">Заведующий кафедрой лингвистики и начальной школы</p></div><a class="link-a" href="https://forms.gle/w2LGgpPKKv68GobM6" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-7.png"><div class="info-place"><p class="p-1">Руденко Галина Михайловна</p><p class="p-2">Заведующий кафедрой математических и естественнонаучных дисциплин</p></div><a class="link-a" href="https://forms.gle/os8MnCgG7H5DAYiv9" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-8.png"><div class="info-place"><p class="p-1">Кильдеев Тимур Анверович</p><p class="p-2">Заведующий кафедрой технологии машиностроения</p></div><a class="link-a" href="https://forms.gle/Ro2MhgKmn7rKSr7J7" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-9.png"><div class="info-place"><p class="p-1">Гомзин Сергей Григорьевич</p><p class="p-2">Заведующий кафедрой промышленной фармацевтики</p></div><a class="link-a" href="https://forms.gle/amhKgyEAv2juXuom7" target="_blank"><div class="link"><p>Обратиться</p></div></a></div><div class="z-info-card"><img class="img-lead" src="img/2-10.png"><div class="info-place"><p class="p-1">Панга Елена Владимировна</p><p class="p-2">Заведующий кафедрой экономики, права и социально-гуманитарных дисциплин</p></div><a class="link-a" href="https://forms.gle/F6zmutUs6DCz949t9" target="_blank"><div class="link"><p>Обратиться</p></div></a></div></div>';
                return res;
            });
            return false; 
        });
    });
</script>
<?php require_once('footer.php');?>
</body>
</html>