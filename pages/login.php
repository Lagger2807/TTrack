<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="/assets/js/common.js"></script>
        <script src="/assets/js/<?php echo basename(__FILE__, '.php'); ?>.js"></script>
        <link rel="stylesheet" href="/assets/css/core.css">
        <link rel="stylesheet" href="/assets/css/<?php echo basename(__FILE__, '.php'); ?>.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    </head>
    <body>
        <div id="login-box">
            <h1>Accedi</h1>
            <div id="login-form">
                <input type="text" placeholder="Username" id="user" name="user" required>
                <input type="password" placeholder="Password" id="password" name="password" required>
                <button type="submit">Accedi</button>
            </div>
        </div>
    
        <div id="signup-box">
            <h3>Non hai un account?</h3>
            <button onclick="window.location.href='/signup'">Creane uno ora</button>
        </div>
    </body>
</html>