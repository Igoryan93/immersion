<?php

session_start();

require "functions.php";

$user = $_SESSION['user'];

is_not_logged_in($user);

function is_not_logged_in($user) {
    if (isset($user) && empty($user)) {
        redirect_to("login.php");
        exit;
    }
}

$allUsers = select_all_users();



