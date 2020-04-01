<?php
$ep = array_slice($_GET,2);
$count = count($ep);
$io = rand(0,1);
if($io===0) { $response = "HEADS"; }
else { $response = "TAILS"; }
$data["endpoint"] = $ep;
$data["response"] = $response;
$data["count"] = $count;
$data["io"] = $io;
?>