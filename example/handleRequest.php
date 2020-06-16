<?php
    /* This file is for use as example of remote server response */
    $datos = $_SERVER['REQUEST_METHOD']=='GET'?$_SERVER['QUERY_STRING']:file_get_contents("php://input");
    
    echo '<p><strong>Method:</strong> '.$_SERVER['REQUEST_METHOD'].'</p>';
    echo '<p><strong>Data:</strong><br /><pre>'.($datos).'<pre></p>';