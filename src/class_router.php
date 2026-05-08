<?php

class Router {
    function __construct() {   
    }

    private $routes = [];

    public function route(string $path, callable $callback) {
        $this->routes[$path] = $callback;
    }

    public function run() {
        $uri = $_SERVER['REQUEST_URI'];
        $found = false;

        foreach($this->routes as $path => $callback) {
            if($path !== $uri) continue;

            $found = true;
            $callback($this);
        }

        if(!$found && isset($this->routes['/404'])) {
            $this->routes['/404']($this);
        }
    }

    public function load_page($page_name) {
        $file = __DIR__ . "/../pages/$page_name";
        if(file_exists($file)) {
            include $file;
        } else {
            echo "Invalid permalink: $page_name";
        }
    }
}