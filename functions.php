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

    $selectUserById = get_user_by_email($email);
    return $selectUserById;

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
        return $user;
    } else {
        return false;
    }
}

// Select all users
function select_all_users() {
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "SELECT * FROM users";
    $statement = $db->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Edit our information
function edit($id, $username, $job_title, $phone, $address) {
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "UPDATE users SET name=:name, work=:work, tel=:tel, address=:address WHERE id=:id";
    $statement = $db->prepare($sql);
    $statement->execute([
        'id'      => $id,
        'name'    => $username,
        'work'    => $job_title,
        'tel'     => $phone,
        'address' => $address,
    ]);
}

// Edit status
function set_status($id, $status) {
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "UPDATE users SET status=:status WHERE id=:id";
    $statement = $db->prepare($sql);
    $statement->execute([
        'id'      => $id,
        'status' => $status
    ]);
}

// Edit image
function upload_avatar($id, $image) {
    $imageName = uniqid($image['image']['name']);
    if(isset($image)) {
        $uploadFile = 'img/demo/avatars/' . $imageName;
        move_uploaded_file($image['image']['tmp_name'], $uploadFile);
    }
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "UPDATE users SET image=:image WHERE id=:id";
    $statement = $db->prepare($sql);
    $statement->execute([
        'id'      => $id,
        'image' => $imageName
    ]);
}

// Edit socials
function add_social_links($id, $vk, $telegram, $instagram) {
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "UPDATE users SET vk=:vk, telegram=:telegram, instagram=:instagram WHERE id=:id";
    $statement = $db->prepare($sql);
    $statement->execute([
        'id'        => $id,
        'vk'        => $vk,
        'telegram'  => $telegram,
        'instagram' => $instagram
    ]);
}

// Role default
function role_default($id, $role) {
    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "UPDATE users SET role=:role WHERE id=:id";
    $statement = $db->prepare($sql);
    $statement->execute([
        'id'        => $id,
        'role' => $role
    ]);
}
