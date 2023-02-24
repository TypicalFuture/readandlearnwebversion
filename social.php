<?php
session_start();

if (empty($_SESSION['user'])) {
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
    <link rel="stylesheet" type="text/css" href="css/social.css">
    <link rel="shortcut icon" href="http://www.example.com/myicon.ico" />
    <title>Социальные сети</title>
</head>
<body>

<div class="header">
    <div class="containerHeader">
        <div class="header_logo">
            <a href="https://mgok.mskobr.ru/" target="_blank"><img src="/img/MGOK.svg" alt="МГОК" title="МГОК" class="logo"/></a>
        </div>
        
        <button class="ex-btn" name="submit" onclick="document.location.replace('exit.php');">Выход</button>


        <div class="user_name"><p><?=preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['user']['full_name']);?></p></div>

        <div class="header-load">
            <a class="load-c" href="online-reception.php">Онлайн-приемная</a>
        </div>
        
        <?php
        
        if ($_SESSION['dostup'] == 2)
        {
        echo '<div class="headerSocial">
            <a class="social" href="social.php">Соц. сети</a>
        </div>';
        }
        
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
    
    <div class="links" id="facebook"><a target="_blank" href="https://www.facebook.com/mgok.online"><img src="img/facebook.png"></a></div>

    <div class="links" id="vk"><a target="_blank" href="https://vk.com/mgok.online"><img src="img/vk.png"></a></div>

    <div class="links" id="youtube"><a target="_blank" href="https://www.youtube.com/channel/UCK8vTUV-lfJ7N2IAWstVZXg?view_as=subscriber"><img src="img/youtube.png"></a></div>

    <div class="links" id="instagram"><a target="_blank" href="https://www.instagram.com/mgok.online"><img src="img/instagram.png"></a></div>

</div>