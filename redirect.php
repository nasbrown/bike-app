<?php

require 'includes/init.php';

require 'includes/googleauth.php';

if(!isset($_GET['code'])){
    exit("Login Failed");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

if(isset($_SESSION['access_token']) && $_SESSION['access_token'] === true){
    $client->setAccessToken($_SESSION['access_token']);
}

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();

var_dump($userInfo->email, $userInfo->givenName, $userInfo->name, $_SESSION['access_token']);

//Url::redirect('/bike-app/admin/');
