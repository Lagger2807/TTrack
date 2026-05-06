<?php

include_once __DIR__ . '/../api/src/DBController.php';

class Login {
    function check_login_status() {
        $env = parse_ini_file(__DIR__ . '/../.env');
        $db_host = $env['DBAddr'];
        $db_name = $env['DBName'];
        $db_user = $env['DBUser'];
        $db_pass = $env['DBPass'];

        $database = new DBController($db_host, $db_name, $db_user, $db_pass);
        $database_connection = $database->getConnection();

        if($_COOKIE['ttrack_login']) {
            // $query = "SELECT * FROM `users_login` WHERE `token` = :token";
            // $statement = $database_connection->prepare($query);

            // $statement->bindParam(':token', $_COOKIE['ttrack_login'], PDO::PARAM_STR);
            
            // $statement->execute();

            // $results = $statement->fetchAll(PDO::FETCH_CLASS);

            // // var_dump($results);

            // if(!$results)
            //     return false;

            // if($results) {
            //     $output = json_encode(array($results[0]->id, MD5(time() . $password . $results[0]->id)));
            // } else {
            //     http_response_code(401);
            //     $output = 'Invalid username or password';
            // }

            //TODO: check it, damn it
            return true;
        } else {
            return false;
        }
    }

    function redirect($url, $permanent = false) {
        header('Location: ' . $url, true, $permanent ? 301 : 302);

        exit();
    }
}