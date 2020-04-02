<?php
$ep = array_slice($_GET,2);
$count = count($ep);
if($count==2) { $response = "wb *".$ep[1]."*"; } 
else { $response = ":wave: sup *room* :v:"; }
$data["endpoint"] = $ep;
$data["response"] = $response;
$data["count"] = $count;
?>
