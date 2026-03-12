<?php
function isUsernameExist($username)
{
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ?');
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    //one line return so we don't need to add {}
    if ($result->num_rows)
        return true;
    return false;
}

function registerUser($name, $username, $password)
{
    global $db;
    $query = $db->prepare('INSERT INTO tbl_users (name, username, passwd) VALUES (?, ?, ?)');
    $query->bind_param('sss', $name, $username, $password);
    $query->execute();
    //one line return so we don't need to add {}
    if ($db->affected_rows)
        return true;
    return false;
}

function logUserIn($username, $passwd)
{
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ? AND passwd = ?');
    $query->bind_param('ss', $username, $passwd);
    $query->execute();
    $result = $query->get_result();
    //one line return so we don't need to add {}
    if ($result->num_rows)
        return $result->fetch_object();
    return false;
}

function loggedInUser()
{
    global $db;
    // 1. Check if session is NOT set, then return null
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $user_id = $_SESSION['user_id'];
    $query = $db->prepare('SELECT * FROM tbl_users WHERE id = ?');
    $query->bind_param('d', $user_id);

    $query->execute();
    $result = $query->get_result();
    // 4. Check if we found a user in the result
    if ($result->num_rows > 0) {
        return $result->fetch_object();
    }
    return false;
}


function isUserHasPassword($passwd)
{
    global $db;
    $user = loggedInUser();
    $query = $db->prepare(
        "SELECT * FROM tbl_users WHERE id = ? AND passwd = ?"
    );
    $query->bind_param('ss', $user->id, $passwd);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows) {
        return true;
    }
    return false;
}

function setUserNewPassowrd($passwd)
{
    global $db;
    $user = loggedInUser();
    $query = $db->prepare(
        "UPDATE tbl_users SET passwd = ? WHERE id = ?"
    );
    $query->bind_param('ss',  $passwd, $user->id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function changeProfilepicture($picture)
{
    global $db;
    $user = loggedInUser();
    $picture_path = uploadpicture($picture);
    if ($picture_path && $user->profile_pic) {
        unlink($user->profile_pic);
    }
    $query = $db->prepare('UPDATE tbl_users SET profile_pic = ? WHERE id = ?');
    $query->bind_param('sd', $picture_path, $user->id);
    $query->execute();
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function deleteProfilepicture()
{
    global $db;
    $user = loggedInUser();
    if ($user->profile_pic) {
        unlink($user->profile_pic);

        $query = $db->prepare('UPDATE tbl_users SET profile_pic = NULL WHERE id = ?');
        $query->bind_param('d', $user->id);
        $query->execute();
        if ($db->affected_rows) {
            return true;
        }
        return false;
    }
}

function uploadpicture($picture)
{
    $pic_name = $picture['name'];
    $pic_size = $picture['size'];
    $tmp_name = $picture['tmp_name'];
    $error = $picture['error'];

    $dir = './assets/images/';

    $allow_exs = ['jpg', 'png', 'jpeg'];
    $picture_ex = pathinfo($pic_name, PATHINFO_EXTENSION);
    $picture_lowercase_ex = strtolower($picture_ex);

    if (!in_array($picture_lowercase_ex, $allow_exs)) {
        throw new Exception('File extension is not allowed!');
    }
    if ($error !== 0) {
        throw new Exception('Unknown error occurred!');
    }
    if ($pic_size > 5242880) {
        throw new Exception('File size is too large! Maximum allowed is 5MB.');
    }
    $new_picture_name = uniqid("PI-") . '.' . $picture_lowercase_ex;
    $picture_path = $dir . $new_picture_name;
    move_uploaded_file($tmp_name, $picture_path);
    return $picture_path;
}

function isAdmin()
{
    $user = loggedInUser();
    return $user && $user->level === 'admin';
}
