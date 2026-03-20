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

function getUsers()
{
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE level <> "admin" OR level IS NULL');
    $query->execute();
    $result = $query->get_result();
    return $result;
}

function deleteUser($id)
{
    global $db;
    $query = $db->prepare('SELECT profile_pic FROM tbl_users WHERE id = ?');
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    $userData = $result->fetch_object();

    if ($userData) {
        if (
            !empty($userData->profile_pic) &&
            $userData->profile_pic !== './assets/images/emptyuser.png' &&
            file_exists($userData->profile_pic)
        ) {
            unlink($userData->profile_pic);
        }

        $delete = $db->prepare('DELETE FROM tbl_users WHERE id = ?');
        $delete->bind_param('i', $id);
        $delete->execute();
        return $db->affected_rows > 0;
    }
    return false;
}

function editUser($id, $data, $file = null)
{
    global $db;
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $oldQuery = $db->prepare("SELECT profile_pic FROM tbl_users WHERE id = ?");
        $oldQuery->bind_param("i", $id);
        $oldQuery->execute();
        $oldPic = $oldQuery->get_result()->fetch_object()->profile_pic;
        if (!empty($oldPic) && $oldPic !== './assets/images/emptyuser.png' && file_exists($oldPic)) {
            unlink($oldPic);
        }
        $targetDir = "assets/images/";
        $fileName = "profile_" . time() . "_" . basename($file["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            $data['profile_pic'] = $targetFile;
        }
    }

    if (isset($data['profile_pic'])) {
        $sql = "UPDATE tbl_users SET name = ?, username = ?, level = ?, profile_pic = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssi", $data['name'], $data['username'], $data['level'], $data['profile_pic'], $id);
    } else {
        $sql = "UPDATE tbl_users SET name = ?, username = ?, level = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssi", $data['name'], $data['username'], $data['level'], $id);
    }
    $stmt->execute();
    return $stmt->affected_rows >= 0;
}
