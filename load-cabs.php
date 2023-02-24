<?php
session_start();

if (empty($_SESSION['user'])) {
    $btex = 'Вход';
}
else
{
    $fullname_u = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['user']['full_name']);
    $btex = 'Выход';
}

global $chet;
global $tip;

require('php-scripts/connect.php');
require('functions.php');


//$fullname = $_SESSION['user']['full_name'];
//if($_SESSION['dostup'] == 2){
    //$class = $_SESSION['user']['class'];
//}
//$tip = $_SESSION['dostup'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Caomatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' type='text/css' href='css/load-c.css'>
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <link rel="stylesheet" type="text/css" href="">
    <script src="js/jquery.js"></script>
    <title>Загруженность кабинетов</title>
</head>
<body>

<?php 
include_once('functions.php');
?>


<div class="header">
    <div class="containerHeader">
        <div class="header_logo">
            <a href="https://mgok.mskobr.ru/" target="_blank"><img src="/img/MGOK.svg" alt="МГОК" title="МГОК" class="logo"/></a>
        </div>
        
        <button class="ex-btn" name="submit" onclick="document.location.replace('exit.php');"><?=$btex?></button>

        <?php
        if (!empty($_SESSION['user']))
            echo '<div class="user_name" id=""><p>'.$fullname_u.'</p></div>';
        ?>
        
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

<?php

        /*if ($_SESSION['dostup'] == 2)
        {
            echo '<div class="headerAdd">
            <a class="add" href="add.php?postsNow=">Дополнительное образование</a>
            </div>';
        } */

echo'

        <!--<div class="headerSchedule">
            <a class="schedule" href="schedule.php">Расписание</a>
        </div> -->
        
    </div>
</div>

<div class="under-h">
	<div class="container">
	<form class="form-get" method="GET" action="load-cabs.php">
		<input class="inp-cab" type="text" name="cab-num" maxlength="7" placeholder="Введите номер кабинета" require autocomplete="off">
		<select class="inp-adr" name="cab-adr" require>
			<option value="Стратонавтов, 15">Стратонавтов, 15</option>
			<option valie="Волгоградский проспект, 42">Волгоградский проспект, 42</option>
			<option value="Лодочная, 7">Лодочная, 7</option>
			<option value="пр.Старопетровский, д.1А">пр.Старопетровский, д.1А</option>
			<option value="Вишневая, 5">ул.Вишневая, д.5</option>
		</select>
		<input name="search-g" class="search-g" type="submit" value="Поиск">
	</form>
	</div>
</div>

<div class="container">
	<div class="load">';
	if(isset($_GET['cab-num'])){
	$inp = $_GET['cab-num'];

	if(isset($_GET['cab-num']) and !empty($inp)){
				$cab_num = $_GET['cab-num'];
				$cab_adr = $_GET['cab-adr'];
				echo'
		<div class="clmn">
			<div class="c-days">Понедельник</div>
			<div class="cel-load1" >';
				$day = 'Понедельник';
				require('php-scripts/cab.php');
				echo '
			</div>
		</div>	

		<div class="clmn">
			<div class="c-days">Вторник</div>
			<div class="cel-load2">';
				$day = 'Вторник';
				require('php-scripts/cab.php');
				echo '
			</div>
		</div>

		<div class="clmn">
			<div class="c-days">Среда</div>
			<div class="cel-load3">';
				$day = 'Среда';
				require('php-scripts/cab.php');
				echo'
			</div>
		</div>

		<div class="clmn">
			<div class="c-days">Четверг</div>
			<div class="cel-load4">';
				$day = 'Четверг';
				require('php-scripts/cab.php');
				echo'	
			</div>
		</div>

		<div class="clmn">	
			<div class="c-days">Пятница</div>
			<div class="cel-load5">';
				$day = 'Пятница';
				require('php-scripts/cab.php');	
				}
				};
				echo'
			</div>
		</div>
	</div>
</div>';
require_once('footer.php');
echo'
</body>
</html>';
?>

<script type="text/javascript">
$(".more_info").click(function () {
    var $title = $(this).find(".title");
    if (!$title.length) {
        $(this).append('<span class="title">' + $(this).attr("title") + '</span>');
    } else {
        $title.remove();
    }
});

$(".teach").click(function () {
    var $title = $(this).find(".title");
    if (!$title.length) {
        $(this).append('<p class="title">' + $(this).attr("title") + '</p>');
    } else {
        $title.remove();
    }
});
</script>