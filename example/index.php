<?php

require_once __DIR__.'/../vendor/autoload.php';

use Underdog1987\SimpleCURL\SimpleCURL;

if(SimpleCURL::isRunnable()){
    $client = new SimpleCURL();
    $client->isGet();
    $client->setUrl('https://google.com');
    $client->ignoreCerts(TRUE);
    $client->prepare();
    $result = $client->execute();
    var_dump($result->getHttpCode());
    var_dump($result->getResponseHeaders());
    var_dump($result->getResponseBody());
}else{
    die("cURL extension is not enabled");
}