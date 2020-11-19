<?php
session_start();

require 'functions.php';

$logged_user_id = $_SESSION['user']['id'];
$edit_user_id = $_GET['id'];

is_author($logged_user_id, $edit_user_id);

$userById = get_user_by_id($edit_user_id);

$_SESSION['editUser'] = $userById;

if ($_SESSION['user']['role'] === 'admin') {
    $logged_user_id = $_SESSION['user']['id'];
}

if (empty($_SESSION['user']) && empty($_SESSION['editUser'])) {
    set_flash_message('danger', 'Необходимо авторизоваться');
    redirect_to('page_login.php');
    exit;
}

if($edit_user_id === null) {
    set_flash_message('danger', 'Можно редактировать только свой профиль');
    redirect_to('users.php');
    exit;
}

delete($_SESSION['editUser']);

if ($_SESSION['user']['role'] === 'admin') {
    set_flash_message('success', 'Пользователь был удален');
    redirect_to("users.php");
    exit;
} else {
    require "loginOut.php";
    redirect_to('page_login.php');
    exit;
}




