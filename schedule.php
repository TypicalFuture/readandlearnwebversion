<?php
session_start();

//print_r($_SESSION['user']);

if (empty($_SESSION['user'])) {
    header('location: index.php');
}
    $fullname = $_SESSION['user']['full_name'];
    $fName = preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $_SESSION['user']['full_name']);
    if($_SESSION['dostup'] == 2){
    $class = $_SESSION['user']['class'];
    $dob = $_SESSION['user']['dob'];
    $username = $_SESSION['user']['username'];
    };
    global $class;
    global $chet;
    global $tip;
?>


<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Caomatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' type='text/css' href='css/main.css'>
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <link rel="stylesheet" type="text/css" href="">
    <script src="js/jquery.js"></script>
    <title>Расписание</title>
</head>
<body>

<?php 
require('php-scripts/connect.php');
//require('php-scripts/deleter.php');
require('functions.php');
require_once('header.php');
?>


<div class="selector">
    <div class="duo-sel">
        <select class="gr-fio" onchange="selected()">
            <option value="Преподаватель">Преподаватель</option>
            <option value="Группа/класс">Группа/класс</option>
<!--            <option value="ФИО">Учащийся</option>-->
        </select>
        <input type="text" class="search-inp">
        <div class="display" id="display"></div>
    </div>
    <div class="sub-back">
        <input type="submit" value="Поиск" class="submit-inp">
    </div>
    
</div>
<div class="telo"></div>

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
require('lessn.php');

$day = 'Вторник';
require('lessn.php');

$day = 'Среда';
require('lessn.php');

$day = 'Четверг';
require('lessn.php');

$day = 'Пятница';
require('lessn.php');

$day = 'Суббота';
require('lessn.php');

$day = 'Воскресенье';
require('lessn.php');


$_SESSION['ar'] = $ar;
$arr = $_SESSION['ar'];
require_once('footer.php');
?>
-->

</body>
</html>

<script type="text/javascript">

function selected(){
    $(".search-inp").val("");
}

$(document).ready(function() {
 
    $(".search-inp").keyup(function() {
 
        var name = $('.search-inp').val();

        var select = $('.gr-fio option:selected').text();
 
        if ((name === "") && (select === "")) {
 
            $("#display").html("");
 
        }
        else {
 
            $.ajax({
 
                type: "POST",
                url: "/php-scripts/schedule-search.php",
                data: {
                    name: name, select: select
                },
                success: function(response) {
                    $("#display").html(response).show();
                }
 
            });
 
        }
 
    });
 
});
 
function fill(Value) {
 
    $('.search-inp').val(Value);
 
    $('#display').hide();
 
}

$(".submit-inp").click(function () {
    var text = $(".search-inp").val();
    var select = $('.gr-fio option:selected').text();

    //alert(text + " " + select);
    if((text != "") && (select != "")){
        $('.container').remove();
        $('.backgr').remove();
        $('.telo').empty();
        
        var days_week = ["Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"];
        var mnt = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        var n = [6, 0, 1, 2, 3, 4, 5];
        var date = new Date();

        var now_day = date.getDay();
        //console.log("now_day: " + now_day);
        var k = 0;

        if (days_week[n[now_day]] == 'Воскресенье') {
            k = 7;
        }

        var date_full = [];

        for (var i=0; i < 7; i++) { 
            date = new Date();
            var diff = date.getDate() - now_day + i + k + (now_day == 0 ? -6:1);
            //console.log(date);

            var date_str = new Date(date.setDate(diff));

            date_full[i] = {
                numb_day: date_str.getDate(),
                month: mnt[date_str.getMonth()],
                day: days_week[n[date_str.getDay()]],
            }
            //console.log(date_full[i]);

        }
        
        $('.search-inp').attr('placeholder', text);
        $('.search-inp').val('');


        if (select === "Учащийся") {
            var jq = date_full;
            
            $.ajax({
                url: "/php-scripts/src-gr-stud.php",
                type: "POST",
                data: {text: text, date_full: jq},
                dataType: "json",
                success: function(group){
                    if(group){
                      //console.log(group);
                        //console.log("js: " + js);
                    $('.telo').append(group); //возвращаем то, в какой группе состоит студент  
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
                    }
                    else{
                       //console.log('Ничего не работает');
                    }
                }
            })
        }

        if (select === "Группа/класс") {
            var jq = date_full;
            
            $.ajax({
                url: "/php-scripts/src-gr-cls.php",
                type: "POST",
                data: {text: text, date_full: jq},
                dataType: "json",
                success: function(group){
                    if(group){
                      //console.log(group);
                        //console.log("js: " + js);
                        $('.telo').append(group); //возвращаем то, в какой группе состоит студент  

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
                    }
                    else{
                        //console.log('Ничего не работает');
                    }
                }
            })
        }

        if (select === "Преподаватель") {
            var jq = date_full;
            
            $.ajax({
                url: "/php-scripts/src-gr-teach.php",
                type: "POST",
                data: {text: text, date_full: jq},
                dataType: "json",
                success: function(group){
                    if(group){
                      //console.log(group);
                        //console.log("js: " + js);
                    $('.telo').append(group); //возвращаем то, в какой группе состоит студент  
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
                    }
                    else{
                        //console.log('Ничего не работает');
                    }
                }
            })
        }
    }
    else{
        alert("Заполните все поля");
    }
    

})

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

