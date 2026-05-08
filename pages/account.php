<?php

include_once __DIR__ . '/../src/class_common.php';
$common = new Common();

$user_id = $common->get_user_accesses(1);

var_dump($user_id);