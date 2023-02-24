<?php

global $chet;
global $tip;

$cabs = get_cabs();

if(!empty($cabs))
{
	$cab = $cabs[0];
	$cabDouble['timeFrom'] = 'som';

	foreach ($cabs as $cab) {
		if ($cabDouble['timeFrom'] != $cab['timeFrom']){
			echo'
			<div class="b-days">
				<div class="time">
					<p>'.substr($cab['timeFrom'], 0, 5).'</p>
					<p>'.substr($cab['timeTo'], 0, 5).'</p>
				</div>

				<div class="lesson">';
				 $pos = 0;
                        $pos = strpos($cab['lesson'], '//');
                        if ($pos === false){
                            if (strlen($cab['lesson']) > 24) {
                                echo '<span class="more_info" title="'.$cab['lesson'].'">'.mb_substr($cab['lesson'], 0, 11, 'UTF-8').'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                            }
                            else{
                                echo '<p>'.$cab['lesson'].'</p>';
                            }
                        }
                        else{
                            $place = str_replace("//", ", ", $cab['lesson']);
                            $teacher = substr($cab['lesson'], 0, $pos);
                            echo '<span class="teach" title="'.$place.'">'.$teacher.'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                        }
				echo '</div>

				<div class="teacher">';

					$pos = 0;
		            $pos = strpos($cab['place'], '//');
		            if ($pos === false){
		                $pos = 0;
                        $pos = strpos($cab['teacher'], '/');
                        if ($pos === false){
                            echo '<p>'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $cab['teacher']).'</p>';
                        }
                        else{
                            $place = str_replace("/", ", ", $cab['teacher']);
                            $teacher = substr($cab['teacher'], 0, $pos);
                            echo '<span class="teach" title="'.$place.'">'.preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2.$3.', $teacher).'<span class="substr">*</span></span><style>.substr{color:#2580A3;} </style>';
                        }
		            }
		            else{
		            	$teacher = $cab["teacher"];
		            	$cb = stristr($cab['place'], $cab_num, true);
		            	$col = mb_substr_count($cb, '//');
		            	for ($i=0; $i < $col; $i++) { 
		            		$teacher = stristr($teacher, '/');
		            		$teacher = substr($teacher, 1);
		            	}
		            	 if(stristr($teacher, '/')) {
		            		$teacher = stristr($teacher, '/', true);
		            	}

		            }

	            echo '                
				</div>
			</div>';
		}
		$cabDouble = $cab;
	}
}

?>