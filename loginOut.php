<?php

session_start();

require "functions.php";

unset($_SESSION['user']);

redirect_to("page_login.php");