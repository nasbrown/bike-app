<?php

require 'includes/init.php';

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] .'/bike-app/redirect.php');

if(!isset($_GET['code'])){
    exit("Login Failed");
}

$client->fetchAccessTokenWithAuthCode($_GET['code']);

$token = $client->getAccessToken();

$_SESSION['access_token'] =  $token;

$client->setAccessToken($token);

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();

//var_dump($userInfo->email, $userInfo->givenName, $userInfo->name, $_SESSION['access_token']);

Url::redirect('/bike-app/admin/');
