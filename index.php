<?php

//If installation script is present, redirect to it
if(is_dir('installer')) {
    header('Location: installer/install.php');
    exit();
}

require __DIR__ . '/router.php';
require __DIR__ . '/login.php';