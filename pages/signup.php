<!DOCTYPE html>
<html>
    <head>
        <title>Registrati</title>
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
        <div id="signup-box">
            <h1>Registrati</h1>
            <div id="signup-form">
                <input type="text" placeholder="Username" id="username" name="username" required>
                <input type="email" placeholder="Email" id="email" name="email" required>
                <input type="password" placeholder="Password" id="password" name="password" required>
                <button type="submit">Registrati</button>
            </div>
        </div>

        <div id="login-box">
            <h3>Hai già un account?</h3>
            <button onclick="window.location.href='/login'">Accedi</button>
        </div>
    </body>
</html>