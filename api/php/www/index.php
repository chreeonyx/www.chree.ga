<?php
session_start();
header('Access-Control-Allow-Origin: *');
$_GET = param($_SERVER['REQUEST_PROTOCOL'].'://'.$_SERVER['HTTP_HOST'], null);
$data = [];
$ep = array_slice($_GET,3);
$epc = count($ep);
if($_GET[0] == 'v1') { $include = in_array(['create', 'delete', 'do', 'is', 'play', 'read', 'update'], $crud) ? '../v1/'.$GET[1].'/index.php' : '../404.json'; }
else { $include = '../404.json'; }
include($include);
print_r(json_encode($data, JSON_PRETTY_PRINT));
unset($data);
?>