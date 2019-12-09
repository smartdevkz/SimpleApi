<?php

$app = $this;

$app->get('about', function(){
    return 'get about function();';
});

$app->get('/', function(){
    return 'Simple API via Feather!!!';
});

?>