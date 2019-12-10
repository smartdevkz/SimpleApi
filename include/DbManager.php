<?php

class DbManager
{

    public $conn;

    function __construct()
    {
        $this->connect();
    }

    function connect(){
        include_once dirname(__FILE__).'/config.php';
        $this->conn = new PDO("mysql:host=".DB_HOST.";charset=utf8".";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->conn;
    }
    
    function getRows($sql,$params){
        try {
            log_msg($sql);
            $stmt = $this->conn->prepare($sql);
            if($params){
                foreach ($params as $key => $value){
                    $stmt->bindParam(":".$key, $value);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
            return $e;
        }
    }

    function getRow($sql,$params){
        try {
            log_msg($sql);
            $stmt = $this->conn->prepare($sql);
            if($params){
                foreach ($params as $key => $value){
                    $stmt->bindParam(":".$key, $value);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchObject();
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
            return $e;
        }
    }

    function execute($sql, $params){
        $stmt = $this->conn->prepare($sql);
        if($params){
            foreach ($params as $key => $value){
                $stmt->bindParam(":".$key, $value);
            }
        }
        $stmt->execute();
    }

}

function log_msg($message){
    error_log($message."<br/>", 3, 'log.html');
}

?>
