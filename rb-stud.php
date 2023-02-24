<?php
session_start();

if ((!$_SESSION['user']) || ($_SESSION['dostup'] == 1)) {
    header('location: index.php');
}

if(!empty($_SESSION['user']['full_name']))
$fullname = $_SESSION['user']['full_name'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Caomatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/record-book1.css">
    <link rel="stylesheet" type="text/css" href="css/rb-stud.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
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
    <div class="content">
        <div class="search-sem">
            <div class="inp-lab">
                <label class="label-sem" for="semestr">Введите номер семестра:</label>
                <input class="select-sem" type="text" id="semestr" maxlength="1">
                <input class="submit-sem" type="submit" value="Поиск">
            </div>

            
        </div>
    </div>
    <script>
            $(".submit-sem").bind("click", function() {
                var fn = <?php echo json_encode($_SESSION['user']['full_name']);?>;
                var group = <?php echo json_encode($_SESSION['user']['class']);?>;
                var sem = $('.select-sem').val();
                if(sem){
                    jQuery.ajax({
                        url: "/php-scripts/rb-points.php",
                        type: "POST",
                        data: {fn:fn, group:group, sem:sem}, // Передаем данные для записи
                        dataType: "json",
                        success: function(result) {
                            if (result){
                                $('.table').remove();
                                console.log(result);
                                if(result.lesson.message == 'no'){
                                    $('.not-find').remove();
                                    $('.content').append('<div class="not-find"><p>Данные на ' + sem + ' семестр не найдены.</p></div>');
                                }
                                else{
                                    $('.not-find').remove();
                                    $('.content').append('<table class="table"><thead><tr class="table-head"><td class="table-cell">Предмет</td><td class="table-cell">Балл</td></tr></thead>');
                                    for (var i = 0; i < result.lesson.lesson.length; i++) {
                                        $('.table').append('<tr class="table-row"><td class="table-cell">' + result.lesson.lesson[i] + '</td><td class="table-cell">' + result.lesson.stud_p[i] + '</td></tr>');    
                                    }
                                    $('.content').append('</table>');
                                }
                            }
                        }
                    })
                }
                else{
                    alert('Введите номер семестра');
                }
                
            })
    </script>

<?php require_once('footer.php');?>
</body>
</html>