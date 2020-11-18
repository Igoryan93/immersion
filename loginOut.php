<?php

session_start();

require "functions.php";

unset($_SESSION['user'], $_SESSION['editUser']);

redirect_to("page_login.php");