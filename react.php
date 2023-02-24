<?php
$host = "localhost";
$database = "u1282713_mgok-online";
$user = "u1282713_reader";
$password = "147852369a";
$charset = 'utf8mb4';

$connection = mysqli_connect($host, $user, $password, $database);

$Received_JSON = file_get_contents('php://input');
 
$obj = json_decode($Received_JSON,true);
 
$name = $obj['user_name'];

$pass = $obj['user_password'];

$CheckSQL = "SELECT * FROM students WHERE username='$name' AND password='$pass'";
$query = "SELECT * FROM students WHERE username='$username' and password='$password'";

$check = mysqli_fetch_array(mysqli_query($connection,$CheckSQL));

if(isset($check)){
    $AuthMSG = 'Ok';
    
    $AuthMSGJson = json_encode($AuthMSG);
    
    echo $AuthMSGJson;
}
else{
    $tryAgain = 'Try again';
    $tryAgainJson = json_encode($tryAgain);
    echo $tryAgainJson;
}

mysqli_close($connection)

?>