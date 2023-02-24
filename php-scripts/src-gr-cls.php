<?php

	require('writter.php');
	require('../functions.php');

	$res = "";

	/** Получаем наш ID статьи из запроса */
	$fio = "";
	$date_full1 = $_POST['date_full'];
	$class = $_POST['text'];

	for ($jk=0; $jk < 7; $jk++) { 

		$date_full = $date_full1[$jk];

		$datetime1 = date_create('2019-12-30');

		$datetime2 = date_create('now');

		$interval = date_diff($datetime1, $datetime2);

		$kolvo = $interval->format('%a');

		$weeks = floor($kolvo/7);

		$chet = $weeks % 2;

		$sql = "SELECT * FROM `schedule` where class = '$class' and Day = '".$date_full['day']."' and (parity = '$chet' or parity is null or parity = 2) ORDER BY `schedule`.`timeFrom` ASC";

		$result = mysqli_query($connection, $sql);

		$table = mysqli_fetch_all($result, MYSQLI_ASSOC);

		$res .= '<div class="container">';

		$numb = 0;
		if (!empty($table)){

			$tabl = $table[0];
			$res .= '<div class="date"><div class="d-num"><p>'.$date_full["numb_day"].' '.$date_full["month"].',</p></div><p>'.$date_full["day"].'</p></div>
			</div>
			<div class="backgr">
			<div class="container" id="second">
			<div class="slide">';
			foreach ($table as $tabl){ 
				$res .= '<div class="lesn">
				<div class="number"><p>'.$tabl['number'].'</p></div>
				<div class="time" style="vertical-align: middle;">'.substr($tabl['timeFrom'], 0, 5).'-'.substr($tabl['timeTo'], 0, 5).'</div>
				<div class="lesson">';
				$pos = 0;
				$pos = strpos($tabl['lesson'], '//');
				if ($pos === false){
					if (strlen($tabl['lesson']) > 110) {
					    $res .= '<span class="more_info" title="'.$tabl['lesson'].'">'.mb_substr($tabl['lesson'], 0, 50, 'UTF-8').'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
					}
					else{
					    $res .= '<p>'.$tabl['lesson'].'</p>';
					}
				}
				else{
					$place = str_replace("//", ", ", $tabl['lesson']);
					$teacher = substr($tabl['lesson'], 0, $pos);
					$res .= '<span class="teach" title="'.$place.'">'.$teacher.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
				}

				$res .= '</div>';
				$res .= '<p class="t-c" id=>Педагог</p><div class="teach-class">';
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
				        $res .= '<p>'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $tabl['teacher']).'</p>';
				    }
				    else{
				        $query = "SELECT * FROM `replacements` WHERE teacher='$t_teacher' and timeF='$t_tf' and timeT='$t_tt' and less='$t_ls'";
				        $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

				        if (mysqli_num_rows($result) > 0){
				            
				            $tch = mysqli_fetch_assoc($result);

				            $t_arr['teach_rep'] = ["fio" => $tch['fio']];
				        }

				        $res .= '<p>'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $t_arr['teach_rep']['fio']).'</p>';
				    
				    }
				}
				else{
				    $place = str_replace("/", ", ", $tabl['teacher']);
				    $teacher = substr($tabl['teacher'], 0, $pos);
				    $res .= '<span class="teach" title="'.$place.'">'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $teacher).'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
				}
				$res .= '</div>';

				$res .= '<p class="cab">Кабинет</p>
				<div class="place">';
				$p = 0;
				$pos = 0;
				$p = strpos($tabl['place'], ' ');
				$pos = strpos($tabl['place'], '//');
				if ($pos === false && $p === false){
				$res .= '<p>'.$tabl['place'].'</p>';
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
				$res .= '<span class="more_info" title="'.$place.'">'.substr($tabl['place'], 0, $i).'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
				}

				$res .= '</div>
				</div>';

			}

			$numb = $tabl['number'];
			$res .= '</div>';
		};

		$res .= '</div></div>';
	}

	header('Content-Type: text/json; charset=utf-8');

	// Кодируем данные в формат json и отправляем
	echo json_encode($res);
?>