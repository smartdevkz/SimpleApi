<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,Authorization");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type:application/json");
require_once 'config/config.php';
require_once 'include/Feather.php';

$app = Feather::getInstance();

$app->get('/', function(){
    return 'Simple API via Feather!!!';
});

$app->run();





