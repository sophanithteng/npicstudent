<?php
// --- DELETE ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if (deleteProfessor($_GET['id'])) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            setTimeout(function() {
                Swal.fire('Deleted!', 'Professor removed.', 'success').then(() => {
                    window.location.href = '?page=professor/list';
                });
            }, 100);
        </script>";
    }
}
?>

<div class="container py-4">

    <!-- HEADER -->
    <div class="row align-items-center mb-5 g-3">
        <div class="col-12 col-md-7 text-center text-md-start">
            <h3 class="fw-bold text-body mb-1">Professor Management</h3>
            <p class="text-secondary small mb-0">Manage all professors in the system.</p>
        </div>

        <div class="col-12 col-md-5 text-center text-md-end">
            <a href="./?page=professor/create_professor"
                class="btn btn-primary px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center">
                <i class="bi bi-person-plus-fill me-2"></i>
                <span>Add New Professor</span>
            </a>
        </div>
    </div>

    <!-- LIST -->
    <div class="professor-list">
        <?php
        $professors = getProfessors(); // create this function if not yet

        while ($row = $professors->fetch_object()):
            $photo = !empty($row->pro_image)
                ? $row->pro_image
                : './assets/professor_images/emptyuser.png';
        ?>
            <div class="d-flex flex-column flex-md-row align-items-center bg-body border shadow-sm rounded-4 p-3 p-md-4 mb-3 transition-hover">

                <!-- LEFT: PROFILE -->
                <div class="d-flex align-items-center mb-3 mb-md-0" style="width:100%; max-width:400px;">
                    <div class="position-relative me-3">
                        <img src="<?php echo $photo; ?>"
                            class="rounded-circle border border-2 border-primary-subtle shadow-sm"
                            style="width:70px;height:70px;object-fit:cover;">
                    </div>

                    <div class="text-start">
                        <div class="fw-bold text-body fs-5">
                            <?php echo htmlspecialchars($row->pro_name); ?>
                        </div>

                        <div class="small text-primary fw-medium">
                            <?php echo htmlspecialchars($row->pro_designation); ?>
                        </div>

                        <div class="small text-muted">
                            <?php echo htmlspecialchars($row->dept_name ?? 'No Department'); ?>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: ACTION -->
                <div class="ms-auto d-flex gap-2">
                    <!-- EDIT -->
                    <button type="button"
                        class="btn btn-sm btn-outline-primary rounded-pill px-3"
                        onclick="openProfessorEditModal(this)"
                        data-id="<?php echo $row->pro_id; ?>"
                        data-name="<?php echo htmlspecialchars($row->pro_name); ?>"
                        data-designation="<?php echo htmlspecialchars($row->pro_designation); ?>"
                        data-description="<?php echo htmlspecialchars($row->pro_description); ?>"
                        data-dept="<?php echo $row->dept_id; ?>"
                        data-photo="<?php echo $photo; ?>">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>

                    <!-- DELETE -->
                    <button type="button"
                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                        onclick="confirmProfessorDelete(<?php echo $row->pro_id; ?>)">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>

                </div>
            </div>
        <?php endwhile; ?>
    </div>

</div>

<div class="modal fade" id="editProfessorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- FORM START -->
            <form method="POST" enctype="multipart/form-data">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">Edit Professor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- HIDDEN ID -->
                    <input type="hidden" id="edit_id" name="edit_id">

                    <!-- IMAGE -->
                    <div class="mb-3 text-center">

                        <img id="edit_preview"
                            src="./assets/professor_images/emptyuser.png"
                            class="rounded-circle"
                            style="width:100px;height:100px;object-fit:cover;cursor:pointer;"
                            onclick="document.getElementById('edit_photo').click();">

                        <input type="file"
                            id="edit_photo"
                            name="edit_photo"
                            class="d-none"
                            accept="image/*"
                            onchange="previewProfessorImage(this)">

                        <small class="text-muted d-block mt-2">
                            Click image to change
                        </small>
                    </div>

                    <!-- NAME -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" id="edit_name" name="edit_name" class="form-control" required>
                    </div>

                    <!-- DESIGNATION -->
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" id="edit_designation" name="edit_designation" class="form-control">
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="edit_description" name="edit_description" class="form-control"></textarea>
                    </div>

                    <!-- DEPARTMENT -->
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select id="edit_dept" name="edit_dept" class="form-control">
                            <option value="">Select</option>
                            <!-- Load departments here -->
                        </select>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" name="btnSaveUpdate" class="btn btn-primary rounded-pill px-4">
                        Save Changes
                    </button>
                </div>

            </form>
            <!-- FORM END -->

        </div>
    </div>
</div>

<!-- DELETE SCRIPT -->
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This professor will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?page=professor/list&action=delete&id=${id}`;
            }
        });
    }
</script>

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