<script>
    $('.addEvent').click(function() {
        var nDay = $(this).attr("id");
        $('.addEvent').remove();
        $("#"+nDay+"second").append("<div class='formSlide'><form class='formEvent'><span class='eventTime'></span><div class='formHead'><span class='sTime'>Время<i>*</i></span><div class='eventTime'><div class='timeFrom'><span>с</span><input name='time-from' type='number' required><input name='time-from' type='number' required>:<input name='time-from' type='number' required><input name='time-from' type='number' required></div><div class='timeTo'>по<input name='time-to' type='number' required><input name='time-to' type='number' required>:<input name='time-to' type='number' required><input name='time-to' type='number' required></div></div></div><div class='formMid'><span>Название<i>*</i></span><textarea name='name' placeholder='Введите название' required></textarea></div><div class='formBot'><div class='divT'><label for='teacher'>Педагог:</label><input name='teacher' id='teacher' type='text' placeholder='Введите ФИО'></div><div class='divP'><label for='eventPlace'>Адрес:</label><input name='place' id='eventPlace' type='text' placeholder='Введите адрес'></div></div><input class='eventSub' type='submit' id='"+nDay+"' value='Добавить'><input class='eventDel' type='submit' value='Отмена'></form></div>");
        $(".eventDel").click(function(){
            $('.formSlide').remove();
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
            var name = $("textarea[name=name]").val();
            var teacher = $("input[name=teacher]").val();
            var place = $("input[name=place]").val();
            var username = '<?php echo $_SESSION['user']['username']?>';
            var group = '<?php echo $_SESSION['user']['class']?>';
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
            if(name != '' && day != '' && group != '' && username != '' && timeToStr != '' && timeFromStr != ''){
                $.ajax({
                    url: "/php-scripts/addEvent.php",
                    type: "POST",
                    data: {timeFrom: timeFromStr, timeTo: timeToStr, name: name, teacher: teacher, place: place, username: username, group: group, day: day},
                    dataType: "json",
                    success: function(addEvent){
                        if(addEvent.message != 2){
                            $("input[name=time-from]").css({'border-color':'#FC6363'});
                            $("input[name=time-to]").css({'border-color':'#FC6363'});
                            $("textarea[name=name]").css({'border-color':'#FC6363'});
                            $("textarea[name=name]").css({'color':'#FC6363'});
                            console.log(addEvent.message);
                        }
                        else window.location.reload();
                        
                        
                        //console.log(addEvent);
                    }
                })
            }
        })
    })
</script>

<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(67036342, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<script>

function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function(e) {
  if (!e.target.matches('.dropbutn')) {
    var myDropdown = document.getElementById("myDropdown");
      if (myDropdown.classList.contains('show')) {
        myDropdown.classList.remove('show');
      }
  }
}

$(".deleteEvent").click(function () {
    var username = '<?php echo $username;?>';
    var id = $(this).attr("name");
    //console.log(id)
    $.ajax({
        url: "/php-scripts/deleteEvent.php",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function(deleteEvent){
            //console.log(deleteEvent);
            if(deleteEvent.message){
                window.location.reload();
            }
        }
    })
})

$(".edit").click(function(){
    if($(".addEvent").hasClass("nodisp")){
        $(".addEvent").removeClass("nodisp");
    }
    else{
        $(".addEvent").addClass("nodisp");
    }
    if($(".deleteEvent").hasClass("nodisp")){
        $(".deleteEvent").removeClass("nodisp");
    }
    else{
        $(".deleteEvent").addClass("nodisp");
    }
    
    
    
    
    /*if($(".container").hasClass("nodisp")){
        $(".container").removeClass("nodisp");
    }
    else{
        $(".container").addClass("nodisp");
    }
    if($(".backgr").hasClass("nodisp")){
        $(".backgr").removeClass("nodisp");
    }
    else{
        $(".backgr").addClass("nodisp");
    }*/
})
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/67036342" style="position:absolute; left:-9999px;" alt="" /></div></noscript>