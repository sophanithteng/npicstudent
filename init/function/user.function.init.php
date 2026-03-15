<?php
function createUser($name, $username, $password, $photo)
{
    global $db;
    $image_path = null;
    if (!empty($photo['name']) && $photo['error'] === 0) {
        $image_path = uploadpicture($photo, $username);
    }

    $query = $db->prepare('INSERT INTO tbl_users (name, username, passwd, profile_pic) VALUES (?, ?, ?, ?)');
    $query->bind_param('ssss', $name, $username, $password, $image_path);
    $query->execute();
    
    if ($db->affected_rows) {
        return true;
    }
    return false;
}

function getUsers(){
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE level <> "admin" OR level IS NULL');
    $query->execute();
    $result = $query->get_result();
    return $result;
}
?>