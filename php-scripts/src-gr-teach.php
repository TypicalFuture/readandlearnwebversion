<?php

	require('writter.php');
	require('../functions.php');

	$res = "";

	/** Получаем наш ID статьи из запроса */
	$fio = $_POST['text'];
	$date_full1 = $_POST['date_full'];
	$class = "";

	/** Если нам передали ID то обновляем */

	for ($jk=0; $jk < 7; $jk++) { 

		$date_full = $date_full1[$jk];

		$datetime1 = date_create('2019-12-30');

		$datetime2 = date_create('now');

		$interval = date_diff($datetime1, $datetime2);

		$kolvo = $interval->format('%a');

		$weeks = floor($kolvo/7);

		$chet = $weeks % 2;

		$sql = "SELECT * FROM `schedule` where  schedule.teacher = '$fio' and day = '".$date_full['day']."' and (parity = '$chet' or parity is null or parity = 2) or schedule.replacement = '$fio' and day = '".$date_full['day']."' ORDER BY `schedule`.`timeFrom` ASC";

		$result = mysqli_query($connection, $sql);

		$table = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$res .= '<div class="container">';

		$numb = 0;
		if (!empty($table)){

			$tabl = $table[0];
			$res .= '<div class="date"><div class="d-num"><p>'.$date_full["numb_day"].' '.$date_full["month"].',</p></div><p>'.$date_full["day"].'</p></div></div><div class="backgr"><div class="container" id="second"><div class="slide">';
		foreach ($table as $tabl){ 
			$res .= '<div class="lesn">
			<div class="number"><p>'.$tabl['number'].'</p></div>
			<div class="time" style="vertical-align: middle;">'.substr($tabl['timeFrom'], 0, 5).'-'.substr($tabl['timeTo'], 0, 5).'</div>
			<div class="lesson">';

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
				$res .= $place;
			}
			else{
				$f = 0;
				$p = strpos($tabl['lesson'], '//');
				if ($p === false) {
					$numpl = $tabl['lesson'];
					$res .= $numpl;
				}
				else{
					$teach = substr($tabl['teacher'], 0, $pos);
					for ($i = 0, $j = strlen($teach); $i < $j; $i++){
						if ($teach[$i] == "/"){
							$f++;
						}
					}
					$numplace = explode("//", $tabl['lesson']);
					$res .= $numplace[$f];
				}
			}

			$res .= '</div>';

			if (strlen($tabl['class']) <= 4){
				$res .= '<p class="t-c">Класс</p>';
			}
			else{
				$res .= '<p class="t-c">Группа</p>';
			}

			$res .= '<div class="teach-class"><p>'.$tabl['class'].'</p></div>';

			$res .= '<p class="cab">Кабинет</p><div class="place">';

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
					$res .= $place;
				}
				else{
					$r = substr($place, 0, strpos($place, " "));
					$res .= '<span class="more_info" title="'.$place.'">'.$r.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
				}
			}
			else{
				$f = 0;
				$p = strpos($tabl['place'], '//');
				if ($p === false) {
					$numpl = $tabl['place'];
					if (strpos($numpl, " ") === false){
						$res .= $numpl;
					}
					else{
						$r = substr($numpl, 0, strpos($numpl, " "));
						$res .= '<span class="more_info" title="'.$numpl.'">'.$r.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
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
						$res .= $numplace[$f];
					}
					else{
						$r = substr($numplace[$f], 0, strpos($numplace[$f], " "));
						$res .= '<span class="more_info" title="'.$numplace[$f].'">'.$r.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
					} 
				}
			}

			$res .= '</div>
			</div>';
		}

		$numb = $tabl['number'];
		$res .= '</div>';
		};

		$sql = "SELECT infoadd.*, addschedule.day, addschedule.time, addschedule.timeTo FROM `infoadd`, `addschedule` where addschedule.teacherName = '$fio' and infoadd.groupName = addschedule.groupName and addschedule.day = '".$date_full['day']."' ORDER BY `addschedule`.`time` ASC";

		$result = mysqli_query($connection, $sql);

		$ada = mysqli_fetch_all($result, MYSQLI_ASSOC);

		if(!empty($ada)){
			$ad = $ada[0];

			if($date_full["day"] == "Суббота" || $date_full["day"] == "Воскресенье" || empty($table)){
				$res .= '<style type="text/css">
				#dop-vih{
				margin-top: 25px;
				}
				</style>
				<div class="date"><div class="d-num"><p>'.$date_full["numb_day"].' '.$date_full["month"].',</p></div><p>'.$date_full["day"].'</p></div>
				</div>
				<div class="backgr">
				<div class="container">
				<div class="dop" id="dop-vih"><p>Дополнительное образование</p></div>';
			}
			else{
				$res .= '<div class="dop"><p>Дополнительное образование</p></div>';
			}
			$res .= '<div class="adsSlide">';
			foreach ($ada as $ad) {
				$res .= '<div class="lesnAd">';
				$numb += 1;
				$res .= '<div class="number"><p>'.$numb.'</p></div>
				<div class="timeAd">'.substr($ad['time'], 0, 5).'-'.substr($ad['timeTo'], 0, 5).'</div>
				<div class="lessonAd">'.$ad['name'].'</div>';

				$res .= '<p class="pl-te">Группа</p>
                    <div class="teacherAd">'.$ad['groupName'].'</div>';


				$res .='<p class="pl-te">Место</p>
				<div class="placeAd">'.$ad['address'].'</div>
				</div>';
			}
			$res .= '</div>';
		}

		$res .= '</div></div>';
	}

	header('Content-Type: text/json; charset=utf-8');

	// Кодируем данные в формат json и отправляем
	echo json_encode($res);
?>