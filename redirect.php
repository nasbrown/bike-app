<?php

require 'includes/init.php';

$conn = require('includes/db.php');

$user = new User;

$client = new Google\Client;

$client->setClientId(BIKE_CLIENT_ID);

$client->setClientSecret(BIKE_CLIENT_SECRET);

$client->setAccessType('offline');

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

$user->firstName = $userInfo->getGivenName();
$user->lastName = $userInfo->getFamilyName();
$user->userEmail = $userInfo->email;
$user->accessToken = $token['access_token'];
$user->refreshToken = $token['refresh_token'];

if($user->doesEmailExist($conn, $user->userEmail)){
    $user->saveCredentials($conn);
} else{
    $user->updateTokens($conn, $user->getId($conn, $userInfo->email));
}

Url::redirect('/bike-app/admin/');
