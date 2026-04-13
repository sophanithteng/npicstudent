<?php
// ===== HANDLE SUBMIT =====
if (isset($_POST['btnSaveReg'])) {

    // ===== HANDLE RELIGION =====
    $religion = $_POST['religion'] ?? '';
    if ($religion === 'Other') {
        $religion = $_POST['religion_other'] ?? '';
    }

    if (!empty($_FILES['profile_pic']['name'])) {

        $filename = time() . '_' . $_FILES['profile_pic']['name'];

        $uploadPath = __DIR__ . '/../assets/images/';

        // create folder if not exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        move_uploaded_file(
            $_FILES['profile_pic']['tmp_name'],
            $uploadPath . $filename
        );
    } else {
        $filename = 'default.png';
    }
    // ===== PREPARE DATA =====
    $data = [
        'profile_pic' => $filename,
        'full_name_kh' => $_POST['full_name_kh'] ?? '',
        'full_name_en' => $_POST['full_name_en'] ?? '',
        'gender' => $_POST['gender'] ?? '',
        'date_of_birth' => $_POST['date_of_birth'] ?? '',
        'place_of_birth' => $_POST['place_of_birth'] ?? '',
        'nationality' => $_POST['nationality'] ?? '',
        'religion' => $religion,
        'marital_status' => $_POST['marital_status'] ?? '',
        'national_id' => $_POST['national_id'] ?? '',
        'phone_number' => $_POST['phone_number'] ?? '',
        'email_address' => $_POST['email_address'] ?? '',
        'current_residence' => $_POST['current_residence'] ?? '',
        'emergency_contact_name' => $_POST['emergency_contact_name'] ?? '',
        'emergency_contact_phone' => $_POST['emergency_contact_phone'] ?? '',
        'account_status' => 'Active'
    ];

    // ===== CHECK EXIST =====
    $existing = getStudentByNationalId($db, $data['national_id']);

    if ($existing) {
        $result = updateStudent($db, $data);
        $message = "Updated successfully!";
    } else {
        $result = registerStudent($db, $data);
        $message = "Registered successfully!";
    }

    echo "<div class='alert alert-success'>$message</div>";
}

// ===== LOAD EXISTING DATA =====
$student = null;
if (isset($_GET['national_id'])) {
    $student = getStudentByNationalId($db, $_GET['national_id']);
}
?>

<!-- ===== FORM ===== -->

<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4 p-4">

        <h4 class="mb-4 fw-bold text-center">Student Registration</h4>

        <form method="POST" enctype="multipart/form-data">

            <!-- Profile -->
            <div class="text-center mb-4">
                <div style="position:relative; display:inline-block;">
                    <label for="profileUpload" style="cursor:pointer;">
                        <img src="<?= !empty($student['profile_pic']) && $student['profile_pic'] !== 'default.png' ? './assets/images/' . $student['profile_pic'] : './assets/images/emptyuser.png' ?>"
                            id="preview"
                            class="rounded-circle border shadow"
                            style="width:120px;height:120px;object-fit:cover;">

                        <span style="
                            position:absolute;
                            bottom:0;
                            right:0;
                            background:#0d6efd;
                            color:white;
                            border-radius:50%;
                            padding:6px;">📷
                        </span>
                    </label>

                    <input type="file" id="profileUpload" name="profile_pic" hidden>
                </div>

                <p class="small text-muted mt-2">Click image to upload</p>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <label>Full Name (KH)</label>
                    <input type="text" name="full_name_kh" class="form-control"
                        value="<?= $student['full_name_kh'] ?? '' ?>">
                </div>

                <div class="col-md-6">
                    <label>Full Name (EN)</label>
                    <input type="text" name="full_name_en" class="form-control"
                        value="<?= $student['full_name_en'] ?? '' ?>">
                </div>

                <div class="col-md-4">
                    <label>Gender</label>
                    <select name="gender" class="form-select">
                        <option value="Male" <?= ($student['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($student['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= ($student['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control"
                        value="<?= $student['date_of_birth'] ?? '' ?>">
                </div>

                <div class="col-md-4">
                    <label>National ID</label>
                    <input type="text" name="national_id" class="form-control"
                        value="<?= $student['national_id'] ?? '' ?>">
                </div>

                <div class="col-md-6">
                    <label>Place of Birth</label>
                    <textarea name="place_of_birth" class="form-control"><?= $student['place_of_birth'] ?? '' ?></textarea>
                </div>

                <div class="col-md-6">
                    <label>Current Residence</label>
                    <textarea name="current_residence" class="form-control"><?= $student['current_residence'] ?? '' ?></textarea>
                </div>

                <div class="col-md-4">
                    <label>Nationality</label>
                    <input type="text" name="nationality" class="form-control"
                        value="<?= $student['nationality'] ?? 'Cambodian' ?>">
                </div>

                <div class="col-md-4">
                    <label>Religion</label>
                    <select name="religion" id="religionSelect" class="form-select">
                        <option value="">-- Select Religion --</option>
                        <option value="Buddhism" <?= ($student['religion'] ?? '') == 'Buddhism' ? 'selected' : '' ?>>Buddhism</option>
                        <option value="Christianity" <?= ($student['religion'] ?? '') == 'Christianity' ? 'selected' : '' ?>>Christianity</option>
                        <option value="Islam" <?= ($student['religion'] ?? '') == 'Islam' ? 'selected' : '' ?>>Islam</option>
                        <option value="Hinduism" <?= ($student['religion'] ?? '') == 'Hinduism' ? 'selected' : '' ?>>Hinduism</option>
                        <option value="Other" <?= !in_array(($student['religion'] ?? ''), ['Buddhism', 'Christianity', 'Islam', 'Hinduism']) ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="col-md-4" id="otherReligionBox" style="display:none;">
                    <label>Specify Religion</label>
                    <input type="text" name="religion_other" class="form-control"
                        value="<?= $student['religion'] ?? '' ?>">
                </div>

                <div class="col-md-4">
                    <label>Marital Status</label>
                    <select name="marital_status" class="form-select">
                        <option value="Single" <?= ($student['marital_status'] ?? '') == 'Single' ? 'selected' : '' ?>>Single</option>
                        <option value="Married" <?= ($student['marital_status'] ?? '') == 'Married' ? 'selected' : '' ?>>Married</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" name="phone_number" class="form-control"
                        value="<?= $student['phone_number'] ?? '' ?>">
                </div>

                <div class="col-md-6">
                    <label>Email</label>
                    <input type="email" name="email_address" class="form-control"
                        value="<?= $student['email_address'] ?? '' ?>">
                </div>

                <div class="col-md-6">
                    <label>Emergency Contact Name</label>
                    <input type="text" name="emergency_contact_name" class="form-control"
                        value="<?= $student['emergency_contact_name'] ?? '' ?>">
                </div>

                <div class="col-md-6">
                    <label>Emergency Contact Phone</label>
                    <input type="text" name="emergency_contact_phone" class="form-control"
                        value="<?= $student['emergency_contact_phone'] ?? '' ?>">
                </div>

            </div>

            <div class="text-center mt-4">
                <button type="submit" name="btnSaveReg" class="btn btn-primary px-5">
                    <?= $student ? 'Update' : 'Register' ?>
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    document.getElementById('profileUpload').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
        }
    });

    document.getElementById('religionSelect').addEventListener('change', function() {
        const box = document.getElementById('otherReligionBox');
        box.style.display = (this.value === 'Other') ? 'block' : 'none';
    });
</script>