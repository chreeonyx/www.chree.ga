<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$data = [];
$_GET = explode('/', $_SERVER['REQUEST_URI']); 
array_shift($_GET); 
$data['GET'] = $_GET;
$data['include'] = in_array($_GET[1], ['create', 'read', 'update', 'delete']) ? '../'.$_GET[0].'/'.$_GET[1].'/index.php' : '../404.json';
include(in_array($_GET[1], ['create', 'read', 'update', 'delete']) ? '../'.$_GET[0].'/'.$_GET[1].'/index.php' : '../404.json');
print_r(json_encode($data, JSON_PRETTY_PRINT));
unset($data);
?>