<?php
$host = "localhost:3306";
//$database = "u1282713_mgok-online";
$database = "u1602320_mgokonline";
//$user = "u1282713_default";
$user = "u1602_mgokonline";
//$password = "147852369a";
$password ="ferawe123";
$charset = 'utf8mb4';
$connection = mysqli_connect($host, $user, $password, $database);
mysqli_set_charset($connection, 'utf8');
unset($host, $database, $user, $password, $charset);
?>