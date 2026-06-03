<?php

class ApiController {
    public function process_request(?PDO $database, string $method, ?string $action = null) {
        $response = '';
        switch($method) {
            case 'GET':
                switch($action) {
                    case 'times':
                        $response = $this->get_times_data($database);
                        break;
                    case 'csv':
                        $response = $this->get_full_csv($database);
                        break;
                }
                break;
            case 'POST':
                switch($action) {
                    case 'login':
                        $response = $this->post_login($database);
                        break;
                    case 'logout':
                        $response = $this->post_logout($database);
                        break;
                    case 'logout-all':
                        $response = $this->post_logout_all($database);
                        break;
                    case 'access':
                        $response = $this->post_login_status($database);
                        break;
                    case 'edit-username':
                        $response = $this->post_edit_username($database);
                        break;
                    case 'add-time':
                        $response = $this->post_add_time($database);
                        break;
                    case 'edit-time':
                        $response = $this->post_edit_time($database);
                        break;
                }
                break;
            default:
                break;
        }    

        return $response;
    }

    #region GET
    private function get_times_data(PDO $database) {
        if(is_null($_GET['user'])) {
            http_response_code(400);
            $output = json_encode('Invalid data'); 
        } else {
            $user = $_GET['user'];
            $query = 'SELECT `id`,`date`,`start_time`,`end_time` FROM `tracked_times` WHERE `user` = :user ORDER BY `date` DESC';
            $statement = $database->prepare($query);

            $statement->bindParam(':user', $user, PDO::PARAM_STR);

            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_CLASS);

            $output = json_encode($results);
        }

