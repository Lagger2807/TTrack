<?php

include_once __DIR__ . '/src/class_login.php';
$login = new Login();

$uri = $_SERVER['REQUEST_URI'];

if(!$login->check_login_status() && ($uri != '/login' && $uri != '/signup')) {
    $login->redirect('/login', true);
    die();
}