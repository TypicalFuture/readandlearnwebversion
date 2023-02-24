<?
$host = "localhost";
$database = "u1282713_mgok-online";
$user = "u1282713_writter";
$password = "b166b0762";
$charset = 'utf8mb4';

$connection = mysqli_connect($host, $user, $password, $database);
mysqli_set_charset($connection, 'utf8');

unset($host, $database, $user, $password, $charset);
?>