<?php

function get_fullname() {

    global $connection;

    $sql = "SELECT Full_name FROM students";

    $result = mysqli_query($connection, $sql);

    $fullname = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $fullname;

}

function get_schedule() {

    global $connection;

    global $class;

    global $day;

    $datetime1 = date_create('2020-12-21');
    
    $datetime2 = date_create('now');
    
    $interval = date_diff($datetime1, $datetime2);
    
    $kolvo = $interval->format('%a');
    
    $weeks = floor($kolvo/7);
   
    $chet = $weeks % 2;

    $sql = "SELECT * FROM `schedule` where class = '$class' and Day = '$day' and (parity = '$chet' or parity is null or parity = 2) \n". " ORDER BY `schedule`.`timeFrom` ASC";

    $result = mysqli_query($connection, $sql);

    $schedule = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $schedule;

}

function get_classSchedule() {

    global $connection;

    global $t_class;

    global $day;

    $datetime1 = date_create('2020-12-21');
    
    $datetime2 = date_create('now');
    
    $interval = date_diff($datetime1, $datetime2);
    
    $kolvo = $interval->format('%a');
    
    $weeks = floor($kolvo/7);
   
    $chet = $weeks % 2;

    $sql = "SELECT * FROM `schedule` where class = '$t_class' and Day = '$day' and (parity = '$chet' or parity is null or parity = 2) \n". " ORDER BY `schedule`.`timeFrom` ASC";

    $result = mysqli_query($connection, $sql);

    $schedule = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $schedule;

}

function get_class_events(){
    global $day;

    global $connection;

    global $t_class;

    $sql = "SELECT DISTINCT(name), class, timeFrom, timeTo, teacher, place, day, date FROM `events` where class = '$t_class' and day = '$day' and (date != '0000-00-00') ORDER BY `timeFrom` ASC";

    $result = mysqli_query($connection, $sql);

    $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $ads;
}

function get_events(){

    global $day;

    global $connection;

    global $username;

    global $class;

    $sql = "SELECT timeTo FROM schedule WHERE class = '$class' and day = '$day' ORDER BY timeFrom DESC LIMIT 1";
    $result1 = mysqli_query($connection, $sql);
    $last = mysqli_fetch_assoc($result1);
    //var_dump($last['timeTo']);
    $lastS = $last['timeTo'];

    $sql = "SELECT * FROM `events` where username = '$username' and day = '$day' and timeFrom >= '$lastS' ORDER BY `timeFrom` ASC";

    $result = mysqli_query($connection, $sql);

    $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $ads;

}

function get_students() {

    global $connection;

    global $fullname;

    $query = "SELECT * FROM posts WHERE studentName = '$fullname'";

    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

    $full_name = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $full_name;
}

function get_tschedule() {

    global $connection;

    global $day;

    global $counter;

    global $fullname;

    $datetime1 = date_create('2020-12-21');
    
    $datetime2 = date_create('now');
    
    $interval = date_diff($datetime1, $datetime2);
    
    $kolvo = $interval->format('%a');
    
    $weeks = floor($kolvo/7);
    
    $chet = $weeks % 2;

    $sql = "SELECT * FROM `schedule` where  schedule.teacher like '%$fullname%' and day = '$day' and (parity = '$chet' or parity is null or parity = 2) or schedule.replacement = '$fullname' and day = '$day' ORDER BY `schedule`.`timeFrom` ASC";

    $result = mysqli_query($connection, $sql);

    $tschedule = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $tschedule;

}


function get_addCheck() {

    global $connection;

    global $fullname;

    /* $sql = "SELECT * FROM `infoadd` where groupName NOT IN(SELECT groupName FROM posts WHERE studentName = 'Воропаев Андрей Константинович') and (11 BETWEEN ageGroupFrom and ageGroupTo) and (categoryadd = 'Техническая направленность')"; */
    
    $sql = "SELECT * FROM `infoadd` where groupName IN (SELECT groupName FROM posts WHERE studentName = '$fullname')";

    $result = mysqli_query($connection, $sql);

    $addSchedule = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $addSchedule;
}

