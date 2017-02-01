<?php
require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if(getenv('devenvironment') == true){
  $dotenv = new Dotenv\Dotenv("./");
  $dotenv->load();
}

$access_token = getenv('access_token');
$access_token_secret = getenv('access_token_secret');
$consumer_key = getenv('consumer_key');
$consumer_secret = getenv('consumer_secret');
$user_id = getenv('user_id');
$sentiment_email = getenv('sentiment_email');

$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
?>
