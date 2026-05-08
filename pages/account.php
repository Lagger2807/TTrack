<?php

include_once __DIR__ . '/../src/class_common.php';
$common = new Common();

$user_id = $common->get_user_accesses();
$user = $common->get_user_data();
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
        <p>Ciao, <?php echo $user->name; ?></p>
        <p>Le tue attività:</p>
        <div>
            <?php foreach($user_id as $access) { ?>
                <p><?php echo $access->creation_date; ?></p>
            <?php } ?>
        </div>
        <button id="logout-button">Logout</button>
    </body>
</html>