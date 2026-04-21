<?php
/*
*   *Declare all routing here*
*/

include_once __DIR__ . '/src/class_router.php';
$router = new Router();

#region Routes
$router->route('/', function($router) {
    $router->load_page('dashboard.php');
});

$router->route('/login', function($router) {
    $router->load_page('login.php');
});

$router->route('/signup', function($router) {
    $router->load_page('signup.php');
});

$router->route('/404', function($router) {
    $router->load_page('404.html');
});
#endregion

$router->run();