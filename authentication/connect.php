<?php
require_once('vendor/autoload.php');
require_once('config.php');
require_once('functions.php');

use GuzzleHttp\Client;

$client = new Client([
    'verify' => false,
]);
try{
    $response = $client->request('GET', 'https://accounts.google.com/.well-known/openid-configuration');
    $discoveryEndpoint = json_decode((string)$response->getBody());
    $tokenEndpoint = $discoveryEndpoint->token_endpoint;
    $userinfoEndpoint = $discoveryEndpoint->userinfo_endpoint;
    $response = $client->request('POST', $tokenEndpoint, [
        'form_params' => [
            'code' => $_GET['code'],
            'client_id' => GOOGLE_ID,
            'client_secret' => GOOGLE_SECRET,
            'redirect_uri' => 'http://localhost/connect.php',
            'grant_type' => 'authorization_code'
        ]
    ]);
    $accessToken = json_decode($response->getBody())->access_token;
    $response = $client->request('GET', $userinfoEndpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken
        ]
    ]);

    $response = json_decode($response->getBody());
    if($response->email_verified === true){
        session_start();
        $user = logUserFromGoogle($response->email);
        $_SESSION['user'] = $user;
        header('Location: /index.php');
        exit();
    }
}
catch(\GuzzleHttp\Exception\ClientExecption $exception){
    var_dump($exception->getMessages());
}

?>