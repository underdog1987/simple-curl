<?php

require_once __DIR__.'/../vendor/autoload.php';

use Underdog1987\SimpleCURL\SimpleCURL;

if(SimpleCURL::isRunnable()){
    // Request with GET Method
    $bar = new SimpleCURL('https://www.google.com/');
    $bar->isGet();
    $bar->ignoreCerts(TRUE);
    $bar->setUserAgent('Mozilla 5.0');
    $bar->prepare();
    $res = $bar->execute();
    var_dump($res->getResponseHeaders());

}else{
    die("cURL extension is not enabled");
}