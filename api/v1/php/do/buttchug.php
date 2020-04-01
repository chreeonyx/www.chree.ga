<?php
$ep = array_slice($_GET,2);
$count = count($ep);
if($count==2) { $response = "buttchugs ".$ep[1]."!!!"; } 
else { $response = "chugs booty!!!"; }
$data["endpoint"] = $ep;
$data["response"] = $response;
$data["count"] = $count;
?>
