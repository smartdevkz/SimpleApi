<?php

$app->get('about', function () {
    return 'get about function();';
});

function getAll($sql, $params = null)
{
    $response = array();
    $db = new DbManager();
    $result = $db->getRows($sql, $params);
    if (is_a($result, 'PDOException')) {
        $response['status'] = 'error';
        $response["message"] = $result->getMessage();
    } else {
        $response['status'] = 'success';
        $response["data"] = $result;
    }
    return $response;
}

function getOne($sql, $params = null)
{
    $response = array();
    $db = new DbManager();
    $result = $db->getRow($sql, $params);
    if (is_a($result, 'PDOException')) {
        $response['status'] = 'error';
        $response["message"] = $result->getMessage();
    } else {
        $response['status'] = 'success';
        $response["data"] = $result;
    }

    return $result;
}

function createObject($table_name, $obj, $params = null)
{
    try {
        if (!$obj && $obj == null) throw new Exception("Body can not be null");
        $fieldStr = getFieldStr($obj);
        $paramStr = getParamStr($obj);
        $sql = "INSERT INTO $table_name($fieldStr) VALUES($paramStr)";
        $db = new DbManager();
        $stmt = $db->conn->prepare($sql);
        foreach (explode(',', $fieldStr) as $field) {
            $stmt->bindParam(":$field", $obj->$field);
        }
        $stmt->execute();
        return true;
    } catch (Exception $ex) {
        echo '{"error":{"text":' . $ex->getMessage() . '}}';
        return $ex;
    }
}



function updateObject($table_name, $criteria, $params = null, $obj)
{
    if (!empty($criteria)) $criteria = ' WHERE ' . $criteria;
    $updateStr = getUpdateStr($obj);
    try {
        $fields = array_keys((array) $obj);
        $sql = "UPDATE $table_name SET $updateStr" . $criteria;
        $db = new DbManager();
        $stmt = $db->conn->prepare($sql);
        foreach ($fields as $field) {
            $stmt->bindParam(":$field", $obj->$field);
        }
        foreach (array_keys($params) as $field) {
            $stmt->bindParam(":$field", $params[$field]);
        }
        $stmt->execute();
        return true;
    } catch (Exception $ex) {
        echo '{"error":{"text":' . $ex->getMessage() . '}}';
        return $ex;
    }
}

function deleteObject($table_name, $criteria, $params = null, $obj)
{
    if (!empty($criteria)) $criteria = ' WHERE ' . $criteria;
    try {
        $fields = array_keys((array) $obj);
        $sql = "DELETE FROM $table_name" . $criteria;
        $db = new DbManager();
        $stmt = $db->conn->prepare($sql);
        foreach ($fields as $field) {
            $stmt->bindParam(":$field", $obj->$field);
        }
        foreach (array_keys($params) as $field) {
            $stmt->bindParam(":$field", $params[$field]);
        }
        $stmt->execute();
        return true;
    } catch (Exception $ex) {
        echo '{"error":{"text":' . $ex->getMessage() . '}}';
        return $ex;
    }
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
