<?php

require_once __DIR__.'/../vendor/autoload.php';

use Underdog1987\SimpleCURL\SimpleCURL;

if(SimpleCURL::isRunnable()){
    $bar = new SimpleCURL('https://intranet.tradeco.com.mx/intranet2/Session/');
    $bar->isDelete();
    $bar->ignoreCerts(TRUE);

    $bar->setUserAgent('Mozilla 5.0');
    $bar->prepare();
    $res = $bar->execute();
    var_dump($res);
}