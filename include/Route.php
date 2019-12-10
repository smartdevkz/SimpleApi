<?php

class Route
{

    private $origin;
    private $path;

    public $controller;
    public $action;
    public $id;

    function run()
    {
        $this->origin = $this->getOrigin();
        $this->path = $this->getPath($this->origin);
        $this->controller = $this->getController($this->path);
        $arr = $this->getActionAndId($this->path, $this->controller);
        $this->action = $arr['action'];
        $this->id = $arr['id'];
        $this->trimAllFields();
    }

    function getOrigin()
    {
        $startIndex = stripos($_SERVER['PHP_SELF'], '/index.php');
        return $startIndex > 0 ? substr($_SERVER['PHP_SELF'], 1, $startIndex - 1) : "";
    }

    function getPath($origin)
    {
        $url = $_SERVER['REQUEST_URI'];
        $endIndex = stripos($url, '?');
        if (empty($origin)) {
            return $endIndex > 0 ? substr($url, 0, $endIndex - 1) : $url;
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
        return $this->trim($path);
    }

    private function getActionAndId($path, $controller)
    {
        $res = array();
        if ($controller == "") return null;
        $startIndex = stripos($path,  '/' . $controller . '/');
        $action = substr($path, $startIndex + 1 + strlen($controller . '/'));
        if (is_numeric($action)) {
            $res['id'] = $action;
            $res['action'] = '';
        } else {
            if (strpos($action, '/') !== false) {
                $arr = explode('/', $action);
                foreach ($arr as $item) {
                    if (!empty($item)) {
                        if (is_numeric($item)) {
                            $res['id'] = $item;
                        } else {
                            $res['action'] = $item;
                        }
                    }
                }
            } else {
                $res['action'] = $action;
            }
        }
        return $res;
    }

    private function trim($str)
    {
        $str = ltrim($str, '/');
        $str = rtrim($str, '/');
        return $str;
    }

    private function trimAllFields()
    {
        $this->controller = $this->trim($this->controller);
        $this->action = $this->trim($this->action);
        $this->id = $this->trim($this->id);
    }
}
