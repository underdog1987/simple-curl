<?php

require_once __DIR__.'/../vendor/autoload.php';

use Underdog1987\SimpleCURL\SimpleCURL;

if(SimpleCURL::isRunnable()){
    // Request with GET Method
    //$bar = new SimpleCURL('https://www.instagram.com/underdog1987/?__a=1');
    $bar = new SimpleCURL('http://127.0.0.1/AnybrisComponents/SimpleCURL/example/handleRequest.php?bar=foo');
    $bar->isGet();
    //$bar->setData('bar=foo');
    //$bar->ignoreCerts(FALSE);
    $bar->setUserAgent('Mozilla 5.0');
    $bar->prepare();
    $res = $bar->execute();
    echo($res->getResponseBody());

}else{
    die("cURL extension is not enabled");
}