<?php
session_start();

require "functions.php";

$data = $_POST;

$image = $_FILES;

$email = get_user_by_email($data['email']);

if (empty($data['email'])) {
    set_flash_message('danger', 'Поле E-mail обязательна для заполнения');
    redirect_to('create_user.php');
    exit;
}

if ($email) {
    set_flash_message('danger', 'Пользователь с E-mail-м ' . $data['email'] . ' уже существует');
    redirect_to('create_user.php');
    exit;
}

$newUser = add_user($data['email'], $data['password']);

$id = $newUser[0]['id'];

edit($id, $data['username'], $data['job_title'], $data['phone'], $data['address']);

set_status($id, $data['status']);

upload_avatar($id, $image);

add_social_links($id, $data['vk'], $data['telegram'], $data['instagram']);

role_default($id, 'user');

set_flash_message('success', 'Пользователь успешно добавлен!');

redirect_to("users.php");
