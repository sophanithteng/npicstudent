<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-body mb-1">User Management</h3>
            <p class="text-muted small mb-0">Manage and view all registered platform users.</p>
        </div>
        <a href="./?page=user/create" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 shadow-sm">
            <i class="bi bi-person-plus-fill"></i>
            <span>Add New User</span>
        </a>
    </div>

    <div class="card border-0 shadow-sm bg-body-tertiary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase">
                        <tr style="font-size: 0.85rem; letter-spacing: 0.5px;">
                            <th class="ps-4 py-3">#</th>
                            <th>Profile</th>
                            <th>Full Name</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php
                        $users = getUsers();
                        $count = 1;
                        while ($row = $users->fetch_object()) {
                        ?>
                            <tr>
                                <td class="ps-4 text-muted fw-medium"><?php echo $count; ?></td>
                                <td>
                                    <div class="position-relative d-inline-block">
                                        <img src="<?php
                                                    if (!empty($row->picture) && file_exists($row->picture)) {
                                                        echo $row->picture;
                                                    } else {
                                                        echo './assets/images/emptyuser.png';
                                                    }
                                                    ?>"
                                            alt="User"
                                            class="rounded-circle border border-2 border-primary-subtle shadow-sm"
                                            style="width: 48px; height: 48px; object-fit: cover;">
                                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle" style="width: 12px; height: 12px;"></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-body"><?php echo htmlspecialchars($row->name); ?></div>
                                    <div class="text-muted small">User ID: #<?php echo $row->id; ?></div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="?page=user/update&id=<?php echo $row->id; ?>"
                                            class="btn btn-sm btn-outline-primary border-0 bg-primary-subtle text-primary-emphasis px-3">
                                            Edit
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger border-0 bg-danger-subtle text-danger-emphasis px-3"
                                            onclick="confirmDelete(<?php echo $row->id; ?>)">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            $count++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>