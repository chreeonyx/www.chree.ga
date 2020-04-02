<?php
$url = 'https://api.bls.gov/publicAPI/v2/timeseries/data/';
$method = 'POST';
$query = array(
	'seriesid'  => array('LNS14000000'),
	'startyear' => '2020',
	'endyear'   => '2020'
);
$data['pd'] = $pd = json_encode($query);
$pd = json_encode($query);
$contentType = 'Content-Type: application/json';
$contentLength = 'Content-Length: ' . strlen($pd);
$result = file_get_contents(
$url, null, stream_context_create(
array(
	'http' => array(
		'method' => $method,
		'header' => $contentType . "\r\n" . $contentLength . "\r\n",
		'content' => $pd
	),
	)
	)
);
$data['headers'] = $http_response_header;
$data['result'] = json_decode($result);

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