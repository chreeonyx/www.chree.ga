<?php
$ep = array_slice($_GET,2);
$count = count($ep);
if($count==2) { $response = "run cmd.exe... ".$ep[1]." IP Adress is 192.168.0.1"; } 
else { $response = "run cmd.exe..."; }
$data["endpoint"] = $ep;
$data["response"] = $response;
$data["count"] = $count;
?>