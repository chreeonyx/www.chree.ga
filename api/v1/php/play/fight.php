<?php
$response = '';
if($epc>0) {
    if($epc>1) {
        if(in_array($ep[0],array('state','states'))) {
            $type = 'state';
            $url = "https://coronavirus-monitor.p.rapidapi.com/coronavirus/johns_hopkins_united_states_latest.php?state=".$ep[1];
        }
        else if(in_array($ep[0],array('country'))) {
            $type = 'country';
            $url = "https://coronavirus-monitor.p.rapidapi.com/coronavirus/latest_stat_by_country.php?country=".$ep[1];
        }
    } else {
        if($ep[0] === "") {  }
    }
} else {
    $response  = "Play Fight Now! *README* _read me/fight";
    $response .= file_get_contents(__DIR__ . '/../../docs/'.$_GET[2].'/README');
}
$data["response"] = $response;
$data["ep"] = $ep;
$data['epc'] = $epc;
?>
