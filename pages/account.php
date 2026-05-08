<?php

include_once __DIR__ . '/../src/class_common.php';
$common = new Common();

$user_id = $common->get_user_accesses();
$user = $common->get_user_data();

$greetings = ['Ciao', 'Salve', 'Benvenuto'];
$greeting = $greetings[array_rand($greetings)];

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Account</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="/assets/js/common.js"></script>
        <script src="/assets/js/<?php echo basename(__FILE__, '.php'); ?>.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"/>
        <link rel="stylesheet" href="/assets/css/core.css">
        <link rel="stylesheet" href="/assets/css/<?php echo basename(__FILE__, '.php'); ?>.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <toolbar>
            <a href="/"><i class="fa-solid fa-house"></i></a>
            <a href="/account"><i class="fa-solid fa-user"></i></a>
        </toolbar>
        <section>
            <div class="tile-background">
                <span><?php echo $greeting . ', ' . $user->name; ?></span>
                
            </div>
        </section>
        <section>
            <div class="tile-background">
                <p>Le tue attività:</p>
                <div>
                    <?php foreach($user_id as $access) { ?>
                        <p><?php echo $access->creation_date; ?></p>
                    <?php } ?>
                </div>
                <button id="logout-button">Logout</button>
            </div>
        </section>
    </body>
</html>