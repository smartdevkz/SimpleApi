<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,Authorization");
header("Access-Control-Allow-Headers: x-access-token, Origin, X-Requested-With, Content-Type, Accept,Authorization");
header("Access-Control-Max-Age: 600");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
header("Content-Type: application/json");
header("HTTP/1.1 200 OK");

require_once 'config.php';
require_once 'include/Feather.php';

$app = new Feather();

$app->get('/', function(){
    return 'Simple API via Feather!!!';
});

$app->run();





