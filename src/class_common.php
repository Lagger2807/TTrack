<?php

include_once __DIR__ . '/../api/src/DBController.php';

Class Common {
    function __construct() {   
    }

    function get_user_id() {
        $database_connection = (new TTDB())->get_connection();

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
                return $results[0]->user_id;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_user_accesses($user_id = null) {
        if(!$user_id) {
            $user_id = $this->get_user_id();
        }

        $database_connection = (new TTDB())->get_connection();
        
        if($user_id) {
            $query = "SELECT `creation_date`,`token`,`user_agent` FROM `users_login` WHERE `user_id` = :user";
            $statement = $database_connection->prepare($query);

            $statement->bindParam(':user', $user_id, PDO::PARAM_STR);
            
            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_CLASS);

            if(!$results) {
                return false;
            }

            if($results) {
                return $results;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_user_data($user_id = null) {
        if(!$user_id) {
            $user_id = $this->get_user_id();
        }

        $database_connection = (new TTDB())->get_connection();

        if($user_id) {
            $query = "SELECT `name` FROM `users` WHERE `id` = :user";
            $statement = $database_connection->prepare($query);

            $statement->bindParam(':user', $user_id, PDO::PARAM_STR);
            
            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_CLASS);

            if(!$results) {
                return false;
            }

            if($results) {
                return $results[0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

Class TTDB {
    protected $database_connection;

    function __construct() {
        $env = parse_ini_file(__DIR__ . '/../.env');
        $db_host = $env['DBAddr'];
        $db_name = $env['DBName'];
        $db_user = $env['DBUser'];
        $db_pass = $env['DBPass'];

        $database = new DBController($db_host, $db_name, $db_user, $db_pass);
        $this->database_connection = $database->getConnection();
    }

    function get_connection() {
        return $this->database_connection;
    }
}