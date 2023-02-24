<?php
session_start();

if (empty($_SESSION['user'])) {
    header('location: index.php');
}

if ($_SESSION['dostup'] == 2){
    header('location: schedule.php');
}

global $chet;
global $tip;

//require('connect.php');
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
    <link rel="stylesheet" type="text/css" href="css/support.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <title>Техподдержка</title>
</head>
<body>

<div class="header">
    <div class="containerHeader">
        <div class="header_logo">
            <a href="https://mgok.mskobr.ru/" target="_blank"><img src="/img/MGOK.svg" alt="МГОК" title="МГОК" class="logo"/></a>
        </div>
        
        <button class="ex-btn" name="submit" onclick="document.location.replace('exit.php');">Выход</button>


        <div class="user_name"><p><?=preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['user']['full_name']);?></p></div>

        <?php
        if ($_SESSION['dostup'] == 1)
        {
        echo '<div class="headerBRS">
            <a class="BRS" href="record-book.php">БРС</a>
        </div>';
        }
        ?>
    
        <div class="header-reception">
            <a class="reception" href="online-reception.php">Онлайн-приемная</a>
        </div>

        <div class="headerSupport">
            <a class="support" href="social.php">Техподдержка</a>
        </div>
        
        <div class="header-load">
            <a class="load-c" href="load-cabs.php">Загруженность кабинетов</a>
        </div>

<?php

        if ($_SESSION['dostup'] == 2)
        {
            echo '<div class="headerAdd">
            <a class="add" href="add.php?postsNow=">Дополнительное образование</a>
            </div>';
        } 
?>
        <div class="headerSchedule">
            <a class="schedule" href="schedule.php">Расписание</a>
        </div>
        
    </div>
</div>

<div class="container">
    
    <p class="title">Google forms:</p>

    <div class="block"><a target="_blank" href="https://forms.gle/TDW2c7Xy4J8oeEfj8"><p>1С</p></a></div>

    <div class="block"><a target="_blank" href="https://forms.gle/rwWkDYt31mMe13KT6"><p>ИТ поддержка</p></a></div>

    <div class="block"><a target="_blank" href="https://forms.gle/E4sSHk5fcrX222YK6"><p>Запрос на выполнение работ АХЧ</p></a></div>

</div>