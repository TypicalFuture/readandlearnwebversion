<?php
    global $day;
    global $tip;


    if ($_SESSION['dostup'] == 2) {
        $table = get_schedule();
    }

    else{
        $table = get_tschedule();
    }
    //проверка на то, прошла ли дата замены преподавателя
    $today = date('Y-m-d');

    $query = "SELECT * FROM `replacements` where dateT < '$today'";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
    if (mysqli_num_rows($result) > 0){

        $rep = "SELECT * FROM `schedule` where rep_date < '$today'";
        $rep_res = mysqli_query($connection, $rep) or die (mysqli_error($connection));
        if (mysqli_num_rows($rep_res) > 0){
            $query1 = "UPDATE schedule SET replacement = NULL";
            $query2 = "UPDATE schedule SET rep_date = NULL";
            $res1 = mysqli_query($connection, $query1) or die(mysqli_error($connection));
            $res2 = mysqli_query($connection, $query2) or die(mysqli_error($connection));
        }

        $query = "DELETE FROM `replacements` WHERE dateT < '$today'";
        $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
        
    }        
    
    $numb = 0;
    if (!empty($table)){

        //$query = "SELECT * from events where "
        
        
        $tabl = $table[0];
        //var_dump($tabl['timeFrom']);
        $timeS = $tabl['timeFrom'];


        $query = "SELECT * FROM events where timeTo <= '$timeS' and day = '$day' and class = '$class' and username = '$username' ORDER BY `events`.`timeFrom` ASC";
        $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
        $firstE = mysqli_fetch_all($result, MYSQLI_ASSOC);
        

        echo '<div class="container"><div class="date"><div class="d-num" id="'.$jimbo[$day].'"><p>'.$date[$day].' '; echo $month[$day].',</p></div><p>'.$day.'</p></div></div>
        <div class="backgr">
            <div class="container" id="'.$jimbo[$day].'second">
                <div class="slide">';
                if (mysqli_num_rows($result) > 0){

                    foreach ($firstE as $events){
                        echo '<div class="lesnAd">';
                        echo '<img class="deleteEvent nodisp" name="'.$events['id'].'" src="/img/x-circle.svg"><div class="addHead"><div class="numberAd"></div>
                        <div class="timeAd">'.substr($events['timeFrom'], 0, 5).'-'.substr($events['timeTo'], 0, 5).'</div></div>
                        <div class="lessonAd">'.$events['name'].'</div>';
                        if ($_SESSION['dostup'] == 2) {
                            echo '<div class="teacherAd">'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $events['teacher']).'</div>';
                        }
                        else{
                            echo '<p class="pl-te">Группа</p>
                            <div class="teacherAd">'.$events['teacher'].'</div>';
                        }
                        
                        echo'<div class="placeAd">'.$events['place'].'</div></div>';
                    }
                }
                foreach ($table as $tabl){ 
                echo '<div class="lesn">
                    <div class="number"><p>'.$tabl['number'].'</p></div>
                    <div class="time" style="vertical-align: middle;">'.substr($tabl['timeFrom'], 0, 5).'-'.substr($tabl['timeTo'], 0, 5).'</div>
                    <div class="lesson">';
                    if ($_SESSION['dostup'] == 2){
                        $pos = 0;
                        $pos = strpos($tabl['lesson'], '//');
                        if ($pos === false){
                            if (strlen($tabl['lesson']) > 110) {
                                echo '<span class="more_info" title="'.$tabl['lesson'].'">'.mb_substr($tabl['lesson'], 0, 50, 'UTF-8').'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                            }
                            else{
                                echo '<p>'.$tabl['lesson'].'</p>';
                            }
                        }
                        else{
                            $place = str_replace("//", ", ", $tabl['lesson']);
                            $teacher = substr($tabl['lesson'], 0, $pos);
                            echo '<span class="teach" title="'.$place.'">'.$teacher.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                        }
                    }
                    
                    else{
                        $tname = $_SESSION['user']['full_name'];
                        $pos = 0;
                        $pos = strpos($tabl['teacher'], $tname);
                        if ($pos == 0){
                            $p = strpos($tabl['lesson'], '//');
                            
                            if ($p !== false) {
                                $place = substr($tabl['lesson'], 0, $p);
                            }
                            else{
                                $place = $tabl['lesson'];
                                if (strlen($tabl['lesson']) > 110) {
                                    $place = '<span class="more_info" title="'.$tabl['lesson'].'">'.substr($tabl['lesson'], 0, 100).'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                                }
                            }
                            echo $place;
                        }
                        else{
                            $f = 0;
                            $p = strpos($tabl['lesson'], '//');
                            if ($p === false) {
                                $numpl = $tabl['lesson'];
                                echo $numpl;
                            }
                            else{
                                $teach = substr($tabl['teacher'], 0, $pos);
                                for ($i = 0, $j = strlen($teach); $i < $j; $i++){
                                    if ($teach[$i] == "/"){
                                        $f++;
                                    }
                                }
                                $numplace = explode("//", $tabl['lesson']);
                                echo $numplace[$f];
                            }
                            
                        }
                    }
                    
                    echo '</div>';
                    if ($_SESSION['dostup'] == 2){
                    echo '<p class="t-c" id=>Педагог</p>
                    <div class="teach-class">';
                        $pos = 0;
                        $t_teacher = $tabl['teacher'];
                        $t_tf = $tabl['timeFrom'];
                        $t_tt = $tabl['timeTo'];
                        $t_ls = $tabl['lesson'];
                        $pos = strpos($tabl['teacher'], '/');
                        if ($pos === false){
                            //Проверка на наличие замены
                            $query = "SELECT * FROM `replacements` WHERE teacher='$t_teacher' and timeF='$t_tf' and timeT='$t_tt' and less='$t_ls'";
                            $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
                            if (mysqli_num_rows($result) == 0){
                                echo '<p>'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $tabl['teacher']).'</p>';
                            }
                            else{
                                $query = "SELECT * FROM `replacements` WHERE teacher='$t_teacher' and timeF='$t_tf' and timeT='$t_tt' and less='$t_ls'";
                                $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

                                if (mysqli_num_rows($result) > 0){
                                    
                                    $tch = mysqli_fetch_assoc($result);

                                    $t_arr['teach_rep'] = ["fio" => $tch['fio']];
                                }

                                echo '<p>'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $t_arr['teach_rep']['fio']).'</p>';
                            
                            }
                        }
                        else{
                            $place = str_replace("/", ", ", $tabl['teacher']);
                            $teacher = substr($tabl['teacher'], 0, $pos);
                            echo '<span class="teach" title="'.$place.'">'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $teacher).'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                        }
                    echo '</div>';
                    }
                    else{
                        if (strlen($tabl['class']) < 6){
                            echo '<p class="t-c">Класс</p>';
                        }
                        else{
                            echo '<p class="t-c">Группа</p>';
                            
                        }
                    echo '<div class="teach-class"><p>'.$tabl['class'].'</p></div>';
                    }
                    echo '<p class="cab">Кабинет</p>
                    <div class="place">';
                    if ($_SESSION['dostup'] == 2){
                        $p = 0;
                        $pos = 0;
                        $p = strpos($tabl['place'], ' ');
                        $pos = strpos($tabl['place'], '//');
                        if ($pos === false && $p === false){
                            echo '<p>'.$tabl['place'].'</p>';
                        }
                        else{
                            if ($pos){
                                $i = $pos;
                                $place = str_replace("//", ", ", $tabl['place']);
                            }
                            else{
                                $i = $p;
                                $place = $tabl['place'];
                            }
                            echo '<span class="more_info" title="'.$place.'">'.substr($tabl['place'], 0, $i).'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                        }
                    }
                    else{

                        $tname = $_SESSION['user']['full_name'];
                        $pos = 0;
                        $pos = strpos($tabl['teacher'], $tname);
                        if ($pos == 0){
                            $p = strpos($tabl['place'], '//');
                            if ($p !== false) {
                                $place = substr($tabl['place'], 0, $p);
                            }
                            else{
                                $place = $tabl['place'];
                            }
                            if (strpos($place, " ") === false){
                                echo $place;
                            }
                            else{
                                $r = substr($place, 0, strpos($place, " "));
                                echo '<span class="more_info" title="'.$place.'">'.$r.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                            }
                        }
                        else{
                            $f = 0;
                            $p = strpos($tabl['place'], '//');
                            if ($p === false) {
                                $numpl = $tabl['place'];
                                if (strpos($numpl, " ") === false){
                                    echo $numpl;
                                }
                                else{
                                    $r = substr($numpl, 0, strpos($numpl, " "));
                                    echo '<span class="more_info" title="'.$numpl.'">'.$r.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                                }   
                                
                            }
                            else{
                                $teach = substr($tabl['teacher'], 0, $pos);
                                for ($i = 0, $j = strlen($teach); $i < $j; $i++){
                                    if ($teach[$i] == "/"){
                                        $f++;
                                    }
                                }
                                $numplace = explode("//", $tabl['place']);
                                if (strpos($numplace[$f], " ") === false){
                                    echo $numplace[$f];
                                }
                                else{
                                    $r = substr($numplace[$f], 0, strpos($numplace[$f], " "));
                                    echo '<span class="more_info" title="'.$numplace[$f].'">'.$r.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                                } 
                                
                            }
                        }
                    }
                    echo '</div>
                </div>';
                
                switch($day){
                    case "Понедельник":
                        $ar[0] = (integer)str_replace(':','',substr($tabl['timeTo'], 0, 5));
                        break;
                    case "Вторник":
                        $ar[1] = (integer)str_replace(':','',substr($tabl['timeTo'], 0, 5));
                    break;
                    case "Среда":
                        $ar[2] = (integer)str_replace(':','',substr($tabl['timeTo'], 0, 5));
                    break;
                    case "Четверг":
                        $ar[3] = (integer)str_replace(':','',substr($tabl['timeTo'], 0, 5));
                    break;
                    case "Пятница":
                        $ar[4] = (integer)str_replace(':','',substr($tabl['timeTo'], 0, 5));
                    break;
                }
            }
            
            $numb = $tabl['number'];
        echo '</div>';
    };

    if ($_SESSION['dostup'] == 2) {
        $ada = get_ads();
    }

    else{
        $ada = get_tads();
    }

    $events = get_events();

    if(empty($table) && empty($ada) && empty($events)){
        echo '<div class="container nodisp"><div class="date"><div class="d-num" id="'.$jimbo[$day].'"><p>'.$date[$day].' '; echo $month[$day].',</p></div><p>'.$day.'</p></div></div><div class="backgr nodisp"><div class="container nodisp" id="'.$jimbo[$day].'second">';
    }
    
    if(!empty($ada) || !empty($events))
    {
        $ad = $ada[0];

        $event = $events[0];

        if($day == "Суббота" || $day == "Воскресенье" || empty($table))
        {
         echo '<style type="text/css">
                #dop-vih{
                    margin-top: 25px;
                }
            </style>';
            echo '
            <div class="container">
            <div class="date"><div class="d-num" id="'.$jimbo[$day].'"><p>'.$date[$day].' '; echo $month[$day].',</p></div><p>'.$day.'</p></div>
            </div>
        
            <div class="backgr">
                <div class="container" id="'.$jimbo[$day].'second">
                    <div class="dop" id="dop-vih"><p>Внеурочная деятельность</p></div><div class="adsSlide">';
                
        }
        else{
            echo '<div class="dop"><p>Внеурочная деятельность</p></div><div class="adsSlide">';
        }
        
        $addLen = count($ada);

        $eventLen = count($events);

        $x = 0;
        $y = 0;
        
        for($i = 0; $i < ($addLen + $eventLen); $i++) {

            if($x < $addLen) {

                if($y < $eventLen) {
                    if(strtotime($ada[$x]['time']) > strtotime($events[$y]['timeFrom'])){
                        echo '<div class="lesnAd">';
                        $numb += 1;
                        echo '<img class="deleteEvent nodisp" name="'.$events[$y]['id'].'" src="/img/x-circle.svg"><div class="addHead"><div class="numberAd"><p>'.$numb.'</p></div>
                        <div class="timeAd">'.substr($events[$y]['timeFrom'], 0, 5).'-'.substr($events[$y]['timeTo'], 0, 5).'</div></div>
                        <div class="lessonAd">'.$events[$y]['name'].'</div>';
                        if ($_SESSION['dostup'] == 2) {
                            echo '<div class="teacherAd">'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $events[$y]['teacher']).'</div>';
                        }
        
                        else{
                            echo '<p class="pl-te">Группа</p>
                            <div class="teacherAd">'.$events[$y]['teacher'].'</div>';
                        }
                        
                        echo'<div class="placeAd">'.$events[$y]['place'].'</div></div>';
                        $y += 1;
                    }
                    else{
                        echo '<div class="lesnAd">';
                        $numb += 1;
                        echo '<div class="addHead"><div class="numberAd"><p>'.$numb.'</p></div>
                        <div class="timeAd">'.substr($ada[$x]['time'], 0, 5).'-'.substr($ada[$x]['timeTo'], 0, 5).'</div></div>
                        <div class="lessonAd">'.$ada[$x]['name'].'</div>';
                        if ($_SESSION['dostup'] == 2) {
                            echo '<div class="teacherAd">'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $ada[$x]['teacherName']).'</div>';
                        }
        
                        else{
                            echo '<p class="pl-te">Группа</p>
                            <div class="teacherAd">'.$ada[$x]['groupName'].'</div>';
                        }
                        
                        echo'<div class="placeAd">'.$ada[$x]['address'].'</div></div>';
                        $x += 1;
                    }
                }
                else{
                    echo '<div class="lesnAd">';
                    $numb += 1;
                    echo '<div class="addHead"><div class="numberAd"><p>'.$numb.'</p></div>
                    <div class="timeAd">'.substr($ada[$x]['time'], 0, 5).'-'.substr($ada[$x]['timeTo'], 0, 5).'</div></div>
                    <div class="lessonAd">'.$ada[$x]['name'].'</div>';
                    if ($_SESSION['dostup'] == 2) {
                        echo '<div class="teacherAd">'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $ada[$x]['teacherName']).'</div>';
                    }
    
                    else{
                        echo '<p class="pl-te">Группа</p>
                        <div class="teacherAd">'.$ada[$x]['groupName'].'</div>';
                    }
                    
                    echo'<div class="placeAd">'.$ada[$x]['address'].'</div></div>';
                    $x += 1;
                }

            }
            else{
                echo '<div class="lesnAd">';
                $numb += 1;
                echo '<img class="deleteEvent nodisp" name="'.$events[$y]['id'].'" src="/img/x-circle.svg"><div class="addHead"><div class="numberAd"><p>'.$numb.'</p></div>
                <div class="timeAd">'.substr($events[$y]['timeFrom'], 0, 5).'-'.substr($events[$y]['timeTo'], 0, 5).'</div></div>
                <div class="lessonAd">'.$events[$y]['name'].'</div>';
                if ($_SESSION['dostup'] == 2) {
                    echo '<div class="teacherAd">'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $events[$y]['teacher']).'</div>';
                }

                else{
                    echo '<p class="pl-te">Группа</p>
                    <div class="teacherAd">'.$events[$y]['teacher'].'</div>';
                }
                
                echo'<div class="placeAd">'.$events[$y]['place'].'</div></div>';
                $y += 1;
            }

        }
        //echo '</div>';
        
    }

    echo '<div class="addEvent nodisp" id="'.$jimbo[$day].'"><img src="/img/addEvent.svg"/><span>Добавить<br>активность</span></div></div>';

echo '</div>
</div>';
?>