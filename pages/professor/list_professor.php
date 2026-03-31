<?php
// --- 1. HANDLE DELETE ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (deleteProfessor($id)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            setTimeout(function() {
                Swal.fire('Deleted!', 'Professor removed.', 'success').then(() => {
                    window.location.href = '?page=professor/list_professor';
                });
            }, 100);
        </script>";
    }
}

// --- 2. HANDLE EDIT ---
if (isset($_POST['btnSaveUpdate'])) {
    $id = $_POST['id'] ?? null; // Match the hidden input name below
    $old = $id ? getProfessorById($id) : null;

    $data = [
        'pro_name'        => !empty($_POST['pro_name']) ? trim($_POST['pro_name']) : ($old['pro_name'] ?? ''),
        'pro_designation' => !empty($_POST['pro_designation']) ? trim($_POST['pro_designation']) : ($old['pro_designation'] ?? ''),
        'pro_description' => !empty($_POST['pro_description']) ? trim($_POST['pro_description']) : ($old['pro_description'] ?? ''),
        'dept_id'         => !empty($_POST['dept_id']) ? $_POST['dept_id'] : ($old['dept_id'] ?? 0),
    ];

    $photo = (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) ? $_FILES['profile_pic'] : null;

    if ($id && editProfessor($id, $data, $photo)) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            setTimeout(function() {
                Swal.fire('Updated!', 'Professor details saved.', 'success').then(() => {
                    window.location.href = '?page=professor/list_professor';
                });
            }, 100);
        </script>";
    }
}
?>

<div class="container py-4">
    <div class="row align-items-center mb-5 g-3">
        <div class="col-12 col-md-7 text-center text-md-start">
            <h3 class="fw-bold text-body mb-1">Professor Management</h3>
            <p class="text-secondary small mb-0">Manage and view all faculty members.</p>
        </div>
        <div class="col-12 col-md-5 text-center text-md-end">
            <a href="./?page=professor/create_professor" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center">
                <i class="bi bi-person-plus-fill me-2"></i>
                <span>Add New Professor</span>
            </a>
        </div>
    </div>

    <div class="professor-list">
        <?php
        $professors = getProfessors();
        while ($row = $professors->fetch_object()):
            $photo = !empty($row->pro_image) ? $row->pro_image : './assets/images/emptyuser.png';
        ?>
            <div class="d-flex flex-column flex-md-row align-items-center bg-body border shadow-sm rounded-4 p-3 p-md-4 mb-3 transition-hover">
                <div class="d-flex align-items-center mb-3 mb-md-0" style="width: 100%; max-width: 450px;">
                    <div class="position-relative me-3">
                        <img src="<?php echo $photo; ?>" class="rounded-circle border border-2 border-primary-subtle shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                    </div>
                    <div class="text-start">
                        <div class="fw-bold text-body fs-5"><?php echo htmlspecialchars($row->pro_name); ?></div>
                        <div class="text-primary small fw-medium"><?php echo htmlspecialchars($row->pro_designation); ?></div>
                        <div class="text-muted small"><?php echo htmlspecialchars($row->dept_name ?? 'General'); ?></div>
                    </div>
                </div>

                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                        onclick="window.openProfessorEditModal(this)"
                        data-id="<?php echo $row->pro_id; ?>"
                        data-name="<?php echo htmlspecialchars($row->pro_name); ?>"
                        data-designation="<?php echo htmlspecialchars($row->pro_designation); ?>"
                        data-description="<?php echo htmlspecialchars($row->pro_description); ?>"
                        data-dept="<?php echo $row->dept_id; ?>"
                        data-photo="<?php echo $photo; ?>">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>

                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                        onclick="window.confirmProfessorDelete(<?php echo $row->pro_id; ?>)">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="modal fade" id="editProfessorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <form id="editProfessorForm" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="" id="edit_preview" class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                            <label for="edit_photo_input" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2" style="cursor: pointer;">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                            <input type="file" name="profile_pic" id="edit_photo_input" hidden onchange="window.previewProfessorImage(this)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Professor Name</label>
                        <input type="text" name="pro_name" id="edit_name" class="form-control rounded-pill px-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Designation</label>
                        <input type="text" name="pro_designation" id="edit_designation" class="form-control rounded-pill px-3">
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Description</label>
                        <textarea name="pro_description" id="edit_description" class="form-control rounded-4 px-3" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Department</label>
                        <select name="dept_id" id="edit_dept" class="form-select rounded-pill px-3">
                            <?php
                            $depts = getDepartments(); // Ensure this function exists
                            while ($d = $depts->fetch_object()): ?>
                                <option value="<?= $d->dept_id ?>"><?= $d->dept_name ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="btnSaveUpdate" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: all 0.3s ease;
    }

    .transition-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
        border-color: var(--bs-primary-border-subtle) !important;
    }
</style>