<?php

$app->get('about', function(){
    return 'get about function();';
});

function getAll($sql, $params=null){
    $response = array();
    $db = new DbManager();
    $result = $db->getRows($sql,$params);
    if(is_a($result,'PDOException')){
        $response['status'] = 'error';
        $response["message"]=$result->getMessage();
    }else{
        $response['status'] = 'success';
        $response["data"] = $result;
    }
    return $response;
}

function getOne($sql, $params=null){
    $response = array();
    $db = new DbManager();
    $result = $db->getRow($sql,$params);
    if(is_a($result,'PDOException')){
        $response['status'] = 'error';
        $response["message"]=$result->getMessage();
    }else{
        $response['status'] = 'success';
        $response["data"] = $result;
    }
    
    return $result;
}

?>