<?php
session_start();
if(isset($_SESSION['user']))
    session_destroy();
require('php-scripts/connect.php');
$crnt = date("N");
$crnt -= 1;
//echo $_SERVER['HTTP_REFERER'];
    if(isset($_POST['username']) and isset($_POST['password']))
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $pos_usr = strripos($username, " ");
        $pos_pas = strripos($password, " ");
        $len_usr = strlen($username);
        $len_pas = strlen($password);
        if((($pos_usr === false) && ($len_usr <= 25)) && (($pos_pas === false) && ($len_pas <= 15))){
            $query = "SELECT id_user FROM users WHERE username='$username' and password='$password'";
            $result = mysqli_query($connection, $query) or die(mysqli_error($connection));
            if(mysqli_num_rows($result) > 0)
            if (mysqli_num_rows($result) > 0){
                $user = mysqli_fetch_assoc($result);
                $_SESSION['dostup'] = 1;
                $_SESSION['user'] = [
                "username" => $user['username'], "full_name" => $user['teacherName']];
                    header('Location: schedule.php');
            }
            else{
                if (mysqli_num_rows($result) > 0){
                    $user = mysqli_fetch_assoc($result);
                    $_SESSION['dostup'] = 2;
                    $_SESSION['user'] = [
                    "username" => $user['username'], "id" => $user['id'], "full_name" => $user['full_name'], "class" => $user['class'], "dob" => $user['date_of_birth']];
                    if ($_POST['username'] == 'admin190'){
                        header('Location: add-panel.php');
                    }
                    elseif($_POST['username'] == 'repadmin'){
                        header('Location: schedule-panel.php');
                    }
                    elseif($_POST['username'] == 'addadmin'){
                        header('Location: add-panel.php');
                    }
                    else{
                        header('Location: pages/studentschedule.php');
                    }
                    /*
                     * if($_POST['id_role'] == 1)
                     * {
                     * header('Location: .php');
                     * }
                     * elseif($_POST['id_role'] == 2)
                     * {
                     * header('Location: schedule.php');
                     * }
                     * elseif($_POST['id_role'] == 3)
                     * {
                     * header('Location: .php');
                     * }
                     *
                     */
                }
            }
        }
        else{
           //
        }
    }
        /**/
echo'
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Мгок онлайн, расписание занятий ГБПОУ МГОК">
    <link rel="shortcut icon" href="icons/icon.svg" />
    <link rel="stylesheet" type="text/css" href="css/author.php">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <title>Добро пожаловать на сайт</title>
</head>
<body>
    <div class="container">
        <form class="form-signinl" action="#'.$crnt.'" method="POST">
            <h2 class="reg">Авторизация</h2>
            <div class="inputtt">
                <p>Логин:</p>
                <input type="text" maxlength="25" name="username" class="inputt" placeholder="Введите вашу почту" requred>
                <p>Пароль:</p>
                <input type="password" maxlength="15" name="password" class="inputt" placeholder="Введите ваш пароль" requred>
            </div>
            <div class="outline-btn">
                <button class="butreg" onclick="redirect();" type="submit">Войти в систему</button>
                <div class="outer"></div>
            </div>
        </form>
    </div>
';

require('pages/footer.php');
echo'
    </body>
</html>
';
?>
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(71857375, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/71857375" style="position:absolute; left:-9999px;" alt="" /></div></noscript>