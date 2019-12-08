<?php

header("Content-Type:application/json");

$requestType =$_SERVER['REQUEST_METHOD'];
//var_dump($requestType);

$name = getActionName();

$response = act($name);

response($response);

function response($obj)
{
    $json_response = json_encode($obj);
    echo $json_response;
}

function getActionName()
{
    $link = $_SERVER['REQUEST_URI'];
    $startIndex = stripos($link, '/api.php');
    if ($startIndex < 0) throw new Exception("Error");
    $endIndex = stripos($link, '?');
    return !$endIndex ? substr($link, $startIndex + 9) : substr($link, $startIndex + 9, ($endIndex - $startIndex - 9));
}

function version()
{
    return "Simple API 1.0";
}

function act($name)
{
    //if (empty($name)) return version();
    include('controllers/subjectsController.php');
}


