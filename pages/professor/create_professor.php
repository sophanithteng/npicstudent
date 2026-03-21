<?php
$pro_name = $pro_designation = $pro_description = $dept_id = '';
$nameErr = $designationErr = $descriptionErr = $deptErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pro_name = trim($_POST['pro_name'] ?? '');
    $pro_designation = trim($_POST['pro_designation'] ?? '');
    $pro_description = trim($_POST['pro_description'] ?? '');
    $dept_id = $_POST['dept_id'] ?? '';

    if (empty($pro_name)) $nameErr = 'Please input professor name!';
    if (empty($pro_designation)) $designationErr = 'Please select designation!';
    if (empty($pro_description)) $descriptionErr = 'Please input biography!';
    if (empty($dept_id)) $deptErr = 'Please select department!';

    if (empty($nameErr) && empty($designationErr) && empty($descriptionErr) && empty($deptErr)) {
        try {
            $pro_image = (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK)
                ? $_FILES['photo']
                : null;

            if (createProfessor($pro_name, $pro_designation, $pro_description, $dept_id, $pro_image)) {

                $pro_name = $pro_designation = $pro_description = $dept_id = '';

                echo '<div class="alert alert-success">Professor added successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Failed to save professor record.</div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}
?>

<style>
    .user-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 20px;
    }

    .profile-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }

    .profile-wrapper img {
        transition: opacity 0.3s ease;
    }

    .profile-wrapper:hover img {
        opacity: 0.8;
    }

    .camera-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0d6efd;
        color: white;
        border: 4px solid var(--bs-body-bg);
        transition: transform 0.2s ease;
        cursor: pointer;
    }

    .profile-wrapper:hover .camera-icon {
        transform: scale(1.1);
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card user-card border-0 shadow-lg bg-body-tertiary p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-body">Add New Professor</h3>
                    <p class="text-muted small">Fill in the professional details below.</p>
                </div>

                <form method="post" action="./?page=professor/create_professor" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <div class="profile-wrapper">
                            <img id="preview" src="./assets/professor_images/emptyuser.png"
                                class="rounded-circle border border-4 border-primary-subtle shadow-sm w-100 h-100"
                                style="object-fit: cover; cursor: pointer;"
                                onclick="document.getElementById('profileUpload').click();">

                            <label for="profileUpload" class="camera-icon rounded-circle position-absolute bottom-0 end-0">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                        </div>
                        <input name="photo" type="file" id="profileUpload" hidden accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Professor Full Name</label>
                        <input name="pro_name" value="<?php echo htmlspecialchars($pro_name) ?>" type="text"
                            class="form-control form-control-lg bg-body <?php echo empty($nameErr) ? '' : 'is-invalid' ?>"
                            placeholder="e.g. Dr. John Smith">
                        <div class="invalid-feedback"><?php echo $nameErr ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Designation (តួនាទី)</label>
                        <select name="pro_designation"
                            class="form-select form-select-lg bg-body <?php echo empty($designationErr) ? '' : 'is-invalid' ?>">

                            <option value="" selected disabled>-- ជ្រើសរើសតួនាទី --</option>

                            <option value="ប្រធានមហាវិទ្យាល័យ" <?php echo ($pro_designation == 'ប្រធានមហាវិទ្យាល័យ') ? 'selected' : ''; ?>>
                                ប្រធានមហាវិទ្យាល័យ (Dean)
                            </option>

                            <option value="អនុប្រធានមហាវិទ្យាល័យ" <?php echo ($pro_designation == 'អនុប្រធានមហាវិទ្យាល័យ') ? 'selected' : ''; ?>>
                                អនុប្រធានមហាវិទ្យាល័យ (Vice Dean)
                            </option>

                            <option value="ប្រធានដេប៉ាតឺម៉ង់" <?php echo ($pro_designation == 'ប្រធានដេប៉ាតឺម៉ង់') ? 'selected' : ''; ?>>
                                ប្រធានដេប៉ាតឺម៉ង់ (Head of Department)
                            </option>

                            <option value="សាស្ត្រាចារ្យ" <?php echo ($pro_designation == 'សាស្ត្រាចារ្យ') ? 'selected' : ''; ?>>
                                សាស្ត្រាចារ្យ (Professor)
                            </option>

                        </select>
                        <div class="invalid-feedback"><?php echo $designationErr ?></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase">Department</label>

                        <select name="dept_id"
                            class="form-select <?php echo empty($deptErr) ? '' : 'is-invalid' ?>">

                            <option value="" disabled selected>-- Select Department --</option>

                            <?php
                            $dept_query = "SELECT dept_id, dept_name FROM tbl_department";
                            $dept_result = $db->query($dept_query);

                            while ($dept = $dept_result->fetch_assoc()):
                            ?>
                                <option value="<?php echo $dept['dept_id']; ?>"
                                    <?php echo ($dept_id == $dept['dept_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept['dept_name']); ?>
                                </option>
                            <?php endwhile; ?>

                        </select>

                        <div class="invalid-feedback"><?php echo $deptErr ?></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase">
                            Biography / Background
                        </label>

                        <textarea name="pro_description"
                            class="form-control <?php echo empty($descriptionErr) ? '' : 'is-invalid' ?>"
                            rows="4"
                            placeholder="Enter professor background..."><?php echo htmlspecialchars($pro_description); ?></textarea>

                        <div class="invalid-feedback"><?php echo $descriptionErr ?></div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold shadow-sm">
                            Save Professor Profile
                        </button>
                        <a href="?page=professor/electrical" class="btn btn-link btn-sm text-decoration-none text-muted mt-2">
                            Back to the Electrical
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('profileUpload').onchange = function(evt) {
        const [file] = this.files;
        if (file) {
            document.getElementById('preview').src = URL.createObjectURL(file);
        }
    }
</script>