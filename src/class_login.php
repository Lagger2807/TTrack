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
            $query = "SELECT * FROM `users_login` WHERE `token` = :token";
            $statement = $database_connection->prepare($query);

            $statement->bindParam(':token', $_COOKIE['ttrack_login'], PDO::PARAM_STR);
            
            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_CLASS);

            if(!$results) {
                return false;
            }

            if($results) {
                $today = date('Y-m-d');
                $expiration_date = date('Y-m-d', strtotime($results[0]->creation_date . '+30 days'));

                //Check if todays date is over the token's expiration date
                if($today <= $expiration_date) {
                    return true;
                } else {
                    $query = "DELETE FROM `users_login` WHERE `users_login`.`id` = :id";
                    $statement = $database_connection->prepare($query);

                    $statement->bindParam(':id', $results[0]->id, PDO::PARAM_STR);

                    $statement->execute();

                    return false;
                }
            } else {
                return false;
            }

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