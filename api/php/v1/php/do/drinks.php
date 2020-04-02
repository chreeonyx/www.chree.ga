<?php
$ep = array_slice($_GET,2);
$count = count($ep);
if($count==2) { $response = "drinks with ".$ep[1]."!!!"; } 
else { $response = "grabs bottle!!!"; }
$data["endpoint"] = $ep;
$data["response"] = $response;
$data["count"] = $count;
?>