        return $output;
    }

    private function get_full_csv(PDO $database) {
        if(is_null($_GET['user'])) {
            http_response_code(400);
            $output = json_encode('Invalid data'); 
        } else {
            $user = $_GET['user'];
            $query = 'SELECT `date`,`start_time`,`end_time` FROM `tracked_times` WHERE `user` = :user ORDER BY `date` DESC';
            $statement = $database->prepare($query);

            $statement->bindParam(':user', $user, PDO::PARAM_STR);

            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_CLASS);

            $output = json_encode($results);
        }

        return $output;
    }
    #endregion

    #region POST
    private function post_login(PDO $database) {
        if(empty($_POST)) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $useragent = $_POST['userAgent'];

        if(is_null($username) || is_null($password)) {
            http_response_code(400);
            $output = json_encode('Invalid data'); 
        } else {
            $query = 'SELECT `id` FROM `users` WHERE `login_name` = :username AND `password` = :password';
            $statement = $database->prepare($query);

            $hashed_psw = MD5($password);

            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->bindParam(':password', $hashed_psw, PDO::PARAM_STR);
            
            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_CLASS);

            if($results) {
                $today = date('Y-m-d');
                $user_id = $results[0]->id;
                $session_hash = MD5(time() . $password . $results[0]->id);

                //Create session on backend system
                $query = 'INSERT INTO `users_login` (`id`, `creation_date`, `token`, `user_id`, `user_agent`) VALUES (NULL, :date, :hash, :userId, :userAgent)';
                $statement = $database->prepare($query);

                $statement->bindParam(':date', $today, PDO::PARAM_STR);
                $statement->bindParam(':hash', $session_hash, PDO::PARAM_STR);
                $statement->bindParam(':userId', $user_id, PDO::PARAM_STR);
                $statement->bindParam(':userAgent', $useragent, PDO::PARAM_STR);

                $statement->execute();

                //Valorize return value with session data
                $output = json_encode(array($user_id, $session_hash));
            } else {
                http_response_code(401);
                $output = json_encode('Invalid username or password');
            }
        }

        return $output;
    }

    private function post_logout(PDO $database) {
        if(empty($_POST)) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }

        $token = $_POST['token'];
        $user_id = $_POST['userId'];

        if(is_null($token) || is_null($user_id)) {
            http_response_code(400);
            $output = json_encode('Invalid data');
        } else {
            $query = 'DELETE FROM `users_login` WHERE `token` = :token AND `user_id` = :user_id';
            $statement = $database->prepare($query);

            $statement->bindParam(':token', $token, PDO::PARAM_STR);
            $statement->bindParam(':user_id', $user_id, PDO::PARAM_STR);

            $query_action = $statement->execute();

            if($query_action) {
                http_response_code(200);
                $output = json_encode('Logout successful');
            } else {
                http_response_code(500);
                $output = json_encode('An error occurred while logging out');
            }
        }

        return $output;
    }

    private function post_logout_all(PDO $database) {
        if(empty($_POST)) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }

        $user_id = $_POST['userId'];

        if(is_null($user_id)) {
            http_response_code(400);
            $output = json_encode('Invalid data');
        } else {
            $query = 'DELETE FROM `users_login` WHERE `user_id` = :user_id';
            $statement = $database->prepare($query);

            $statement->bindParam(':user_id', $user_id, PDO::PARAM_STR);

            $query_action = $statement->execute();

            if($query_action) {
                http_response_code(200);
                $output = json_encode('Logout successful');
            } else {
                http_response_code(500);
                $output = json_encode('An error occurred while logging out');
            }
        }

        return $output;
    }

    private function post_login_status(PDO $database) {
        if(empty($_POST)) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }

        $token = $_POST['token'];
        $user_id = $_POST['user'];

        if(is_null($token) || is_null($user_id)) {
            http_response_code(400);
            $output = json_encode('Invalid data');
        } else {
            $query = 'SELECT * FROM `users_login` WHERE `token` = :token AND `user_id` = :user_id';
            $statement = $database->prepare($query);

            $statement->bindParam(':token', $token, PDO::PARAM_STR);
            $statement->bindParam(':user_id', $user_id, PDO::PARAM_STR);

            $statement->execute();

            $results = $statement->fetchAll(PDO::FETCH_CLASS);

            if($results) {
                $today = date('Y-m-d');
                $expiration_date = date('Y-m-d', strtotime($results[0]->creation_date . '+30 days'));
                
                //Check if todays date is over the token's expiration date
                if($today > $expiration_date) {
                    http_response_code(418);
                    $output = json_encode('Token expired');
                } else {
                    http_response_code(200);
                    $output = json_encode('Valid token');
                }
                
            } else {
                http_response_code(401);
                $output = json_encode('Invalid access token');
            }
        }

        return $output;
    }

    private function post_signup(PDO $database) {
        if(empty($_POST)) {
            $_POST = json_decode(file_get_contents("php://input"), true);
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $display_name = $_POST['display_name'];
        $password = $_POST['password'];

        if(is_null($username) || is_null($password)) {
            http_response_code(400);
            $output = json_encode('Invalid data');
        } else {
            $query = "INSERT INTO `users`(`id`, `login_name`, `email`, `name` `password`) VALUES (NULL, :name, :email, :displayname :password)";
            $statement = $database->prepare($query);

            $hashed_psw = MD5($password);

            $statement->bindParam(':name', $username, PDO::PARAM_STR);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':displayname', $display_name, PDO::PARAM_STR);
            $statement->bindParam(':password', $hashed_psw, PDO::PARAM_STR);

            $query_action = $statement->execute();

            http_response_code(200);
            $output = json_encode('User created successfully');
        }

        return $output;
    }

    private function post_add_time(PDO $database) {
        if(empty($_POST)) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }

        $date = $_POST['date'];
        $start_time = $_POST['start'];
        $end_time = $_POST['end'];
        $user_id = $_POST['user'];

        if(is_null($date) || is_null($start_time) || is_null($end_time) || is_null($user_id)) {
            http_response_code(400);
            $output = json_encode('Invalid data');
        } else {
            $query = 'INSERT INTO `tracked_times` (`id`, `date`, `start_time`, `end_time`, `user`) VALUES (NULL, :date, :start_time, :end_time, :user)';
            $statement = $database->prepare($query);

            $statement->bindParam(':date', $date, PDO::PARAM_STR);
            $statement->bindParam(':start_time', $start_time, PDO::PARAM_STR);
            $statement->bindParam(':end_time', $end_time, PDO::PARAM_STR);
            $statement->bindParam(':user', $user_id, PDO::PARAM_STR);

            $query_action = $statement->execute();
            $output = json_encode($query_action);
        }

        return $output;
    }

    private function post_edit_time(PDO $database) {
        if(empty($_POST)) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }

        $id = $_POST['id'];
        $date = $_POST['date'];
        $start_time = $_POST['start'];
        $end_time = $_POST['end'];
        $user_id = $_POST['user'];

        $output = '';

        if((is_null($id) || $id == '') || is_null($date) || is_null($start_time) || is_null($end_time) || is_null($user_id)) {
            http_response_code(400);
            $output = json_encode('Invalid data');
        } else {
            $query = 'UPDATE `tracked_times` SET `date` = :date, `start_time` = :start_time, `end_time` = :end_time WHERE `id` = :id AND `user` = :user';
            $statement = $database->prepare($query);

            $statement->bindParam(':id', $id, PDO::PARAM_STR);
            $statement->bindParam(':date', $date, PDO::PARAM_STR);
            $statement->bindParam(':start_time', $start_time, PDO::PARAM_STR);
            $statement->bindParam(':end_time', $end_time, PDO::PARAM_STR);
            $statement->bindParam(':user', $user_id, PDO::PARAM_STR);

            $query_action = $statement->execute();
            $output = json_encode($query_action);
        }

        return $output;
    }

    private function post_edit_username(PDO $database) {
        if(empty($_POST)) {
            $_POST = json_decode(file_get_contents('php://input'), true);
        }

        $token = $_POST['token'];
        $user_id = $_POST['userId'];
        $new_username = $_POST['newName'];

        $output = '';

        if(is_null($token) || $token == '' || is_null($new_username) || $new_username == '' || is_null($user_id) || $user_id == '') {
            http_response_code(400);
            $output = json_encode('Invalid data');
        } else {
            $query = 'UPDATE `users` INNER JOIN `users_login` ON `users_login`.`user_id` = `users`.`id` SET `users`.`name` = :name WHERE `users_login`.`token` = :token AND `users_login`.`user_id` = :user_id';
            $statement = $database->prepare($query);

            $statement->bindParam(':name', $new_username, PDO::PARAM_STR);
            $statement->bindParam(':token', $token, PDO::PARAM_STR);
            $statement->bindParam(':user_id', $user_id, PDO::PARAM_STR);

            $query_action = $statement->execute();
            $output = json_encode($query_action);
        }
        
        return $output;
    }
    #endregion
}