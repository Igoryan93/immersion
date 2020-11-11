<?php
// Registration
function get_user_by_email($email){
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $db->prepare($sql);
    $statement->execute(['email' => $email]);
    $user = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $user;
};

function add_user($email, $password) {
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $db->prepare($sql);
    $statement->execute([
        'email'    => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
};

function set_flash_message($name, $message) {
    $_SESSION[$name] = $message;
};

function display_flash_message($name) {
    if(isset($_SESSION[$name])) {
        echo '<div class="alert alert-'. $name .' text-dark" role="alert">' . $_SESSION[$name] . '</div>';
        unset($_SESSION[$name]);
    }
}

function redirect_to($path){
    header('Location: /' . $path);
};

// Auth
function login($email, $password) {
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "SELECT * FROM users WHERE email=:email";
    $statement = $db->prepare($sql);
    $statement->execute([
        'email'  => $email
    ]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if(password_verify($password, $user['password'])) {
        return true;
    } else {
        return false;
    }
}

