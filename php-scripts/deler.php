<?
$host = "localhost";
$database = "u1282713_mgok-online";
$user = "u1282713_deler";
$password = "9el5t5m5";
$charset = 'utf8mb4';

$connection = mysqli_connect($host, $user, $password, $database);
mysqli_set_charset($connection, 'utf8');

unset($host, $database, $user, $password, $charset);
?>