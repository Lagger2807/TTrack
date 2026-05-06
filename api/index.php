<?php

$env = parse_ini_file(__DIR__ . '/../.env');
$db_host = $env['DBAddr'];
$db_name = $env['DBName'];
$db_user = $env['DBUser'];
$db_pass = $env['DBPass'];

header("Content-type: application/json; charset=UTF-8");

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

$parsed_url = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));

$action = $parsed_url[2];

$database = new DBController($db_host, $db_name, $db_user, $db_pass);
$database_connection = $database->getConnection();

$api_controller = new APIController();
$response = $api_controller->process_request($database_connection, $_SERVER["REQUEST_METHOD"], $action);

echo $response;

// var_dump($parsed_url);
// var_dump($_GET);
// var_dump(json_decode(file_get_contents("php://input"), true));
// var_dump($_SERVER["REQUEST_METHOD"]);