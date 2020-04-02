<?php
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Content-type: application/json');
include(count($_GET) > 2 ? $_GET[1].'/index.php' : '../404.json');
mysqli_close($con);
?>