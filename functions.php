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
    $imageName = uniqid() . $image['image']['name'];
    $oldName = get_user_by_id($id);
    $oldImage = 'img/demo/avatars/' . $oldName['image'];
    if(isset($image)) {
        if(file_exists($oldImage)) {
            unlink($oldImage);
        }
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



// Checking image
function has_image($user_id, $image) {
    $getImage = get_user_by_id($user_id);

    if(empty($getImage['image']) || $getImage['image'] !== $image) {
        return false;
    } else {
        return $getImage;
    }
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

// Check author
function is_author($logged_user_id, $edit_user_id) {
    $user = $_SESSION['user'];

    if($logged_user_id !== $edit_user_id && $user['role'] !== 'admin') {
        set_flash_message('danger', 'Можно редактировать только свой профиль');
        redirect_to('users.php');
        exit;
    }
}

// Get user by id
function get_user_by_id($id) {
   $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
   $sql = "SELECT * FROM users WHERE id=:id";
   $statement = $db->prepare($sql);
   $statement->execute([
        'id' => $id
   ]);
   $user = $statement->fetch(PDO::FETCH_ASSOC);
   return $user;
}

// Edit info logged user
function edit_info($user_id, $username, $job_title, $phone, $address) {
    $id = $user_id['id'];
    edit($id, $username, $job_title, $phone, $address);
    set_flash_message('success', 'Пользователь с E-mail ' . $user_id['email'] . ' был успешно изменен!');
    redirect_to('edit.php?id=' . $id);
}

// Edit security profile
function edit_credantials($user_id, $email, $password) {
    $userId = get_user_by_id($user_id['id']);
    $userEmail = get_user_by_email($email);

    if ($userEmail[0]['id'] !== $userId['id'] && $userEmail[0]['id'] !== null) {
        if($userId['email'] === $email) {
            set_flash_message('danger', 'Этот Email уже занят');
            redirect_to('security.php?id=' . $user_id['id']);
            exit;
        }
        set_flash_message('danger', 'Этот Email уже занят');
        redirect_to('security.php?id=' . $user_id['id']);
        exit;
    }

    $db = new PDO("mysql:host=localhost; dbname=registration", "root", "root");
    $sql = "UPDATE users SET email=:email, password=:password WHERE id=:id";
    $statement = $db->prepare($sql);
    $statement->execute([
        'id'       => $user_id['id'],
        'email'    => $email,
        'password' => $password
    ]);

    set_flash_message('success', 'Профиль успешно обновлен');
    redirect_to('security.php?id=' . $user_id['id']);

}