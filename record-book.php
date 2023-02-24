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

$table = get_entry();

$e_group = get_enrty_class_group();


?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Caomatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/record-book1.css">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <script src="js/jquery.js"></script>
    <title>Бально-рейтинговая система</title>
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
	<div class="container">
		<?echo '
		<div class="select">
			<div class="name-s">
				<select id="name-sel" onchange="selected()">
					<option value="Наименование дисциплины">Наименование дисциплины</option>';
					if (!empty($table)) {
						$tabl = $table[0];
						foreach ($table as $tabl){
							echo '<option class="name-sel" value="'.$tabl["lesson"].'">'.$tabl["lesson"].'</option>';
						}
					}
				echo '</select>
			</div>
			<div class="group-s">
				<select id="group-sel">
					<option value="group">Группа</option>
				</select>
			</div>
			<div class="sem-s">
				<input class="sem-inp" type="text" maxlength="1" placeholder="Семестр">
			</div>
		</div>';
		?>
		<div class="moduls">
			<a href="#1" class="modul" id="m1">Модуль 1</a>
			<a href="#2" class="modul m" id="m2">Модуль 2</a>
			<a href="#3" class="modul m" id="m3">Модуль 3</a>
			<a href="#4" class="modul m" id="m4">Модуль 4</a>
			<a href="#5" class="modul m" id="m5">Модуль 5</a>
			<a href="#6" class="modul m" id="m6">Модуль 6</a>
			<a href="#7" class="modul m" id="m7">Модуль 7</a>
			<a href="#i" class="modul" id="mI">Итоги</a>
		</div>
		<div class="res">
			
		</div>
	</div>
</body>
</html>

