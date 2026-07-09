<?php

require 'includes/init.php';

require 'includes/googleauth.php';

if(!isset($_GET['code'])){
    exit("Login Failed");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

$client->setAccessToken($token['access_token']);

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();

var_dump($userInfo->email, $userInfo->givenName, $userInfo->name);
