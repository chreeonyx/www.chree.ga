<?php
$id = "";
$token = "";
$headers = array(
		"Authorization: Bearer $token",
		"Content-Type: application/json"
);
$response = '';

$curl = curl_init();
$type = "all";
$url = 'https://api.careeronestop.org/v1/unemployment/'.$id.'/0/state/';
if($epc>0) {
    if($epc>1) {
		if(in_array($ep[1],array('state'))) {
			$type = 'state';
			$url = 'https://api.careeronestop.org/v1/unemployment/'.$token.'/'.$ep[0].'/'.$ep[1];
		}
		else if(in_array($ep[1],array('county'))) {
			$type = 'county';
			$url = 'https://api.careeronestop.org/v1/unemployment/'.$id.'/'.$ep[0].'/'.$ep[1];
		}
		else {
    	    $type = "state";
            $url = 'https://api.careeronestop.org/v1/unemployment/'.$id.'/0/state/';			
		}
    } 
    else if($epc === 2) {
    	$type = "state";
        $url = 'https://api.careeronestop.org/v1/unemployment/'.$id.'/0/state/';
    }
}


curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => $headers
));

$results = json_decode(curl_exec($curl),true);
//$data["results"] = $results;
if($type == 'county') {
    $data["CountyList"] = $results['CountyList'];
    $x=0; foreach($data['CountyList'] as $county) {
    	//$data['AreaName'] = $data['CountyList'][$x]['AreaName'];
    	//$data['UnEmpCount'] = $UnEmpCount = $data['CountyList'][$x]['UnEmpCount'];
    	//$data['UnEmpRate'] = $UnEmpRate = $data['CountyList'][$x]['UnEmpRate'];
    	//$data['county'][$AreaName];
    	$data['counties'][$x] = $county['AreaName'].' :busts_in_silhouette: '.$UnEmpCount.' ('.$UnEmpRate.'%)';
    	$data['ep1'] = $ep[1];
        if($county['AreaName'] === urldecode($ep[2])) { $data['response'] = "*BROKE* *MOFOS* @ ".$county['AreaName'].':'.' :busts_in_silhouette: '.$UnEmpCount.' ('.$UnEmpRate.'%)'; }
    	$x++;
    }
}

$err = curl_error($curl);

curl_close($curl);

if ($err) {
    $data['error'] = $err;
} else {
    $data["ep"] = $ep;
    $data["epc"] = $epc;
    //$data["StateList"] = $results['StateList'];
    $data["url"] = $url;
}
function search($v,$array) {
	$item = null;
	$data['array'] = $array;
	foreach($array as $struct) {
		if ($v == $struct[$v]) {
			$item = $struct;
			break;
		}
	}
}
?>