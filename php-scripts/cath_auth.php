<?php
$host = "localhost";
$database = "u1282713_cathedras";
$user = "u1282713_editor";
$password = "b166b0762";

$connection = mysqli_connect($host, $user, $password, $database) or die("Ошибка ".mysqli_error($connection));
mysqli_set_charset($connection, 'utf8');

unset($host, $database, $user, $password, $charset);
?>