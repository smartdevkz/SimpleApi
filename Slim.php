<?php 

class Slim{
    private $routes;

    public function __construct()
    {
        $this->routes = array();
    }

    public function get($name, $f){
        $this->routes['get'.$name] = $f;

    }

    public function run(){
        $name ='getindex';
        $f = $this->routes[$name];
        return $f();
    }
}

?>