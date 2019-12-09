<?php

require_once 'include/DbManager.php';

$app->get('/',function(){
    return getAll("select * from subjects");
});

?>