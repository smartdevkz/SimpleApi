<?php

require_once __DIR__.'/../include/DB.php';
require_once __DIR__.'/../include/jwt.php';

function getAll($sql, $params = null)
{
    $db = DB::getInstance();
    return $db->getRows($sql, $params);
}

function getOne($sql, $params = null)
{
    $db = DB::getInstance();
    return $db->getRow($sql, $params);
}

function createObject($table_name, $obj, $params = null)
{
    if (!$obj && $obj == null) throw new Exception("Body can not be null");
    $fieldStr = getFieldStr($obj);
    $paramStr = getParamStr($obj);
    $sql = "INSERT INTO $table_name($fieldStr) VALUES($paramStr)";
    $db = DB::getInstance();
    $stmt = $db->conn->prepare($sql);
    foreach (explode(',', $fieldStr) as $field) {
        $stmt->bindParam(":$field", $obj->$field);
    }
    $stmt->execute();
    $lastId = $db->conn->lastInsertId();
    return getOne("select * from journal where id=$lastId");
}

function updateObject($table_name, $criteria, $params = null, $obj)
{
    if (!empty($criteria)) $criteria = ' WHERE ' . $criteria;
    $updateStr = getUpdateStr($obj);

    $fields = array_keys((array) $obj);
    $sql = "UPDATE $table_name SET $updateStr" . $criteria;
    $db = DB::getInstance();
    $stmt = $db->conn->prepare($sql);
    foreach ($fields as $field) {
        $stmt->bindParam(":$field", $obj->$field);
    }
    foreach (array_keys($params) as $field) {
        $stmt->bindParam(":$field", $params[$field]);
    }
    $stmt->execute();
    return true;
}

function deleteObject($table_name, $criteria, $params = null, $obj)
{
    if (!empty($criteria)) $criteria = ' WHERE ' . $criteria;

    $fields = array_keys((array) $obj);
    $sql = "DELETE FROM $table_name" . $criteria;
    $db = DB::getInstance();
    $stmt = $db->conn->prepare($sql);
    foreach ($fields as $field) {
        $stmt->bindParam(":$field", $obj->$field);
    }
    foreach (array_keys($params) as $field) {
        $stmt->bindParam(":$field", $params[$field]);
    }
    $stmt->execute();
    return true;
}

function getFieldStr($obj)
{
    return implode(",", array_keys((array) $obj));
}

function getParamStr($obj)
{
    return implode(',', array_map(function ($a) {
        return ':' . $a;
    }, array_keys((array) $obj)));
}

function getUpdateStr($obj)
{
    return implode(',', array_map(function ($a) {
        return $a . '=:' . $a;
    }, array_keys((array) $obj)));
}

function getBearerToken()
{
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    throw new Exception('Access Token Not found');
}

function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

function validateToken()
{
    $token = getBearerToken();
    $payload = JWT::decode($token, SECRETE_KEY, ['HS256']);
    return $payload;
}

$app->post('generateToken', function ($params, $obj) {
    $email = $obj->email;
    $password = $obj->password;
    unset($obj->password);
    $user = getOne("select * from users where email=:email", $obj);
    if (password_verify($password, $user->password)) {

        $paylod = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() + (10*24*60 * 60),
            'userId' => $user->id
        ];

        $token = JWT::encode($paylod, SECRETE_KEY);
        return $token;
    } else {
        throw new Exception('Login failed');
    }
});

$app->post('auth', function ($params, $obj) {
    $email = $obj->email;
    $password = $obj->password;
    unset($obj->password);
    $user = getOne("select * from users where email=:email", $obj);
    if (password_verify($password, $user->password)) {

        $paylod = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() + (10*24*60 * 60),
            'userId' => $user->id
        ];

        $token = JWT::encode($paylod, SECRETE_KEY);
        unset($user->password);

        return array('token'=>$token, 'user'=>$user);
    } else {
        throw new Exception('Login failed');
    }
});
