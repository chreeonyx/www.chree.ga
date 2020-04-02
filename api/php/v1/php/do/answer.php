<?php
$questions = array(
  "How many licks does it take to get to the center of a tootsie pop" => "The world may never know...",
  "If a tree fell and nobody was there to hear it does it make a sound" => "Depends if somebody was there with them..."
);
$count = count($questions);
$question = urldecode($ep[0]);
$last_joke = $count === 1 ? 0 : count($questions)-1;
$response = $questions[$question];
$data['question'] = $question;
$data['response'] = $response;
$data['questions'] = $questions;
$data['ep'] = $ep;
?>
