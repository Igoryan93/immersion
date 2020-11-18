<?php

session_start();

require "functions.php";

$id = $_SESSION['editUser']['id'];
$image = $_FILES;

if($image['image']['error'] == '4') {
    set_flash_message('danger', 'Поле изображение не должно быть пустым');
    redirect_to("media.php?id=" . $id);
    exit;
}

upload_avatar($id, $image);

set_flash_message('success', 'Профиль был успешно обновлен');

redirect_to('page_profile.php?id=' . $id);