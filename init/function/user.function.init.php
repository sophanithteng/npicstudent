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


function createProfessor($pro_name, $pro_designation, $pro_description, $dept_id, $pro_image)
{
    global $db;

    $image_path = null;

    if (!empty($pro_image['name']) && $pro_image['error'] === 0) {
        $image_path = uploadpicture($pro_image, $pro_name);
    }

    $query = $db->prepare('INSERT INTO tbl_professor
    (pro_name, pro_designation, pro_description, dept_id, pro_image) 
    VALUES (?, ?, ?, ?, ?)');

    if (!$query) {
        return false;
    }

    $query->bind_param(
        'sssis',
        $pro_name,
        $pro_designation,
        $pro_description,
        $dept_id,
        $image_path
    );

    if ($query->execute()) {
        return $query->affected_rows > 0;
    }

    return false;
}

function getProfessors()
{
    global $db;

    $query = "
        SELECT p.*, d.dept_name 
        FROM tbl_professor p
        LEFT JOIN tbl_department d ON p.dept_id = d.dept_id
        ORDER BY p.pro_id DESC
    ";

    return $db->query($query);
}

function deleteProfessor($id)
{
    global $db;

    $query = $db->prepare('SELECT pro_image FROM tbl_professor WHERE pro_id = ?');
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    $data = $result->fetch_object();

    if ($data) {

        if (
            !empty($data->pro_image) &&
            $data->pro_image !== 'assets/professor_images/emptyuser.png' &&
            file_exists($data->pro_image)
        ) {
            unlink($data->pro_image);
        }

        $delete = $db->prepare('DELETE FROM tbl_professor WHERE pro_id = ?');
        $delete->bind_param('i', $id);
        $delete->execute();

        return $db->affected_rows > 0;
    }

    return false;
}

function editProfessor($id, $data, $file = null)
{
    global $db;

    if ($file && $file['error'] === UPLOAD_ERR_OK) {

        $oldQuery = $db->prepare("SELECT pro_image FROM tbl_professor WHERE pro_id = ?");
        $oldQuery->bind_param("i", $id);
        $oldQuery->execute();
        $res = $oldQuery->get_result()->fetch_object();
        $oldImage = $res ? $res->pro_image : '';

        if (!empty($oldImage) && strpos($oldImage, 'emptyuser.png') === false && file_exists($oldImage)) {
            unlink($oldImage);
        }

        $targetDir = "assets/professor_images/";
        if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); } 
        
        $fileName = "pro_" . time() . "_" . basename($file["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            $data['pro_image'] = $targetFile;
        }
    }

    if (isset($data['pro_image'])) {
        $sql = "UPDATE tbl_professor 
                SET pro_name = ?, pro_designation = ?, pro_description = ?, dept_id = ?, pro_image = ?
                WHERE pro_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssisi", 
            $data['pro_name'], 
            $data['pro_designation'], 
            $data['pro_description'], 
            $data['dept_id'], 
            $data['pro_image'], 
            $id
        );
    } else {
        $sql = "UPDATE tbl_professor 
                SET pro_name = ?, pro_designation = ?, pro_description = ?, dept_id = ?
                WHERE pro_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssii", 
            $data['pro_name'], 
            $data['pro_designation'], 
            $data['pro_description'], 
            $data['dept_id'], 
            $id
        );
    }

    $stmt->execute();
    return $stmt->errno === 0; 
}

function getProfessorById($id)
{
    global $db;

    $stmt = $db->prepare("SELECT * FROM tbl_professor WHERE pro_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function getDepartments()
{
    global $db;
    
    $query = "SELECT * FROM tbl_department ORDER BY dept_name ASC";
    $result = $db->query($query);
    
    if (!$result) {
        return false;
    }
    
    return $result;
}