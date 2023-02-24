<?php
	session_start();

	if(!empty($_SESSION['user']['full_name']))
		$fullname = $_SESSION['user']['full_name'];
	else{
		$fullname = 'Не найдено';
	}
	
	require('connect-base.php');
	require('func.php');
	
	$p = get_points();
	//echo $p;
	$l = get_acc();
	$ar = get_block($l);
	$bl = $ar[0];
	$cath = get_cath();
	
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Caomatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/rating1.css">
    <link rel="shortcut icon" href="http://www.example.com/myicon.ico"/>
    <script src="js/jquery.js"></script>
    <title>Бально-рейтинговая система</title>
</head>
<body>
	<div class="header">
		<div class="header-container">
			<div class="header-logo">
	            <a href="https://mgok.mskobr.ru/" target="_blank"><img src="/img/MGOK.svg" alt="МГОК" title="МГОК" class="logo"/></a>
	        </div>

	        <button class="back" onclick="document.location.replace('exit_cath.php');">Выход</button>
	        <div class="rating">
	        	<p class="rat">Рейтинг кафедр</p>
	        	<div class="ratline"></div>
	        </div>
	        <p class="fullname" style="display: none;"><?=$fullname?></p>
        	<div class="username" id=""><p><?=preg_replace('~^(\S++)\s++(\S)\S++\s++(\S)\S++$~u', '$1 $2<strong id="point">.</strong>$3<strong id="point">.</strong>', $_SESSION['user']['full_name']);?></p></div>

		</div>
	</div>
	<div class="blocks">
		
		<div class="blocks-container">
			<?php
			for ($i=0; $i < count($bl); $i++) { 
				if ($bl[$i]['block_num'] != 0) {
					echo '
					<div class="block">
						<a href="#'.$bl[$i]['block_num'].'" class="block-a" id="'.$bl[$i]['block_num'].'">Блок '.$bl[$i]['block_num'].'</a>
					</div>';
				}
			}
			
			echo '
			<div class="block">
				<a href="#i" class="block-a" id="bI">Итоги</a>
			</div>';
			
			?>
		</div>
		
	</div>
	<div class="conteiner">
		<div class="res">

		</div>
	</div>
</body>
</html>