<script>
	function initials(str) {
	  return str.split(/\s+/).map((w,i) => i ? w.substring(0,1).toUpperCase() + '.' : w).join(' ');
	}
	
	function selected(){
		var name_select = $('#name-sel option:selected').text();
		//alert(name_select);
		var teacher = $('.fullname').text();
		$('#group-sel').empty();
		jQuery.ajax({
            url: "/php-scripts/group-select.php",
            type: "POST",
            data: {name_select:name_select, teacher:teacher}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
            	console.log(result);
            	$('#group-sel').attr('size', result.class.class.length);
            	for(i = 0; i < result.class.class.length; i++){
            		$('#group-sel').append('<option value=' + result.class.class[i] + '>' + result.class.class[i] + '</option>');
            	}
            }
        })

	}
	
	$(".modul").bind("click", function() {
		$(".modul").removeClass("selected");
		$('.modul').css({'color':'#707070'});
		$(this).css({'color':'#4F80A1'});
		var link = $(this).attr("id");
		$("#"+link).addClass("selected");

	})

	$(document).ready(function() {
        $("#m1").bind("click", function() {

        	var lesson = document.getElementById('name-sel').value;

        	var group = document.getElementById('group-sel').value;
        	
        	var semestr = $('.sem-inp').val();

        	var teacher = $('.fullname').text();

        	$( ".res").empty();
        	$( ".update").remove();


        	if ((lesson === "" || lesson === "Наименование дисциплины") || (group === "" || group ==="Группа") || semestr == ""){
        		alert("Не все поля заполнены");
        		return false;
        	}

        	jQuery.ajax({
            url: "/php-scripts/module_1.php",
            type: "POST",
            data: {lesson:lesson, group:group, semestr:semestr, teacher:teacher}, // Передаем данные для записи
            dataType: "json",
            success: function(result) {
                if (result){ 
                    var res ="";
		        	var b_entry="";
		        	var b_control="";
		        	//$(".m-table").remove();
		        	function table_res(){
		        		res += '<div class="m-table"><p class="m-title">Входной (стартовый), текущий, рубежный контроль и промежуточная аттестация</p><table class="m1-table"><tr class="t-header"><td></td><td>Входной (стартовый) контроль</td><td>Текущий контроль</td><td>Рубежный контроль</td><td>Промежуточная аттестация</td></tr><tr class="t-header t-h2"><td>Максимальное количество баллов</td><td>5 баллов</td><td>30 баллов</td><td>15 баллов</td><td>20 баллов</td></tr></table><div style="overflow-x:auto; width: 165px; display: block; float: left;"><table class="m1-t2"><tr class="m1-r2"><td class="r2-name">Название контрольных точек</td></tr><tr class="m1-r3"><td class="r3-name">Максимальное количество баллов на каждый блок</td></tr>';
		        		for(var i = 0; i < result.stud.full_name.length; i++){
		        			res += '<tr class="m1-r4"><td class="student-name"><p>'+ initials(result.stud.full_name[i]) +'</p><p class="number-stud" style="display: none">' + i + '</p></td></tr>';
		        		}

		        		res += '</table></div><div style="overflow-x:auto; width: 195px; display: block; float: left;"><table id="entry_m" class="m1-t3"><tr class="m1-t3-r1">';

		        		function showtable(stud_list, score_list, name_table, plus, mtr1, tdpoints, points, mtr2, tdspoints, spoints){

			        		if(score_list != null){

			        			var name_cp = [];
			        			var len = 0;
			        			for (var i = 0; i < score_list.name_cp.length; i++){
			        				if(name_cp){
			        					var bool = new Boolean(true);
			        					for (var k = 0; k < len; k++){
			        						if(name_cp[k]["name_cp"] === score_list.name_cp[i]){
			        							bool = false;
			        							break;
			        						}
			        					}
			        					if(bool){
			        					    if (score_list.maxb_cp[i] == '0'){
    			        					    name_cp[len] = {
    			        					        name_cp: score_list.name_cp[i],
    				        						maxb_cp: "",
    			        						}
			        					    }
			        					    else
    			        						name_cp[len] = {
    				        						name_cp: score_list.name_cp[i],
    				        						maxb_cp: score_list.maxb_cp[i],
    			        						}
			        						len++;
			        					}
			        				}
			        				else{
		        				    	if (score_list.maxb_cp[i] == '0'){
			        					    name_cp[len] = {
			        					        name_cp: score_list.name_cp[i],
				        						maxb_cp: "",
			        						}
		        					    }
		        					    else
			        						name_cp[len] = {
				        						name_cp: score_list.name_cp[i],
				        						maxb_cp: score_list.maxb_cp[i],
			        						}
			        					len++;
			        				}
			        			}
			        			console.log(name_cp);

			        			var entry = [];

		        				for(var j = 0; j < stud_list.full_name.length; j++){
		        					var entry_mas = [];
		        					
		        					for(var i = 0; i < name_cp.length; i++){
		        						bool = true;
			        					for(var k = 0; k < score_list.student.length; k++){
			        						
				        					if((stud_list.full_name[j] === score_list.student[k]) && (name_cp[i]["name_cp"] === score_list.name_cp[k])){
				        						bool = false;
				        						entry_mas[i] = {
				        							student_p: score_list.student_p[k],
				        						}
				        						break;
				        					}
				        				}
				        				console.log(bool);
				        				if(bool){
				        					entry_mas[i] = {
				        							student: stud_list.full_name[j],
				        							name_cp: name_cp[i]["name_cp"],
				        							maxb_cp: name_cp[i]["maxb_cp"],
				        							student_p: "",
				        						}
				        				}
				        			}
				        			entry[j] = entry_mas;
		        				}
			        				
			        			console.log(entry);

				        	}
				        	else{

				        		var name_cp = [];
				        		name_cp[0] = {
			        						name_cp: "Контрольная точка",
			        						maxb_cp: "",
			        					}

				        		var entry = [];
				        		var entry_mas = [];
		        				for(var j = 0; j < stud_list.full_name.length; j++){
		        					entry_mas[0] = {
		        							student_p: "",
		        						}
				        			entry[j] = entry_mas;
		        				}
		        				console.log(entry);	
				        	}


			        		for(l = 0; l < name_cp.length; l++){
		        				res += '<td class="' + name_table + '" id="' + l + '"><p>'+ name_cp[l]["name_cp"] +'</p></td>';
		        			}

		        			res += '<td class="' + plus + '"><img src="icons/plus.svg"></td></tr><tr class="' + mtr1 + '">';

		        			for(l = 0; l < name_cp.length; l++){
		        				res += '<td class="' + tdpoints + '" id="' + l + '"><input maxlength="3" type="text" class="' + points + '" value="' + name_cp[l]["maxb_cp"] + '"></td>';
		        			}

		        			for(l = 0; l < entry.length; l++){
		        				res += '<tr class="' + mtr2 + '">';
		        				for(i = 0; i < entry[l].length; i++)
		        					res += '<td class="' + tdspoints + '"><input value="' + entry[l][i]["student_p"] + '"type="text" id="' + l + '_' + i + '" maxlength="3" class="' + spoints + '"></td>';
		        				res += '</tr>';
		        			}

		        			return name_cp.length;
		        		}

		        		var en1 = showtable(result.stud, result.entry, "entry", "plus", "m1-t3-r2", "td-points", "points", "m1-t3-r3", "td-s-points", "s-points");

		        		res += '</table></div><div style="overflow-x:auto; width: 390px; display: block; float: left;"><table class="m1-t4"><tr class="m1-t4-r1">';

		        		var en2 = showtable(result.stud, result.current, "current", "plus2", "m1-t4-r2", "td-points-2", "points2", "m1-t4-r3", "td-s-points-2", "s-points2");

		        		res += '</table></div><div style="overflow-x:auto; width: 195px; display: block; float: left;"><table class="m1-t5"><tr class="m1-t5-r1">';

		        		var en3 = showtable(result.stud, result.control, "control", "plus3", "m1-t5-r2", "td-points-3", "points3", "m1-t5-r3", "td-s-points-3", "s-points3");

		        		res += '</table></div><div style="overflow-x:auto; width: 195px; display: block; float: left;"><table class="m1-t6"><tr class="m1-t6-r1">';

		        		var en4 = showtable(result.stud, result.att, "att", "plus4", "m1-t6-r2", "td-points-4", "points4", "m1-t6-r3", "td-s-points-4", "s-points4");

		        		res += '</table></div></div>';

		        		/*if(result.entry != null){

		        			var name_cp = [];
		        			for (var i = 0; i < result.entry.name_cp.length; i++){
		        				if(name_cp.length > 0){
		        					var bool = new Boolean(true);
		        					for (j = 0; j < i; j++){
		        						if(name_cp[j]["name_cp"] === result.entry.name_cp[i]){
		        							bool = false;
		        							break;
		        						}
		        					}
		        					if(bool){
		        						name_cp[i] = {
			        						name_cp: result.entry.name_cp[i],
			        						maxb_cp: result.entry.maxb_cp[i],
		        						}
		        					}
		        				}
		        				else{
		        					name_cp[i] = {
		        						name_cp: result.entry.name_cp[i],
		        						maxb_cp: result.entry.maxb_cp[i],
		        					}
		        				}
		        			}
		        			console.log(name_cp);

		        			var entry = [];

	        				for(var j = 0; j < result.stud.full_name.length; j++){
	        					var entry_mas = [];
	        					
	        					for(var i = 0; i < name_cp.length; i++){
	        						bool = true;
		        					for(var k = 0; k < result.entry.student.length; k++){
		        						
			        					if((result.stud.full_name[j] === result.entry.student[k]) && (name_cp[i]["name_cp"] === result.entry.name_cp[k])){
			        						bool = false;
			        						entry_mas[i] = {
			        							student: result.stud.full_name[j],
			        							name_cp: result.entry.name_cp[k],
			        							max_cp: result.entry.maxb_cp[k],
			        							student_p: result.entry.student_p[k],
			        						}
			        						break;
			        					}
			        				}
			        				console.log(bool);
			        				if(bool){
			        					entry_mas[i] = {
			        							student: result.stud.full_name[j],
			        							name_cp: name_cp[i]["name_cp"],
			        							max_cp: name_cp[i]["maxb_cp"],
			        							student_p: "",
			        						}
			        				}
			        			}
			        			entry[j] = entry_mas;
	        				}
		        				
		        			console.log(entry);

			        	}
			        	else{

			        		var name_cp = [];
			        		name_cp[0] = {
		        						name_cp: "Контрольная точка",
		        						maxb_cp: "",
		        					}

			        		var entry = [];
			        		var entry_mas = [];
	        				for(var j = 0; j < result.stud.full_name.length; j++){
	        					entry_mas[0] = {
	        							student: result.stud.full_name[j],
	        							name_cp: "Контрольная точка",
	        							max_cp: "",
	        							student_p: "",
	        						}
			        			entry[j] = entry_mas;
	        				}
	        				console.log(entry);	
			        	}


		        		for(l = 0; l < name_cp.length; l++){
	        				res += '<td class="entry" id="entry"><p>'+ name_cp[l]["name_cp"] +'</p></td>';
	        			}

	        			res += '<td class="plus"><img src="icons/plus.svg"></td></tr><tr class="m1-t3-r2">';

	        			for(l = 0; l < name_cp.length; l++){
	        				res += '<td class="td-points"><input maxlength="1" type="text" class="points" value="' + name_cp[l]["maxb_cp"] + '"></td>';
	        			}

	        			for(l = 0; l < entry.length; l++){
	        				res += '<tr class="m1-t3-r3">';
	        				for(i = 0; i < entry[l].length; i++)
	        					res += '<td class="td-s-points"><input value="' + entry[l][i]["student_p"] + '"type="text" maxlength="3" class="s-points"></td>';
	        				res += '</tr>';
	        			}*/
		        		
		      				//тут был плюс

		        		return [res, en1, en2, en3, en4, result.stud.full_name];
		        	}
					var res1 = table_res();
					res1[0] += '<input type="submit" class="update" value="Сохранить">';
					$(".res").append(res1[0]);

					console.log(res1);

					entry_name = res1[1];

					console.log("entry_name " + entry_name);

					current_name = res1[2];

					control_name = res1[3];

					att_name = res1[4];

					list_stud = res1[5];

					var all = [];

					$(".update").on("click", function(){

						var entry_name_cp = Array.from($(".entry p"));
						var entry_maxb_cp = Array.from($(".td-points input"));
						var entry_student_p = Array.from($(".td-s-points input"));
						var count = 0;
						var numb_p = 0;

						for(i = 0; i < list_stud.length; i++){
							for(j = 0; j < entry_name; j++){
								if($(entry_student_p[numb_p]).val() != ""){
									all[count] = {
										student: list_stud[i],
										stud_group: $("#group-sel").val(),
										teacher: $(".fullname").text(),
										lesson: $("#name-sel").val(),
										name_cp: $(entry_name_cp[j]).text(),
										maxb_cp: $(entry_maxb_cp[j]).val(),
										student_p: $(entry_student_p[numb_p]).val(),
										module_name: "Входной (стартовый) контроль",
										semestr: $(".sem-inp").val(),
									}
									count++;
								}
								numb_p++;
							}
						}

						var current_name_cp = Array.from($(".current p"));
						var current_maxb_cp = Array.from($(".td-points-2 input"));
						var current_student_p = Array.from($(".td-s-points-2 input"));
						//var count = 0;
						var numb_p = 0;

						for(i = 0; i < list_stud.length; i++){
							for(j = 0; j < current_name; j++){
								if($(current_student_p[numb_p]).val() != ""){
									all[count] = {
										student: list_stud[i],
										stud_group: $("#group-sel").val(),
										teacher: $(".fullname").text(),
										lesson: $("#name-sel").val(),
										name_cp: $(current_name_cp[j]).text(),
										maxb_cp: $(current_maxb_cp[j]).val(),
										student_p: $(current_student_p[numb_p]).val(),
										module_name: "Текущий контроль",
										semestr: $(".sem-inp").val(),
									}
									count++;
								}
								numb_p++;
							}
						}

						var control_name_cp = Array.from($(".control p"));
						var control_maxb_cp = Array.from($(".td-points-3 input"));
						var control_student_p = Array.from($(".td-s-points-3 input"));
						//var count = 0;
						var numb_p = 0;

						for(i = 0; i < list_stud.length; i++){
							for(j = 0; j < control_name; j++){
								if($(control_student_p[numb_p]).val() != ""){
									all[count] = {
										student: list_stud[i],
										stud_group: $("#group-sel").val(),
										teacher: $(".fullname").text(),
										lesson: $("#name-sel").val(),
										name_cp: $(control_name_cp[j]).text(),
										maxb_cp: $(control_maxb_cp[j]).val(),
										student_p: $(control_student_p[numb_p]).val(),
										module_name: "Рубежный контроль",
										semestr: $(".sem-inp").val(),
									}
									count++;
								}
								numb_p++;
							}
						}

						var att_name_cp = Array.from($(".att p"));
						var att_maxb_cp = Array.from($(".td-points-4 input"));
						var att_student_p = Array.from($(".td-s-points-4 input"));
						//var count = 0;
						var numb_p = 0;

						for(i = 0; i < list_stud.length; i++){
							for(j = 0; j < att_name; j++){
								if($(att_student_p[numb_p]).val() != ""){
									all[count] = {
										student: list_stud[i],
										stud_group: $("#group-sel").val(),
										teacher: $(".fullname").text(),
										lesson: $("#name-sel").val(),
										name_cp: $(att_name_cp[j]).text(),
										maxb_cp: $(att_maxb_cp[j]).val(),
										student_p: $(att_student_p[numb_p]).val(),
										module_name: "Промежуточная аттестация",
										semestr: $(".sem-inp").val(),
									}
									count++;
								}
								numb_p++;
							}
						}

						console.log(all);

						jQuery.ajax({
					        url: "/php-scripts/module_1-update.php",
					        type: "POST",
					        data: {all:all}, // Передаем данные для записи
					        dataType: "json",
					        success: function(result) {
					            if (result){ 
					            	console.log(result);

								}
							}
						})
					})


					$(".entry").bind("click", function(){
		    			var id = $(this).attr("id");
		    			var old = $("#" + id + ".entry p").text();

						let name = prompt();
						name = name.trim();
						
						if(name){
							var change_t = $("#" + id + ".entry p").text(name);
							var change = $("#" + id + ".entry p").text();
							var stud_group = $("#group-sel").val();
							var teacher = $(".fullname").text();
							var lesson = $("#name-sel").val();
							var module_name = "Входной (стартовый) контроль";
							var semestr = $(".sem-inp").val();
							jQuery.ajax({
						        url: "/php-scripts/module_1-update-name_cp.php",
						        type: "POST",
						        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
						        dataType: "json",
						        success: function(result) {
						            if (result){ 
						            	console.log(result);

									}
									//return false;
								}
							})
						}
					})

					$(".current").bind("click", function(){
		    			var id = $(this).attr("id");
		    			var old = $("#" + id + ".current p").text();

						let name = prompt('Изменить название?', old);
						name = name.trim();
						
						if(name){
							var change_t = $("#" + id + ".current p").text(name);
							var change = $("#" + id + ".current p").text();
							var stud_group = $("#group-sel").val();
							var teacher = $(".fullname").text();
							var lesson = $("#name-sel").val();
							var module_name = "Текущий контроль";
							var semestr = $(".sem-inp").val();
							jQuery.ajax({
						        url: "/php-scripts/module_1-update-name_cp.php",
						        type: "POST",
						        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
						        dataType: "json",
						        success: function(result) {
						            if (result){ 
						            	console.log(result);

									}
									//return false;
								}
							})
						}
					})

					$(".control").bind("click", function(){
		    			var id = $(this).attr("id");
		    			var old = $("#" + id + ".control p").text();

						let name = prompt();
						name = name.trim();
						
						if(name){
							var change_t = $("#" + id + ".control p").text(name);
							var change = $("#" + id + ".control p").text();
							var stud_group = $("#group-sel").val();
							var teacher = $(".fullname").text();
							var lesson = $("#name-sel").val();
							var module_name = "Рубежный контроль";
							var semestr = $(".sem-inp").val();
							jQuery.ajax({
						        url: "/php-scripts/module_1-update-name_cp.php",
						        type: "POST",
						        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
						        dataType: "json",
						        success: function(result) {
						            if (result){ 
						            	console.log(result);

									}
									//return false;
								}
							})
						}
					})

					$(".att").bind("click", function(){
		    			var id = $(this).attr("id");
		    			var old = $("#" + id + ".att p").text();

						let name = prompt();
						name = name.trim();
						
						if(name){
							var change_t = $("#" + id + ".att p").text(name);
							var change = $("#" + id + ".att p").text();
							var stud_group = $("#group-sel").val();
							var teacher = $(".fullname").text();
							var lesson = $("#name-sel").val();
							var module_name = "Промежуточная аттестация";
							var semestr = $(".sem-inp").val();
							jQuery.ajax({
						        url: "/php-scripts/module_1-update-name_cp.php",
						        type: "POST",
						        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
						        dataType: "json",
						        success: function(result) {
						            if (result){ 
						            	console.log(result);

									}
									//return false;
								}
							})
						}
					})

		        	$(".plus").on("click", function(){
			    		let name = prompt();
			    		name = name.trim();
			    		if(name){
				    		var and1 = '';
				    		var and2 = '';
				 
				    		$('<td class="entry" id="' + entry_name + '"><p>'+ name +'</p></td>').insertBefore('.plus');
				    		entry_name++;
				    		$('.m1-t3-r2').append(function(){
				    			and1 += '<td class="td-points"><input maxlength="3" type="text" class="points" value></td>';
				    			return and1;
				    		})
				    		$('.m1-t3-r3').append(function(){
				    			var and2 = '';
				    			and2 += '<td class="td-s-points"><input type="text" maxlength="3" class="s-points"></td>';
				    			return and2;
			    			})

			    			$(".entry").bind("click", function(){
			    			var id = $(this).attr("id");
			    			var old = $("#" + id + ".entry p").text();
			    			old = old.trim();

							let name = prompt();
							name = name.trim();
							
							if(name){
								var change_t = $("#" + id + ".entry p").text(name);
								var change = $("#" + id + ".entry p").text();
								var stud_group = $("#group-sel").val();
								var teacher = $(".fullname").text();
								var lesson = $("#name-sel").val();
								var module_name = "Входной (стартовый) контроль";
								var semestr = $(".sem-inp").val();
								jQuery.ajax({
							        url: "/php-scripts/module_1-update-name_cp.php",
							        type: "POST",
							        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
							        dataType: "json",
							        success: function(result) {
							            if (result){ 
							            	console.log(result);

										}
										//return false;
									}
								})
							}
						})
			    		}
		    		})


					$(".plus2").bind("click", function(){
			    		let name = prompt();
			    		name = name.trim();
			    		if(name){
				    		var and1 = '';
				    		var and2 = '';
				    		$('<td class="current" id="' + current_name + '"><p>' + name + '</p></td>').insertBefore('.plus2');
				    		current_name++;
				    		$('.m1-t4-r2').append(function(){
				    			and1 += '<td class="td-points-2"><input maxlength="3" type="text" class="points2"></td>'
				    			return and1;
				    		})
				    		$('.m1-t4-r3').append(function(){
				    			var and2 = '';
				    			and2 += '<td class="td-s-points-2"><input type="text" maxlength="3" class="s-points2"></td>';
				    			return and2;
				    		})

				    		$(".current").bind("click", function(){
			    			var id = $(this).attr("id");
			    			var old = $("#" + id + ".current p").text();
                            old = old.trim();
                            
							let name = prompt();
							name = name.trim();
							
							if(name){
								var change_t = $("#" + id + ".current p").text(name);
								var change = $("#" + id + ".current p").text();
								var stud_group = $("#group-sel").val();
								var teacher = $(".fullname").text();
								var lesson = $("#name-sel").val();
								var module_name = "Текущий контроль";
								var semestr = $(".sem-inp").val();
								jQuery.ajax({
							        url: "/php-scripts/module_1-update-name_cp.php",
							        type: "POST",
							        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
							        dataType: "json",
							        success: function(result) {
							            if (result){ 
							            	console.log(result);

										}
									}
								})
							}
						})
				    	}
			    	})
					$(".plus3").bind("click", function(){
			    		let name = prompt();
			    		name = name.trim();
			    		if(name){
				    		var and1 = '';
				    		var and2 = '';
				    		$('<td class="control" id="' + control_name + '"><p>' + name + '</p></td>').insertBefore('.plus3');
				    		control_name++;
				    		$('.m1-t5-r2').append(function(){
				    			and1 += '<td class="td-points-3"><input maxlength="3" type="text" class="points3"></td>'
				    			return and1;
				    		})
				    		$('.m1-t5-r3').append(function(){
				    			var and2 = '';
				    			and2 += '<td class="td-s-points-3"><input type="text" maxlength="3" class="s-points3"></td>';
				    			return and2;
				    		})

				    		$(".control").bind("click", function(){
			    			var id = $(this).attr("id");
			    			var old = $("#" + id + ".control p").text();
			    			old = old.trim();

							let name = prompt();
							name = name.trim();
							
							if(name){
								var change_t = $("#" + id + ".control p").text(name);
								var change = $("#" + id + ".control p").text();
								var stud_group = $("#group-sel").val();
								var teacher = $(".fullname").text();
								var lesson = $("#name-sel").val();
								var module_name = "Рубежный контроль";
								var semestr = $(".sem-inp").val();
								jQuery.ajax({
							        url: "/php-scripts/module_1-update-name_cp.php",
							        type: "POST",
							        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
							        dataType: "json",
							        success: function(result) {
							            if (result){ 
							            	console.log(result);

										}
									}
								})
							}
						})
				    	}
			    	})
					$(".plus4").bind("click", function(){
			    		let name = prompt();
			    		name = name.trim();
			    		if(name){
				    		var and1 = '';
				    		var and2 = '';
				    		$('<td class="att" id="' + att_name + '"><p>' + name + '</p></td>').insertBefore('.plus4');
				    		att_name++;
				    		$('.m1-t6-r2').append(function(){
				    			and1 += '<td class="td-points-4"><input maxlength="3" type="text" class="points4"></td>'
				    			return and1;
				    		})
				    		$('.m1-t6-r3').append(function(){
				    			var and2 = '';
				    			and2 += '<td class="td-s-points-4"><input type="text" maxlength="3" class="s-points4"></td>';
				    			return and2;
				    		})

				    		$(".att").bind("click", function(){
			    			var id = $(this).attr("id");
			    			var old = $("#" + id + ".att p").text();
			    			old = old.trim();

							let name = prompt();
							name = name.trim();
							
							if(name){
								var change_t = $("#" + id + ".att p").text(name);
								var change = $("#" + id + ".att p").text();
								var stud_group = $("#group-sel").val();
								var teacher = $(".fullname").text();
								var lesson = $("#name-sel").val();
								var module_name = "Промежуточная аттестация";
								var semestr = $(".sem-inp").val();
								jQuery.ajax({
							        url: "/php-scripts/module_1-update-name_cp.php",
							        type: "POST",
							        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
							        dataType: "json",
							        success: function(result) {
							            if (result){ 
							            	console.log(result);

										}
									}
								})
							}
						})
				    	}
			    	})

                    console.log(result);
                }else{
                    alert(result.message);
                }
                return false;
            }
        });

        	
        	/*
        	var res ="";
        	var b_entry="";
        	var b_control="";
        	//$(".m-table").remove();
        	$(".res").append(function(){
        		res += '<div class="m-table"><p class="m-title">Входной (стартовый), текущий, рубежный контроль и промежуточная аттестация</p><table class="m1-table"><tr class="t-header"><td></td><td>Входной (стартовый) контроль</td><td>Текущий контроль</td><td>Рубежный контроль</td><td>Промежуточная аттестация</td></tr><tr class="t-header t-h2"><td>Максимальное количество баллов</td><td>5 баллов</td><td>30 баллов</td><td>15 баллов</td><td>20 баллов</td></tr></table><div style="overflow-x:auto; width: 165px; display: block; float: left;"><table class="m1-t2"><tr class="m1-r2"><td class="r2-name">Название контрольных точек</td></tr><tr class="m1-r3"><td class="r3-name">Максимальное количество баллов на каждый блок</td></tr><tr class="m1-r4"><td class="student-name"><p>Воропаев А.К.</p></td></tr><tr class="m1-r4"><td class="student-name"><p>Воропаев А.К.</p></td></tr><tr class="m1-r4"><td class="student-name"><p>Воропаев А.К.</p></td></tr></table></div><div style="overflow-x:auto; width: 195px; display: block; float: left;"><table id="entry_m" class="m1-t3"><tr class="m1-t3-r1"><td class="plus"><img src="icons/plus.svg"></td></tr><tr class="m1-t3-r2"><td class="td-points"></tr><tr class="m1-t3-r3"><td class="td-s-points"><input type="text" maxlength="3" class="s-points"></td></tr><tr class="m1-t3-r3"><td class="td-s-points"><input type="text" maxlength="3" class="s-points"></td></tr><tr class="m1-t3-r3"><td class="td-s-points"><input type="text" maxlength="1" class="s-points"></td></tr></table></div><div style="overflow-x:auto; width: 390px; display: block; float: left;"><table class="m1-t4"><tr class="m1-t4-r1"><td class="plus2"><img src="icons/plus.svg"></td></tr><tr class="m1-t4-r2"></tr><tr class="m1-t4-r3"><td class="td-s-points-2"><input type="text" maxlength="2" class="s-points2"></td></tr></table></div><div style="overflow-x:auto; width: 195px; display: block; float: left;"><table class="m1-t5"><tr class="m1-t5-r1"><td class="plus3"><img src="icons/plus.svg"></td></tr><tr class="m1-t5-r2"></tr><tr class="m1-t5-r3"><td class="td-s-points-3"><input type="text" maxlength="2" class="s-points3"></td></tr></table></div><div style="overflow-x:auto; width: 195px; display: block; float: left;"><table class="m1-t6"><tr class="m1-t6-r1"><td class="plus4"><img src="icons/plus.svg"></td></tr><tr class="m1-t6-r2"></tr><tr class="m1-t6-r3"><td class="td-s-points-4"><input type="text" maxlength="2" class="s-points4"></td></tr></table></div></div>';
        		return res;
        	})*/

        	$(".plus").on("click", function(){
	    		let name = prompt();
	    		var and1 = '';
	    		var and2 = '';
	    		$('<td class="entry" id="entry"><p>'+ name +'</p></td>').insertBefore('.plus');
	    		$('.m1-t3-r2').append(function(){
	    			and1 += '<td class="td-points"><input maxlength="3" type="text" class="points"></td>';
	    			return and1;
	    		})
	    		$('.m1-t3-r3').append(function(){
	    			var and2 = '';
	    			and2 += '<td class="td-s-points"><input type="text" maxlength="3" class="s-points"></td>';
	    			return and2;
	    		})

	    		return false;
    		})

    		$(".entry").bind("click", function(){
				let name = prompt();
				name = name.trim();
				if(name)
					$(this).text(name);
			})

			$(".plus2").bind("click", function(){
	    		let name = prompt();
	    		var and1 = '';
	    		var and2 = '';
	    		$('<td class="current" id="current"><p>' + name + '</p></td>').insertBefore('.plus2');
	    		$('.m1-t4-r2').append(function(){
	    			and1 += '<td class="td-points-2"><input maxlength="3" type="text" class="points2"></td>'
	    			return and1;
	    		})
	    		$('.m1-t4-r3').append(function(){
	    			and2 += '<td class="td-s-points-2"><input type="text" maxlength="3" class="s-points2"></td>';
	    			return and2;
	    		})

	    		return false;
	    	})
			$(".plus3").bind("click", function(){
	    		let name = prompt();
	    		var and1 = '';
	    		var and2 = '';
	    		$('<td class="control" id="control"><p>' + name + '</p></td>').insertBefore('.plus3');
	    		$('.m1-t5-r2').append(function(){
	    			and1 += '<td class="td-points-3"><input maxlength="3" type="text" class="points3"></td>'
	    			return and1;
	    		})
	    		$('.m1-t5-r3').append(function(){
	    			and2 += '<td class="td-s-points-3"><input type="text" maxlength="3" class="s-points3"></td>';
	    			return and2;
	    		})

	    		return false;
	    	})
			$(".plus4").bind("click", function(){
	    		let name = prompt();
	    		var and1 = '';
	    		var and2 = '';
	    		$('<td class="att" id="att"><p>' + name + '</p></td>').insertBefore('.plus4');
	    		$('.m1-t6-r2').append(function(){
	    			and1 += '<td class="td-points-4"><input maxlength="3" type="text" class="points4"></td>'
	    			return and1;
	    		})
	    		$('.m1-t6-r3').append(function(){
	    			and2 += '<td class="td-s-points-4"><input type="text" maxlength="3" class="s-points4"></td>';
	    			return and2;
	    		})

	    		return false;
	    	})

        	return false;
        })
    })
    
    $(document).ready(function(){
		$(".m").bind("click", function() {

			var modules = $(this).text();

        	var lesson = document.getElementById('name-sel').value;

        	var group = document.getElementById('group-sel').value;
        	
        	var semestr = $('.sem-inp').val();

        	var teacher = $('.fullname').text();

        	$( ".res").empty();
        	$( ".update").remove();

        	if ((lesson === "" || lesson === "Наименование дисциплины") || (group === "" || group ==="Группа") || semestr == ""){
        		alert("Не все поля заполнены");
        		return false;
        	}

        	jQuery.ajax({
	            url: "/php-scripts/module_2.php",
	            type: "POST",
	            data: {modules:modules, lesson:lesson, group:group, semestr:semestr, teacher:teacher}, // Передаем данные для записи
	            dataType: "json",
	            success: function(result) {
	                if (result){
	                	var modul = result.modules;;
	                	var res ="";
	                	var all_name = "";
	                	var points = "";
	                	var ncpmp = "";
	                	var mtrtd = "";
	                	var theme = "";

	                	if ((modul == 'Модуль 2')||(modul == 'Модуль 3')){
	                		all_name = "Название <br> контролных точек";
	                		if (modul == 'Модуль 2'){
	                			points = "40 баллов";
	                			theme = "Выполнение практических (лабораторных) работ";
	                		}
	                		else{
	                			points = "10 баллов";
	                			theme = "Выполнение самостоятельной работы";
	                		}
	                	}
	                	else
	                		if ((modul == 'Модуль 5')||(modul == 'Модуль 7')){
	                			all_name = "Название мероприятия";
	                			points = "30 баллов";
	                			if(modul == 'Модуль 5')
	                				theme = "Участие в мероприятиях по изучаемой дисциплине или междисциплинарному курсу";
	                			if(modul == 'Модуль 7')
	                				theme = "Блок преподавателя";
	                		}
	                		else{
	                			points = "20 баллов";
	                			if (modul == 'Модуль 4'){
	                				all_name = "Номер занятия";
	                				theme = "Посещение учебных занятий";
	                			}
	                			else{
	                				all_name = "Название внебюджетного курса";
	                				theme = "Участие в дополнительных внебюджетных курсах (кружках) по изучаемой дисциплине, организованными ГБПОУ МГОК";
	                			}
	                		}

	                	if (modul == 'Модуль 2'){
	                		ncpmp = "name-cp-m2-p";
	                		mtrtd = "m2-t2-r1-td";
	                	}
	                	else{
		                	if (modul == 'Модуль 4'){
		                		ncpmp = "name-cp-m2-p";
		                		mtrtd = "m2-t2-r1-tdw";
		                	}
		                	else{
		                		ncpmp = "name-cp-m2-pn";
		                		mtrtd = "m2-t2-r1-tdn";
		                	}
	                	}

	                	console.log(all_name);
	                	console.log(points);
	                	console.log(ncpmp);

	                	res += '<div class="m2-table"><p class="m-title">' + theme + '</p><table class="m2-t1"><tr class="m2-t1-r1"><td class="m2-t1-r1-td1">Максимальное количество баллов на модуль</td><td class="m2-t1-r1-td2">' + points + '</td></tr></table><table class="m2-t2-f"><tr class="m2-t2-r1"><td class="m2-t2-r1-td1">' + all_name + '</td></tr>';

	                	var mas_names = [];
	                	var mas_maxb = [];
	                	var mas_stud = [];
	                	if(result.m){
	                		var len = 0;
	                		for (var i = 0; i < result.m.name_cp.length; i++){
			        				if(mas_names){
			        					var bool = new Boolean(true);
			        					for (var k = 0; k < len; k++){
			        						if(mas_names[k] === result.m.name_cp[i]){
			        							bool = false;
			        							break;
			        						}
			        					}
			        					if(bool){
			        						mas_names[len] = result.m.name_cp[i];
			        					    if (result.m.maxb_cp[i] == '0'){
			        					    	mas_maxb[len] = "";
			        					    }
			        					    else
			        					    	mas_maxb[len] = result.m.maxb_cp[i];
			        						len++;
			        					}
			        				}
			        				else{
		        				    	mas_names[len] = result.m.name_cp[i];
		        					    if (result.m.maxb_cp[i] == '0'){
		        					    	mas_maxb[len] = "";
		        					    }
		        					    else
		        					    	mas_maxb[len] = result.m.maxb_cp[i];
		        						len++;
			        				}
			        			}

		        				for(var j = 0; j < result.stud.full_name.length; j++){
		        					var mas_s_points = [];
		        					
		        					for(var i = 0; i < mas_names.length; i++){
		        						bool = true;
			        					for(var k = 0; k < result.m.student.length; k++){
			        						
				        					if((result.stud.full_name[j] === result.m.student[k]) && (mas_names[i] === result.m.name_cp[k])){
				        						bool = false;
				        						if (modul == "Модуль 4"){
				        							mas_s_points[i] = "checked";
				        						}
				        						else
				        							mas_s_points[i] = result.m.student_p[k];
				        						break;
				        					}
				        				}
				        				if(bool){
				        					mas_s_points[i] = "";
				        				}
				        			}
				        			mas_stud[j] = mas_s_points;
		        				}
	                	}
	                	else{
	                		if (modul == 'Модуль 4')
	                			mas_names[0] = '1';
	                		else
	                			mas_names[0] = 'Введите название';
	                		mas_maxb[0] = '';
	                		for(i = 0; i < result.stud.full_name.length; i++){
	                			var mas_s_points = [];
	                			mas_s_points[0] = '';
	                			mas_stud[i] = mas_s_points;
	                		}
	                	}

	                	if((modul == 'Модуль 2')||(modul == 'Модуль 3')){
		        			res += '<tr class="m2-t2-r2-s"><td class="m2-t2-r2-td1">Максимальное количество баллов, на каждый блок</td></tr>';
		        		}
		        		
		        		if((modul == 'Модуль 4')){
		        			res += '<tr class="m2-t2-r2-s"><td class="m2-t2-r2-td1">Максимальное количество баллов <br>за одно занятие</td></tr>';
		        		}

		        		for(i = 0; i < result.stud.full_name.length; i++)
		        			res += '<tr class="m2-t2-r3-s"><td class="m2-t2-r3-td1">' + initials(result.stud.full_name[i]) + '</td></tr>';

		        		res += '</table><div style="overflow-x:auto; width: 975px; display: block; float: left;"><table class="m2-t2"><tr class="m2-t2-r1">';


	                	for(l = 0; l < mas_names.length; l++){
		        				res += '<td class="' + mtrtd + '" id="' + l + '"><p class="' + ncpmp + '">'+ mas_names[l] +'</p></td>';
		        			}

		        		res += '<td class="m2-t2-r1-td-plus"><img src="icons/plus.svg"></td></tr>';

		        		if((modul == 'Модуль 2')||(modul == 'Модуль 3')){
		        			res += '<tr class="m2-t2-r2">';
		        			for(i = 0; i < mas_maxb.length; i++){
		        				res += '<td class="m2-t2-r2-td"><input type="text" class="m2-points" maxlength="3" value="' + mas_maxb[i] + '"></td>';
		        			}
		        			res += '</tr>';
		        		}

		        		if((modul == 'Модуль 4')){
		        			var s = 20 / mas_names.length;
		        			res += '<tr class="m2-t2-r2"><td colspan="88" class="m2-t2-r2-tdf">' + Math.floor(s * 100) / 100 + '</td></tr>';
		        		}

		        		for(i = 0; i < result.stud.full_name.length; i++){
		        			res += '<tr class="m2-t2-r3">';
		        			for(j = 0; j < mas_stud[i].length; j++){
		        				if (modul != "Модуль 4"){
		        				res += '<td class="m2-t2-r3-td"><input type="text" class="m2-s-points" maxlength="3" value="' + mas_stud[i][j] + '"></td>';
			        			}
			        			else{
			        				res += '<td class="m2-t2-r3-td"><label class="che"><input type="checkbox" ' + mas_stud[i][j] + '/><span class="checkmark"></span></label></td>';
			        			}
		        			}
		        			res += '</tr>';
		        		}

	                	res += '</table></div></div>';

	                	res += '<input type="submit" class="update_all" value="Сохранить">';

	                	$('.res').append(res);
	                	
	                	var idd = mas_names.length;

						var all = [];

						$(".update_all").on("click", function(){

							var entry_name_cp = Array.from($("." + mtrtd + " p"));
							if(modul == 'Модуль 4')
								var entry_maxb_cp = Math.floor((20 / idd) * 100) / 100;
							if ((modul == 'Модуль 2')||(modul == 'Модуль 3'))
								var entry_maxb_cp = Array.from($(".m2-t2-r2-td input"));
							var entry_student_p = Array.from($(".m2-t2-r3-td input"));
							var count = 0;
							var numb_p = 0;

							console.log(entry_student_p);

							for(i = 0; i < result.stud.full_name.length; i++){
								for(j = 0; j < idd; j++){
									if(modul == 'Модуль 4'){
										if($(entry_student_p[numb_p]).is(':checked')){
											all[count] = {
												student: result.stud.full_name[i],
												stud_group: $("#group-sel").val(),
												teacher: $(".fullname").text(),
												lesson: $("#name-sel").val(),
												name_cp: $(entry_name_cp[j]).text().trim(),
												maxb_cp: entry_maxb_cp,
												student_p: '1',
												module_name: modul,
												semestr: $(".sem-inp").val(),
											}
											count++;
										}
									}
									else{
										if($(entry_student_p[numb_p]).val() != ""){
											if ((modul == 'Модуль 2')||(modul == 'Модуль 3')){
												all[count] = {
													student: result.stud.full_name[i],
													stud_group: $("#group-sel").val(),
													teacher: $(".fullname").text(),
													lesson: $("#name-sel").val(),
													name_cp: $(entry_name_cp[j]).text(),
													maxb_cp: $(entry_maxb_cp[j]).val(),
													student_p: $(entry_student_p[numb_p]).val(),
													module_name: modul,
													semestr: $(".sem-inp").val(),
												}
											}
											else{
												all[count] = {
													student: result.stud.full_name[i],
													stud_group: $("#group-sel").val(),
													teacher: $(".fullname").text(),
													lesson: $("#name-sel").val(),
													name_cp: $(entry_name_cp[j]).text(),
													maxb_cp: "0",
													student_p: $(entry_student_p[numb_p]).val(),
													module_name: modul,
													semestr: $(".sem-inp").val(),
												}
											}
											count++;
										}
									}
									numb_p++;
								}
							}
							console.log('all');
							console.log(all);

							jQuery.ajax({
						        url: "/php-scripts/module_2-update.php",
						        type: "POST",
						        data: {all:all}, // Передаем данные для записи
						        dataType: "json",
						        success: function(result) {
						            if (result){ 
						            	console.log(result);

									}
								}
							})
						})

	                	$(".m2-t2-r1-td-plus").on("click", function(){
				    		if (modul != "Модуль 4"){
					    		let name = prompt();
					    		if(name)
					    			name = name.trim();
					    		if(name){
						    		var and1 = '';
						    		var and2 = '';

						 			
						    		$('<td class="' + mtrtd + '" id="' + idd + '"><p class="' + ncpmp + '">'+ name +'</p></td>').insertBefore('.m2-t2-r1-td-plus');
						    		idd++;
						    		if ((modul == 'Модуль 2')||(modul == 'Модуль 3')){
							    		$('.m2-t2-r2').append(function(){
							    			and1 += '<td class="m2-t2-r2-td"><input maxlength="3" type="text" class="m2-points" value></td>';
							    			return and1;
							    		})
						    		}
						    		$('.m2-t2-r3').append(function(){
						    			var and2 = '';
						    			and2 += '<td class="m2-t2-r3-td"><input type="text" maxlength="3" class="m2-s-points"></td>';
						    			return and2;
					    			})

					    			$("." + mtrtd + "").bind("click", function(){
					    			var id = $(this).attr("id");
					    			var old = $("#" + id + "." + mtrtd + " p").text();
									})
					    		}
					    	}
					    	else{
					    		$('<td class="' + mtrtd + '" id="' + idd + '"><p class="' + ncpmp + '">'+ (idd+1) +'</p></td>').insertBefore('.m2-t2-r1-td-plus');
					    		idd++;

					    		$('.m2-t2-r2').empty();

					    		$('.m2-t2-r2').append(function(){
						    			var and2 = '';
						    			var s = 20 / idd;
						    			and2 += '<td colspan="88" class="m2-t2-r2-tdf">' + Math.floor(s * 100) / 100 + '</td>';
						    			return and2;
					    		})

					    		$('.m2-t2-r3').append(function(){
					    			var and2 = '';
					    			and2 += '<td class="m2-t2-r3-td"><label class="che"><input type="checkbox"><span class="checkmark"></span></label></td>';
					    			return and2;
				    			})

				    			$("." + mtrtd + "").bind("click", function(){
				    			var id = $(this).attr("id");
				    			var old = $("#" + id + "." + mtrtd + " p").text();
								})
					    	}
		    			})

		    			$("." + mtrtd + "").bind("click", function(){
		    				if (modul != "Модуль 4"){
				    			var id = $(this).attr("id");
				    			var old = $("#" + id + "." + mtrtd + " p").text();
				    			old = old.trim();

								let name = prompt('Изменить название?', old);
								name = name.trim();
								if(name){
									var change_t = $("#" + id + "." + mtrtd + " p").text(name);
									var change = $("#" + id + "." + mtrtd + " p").text();
									var stud_group = $("#group-sel").val();
									var teacher = $(".fullname").text();
									var lesson = $("#name-sel").val();
									var module_name = modul;
									var semestr = $(".sem-inp").val();
									$.ajax({
								        url: "/php-scripts/module_2-update-name_cp.php",
								        type: "POST",
								        data: {old:old, change:change, stud_group:stud_group, teacher: teacher, lesson:lesson, module_name:module_name, semestr:semestr}, // Передаем данные для записи
								        dataType: "json",
								        success: function(result) {
								            if (result){ 
								            	console.log(result);

											}
											//return false;
										}
									})
								}
							}
						})
	                }
	            }
            })
        });
	});
	
	$(document).ready(function(){
		$("#mI").bind("click", function() {

			var lesson = document.getElementById('name-sel').value;

        	var group = document.getElementById('group-sel').value;
        	
        	var semestr = $('.sem-inp').val();

        	var teacher = $('.fullname').text();

        	if ((lesson === "" || lesson === "Наименование дисциплины") || (group === "" || group ==="Группа") || semestr == ""){
        		alert("Не все поля заполнены");
        		return false;
        	}

        	$( ".res").empty();
        	$( ".update").remove();

        	jQuery.ajax({
	            url: "/php-scripts/modules-result.php",
	            type: "POST",
	            data: {lesson:lesson, group:group, semestr:semestr, teacher:teacher}, // Передаем данные для записи
	            dataType: "json",
	            success: function(result) {
	                if (result){

	                	console.log(result);
	                	res = "";

	                	res += '<table class="t-i"><tr class="t-i-r1"><td class="t-i-r1-td1">ФИО</td><td class="t-i-r1-td2"><p>Модуль 1</p></td><td class="t-i-r1-td2"><p>Модуль 2</p></td><td class="t-i-r1-td2"><p>Модуль 3</p></td><td class="t-i-r1-td2"><p>Модуль 4</p></td><td class="t-i-r1-td2"><p>Модуль 5</p></td><td class="t-i-r1-td2"><p>Модуль 6</p></td><td class="t-i-r1-td2"><p>Модуль 7</p></td><td class="t-i-r1-td2"><p>Итоговый балл</p></td><td class="t-i-r1-td3"><p>Оценка</p></td></tr>';
	                	for(var i = 0; i < result.stud.full_name.length; i++){

	                		if(!result.stud_p[i])
	                			result.stud_p[i] = 0;

	                		if(!result.stud_p2[i])
	                			result.stud_p2[i] = 0;

	                		if(!result.stud_p3[i])
	                			result.stud_p3[i] = 0;

	                		if(!result.stud_p4[i])
	                			result.stud_p4[i] = 0;

	                		if(!result.stud_p5[i])
	                			result.stud_p5[i] = 0;

	                		if(!result.stud_p6[i])
	                			result.stud_p6[i] = 0;

	                		if(!result.stud_p7[i])
	                			result.stud_p7[i] = 0;

	                		var rating = result.stud_p[i] + result.stud_p2[i] + result.stud_p3[i] + result.stud_p4[i] + result.stud_p5[i] + result.stud_p6[i] + result.stud_p7[i];
	                		if (rating < 110)
	                			rating = 'Н/А';
	                		if ((rating >= 110) && (rating < 143))
	                			rating = 3;
	                		if((rating >= 143) && (rating <187))
	                			rating = 4;
	                		if((rating >= 187))
	                			rating = 5;

	                		res += '<tr class="t-i-r2"><td class="t-i-r2-td1">' + initials(result.stud.full_name[i]) + '</td><td class="t-i-r2-td2">' + result.stud_p[i] + '</td><td class="t-i-r2-td2">' + result.stud_p2[i] + '</td><td class="t-i-r2-td2">' + result.stud_p3[i] + '</td><td class="t-i-r2-td2">' + result.stud_p4[i] + '</td><td class="t-i-r2-td2">' + result.stud_p5[i] + '</td><td class="t-i-r2-td2">' + result.stud_p6[i] + '</td><td class="t-i-r2-td2">' + result.stud_p7[i] + '</td><td class="t-i-r2-td2">' + (result.stud_p[i] + result.stud_p2[i] + result.stud_p3[i] + result.stud_p4[i] + result.stud_p5[i] + result.stud_p6[i] + result.stud_p7[i]) + '</td><td class="t-i-r2-td3">' + rating + '</td></tr>';
	                	}
				
						res += '</table>';

						$('.res').append(res);

	                }
	            }
    		})

		})
	})


</script>