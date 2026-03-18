<?php
// Handle Delete Success
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if (deleteUser($_GET['id'])) {
        echo "<script>
            Swal.fire({
                title: 'Deleted!',
                text: 'User has been removed.',
                icon: 'success'
            }).then(() => {
                window.location='?page=user/list';
            });
        </script>";
        exit();
    }
}

// Handle Update Success
if (isset($_POST['btnSaveUpdate'])) {
    // ... your editUser logic ...
    if (editUser($id, $data, $file)) {
        echo "<script>
            Swal.fire({
                title: 'Updated!',
                text: 'User data has been saved successfully.',
                icon: 'success'
            }).then(() => {
                window.location='?page=user/list';
            });
        </script>";
    }
}
?>

<div class="container py-4">
    <div class="row align-items-center mb-5 g-3">
        <div class="col-12 col-md-7 text-center text-md-start">
            <h3 class="fw-bold text-body mb-1">User Management</h3>
            <p class="text-secondary small mb-0">Manage and view all registered platform users.</p>
        </div>
        <div class="col-12 col-md-5 text-center text-md-end">
            <a href="./?page=user/create" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm d-inline-flex align-items-center">
                <i class="bi bi-person-plus-fill me-2"></i>
                <span>Add New User</span>
            </a>
        </div>
    </div>

    <div class="user-list">
        <?php
        $users = getUsers();
        while ($row = $users->fetch_object()) {
            $userPhoto = (!empty($row->profile_pic)) ? $row->profile_pic : './assets/images/emptyuser.png';
        ?>
            <div class="d-flex flex-column flex-md-row align-items-center bg-body border shadow-sm rounded-4 p-3 p-md-4 mb-3 transition-hover">
                <div class="d-flex align-items-center mb-3 mb-md-0" style="width: 100%; max-width: 350px;">
                    <div class="position-relative me-3">
                        <img src="<?php echo $userPhoto; ?>" class="rounded-circle border border-2 border-primary-subtle shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" style="width: 14px; height: 14px;"></span>
                    </div>
                    <div class="text-start">
                        <div class="fw-bold text-body fs-5"><?php echo htmlspecialchars($row->name); ?></div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill small fw-medium"><?php echo strtoupper($row->level); ?></span>
                    </div>
                </div>

                <div class="flex-grow-1 w-100 py-3 py-md-0 border-top border-md-top-0 text-center text-md-start">
                    <div class="d-flex flex-column flex-md-row gap-md-4">
                        <div class="mb-2 mb-md-0">
                            <small class="text-secondary d-md-none d-block">Name</small>
                            <span class="text-body fw-medium"><?php echo htmlspecialchars($row->name); ?></span>
                        </div>
                        <div>
                            <small class="text-secondary d-md-none d-block">Joined</small>
                            <span class="text-secondary small">18/03/2026</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center justify-content-md-end gap-2">
                    <button type="button"
                        class="btn btn-sm btn-outline-primary rounded-pill px-3"
                        onclick='openEditModal(<?php echo json_encode($row); ?>)'>
                        <i class="bi bi-pencil me-1"></i> Edit
                    </button>

                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                        onclick="confirmDelete(<?php echo $row->id; ?>)">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="" id="edit_preview" class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                            <label for="edit_photo" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2" style="cursor: pointer;">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                            <input type="file" name="profile_pic" id="edit_photo" hidden onchange="previewImage(this)">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control rounded-pill px-3">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="form-control rounded-pill px-3">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">User Level</label>
                        <select name="level" id="edit_level" class="form-select rounded-pill px-3">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
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

    @media (min-width: 768px) {
        .border-md-top-0 {
            border-top: 0 !important;
        }
    }
</style>