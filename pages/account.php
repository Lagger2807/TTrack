<?php

include_once __DIR__ . '/../src/class_common.php';
$common = new Common();

$user_id = $common->get_user_accesses();
$user = $common->get_user_data();

$greetings = ['Ciao', 'Salve', 'Benvenuto'];
$greeting = $greetings[array_rand($greetings)];

$OSes = [
    'Windows' => '<i style="color: #0078D4;" class="fa-brands fa-windows"></i>',
    'Linux' => '<i style="color: #101820;" class="fa-brands fa-linux"></i>',
    'Mac OS' => '<i style="color: #101820;" class="fa-brands fa-apple"></i>',
    'Android' => '<i style="color: #3DDC84;" class="fa-brands fa-android"></i>',
    'iOS' => '<i style="color: #101820;" class="fa-brands fa-apple"></i>',
    'iPhone' => '<i style="color: #101820;" class="fa-brands fa-apple"></i>',
    'iPad' => '<i style="color: #101820;" class="fa-brands fa-apple"></i>'
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
                <div id="login-history">
                    <?php foreach($user_id as $access) { ?>
                        <p class="login-entry">
                            <?php if ($access->token === $_COOKIE['ttrack_login']) {
                                echo '<strong class="current-device">Dispositivo corrente</strong>';
                            }
                            ?>
                            <?php if($access->user_agent != '') {
                                echo get_OS_icon($access->user_agent, $OSes);
                                echo get_formatted_user_agent($access->user_agent);
                            } else {
                                echo 'Dispositivo sconosciuto';
                            } ?>
                            <span class="login-date"><?php echo $access->creation_date; ?></span>
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

function get_OS_icon($userAgent, $OSes) {
    foreach ($OSes as $os => $icon) {
        if (strpos(strtolower($userAgent), strtolower($os)) !== false) {
            return $icon;
        }
    }

    return '<i class="fa-solid fa-question"></i>'; // fallback icon
}

function get_formatted_user_agent($userAgent) {
    $browser = strpos($userAgent, 'Firefox') !== false ? 'Firefox' :
               (strpos($userAgent, 'Chrome') !== false ? 'Chrome' :
               (strpos($userAgent, 'Safari') !== false ? 'Safari' :
               (strpos($userAgent, 'Edge') !== false ? 'Edge' : 'Browser sconosciuto')));

    $OS = strpos($userAgent, 'Windows') !== false ? 'Windows' :
          (strpos($userAgent, 'Linux') !== false ? 'Linux' :
          (strpos($userAgent, 'Mac OS') !== false ? 'Mac OS' :
          (strpos($userAgent, 'Android') !== false ? 'Android' :
          (strpos($userAgent, 'iOS') !== false ? 'iOS' : 'Sistema operativo sconosciuto'))));

    return $browser . ' su ' . $OS;
}

?>