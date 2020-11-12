<?php

session_start();

require "functions.php";

$email    = $_POST['email'];
$password = $_POST['password'];

$user = login($email, $password);

if(empty($user)) {
    set_flash_message('danger', 'Вы ввели неправильный Email или Пароль');
    redirect_to('page_Login.php');
    exit;
}

set_flash_message('success', 'Здравствуйте ' . $email);

$_SESSION['user'] = $user;

redirect_to('users.php');