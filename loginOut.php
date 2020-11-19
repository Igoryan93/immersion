<?php

session_start();

unset($_SESSION['user'], $_SESSION['editUser']);

header("Location: page_register.php");