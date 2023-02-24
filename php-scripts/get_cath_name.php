<?php

require('../connect-base.php');

$sql = "SELECT * FROM cathedr";
$result = mysqli_query($connection, $sql);
$cath = mysqli_fetch_all($result, MYSQLI_ASSOC);

$out = $cath;
//var_dump($number);
header('Content-Type: text/json; charset=utf-8');

// Кодируем данные в формат json и отправляем
echo json_encode($out);

?>