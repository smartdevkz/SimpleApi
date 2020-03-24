<?php

class DB
{

    public $conn;
    static $instance;

    private function __construct()
    {
        $this->connect();
    }

    static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    function connect()
    {
        $this->conn = new PDO("mysql:host=" . DB_HOST . ";charset=utf8" . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->conn;
    }

    function getRows($sql, $params)
    {
        log_msg($sql);
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            foreach ($params as $key => $value) {
                $stmt->bindParam(":" . $key, $value);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    function getRow($sql, $params)
    {
        log_msg($sql);
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            foreach ($params as $key => $value) {
                $stmt->bindParam(":" . $key, $value);
            }
        }
        $stmt->execute();
        return $stmt->fetchObject();
    }

    function execute($sql, $params)
    {
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            foreach ($params as $key => $value) {
                $stmt->bindParam(":" . $key, $value);
            }
        }
        $stmt->execute();
    }
}

function log_msg($message)
{
    error_log($message . "\n", 3, 'log.txt');
}
