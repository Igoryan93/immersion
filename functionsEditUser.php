<?php

session_start();

require "functions.php";

$user_id   = $_SESSION['editUser'];
$username  = $_POST['username'];
$job_title = $_POST['job_title'];
$phone     = $_POST['phone'];
$address   = $_POST['address'];

edit_info($user_id, $username, $job_title, $phone, $address);