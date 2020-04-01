<?php
$curl = curl_init();
$type = '';
if($epc>0) {
    if($epc===2) {
        if(in_array($ep[0],array('state','states'))) {
            $type = 'state';
            $url = "https://coronavirus-monitor.p.rapidapi.com/coronavirus/johns_hopkins_latest_usa_statistic_by_state.php?state=".$ep[1];
        }
        else if(in_array($ep[0],array('country'))) {
            $type = 'country';
            $url = "https://coronavirus-monitor.p.rapidapi.com/coronavirus/cases_by_country.php?country=".$ep[1];
        }
    }
    else if($epc===1) {
        $url = "https://coronavirus-monitor-v2.p.rapidapi.com/coronavirus/hopkins_united_states_all_states_latest.php";      
    }
} else {
	$type = "world";
    $url = "https://coronavirus-monitor.p.rapidapi.com/coronavirus/worldstat.php";
}

curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(),
));

$results = json_decode(curl_exec($curl),true);
$err = curl_error($curl);

curl_close($curl);

$response = "";
$url = "";
if($type === 'state') {
    $cases = $results['usa_cases_by_state'][0]['cases_number'];
    $data['dead'] = $dead = $results['usa_deaths'][0]['death_cases'];
    $place = $results['state'];
    $response = $place.': 💉 *'.$cases.'* ☠️ *'.$dead.'*';
}
if($type === 'country') {
    $cases = $results['latest_stat_by_country'][0]['total_cases'];
    $dead = $results['latest_stat_by_country'][0]['total_deaths'];
    $place = $results['latest_stat_by_country'][0]['country_name'];
    $response = $place.': 💉 *'.$cases.'* ☠️ *'.$dead.'*';
}
if($type === 'world') {
    $cases = $results['total_cases'];
    $dead = $results['total_deaths'];
    $response = 'world: 💉 *'.$cases.'* ☠️ *'.$dead.'*';
}

if ($err) {
    $data['error'] = $err;
} else {
    $data["endpoint"] = $ep;
    $data["response"] = $response;
    $data["type"] = $type;
    $data["results"] = $results;
    $data["count"] = $epc;
}
?>