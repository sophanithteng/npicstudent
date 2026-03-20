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

    try {
        $picture_path = uploadpicture($picture, $user);
    } catch (Exception $e) {
        return false; 
    }

    if ($picture_path && !empty($user->profile_pic) && file_exists($user->profile_pic)) {
        @unlink($user->profile_pic); 
    }

    $query = $db->prepare('UPDATE tbl_users SET profile_pic = ? WHERE id = ?');
    $query->bind_param('si', $picture_path, $user->id); 
    $query->execute();

    return $db->affected_rows > 0;
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

function uploadpicture($file, $user)
{
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $maxSize = 2 * 1024 * 1024; 

    $fileType = $file['type'];
    $fileSize = $file['size'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    if ($fileError !== 0) {
        throw new Exception("Upload Error: Code " . $fileError);
    }

    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception("Invalid file type! Only JPG and PNG are allowed.");
    }

    if ($fileSize > $maxSize) {
        throw new Exception("File is too large! Maximum size is 2MB.");
    }

    $dir = 'assets/images/';

    if (is_object($user) && isset($user->username)) {
        $usernameString = $user->username;
    } else {
        $usernameString = (string)$user; 
    }

    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $cleanUsername = preg_replace('/[^A-Za-z0-9]/', '', $usernameString);
    $fileNameNew = "profile_" . $cleanUsername . "_" . time() . "." . $fileExt; 
    $fileDestination = $dir . $fileNameNew;

    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    if (move_uploaded_file($fileTmpName, $fileDestination)) {
        return $fileDestination; 
    } else {
        throw new Exception("Failed to move file. Check folder permissions.");
    }
}

function isAdmin()
{
    $user = loggedInUser();
    return $user && $user->level === 'admin';
}
