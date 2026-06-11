<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    #region Create .env file
    $env = file_get_contents(__DIR__ . '/../.env.example');

    $env = str_replace(
        ['{{DB_Host}}', '{{DB_User}}', '{{DB_Pass}}', '{{DB_Name}}'],
        [
            $_POST['db_host'],
            $_POST['db_user'],
            $_POST['db_pass'],
            $_POST['db_name']
        ],
        $env
    );

    $result = file_put_contents(__DIR__ . '/../.env', $env);

    if ($result === false) {
        echo 'Failed to write file.<br>';
        echo 'Path: ' . __DIR__ . '/../.env<br>';
        var_dump(error_get_last());
        exit;
    }
    #endregion

    #region Create .htaccess file
    $htaccess = '
    RewriteEngine On

    RewriteCond %{REQUEST_URI} ^/installer/install\.php$
    RewriteCond %{DOCUMENT_ROOT}/installer/install.php !-f
    RewriteRule ^ / [R=302,L]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.+)$ index.php/$1 [QSA,L]';

    $result = file_put_contents(__DIR__ . '/../.htaccess', $htaccess);

    if ($result === false) {
        echo 'Failed to write file.<br>';
        echo 'Path: ' . __DIR__ . '/../.htaccess<br>';
        var_dump(error_get_last());
        exit;
    }
    #endregion

    #region Create database and tables
    try {
        $pdo = new PDO(
            'mysql:host=' . $_POST['db_host'] . ';charset=utf8mb4',
            $_POST['db_user'],
            $_POST['db_pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $dbname = $_POST['db_name'];
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $pdo->exec("USE `$dbname`");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `users` (
                `id`         INT          NOT NULL AUTO_INCREMENT,
                `login_name` VARCHAR(255) NOT NULL,
                `email`      TEXT         NOT NULL,
                `name`       TEXT         NOT NULL,
                `password`   TEXT         NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `login_name` (`login_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `tracked_times` (
                `id`         INT  NOT NULL AUTO_INCREMENT,
                `date`       DATE NOT NULL,
                `start_time` TIME NOT NULL,
                `end_time`   TIME NOT NULL,
                `user`       INT  NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `users_login` (
                `id`            INT  NOT NULL AUTO_INCREMENT,
                `creation_date` DATE NOT NULL,
                `token`         TEXT NOT NULL,
                `user_id`       INT  NOT NULL,
                `user_agent`    TEXT NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    } catch (PDOException $e) {
        echo 'Error initializing database: ' . $e->getMessage() . '<br>';
        exit;
    }
    #endregion

    echo 'Installation completed. Delete or rename the installer folder and refresh the page to start using the application.';
    exit;
}

?>

<html>
    <head>
        <title>Installation...</title>
        <link rel="stylesheet" type="text/css" href="install.css">
    </head>
    <body>
        <div id="database-setup">
            <p>Enter below the database connection details. The installer will create the database and tables for you.</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="db_host">Database host:</label><br>
                <input type="text" id="db_host" name="db_host" value="localhost" required><br><br>
                <label for="db_user">Database username:</label><br>
                <input type="text" id="db_user" name="db_user" required><br><br>
                <label for="db_pass">Database password:</label><br>
                <input type="password" id="db_pass" name="db_pass"><br><br>
                <label for="db_name">Database name:</label><br>
                <input type="text" id="db_name" name="db_name" value="ttrack" required><br><br>
                <input type="submit" value="Install">
            </form>
        </div>
    </body>
</html>