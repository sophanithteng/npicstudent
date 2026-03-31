<?
function isAlreadyRegistered($db, $national_id) {
    $sql = "SELECT COUNT(*) as total FROM tbl_registration WHERE national_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $national_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] > 0;
}
function getStudentByNationalId($db, $national_id) {
    $sql = "SELECT * FROM tbl_registration WHERE national_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $national_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function registerStudent($db, $data) {
    $sql = "INSERT INTO tbl_registration (
        profile_pic, full_name_kh, full_name_en, gender, date_of_birth,
        place_of_birth, nationality, religion, marital_status, national_id,
        phone_number, email_address, current_residence,
        emergency_contact_name, emergency_contact_phone, account_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssss",
        $data['profile_pic'],
        $data['full_name_kh'],
        $data['full_name_en'],
        $data['gender'],
        $data['date_of_birth'],
        $data['place_of_birth'],
        $data['nationality'],
        $data['religion'],
        $data['marital_status'],
        $data['national_id'],
        $data['phone_number'],
        $data['email_address'],
        $data['current_residence'],
        $data['emergency_contact_name'],
        $data['emergency_contact_phone'],
        $data['account_status']
    );

    return $stmt->execute();
}

function updateStudent($db, $data) {
    $sql = "UPDATE tbl_registration SET
        profile_pic=?, full_name_kh=?, full_name_en=?, gender=?, date_of_birth=?,
        place_of_birth=?, nationality=?, religion=?, marital_status=?,
        phone_number=?, email_address=?, current_residence=?,
        emergency_contact_name=?, emergency_contact_phone=?, account_status=?
        WHERE national_id=?";

    $stmt = $db->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssss",
        $data['profile_pic'],
        $data['full_name_kh'],
        $data['full_name_en'],
        $data['gender'],
        $data['date_of_birth'],
        $data['place_of_birth'],
        $data['nationality'],
        $data['religion'],
        $data['marital_status'],
        $data['phone_number'],
        $data['email_address'],
        $data['current_residence'],
        $data['emergency_contact_name'],
        $data['emergency_contact_phone'],
        $data['account_status'],
        $data['national_id']
    );

    return $stmt->execute();
}

?>