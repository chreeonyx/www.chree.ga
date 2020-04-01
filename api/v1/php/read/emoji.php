<?php
$ep = array_slice($_GET,2);
$count = count($ep);

$short = $_GET[3];
$short === "joy" ? $emoji = "😂" : null;
$short === "finger" ? $emoji = "🖕" : null;
//$data["GET"] = $emoji;
$response = '';
if($count===3 && $ep[2]) { 

    $data['int'] = (int)$ep[2];
    if($data['int']>0) {
    $x=0; while($x < $data['int']) {
        $response .= $emoji.' ';
        $x++;
    }
    } else {        
        $response = $emoji;   
    }
} else {
    $response = $emoji;    
}

$data["response"] = $response;
$data["endpoint"] = $ep;
$data["count"] = $count;
?>
