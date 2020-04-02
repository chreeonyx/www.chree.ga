<?php
$jokes = [
  "So a guy walks into a bar... He leaves drunk",
  "Why did the thot cross the road... To get another man",
];
$last_joke = count($jokes)-1;
$response = $jokes[rand(0,$last_joke)].' 🥁 badum tssst';
$data['response'] = $response;
?>
