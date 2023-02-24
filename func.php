<?php

function get_acc() {

    global $connection;
    
    global $fullname;

    $sql = "SELECT acc FROM access WHERE fio = '$fullname'";

    $result = mysqli_query($connection, $sql);

    $str = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $str = $str[0]["acc"];
    $i = 0;
    $acc = [];

    while ($str) {
    	$pos = strpos ( $str , ',' , 0 );
    	$pos += 1;
    	$acc[$i] = stristr ( $str , ',' , true );
    	$str = substr($str, $pos);
    	$i++;
    }
    

    return $acc;

}

function get_cath(){
	global $connection;
	$sql = "SELECT * FROM cathedr ORDER BY `cathedr`.`cath_num` ASC";
	$result = mysqli_query($connection, $sql);
    $cath = mysqli_fetch_all($result, MYSQLI_ASSOC);
    for ($i=0; $i < count($cath); $i++) { 
    	$cathedr[$i] = $cath[$i]['cath_name'];
    }

    return $cathedr;
}

function get_block( $num ) {

	global $connection;
	$block = [];
	asort($num);
	$j = 0;
	
	if(($num[0] == 0)&&(!$num[1])){
	    $sql = "SELECT * FROM criteria ORDER BY `criteria`.`criteria_num` ASC";
	    $result = mysqli_query($connection, $sql);
	    $st = mysqli_fetch_all($result, MYSQLI_ASSOC);
	    $str = $st;
	    
    	for ($i = 0; $i < count($str); $i++){
    
    	    $bool = TRUE;
    
    	    for ($k = 0; $k < $j; $k++){
    
    	    	if ($str[$i]['block_num'] == $block[$k]['block_num']){
    	    		$bool = false;
    	    		break;
    	    	}
    
    	    }
    
    	    if ($bool){
    	    	if ($str[$i]['block_num'] == 0) {
    	    		$block[$j]['block_num'] = 0;
    	    		$block[$j]['block_name'] = 0;
    	    	}
    	    	else{
    	    		$d = $str[$i]['block_num'];
    	    		
    		    	$sql1 = "SELECT * FROM blocks WHERE block_num = '".$d."'";
    			    $result1 = mysqli_query($connection, $sql1);
    			    $blo = mysqli_fetch_all($result1, MYSQLI_ASSOC);
    			    $block[$j] = $blo[0];
    			}
    		    $j++;
    	    }
    
    	}
    	
	}
    else{
    	for ($i = 0; $i < count($num); $i++){
    
    	    $sql = "SELECT * FROM criteria WHERE criteria_num = '".$num[$i]."' ORDER BY `criteria`.`block_num` ASC";
    
    	    $result = mysqli_query($connection, $sql);
    
    	    $st = mysqli_fetch_all($result, MYSQLI_ASSOC);
    	    $str[$i] = $st[0];
    
    	    $bool = TRUE;
    
    	    for ($k = 0; $k < $j; $k++){
    
    	    	if ($str[$i]['block_num'] == $block[$k]['block_num']){
    	    		$bool = false;
    	    		break;
    	    	}
    
    	    }
    
    	    if ($bool){
    	    	if ($str[$i]['block_num'] == 0) {
    	    		$block[$j]['block_num'] = 0;
    	    		$block[$j]['block_name'] = 0;
    	    	}
    	    	else{
    	    		$d = $str[$i]['block_num'];
    	    		
    		    	$sql1 = "SELECT * FROM blocks WHERE block_num = '".$d."'";
    			    $result1 = mysqli_query($connection, $sql1);
    			    $blo = mysqli_fetch_all($result1, MYSQLI_ASSOC);
    			    $block[$j] = $blo[0];
    			}
    		    $j++;
    	    }
    
    	}
    }
    //var_dump( $block);

	$sect = [];
	$l = 0;
	
	//echo $j;

	for ($i=0; $i < $j; $i++) { 
		$l = 0;
		for ($h = 0; $h < count($str); $h++){
			$bool = TRUE;
			if ($str[$h]['block_num'] == $block[$i]["block_num"]){

			    for ($k = 0; $k < $l; $k++){

			    	if ($str[$h]['section_num'] == $sect[$i][$k]['section_num']){
			    		$bool = false;
			    		break;
			    	}

			    }

			    if ($bool){
			    	if ($str[$i]['block_num'] == 0) {
			    		$sect[$i][$l]['section_num'] = 0;
			    		$sect[$i][$l]['section_name'] = 0;
			    	}
			    	else{
				    	$sql1 = "SELECT * FROM sections WHERE section_num = '".$str[$h]['section_num']."'";
					    $result1 = mysqli_query($connection, $sql1);
					    $sec = mysqli_fetch_all($result1, MYSQLI_ASSOC);
					    $sect[$i][$l]['section_num'] = $sec[0]['section_num'];
					    $sect[$i][$l]['section_name'] = $sec[0]['section_name'];
					}
				    $l++;
			    }
			}
		}
	}
	

	$crit = [];
	for ($i=0; $i < $j; $i++) { 
		
		for ($n=0; $n < count($sect[$i]); $n++) {
			$l = 0;
			for ($h = 0; $h < count($str); $h++){
				$bool = TRUE;
				if (($str[$h]['section_num'] == $sect[$i][$n]['section_num'])&&($str[$h]['block_num'] == $block[$i]["block_num"])){

				    for ($k = 0; $k < $l; $k++){

				    	if ($str[$h]['criteria_num'] == $crit[$i][$n][$k]['criteria_num']){
				    		$bool = false;
				    		break;
				    	}

				    }

				    if ($bool){
				    	if ($str[$i]['block_num'] == 0) {
				    		$crit[$i][$n][$l]['criteria_num'] = 0;
				    		$crit[$i][$n][$l]['criteria_name'] = 0;
				    		$crit[$i][$n][$l]['criteria_mult'] = 0;
				    	}
				    	else{
				    		$crit[$i][$n][$l]['criteria_num'] = $str[$h]['criteria_num'];
				    		$crit[$i][$n][$l]['criteria_name'] = $str[$h]['criteria_name'];
				    		$crit[$i][$n][$l]['criteria_mult'] = $str[$h]['criteria_mult'];
						}
					    $l++;
				    }
				}
			}
		}
	}

/*
	echo "<br>";
	echo "<br>";
	echo "<br>";
	for ($i=0; $i < $j; $i++) { 
			echo $block[$i]['block_num'];
			echo "<br>";
			echo "<br>";
		for ($k=0; $k < count($sect[$i]); $k++) {
			echo $sect[$i][$k]['section_num'];
			echo "<br>";
			echo "<br>";
			echo $sect[$i][$k]['section_name'];
			echo "<br>";
			echo "<br>";
			for ($n=0; $n < count($crit[$i][$k]); $n++) {
				echo $crit[$i][$k][$n]['criteria_num'];
				echo "<br>";
				echo "<br>";
				echo $crit[$i][$k][$n]['criteria_name'];
				echo "<br>";
				echo "<br>";
				echo $crit[$i][$k][$n]['criteria_mult'];
				echo "<br>";
				echo "<br>";
				echo "<br>";
			}
			echo "<br>";
			echo "<br>";
		}
	}*/
	$a[0] = $block;
	$a[1] = $sect;
	$a[2] = $crit;
	return $a;
}

