<?php
header("Content-Type: application/json; charset=UTF-8");

error_reporting(0);
ini_set('display_errors', 0);

include_once '../init/db.init.php';

if (!$db) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

// ✅ Use slug instead of Khmer name
$slug = $_GET['slug'] ?? '';

// ✅ Base query
$query = "
SELECT p.pro_name, p.pro_designation, p.pro_description, p.pro_image, d.dept_name, d.dept_slug
FROM tbl_professor p
JOIN tbl_department d ON p.dept_id = d.dept_id
";

// ✅ Filter by slug
if (!empty($slug)) {
    $slug = $db->real_escape_string($slug);
    $query .= " WHERE d.dept_slug = '$slug'";
}

$result = $db->query($query);

if (!$result) {
    echo json_encode(["error" => $db->error]);
    exit;
}

$professors = [];

while ($row = $result->fetch_assoc()) {
    $professors[] = [
        'name' => $row['pro_name'],
        'role' => $row['pro_designation'],
        'desc' => $row['pro_description'],
        'img'  => !empty($row['pro_image']) 
                    ? $row['pro_image'] 
                    : 'assets/professor_images/emptyuser.png',
        'link' => 'professor/details&name=' . urlencode($row['pro_name']),
        'department' => $row['dept_name'],
        'slug' => $row['dept_slug']
    ];
}

echo json_encode($professors);