<?php
$response = file_get_contents(__DIR__ . '/../../docs/'.$ep[0].'/README');
$data["response"] = $response;
$data["ep"] = $ep;
?>
