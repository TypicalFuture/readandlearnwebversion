<?php
session_start();
require('php-scripts/cath_auth.php');

//echo $_SERVER['HTTP_REFERER'];

    if(isset($_POST['username']) and isset($_POST['password']))
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        $pos_usr = strripos($username, " ");
        $pos_pas = strripos($password, " ");
        
        $len_usr = strlen($username);
        $len_pas = strlen($password);
        
        if((($pos_usr === false) && ($len_usr <= 25)) &&(($pos_pas === false) && ($len_pas <= 15))){
        
            $query = "SELECT * FROM users WHERE login='$username' and password='$password'";
            $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

            if (mysqli_num_rows($result) > 0){
                
                $user = mysqli_fetch_assoc($result);

                $_SESSION['user'] = [
                "username" => $user['login'], "full_name" => $user['full_name']];
                    
                    header('Location: rating.php');
                    
            }

        }

        else{

            echo "<script>alert('Логин или пароль введены неправильно');</script>";
        }

        /**/
    }

echo'
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="http://www.example.com/myicon.ico" />
    <link rel="stylesheet" type="text/css" href="css/rating-auth.css">
    <title>Вход</title>
</head>
<body>
    <div class="container">
        <form class="form-signinl" action="#'.$crnt.'" method="POST">
            <h2 class="reg">Вход:</h2>
            <div class="inputtt">
                <p>Логин:</p>
                <input type="text" name="username" class="textboxindex" placeholder="Введите логин" requred>
                <p>Пароль:</p>
                <input type="password" name="password" class="textboxindex" placeholder="Введите пароль" requred>
            </div>
            <div class="outline-btn">
                <button class="butreg" type="submit">Войти</button>
            </div>
        </form>
        
    
        
    </div>
</body>
</html>';

?>