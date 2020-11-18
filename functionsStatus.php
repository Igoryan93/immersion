<?php
session_start();

require "functions.php";

$userId = $_SESSION['editUser']['id'];
$status = $_POST['status'];

set_status($userId, $status);

set_flash_message('success', 'Профиль успешно обновлен');

redirect_to('page_profile.php?id=' . $userId);