<script>
	var all = <?php echo json_encode($ar);?>; // <-- no quotes, no parsify
	var cathedras = <?php echo json_encode($cath);?>;
	var acc = <?php echo json_encode($l);?>;
	

    //console.log(all);

	var bl = all[0];

	var razd = all[1];

	var crit = all[2];

	function result( cathedras ){

		var res = "";
		res += '<table class="result"><tr class="cath"><td class="emp"></td>';
		
		for (var i = 0; i < cathedras.length; i++) {
			var l = (i % 2) ? "n" : "y";
			//console.log(l);
			res += '<td class="cathedras" id="'+ l +'"><p>' + cathedras[i] + '</p></td>';
		}
		res += '</tr>';

		//var points = <?php //echo json_encode($p);?>;
		//var blocks = <?php //echo json_encode(get_blocks());?>;
		var points = [];
		var blocks = [];
		var mas = [];
		$.ajax({
            url: "/php-scripts/get_all.php",
            type: "POST",
            data: {cathedras: cathedras}, // Передаем данные для записи
            dataType: "json",
            async: false,
            success: function(get_points) {
            	points = get_points[0];
            	blocks = get_points[1];
            }
        })
		//console.log(points);

		for(var i = 0; i < cathedras.length; i++){
			mas[i] = 0;
		}
		
		for (var i = 0; i < blocks.length; i++) {
			res += '<tr class="res-r"><td class="res-td">Блок ' + blocks[i]["block_num"] + ' "' + blocks[i]["block_name"] + '"</td>';
			for (var j = 0; j < cathedras.length; j++) {
				mas[j] += points[j][i];
				if((((points[j][i].toFixed(1) * 10) % 10) > 0) || (((points[j][i].toFixed(1) * 10) % 10) < 0)){
        		   points[j][i] = points[j][i].toFixed(1);
        		}
				res += '<td class="res-td">' + points[j][i] + '</td>';
			}
			res += '</tr>';
		}

		res += '<tr class="res-r"><td class="res-td-res" id="i">Итоги</td>';
		
		

		for(var i = 0; i < cathedras.length; i++){
		    if((((mas[i].toFixed(1) * 10) % 10) > 0) || (((mas[i].toFixed(1) * 10) % 10) < 0)){
    		    mas[i] = mas[i].toFixed(1);
    		}
			res += '<td class="res-td-res">' + mas[i] + '</td>';
		}

		res += '</table>';

		return res;
	}

	function inp_blure(index, sec_num, crit_num){
		//console.log(sec_num);
		index = index.toString();
		var inp_val = $('.td-get #'+index).val();
		if(inp_val){

			if (sec_num != 1000){
				sec_num = "Раздел " + sec_num;
			}
			else
				sec_num = "Штрафные баллы";

			//console.log(sec_num);
			var mass = [];
			var pos = index.indexOf('0');
			//var pos1 = index.lastIndexOf('0');
			
			
			var mult_id = "";
			mult_id += index.toString();
			var len = mult_id.length;
			var i = len-1;
			
			if (index[len-1] != 0){
			    while (mult_id[i] != '0'){
			        console.log(mult_id);
			        console.log(i);
			        mult_id = mult_id.substring(0, i);
			        i--;
			    }
			}
			else{
			    mult_id = mult_id.substring(0, i);
			    i--;
			    while (mult_id[i] != '0'){
			        console.log(mult_id[i]);
			        console.log(mult_id);
			        console.log(i);
			        mult_id = mult_id.substring(0, i);
			        i--;
			    }
			}
			
			//var mult_id = index.substring(0, pos);
			var cath_num = index.substring(pos+1);
			if (cath_num.indexOf('0') != -1){
				var pos = cath_num.indexOf('0');
				if (cath_num[pos-1]){
				    
				}
				else
				    cath_num = cath_num.substring(pos+1);
			}
			
			var block_num = $('.td-get #'+index).attr("name");
			//console.log(mult_id);
			var mult_val1 = $('#'+(mult_id)).text();
			mult_val = inp_val * mult_val1;
			//console.log(mult_val);
			var res = $('.critz-tr #'+index+' .mult_div').text(mult_val.toFixed(1));
			
			mass[0] = {
				cath_num: cath_num,
				block_num: block_num,
				section_num: sec_num,
				criteria_num: crit_num,
				criteria_mult: mult_val1,
				point: inp_val,
			}

			$.ajax({
	        url: "/php-scripts/rating_update.php",
	        type: "POST",
	        data: {mass: mass}, // Передаем данные для записи
	        dataType: "json",
	        success: function(update_points) {
	        	//console.log(update_points);
	        }
	    })
			//console.log(mass);
		}
		 
		//console.log(inp_val);
	}

	function update_data(){

		var sec = Array.from($(".sec-td"));
		var crit_num = Array.from($(".critz-td1"));
		var mult = Array.from($(".critz-td3"));
		//var se = document.querySelector(".critz-td1").getAttribute("id");
		var points = Array.from($(".td-get input"));
		var block = $(".MyClass").text();
		//console.log($(sec).text());
		var pos = block.indexOf(' ');
		var block_number = block.substring(pos+1);
		//console.log($(points[1]).val());
		//console.log((crit_num[4]).getAttribute("id"));
		//console.log($(block).text());
		//cathedras
		var count = 0;
		var mass = [];
		var rec = 0;

		for (var i = 0; i < crit_num.length; i++) {

			for (var j = 0; j < cathedras.length; j++) {

				if ($(points[count]).val() != ""){
					mass[rec] = {
						cath_num: ++j,
						block_num: block_number,
						section_num: (crit_num[i]).getAttribute("id"),
						criteria_num: $(crit_num[i]).text(),
						criteria_mult: $(mult[i]).text(),
						point: $(points[count]).val(),
					}
					rec++;
				}
				count++;
			}
		}
		$.ajax({
	        url: "/php-scripts/rating_update.php",
	        type: "POST",
	        data: {mass: mass}, // Передаем данные для записи
	        dataType: "json",
	        success: function(update_points) {
	        	//console.log(update_points);
	        }
	    })
		//console.log(mass);
	}

	function blocks16(link){

		

		var res = '';
		res += '<table class="blocks16-t"><tr class="bl16-r1"><td class="bl16-numb">№</td><td class="bl16-crit">Критерии эффективности</td><td class="bl16-weight">Удельный вес критерия за 1 ед.</td>';

		for (var i = 0; i < cathedras.length; i++){
			res += '<td class="bl16-cath"><p class="cath-p">' + cathedras[i] + '</p></td>';
		}

		//console.log(link);
		//alert(link);

		res += '</tr></table>';

		var index = 1;
		var rows = 1;

		for (var i = 0; i < bl.length; i++){
			//console.log(bl[i]);
			if(bl[i]['block_num'] === link){
				for(j = 0; j < razd[i].length; j++){
					if(razd[i][j]['section_num'] != 'Раздел 0'){
						res += '<table class="sections"><tr class="sec-row"><td class="sec-td">' + razd[i][j]["section_num"];
						if(razd[i][j]["section_num"] != 'Штрафные баллы'){
							res += ' ' + razd[i][j]["section_name"] + '</td></tr></table>';
						}
						else{
							res +='</td></tr></table>';
						}
					}
					res += '<table class="critz">';
					for(k = 0; k < crit[i][j].length; k++){
						res += '<tr class="critz-tr"><td class="critz-td1" id="' + razd[i][j]["section_num"] + '">' + crit[i][j][k]["criteria_num"] + '</td><td class="critz-td2">' + crit[i][j][k]["criteria_name"] + '</td><td class="critz-td3" id="' + rows*1000 + '">' + crit[i][j][k]["criteria_mult"]+ '</td>';
						
						var t = crit[i][j][k]["criteria_mult"];
						index = 1;
						$.ajax({
				            url: "/php-scripts/get_points.php",
				            type: "POST",
				            data: {num: crit[i][j][k]["criteria_num"], cathedras: cathedras}, // Передаем данные для записи
				            dataType: "json",
				            async: false,
				            success: function(get_points) {
				            	for (var l = 0; l < cathedras.length; l++) {

				            		var mult = get_points[l] * t;
				            		var index_1 = rows*1000 + "" + index;
				            		//console.log(index_1);
				            		var b = bl[i]['block_num'];//*1000 + (l + 1);
				            		var sec = 0;
				            		if (razd[i][j]["section_num"] != 'Штрафные баллы'){
				            			var pos = razd[i][j]["section_num"].indexOf(' ');
										var sec = razd[i][j]["section_num"].substring(pos+1);
				            		}
				            		else
				            			sec = 1000;
				            		res += '<td class="td-get">';
				            		//console.log(acc);
				            		
				            		if(((mult.toFixed(1) * 10) % 10) != 0){
				            		    mult = mult.toFixed(1);
				            		}
				            		if((acc[0] == 0) && (!acc[1]))
				            		{
				            		    if(get_points[l])
    				            			res += get_points[l] +'</td><td class="td-mult" id="' + index_1 + '"><div class="mult_div">'+ mult;
    				            		else{
    				            			res += '</td><td class="td-mult" id="' + index_1 + '"><div class="mult_div">'+ mult;
    				            		}
				            		}
				            		else{
    				            		if(get_points[l])
    				            			res += '<input onblur="inp_blure(' + index_1 + ', ' + sec + ', ' + crit[i][j][k]["criteria_num"] + ')" id="' + index_1 + '" class="td-get-inp" name="'+ b +'" value='+ get_points[l] +'></td><td class="td-mult" id="' + index_1 + '"><div class="mult_div">'+ mult;
    				            		else{
    				            			res += '<input onblur="inp_blure(' + index_1 + ', ' + sec + ', ' + crit[i][j][k]["criteria_num"] + ')" id="' + index_1 + '" class="td-get-inp" name="'+ b +'" value=""></td><td class="td-mult" id="' + index_1 + '"><div class="mult_div">'+ mult;
    				            		}
				            		}
				            		res += '</div></td>';

				            		index++;
				            		//console.log(res);
				            		resalt = res;
				            	}
				            	
				            	return res;
				            }
				        })
				        rows++;
				        //return resalt;
				        res += '</tr>';
				        //console.log(get_points);
						
					}
					res += '</table>';
				}
				break;
			}
		}
		//res += '<input type="submit" onclick="update_data()" class="update" value="Сохранить">';

		return res;
	}

	$(document).ready(function() {

		var n;
		var res = "";

		$(".block-a").removeClass("MyClass");
		var link = $('.block-a').attr("id");
		$("#"+link).addClass("MyClass");

		//console.log(link);
		//console.log(bl[0]);

		if (bl[0]['block_num'] != 0) {
			n = 0;
		}
		else{
			if (bl[1]) {
				n = 1;
			}
			else{
				n = 3;
			}
		}
        
        res += blocks16(link);
        
		/*if (n != 3) {
			res += blocks16(link);
		}
		else{
			res += result(cathedras);
		}*/

		$(".res").append(res);




		$(".block-a").bind("click", function() {
			$(".res").empty();
    		$(".block-a").removeClass("MyClass");
			var link = $(this).attr("id");
			$("#"+link).addClass("MyClass");
			var res = "";
			//document.getElementById("id").className += " MyClass";
			if (link === 'bI'){
				if (window.matchMedia('(min-width: 1920px)').matches) {

	    		}
	    		if (window.matchMedia('(max-width: 1919px)').matches) {
	    			$('.res').css({'width' : '1140px'});

	    		}
	    		res += result(cathedras);
		    	$(".res").append(res);

	    	}
	    	else{
	    		$('.res').css({'width' : '1464px'});
	    		res += blocks16(link);
	    		$(".res").append(res);
	    	}

	    	$(function() {
		    let header = $('.blocks16-t');
		    let hederHeight = header.height(); // вычисляем высоту шапки
		    $(window).scroll(function() {
		        if($(this).scrollTop() > 300) {
		            header.addClass('header_fixed');
		            $('body').css({
		            'paddingTop': hederHeight+'px' // делаем отступ у body, равный высоте шапки
		            });
		            $('.bl16-r1').css({
		            'position': "relative",
		            'border-bottom': "1px solid #4F80A1"
		            })
		            $('.blocks16-t').css({
		            'position': "sticky",
		            })
		            
		        } else {
		            header.removeClass('header_fixed');
		            $('body').css({
		            'paddingTop': 0 // удаляю отступ у body, равный высоте шапки
		            })
		            $('.bl16-r1').css({
		            'position': "static"
		            })
		            $('.blocks16-t').css({
		            'position': "unset",
		            'border-bottom': "none"
		            })
		        }
		    });
		});

		})
		
		$(function() {
		    let header = $('.blocks16-t');
		    let hederHeight = header.height(); // вычисляем высоту шапки
		    $(window).scroll(function() {
		        if($(this).scrollTop() > 300) {
		            header.addClass('header_fixed');
		            $('body').css({
		            'paddingTop': hederHeight+'px' // делаем отступ у body, равный высоте шапки
		            });
		            $('.bl16-r1').css({
		            'position': "relative",
		            'border-bottom': "1px solid #4F80A1"
		            })
		            $('.blocks16-t').css({
		            'position': "sticky",
		            })
		            
		        } else {
		            header.removeClass('header_fixed');
		            $('body').css({
		            'paddingTop': 0 // удаляю отступ у body, равный высоте шапки
		            })
		            $('.bl16-r1').css({
		            'position': "static"
		            })
		            $('.blocks16-t').css({
		            'position': "unset",
		            'border-bottom': "none"
		            })
		        }
		    });
		});

    })

</script>

<!--<tr><td>название блока</td><td>балл кафедры</td></tr> -->