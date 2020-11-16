<?php

session_start();

require "functions.php";

$user_id  = $_SESSION['editUser'];
$email    = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

if($user_id['id'] !== $_SESSION['user']['id'] && $_SESSION['user']['role'] !== 'admin'){
    echo 'У вас не достаточно прав'; exit;
}

edit_credantials($user_id, $email, $password);







