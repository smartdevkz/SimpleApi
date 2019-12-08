<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,Authorization");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS");
//header("Content-Type:application/json");
header('Content-Type: text/html');

$requestType = $_SERVER['REQUEST_METHOD'];
//var_dump($requestType);

//var_dump($_SERVER);

//echo 'req_uri: ' . $_SERVER['REQUEST_URI'];

//echo '  PHP_SELF: ' . $_SERVER['PHP_SELF'];
$origin  = getOrigin();
echo '<br/>origin: ' . $origin;

$path = getPath($origin);
echo "<br/>path: " . $path;

$controller = getController($path);
echo "<br/>controller: " . $controller;

$action = getAction($path, $controller);
echo "<br/>action: " . $action;

return;

$response = act($name);

response($response);

function getOrigin()
{
    $startIndex = stripos($_SERVER['PHP_SELF'], '/index.php');
    return $startIndex > 0 ? substr($_SERVER['PHP_SELF'], 1, $startIndex - 1) : "";
}

function response($obj)
{
    $json_response = json_encode($obj);
    echo $json_response;
}

function getPath($origin)
{
    $url = $_SERVER['REQUEST_URI'];
    $endIndex = stripos($url, '?');
    if (empty($origin)) {
        return $endIndex > 0 ? substr($url, 1, $endIndex - 1) : $url;
    } else {
        $startIndex = stripos($url, '/' . $origin . '/');
        if ($startIndex < 0) throw new Exception("Error");
        return !$endIndex ? substr($url, $startIndex + strlen($origin) + 1) : substr($url, $startIndex + strlen($origin) + 1, $endIndex - ($startIndex + strlen($origin) + 1));
    }
}

function getController($path)
{
    $arr = explode('/', $path);
    $k = 0;
    $name = "";
    foreach ($arr as $item) {
        if (!empty($item)) {
            $k++;
            if ($k > 1) return $name;
            $name = $item;
        }
    }
    return $path;
}

function getAction($path, $controller)
{
    if ($controller == "") return "";
    $startIndex = stripos($path,  '/' . $controller . '/');
    return substr($path, $startIndex + 1 + strlen($controller . '/'));
}


function version()
{
    return "Simple API 1.0";
}

function act($name)
{
    //if (empty($name)) return version();
    try {
        include('controllers/' . $name . 'Controller.php');
    } catch (Exception $ex) {
        //return "error: "+$ex->getMessage();
    }
}
