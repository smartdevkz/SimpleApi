<?php
require_once 'Slim.php';

$app = new Slim();

$app->get('index',function(){
    echo "get index";
});

$app->run();
?>