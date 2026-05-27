<?php

include_once __DIR__ . '/../src/class_common.php';
$common = new Common();

$user_id = $common->get_user_accesses();
$user = $common->get_user_data();

$greetings = ['Ciao', 'Salve', 'Benvenuto'];
$greeting = $greetings[array_rand($greetings)];

$OSes = [
    'Windows' => '<i class="fa-brands fa-windows"></i>',
    'Linux' => '<i class="fa-brands fa-linux"></i>',
    'Mac OS' => '<i class="fa-brands fa-apple"></i>',
    'Android' => '<i class="fa-brands fa-android"></i>',
    'iOS' => '<i class="fa-brands fa-apple"></i>',
    'iPhone' => '<i class="fa-brands fa-apple"></i>',
    'iPad' => '<i class="fa-brands fa-apple"></i>'
];

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
                <span><button id="edit-name-button" onclick="EditName()"><i class="fa-regular fa-pen-to-square"></i></button></span>
            </div>
        </section>
        <section>
            <div class="tile-background">
                <p>I tuoi accessi:</p>
                <div>
                    <?php foreach($user_id as $access) { ?>
                        <p>
                            <?php echo $access->creation_date; ?> da:
                            <?php if ($access->token === $_COOKIE['ttrack_login']) {
                                echo '<strong>(Questo dispositivo)</strong>';
                            }
                            ?>
                            <?php if($access->user_agent != '') {
                                echo getOSIcon($access->user_agent, $OSes);
                                echo $access->user_agent;
                            } ?>
                        </p>
                    <?php } ?>
                </div>
                <button id="logout-button" onclick="Logout()">Disconettiti</button>
                <button id="logout-all-button" onclick="LogoutAll()">Disconnetti da tutti i dispositivi</button>
            </div>
        </section>
    </body>
</html>

<?php

function getOSIcon($userAgent, $OSes) {
    foreach ($OSes as $os => $icon) {
        if (strpos(strtolower($userAgent), strtolower($os)) !== false) {
            return $icon;
        }
    }

    return '<i class="fa-solid fa-question"></i>'; // fallback icon
}

?>