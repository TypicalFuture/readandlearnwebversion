<?php
session_start();

//print_r($_SESSION['user']);

if (empty($_SESSION['user'])) {
    header('location: index.php');
}

if(!empty($_SESSION['user']['full_name']))
	$fullname = $_SESSION['user']['full_name'];

if(!empty($_SESSION['dostup']))
	if($_SESSION['dostup'] != 1)
		header('location: schedule.php');

require('php-scripts/connect.php');
require('functions.php');

$query = "SELECT class FROM `class_teacher` where fullname = '$fullname'";
$res = mysqli_query($connection, $query) or die(mysqli_error($connection));
$arrClass = mysqli_fetch_assoc($res);
$t_class = $arrClass['class'];

$query = $connection->query("SELECT username FROM `students` where class = '$t_class'");
if (mysqli_num_rows($query) > 0){

    while($row = $query->fetch_assoc()){
        $users['user'][] = $row['username'];
    }

    $out['users'] = $users;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Caomatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/record-book1.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <script src="js/jquery.js"></script>
    <title>Мероприятия класса</title>
</head>
<body>
	<div class="header">
		<div class="header-container">
			<div class="header-logo">
	            <a href="https://mgok.mskobr.ru/" target="_blank"><img src="/img/MGOK.svg" alt="МГОК" title="МГОК" class="logo"/></a>
	        </div>

	        <a class="back" href="schedule.php">Назад</a>
	        <p class="fullname" style="display: none;"><?=$_SESSION['user']['full_name']?></p>
        	<div class="username" id=""><p><?=preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2<strong id="point">.</strong>$3<strong id="point">.</strong>', $_SESSION['user']['full_name']);?></p></div>

		</div>
	</div>
<?php 

$_SESSION['days'] = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");

$mnt = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

$now_day = date("N");

$now_day -= 1;

if ($_SESSION['days'][$now_day] == 'Воскресенье') {
    $k = 7;
}
else{
    $k = 0;
}

for ($i=0; $i < 7; $i++) { 
     $date[$_SESSION['days'][$i]] = date ("j", time() - (($i*(-1) - $k) + date("N")-1) * 24*60*60);

     $m = date ("n", time() - (($i*(-1) - $k) + date("N")-1) * 24*60*60);

     $m -= 1;

     $month[$_SESSION['days'][$i]] = $mnt[$m];

     $jimbo[$_SESSION['days'][$i]] = $i;
 } 


$day = 'Понедельник';
require('classSchedule.php');

$day = 'Вторник';
require('classSchedule.php');

$day = 'Среда';
require('classSchedule.php');

$day = 'Четверг';
require('classSchedule.php');

$day = 'Пятница';
require('classSchedule.php');

$day = 'Суббота';
require('classSchedule.php');

$day = 'Воскресенье';
require('classSchedule.php');

$_SESSION['ar'] = $ar;
$arr = $_SESSION['ar'];
require_once('footer.php');
?>
<script>
    $('.addEvent').click(function() {
        var nDay = $(this).attr("id");
        var today = new Date().getFullYear()+'-'+("0"+(new Date().getMonth()+1)).slice(-2)+'-'+("0"+new Date().getDate()).slice(-2);
        $('.addEvent').hide();
        $("#"+nDay+"second").append("<div class='formSlide'><form class='formEvent formTeacher'><span class='eventTime'></span><div class='formHead'><span class='sTime'>Время<i>*</i></span><div class='eventTime'><div class='timeFrom'><span>с</span><input name='time-from' type='number' required><input name='time-from' type='number' required>:<input name='time-from' type='number' required><input name='time-from' type='number' required></div><div class='timeTo'>по<input name='time-to' type='number' required><input name='time-to' type='number' required>:<input name='time-to' type='number' required><input name='time-to' type='number' required></div></div></div><div class='formMid'><div class='eventDate'><span>Дата<i>*</i></span><input type='date' min='"+today+"' required/></div><span>Название<i>*</i></span><textarea name='name' placeholder='Введите название' required></textarea></div><div class='formBot'><div class='divT'><label for='teacher'>Педагог:</label><input name='teacher' id='teacher' type='text' placeholder='Введите ФИО'></div><div class='divP'><label for='eventPlace'>Адрес:</label><input name='place' id='eventPlace' type='text' placeholder='Введите адрес'></div></div><input class='eventSub' type='submit' id='"+nDay+"' value='Добавить'><input class='eventDel' type='submit' value='Отмена'></form></div>");
        $(".eventDel").click(function(){
            $('.formSlide').remove();
            $('.addEvent').show();
            var users = <?php echo json_encode($out);?>;
            //console.log(users.users.user);
        })
        $("input[type='number']").keyup(function () {
            if ($(this).val().match(/^\d{1}$/)) {
                $(this).next("input").focus();
            } else {
                $(this).val("");
            }
        });



        $("input[type='number']").keydown(function (e) {
            if ((e.which == 8 || e.which == 46) && $(this).val() == "") {
            $(this).prev("input").focus();
            }
        });
        $(".eventSub").click(function(event){
            //console.log('hi');
            event.preventDefault();
            var timeFrom = [];
            var timeTo = [];
            $("input[name=time-from]").each(function(){
                timeFrom.push($(this).val());
            })
            $("input[name=time-to]").each(function(){
                timeTo.push($(this).val());
            })
            var timeFromStr = '';
            timeFromStr += timeFrom[0]+timeFrom[1]+':'+timeFrom[2]+timeFrom[3];
            var timeToStr = '';
            timeToStr += timeTo[0]+timeTo[1]+':'+timeTo[2]+timeTo[3];
            var date = $("input[type=date]").val();
            var name = $("textarea[name=name]").val();
            var teacher = $("input[name=teacher]").val();
            var place = $("input[name=place]").val();
            var group = '<?php echo $t_class?>';
            var users = <?php echo json_encode($out);?>;
            var day = '';
            switch(nDay){
                case '0':
                    day = 'Понедельник';
                    break;
                case '1':
                    day = 'Вторник';
                    break;
                case '2':
                    day = 'Среда';
                    break;
                case '3':
                    day = 'Четверг';
                    break;
                case '4':
                    day = 'Пятница';
                    break;
                case '5':
                    day = 'Суббота';
                    break;
                case '6':
                    day = 'Воскресенье';
                    break;
            }
            //console.log(timeFromStr + ' - ' + timeToStr+ '\n' +name+'\n'+teacher+'\n'+place+'\n'+username+'\n'+day);
            if(name != '' && day != '' && group != '' && date != '' && timeToStr != '' && timeFromStr != ''){
                console.log(users);
                $.ajax({
                    url: "/php-scripts/addEventTeacher.php",
                    type: "POST",
                    data: {timeFrom: timeFromStr, timeTo: timeToStr, name: name, teacher: teacher, place: place, group: group, day: day, date:date, users: users},
                    dataType: "json",
                    success: function(addEvent){
                        window.location.reload();
                        //console.log(addEvent);
                    }
                })
            }
        })
    })
    $(".deleteEvent").click(function () {
    var username = '<?php echo $username;?>';
    var name = $(this).attr("name");
    var group = '<?php echo $t_class?>';
    //console.log(id)
    $.ajax({
        url: "/php-scripts/deleteClassEvent.php",
        type: "POST",
        data: {name: name, group: group},
        dataType: "json",
        success: function(deleteEvent){
            //console.log(deleteEvent);
            if(deleteEvent.message){
                window.location.reload();
            }
        }
    })
})
</script>