function get_addCheckMenu() {

    global $connection;

    global $fullname;

    global $catAdd;

    global $dob;

    $now = new DateTime('now');
    
    $interv = $now->diff(new DateTime($_SESSION['user']['dob']));
    
    $years = $interv->format('%y');
    
    $ag = (int) $years;
    /* and (categoryadd = '$catAdd') and ('$years' between ageGroupFrom and ageGroupTo) */
    
    /* $sql = "SELECT * FROM `infoadd` where groupName NOT IN(SELECT groupName FROM posts WHERE studentName = 'Воропаев Андрей Константинович') and (11 BETWEEN ageGroupFrom and ageGroupTo) and (categoryadd = 'Техническая направленность')"; */
    $sql = "SELECT * FROM `infoadd` where groupName NOT IN(SELECT groupName FROM posts WHERE studentName = '$fullname') and (categoryadd = '$catAdd') and ('$ag' BETWEEN ageGroupFrom and ageGroupTo)";

    $result = mysqli_query($connection, $sql);

    $addScheduleMenu = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $addScheduleMenu;
}

function get_dayAddCheck() {

    global $connection;
    
    global $groupName;

    global $arr;

    global $i;

    $sql = "SELECT * FROM `addschedule` where groupname = '$groupName' and day = '$arr[$i]' \n"."ORDER BY `time` ASC";

    $result = mysqli_query($connection, $sql);

    $AddSchedule = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $AddSchedule;
    
}

function get_ads() {

    global $day;

    global $connection;

    global $fullname;

    $sql = "SELECT infoadd.*, addschedule.day, addschedule.time, addschedule.timeTo FROM `infoadd`, `addschedule` where infoadd.groupName IN (SELECT groupName FROM posts WHERE studentName = '$fullname') and infoadd.groupName = addschedule.groupName and addschedule.day = '$day' ORDER BY `addschedule`.`time` ASC";

    $result = mysqli_query($connection, $sql);

    $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $ads;
}

function get_info() {

    global $connection;

    $grp = $_POST['srch-grp'];

    $sql = "SELECT * FROM infoadd, addschedule where infoadd.groupName = '$grp'";

    $result = mysqli_query($connection, $sql);

    $inf = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $inf; 
}

function get_tads() {

    global $day;

    global $connection;

    global $fullname;

    $sql = "SELECT infoadd.*, addschedule.day, addschedule.time, addschedule.timeTo FROM `infoadd`, `addschedule` where addschedule.teacherName = '$fullname' and infoadd.groupName = addschedule.groupName and addschedule.day = '$day' ORDER BY `addschedule`.`time` ASC";

    $result = mysqli_query($connection, $sql);

    $ads = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $ads;
}

function get_unposts(){

    global $connection;

    $sql = "SELECT * FROM `unposts`";

    $result = mysqli_query($connection, $sql);

    $app_add = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $app_add;
}

function get_app_add(){

    global $connection;

    $sql = "SELECT * FROM `app_add`";

    $result = mysqli_query($connection, $sql);

    $unp = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $unp;
}
//SELECT infoadd.*, addschedule.day, addschedule.time, addschedule.timeTo FROM `infoadd`, `addschedule` where infoadd.groupName IN (SELECT groupName FROM posts WHERE studentName = 'Воропаев Андрей Константинович') and infoadd.groupName = addschedule.groupName and addschedule.day = 'Среда' ORDER BY `addschedule`.`time` ASC

function get_teacher(){

    global $connection;

    $sql = "SELECT * FROM `teachers`  \n" . "ORDER BY `teachers`.`teacherName` DESC";

    $result = mysqli_query($connection, $sql);

    $getT = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $getT;
}

function get_cabs(){

    global $cab_adr;

    global $connection;

    global $cab_num;

    global $day;

    $sql = "SELECT * FROM `schedule` where schedule.day = '$day' and schedule.address = '$cab_adr' and schedule.place like '%$cab_num%' ORDER BY `schedule`.`timeFrom` ASC";

    $result = mysqli_query($connection, $sql);

    $getT = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $getT;
}

function get_info_cab(){

    global $connection;

    global $cab_num;

    $sql = "SELECT * FROM `cabs` where cabs.cab_number = '$cab_num'";

    $result = mysqli_query($connection, $sql);

    $getT = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $getT;
}

function get_swaps(){

    global $connection;

    $sql = "SELECT * FROM `replacements` ORDER BY `replacements`.`dateT` ASC";

    $result = mysqli_query($connection, $sql);

    $swap = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $swap;

}

function get_entry(){

    global $connection;

    global $fullname;

    $sql = "SELECT distinct lesson FROM `schedule` WHERE teacher = '$fullname' GROUP BY lesson";

    $result = mysqli_query($connection, $sql);

    $entry = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $entry;
}

function get_enrty_class_group(){
    
    global $connection;

    global $fullname;

    $sql = "SELECT distinct class FROM `schedule` WHERE teacher = '$fullname' GROUP BY class";

    $result = mysqli_query($connection, $sql);

    $entry = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $entry;
}
?>