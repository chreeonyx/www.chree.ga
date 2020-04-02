<?php
$host = '';
$user = '';
$pass = '';
$dtbs = '';
$con = mysqli_connect($host, $user, $pass, $dtbs);
if(mysqli_connect_errno()) { $log = "Failed to connect to MySQL: ".mysqli_connect_error().'<br>'; }
else { mysqli_set_charset($con,'utf8mb4'); }
?>