function get_points() {

	global $connection;
	//$date = date();
	$month = date("m");
	$year = date("Y");
	$date = date("Y-m-d");

	$poin = 1;

		$sql = "SELECT * FROM points WHERE month(date) = $month AND year(date) = $year";
		$result = mysqli_query($connection, $sql);

		if (mysqli_num_rows($result) == 0) {
			$sql1 = "INSERT INTO `backup`(`cath_num`, `block_num`, `section_num`, `criteria_num`, `criteria_mult`, `point`, `date`) SELECT cath_num, block_num, section_num, criteria_num, criteria_mult, point, date FROM `points` where month(date) = $month-1";
			$result = mysqli_query($connection, $sql1) or die(mysqli_error($connection));
			if($result){
				$sql1 = "UPDATE `points` SET `date`= '$date' WHERE month(date) = $month-1";
				$result = mysqli_query($connection, $sql1) or die(mysqli_error($connection));
				$poin = 0;
			}
		}

    return $poin;
}

function get_blocks() {

	global $connection;

	$sql1 = "SELECT * FROM blocks ORDER BY `blocks`.`block_num` ASC";
	$result1 = mysqli_query($connection, $sql1);
	$blocks = mysqli_fetch_all($result1, MYSQLI_ASSOC);

	return $blocks;
}

?>