<?php
$oldPasswd = $newPasswd = $confirmNewPasswd = '';
$oldPasswdErr = $newPasswdErr  = '';

// --- Logic for Password Change ---
if (isset($_POST['changePasswd'])) {
    $oldPasswd = trim($_POST['oldPasswd'] ?? '');
    $newPasswd = trim($_POST['newPasswd'] ?? '');
    $confirmNewPasswd = trim($_POST['confirmNewPasswd'] ?? '');

    if (empty($oldPasswd)) {
        $oldPasswdErr = 'Please input your old password';
    }
    if (empty($newPasswd)) {
        $newPasswdErr = 'Please input your new password';
    }
    if ($newPasswd !== $confirmNewPasswd) {
        $newPasswdErr = 'Passwords do not match';
    }

    // Check if old password is correct
    if (empty($oldPasswdErr) && !isUserHasPassword($oldPasswd)) {
        $oldPasswdErr = 'Old password is incorrect';
    }

    if (empty($oldPasswdErr) && empty($newPasswdErr)) {
        if (setUserNewPassowrd($newPasswd)) {
            // Redirect to logout to force re-login with new password
            header('Location: ./?page=logout');
            exit;
        } else {
            echo '<div class="alert alert-danger">Failed to update password. Try again.</div>';
        }
    }
}

// --- Logic for Profile Picture ---
if (isset($_POST['uploadpicture']) && isset($_FILES['picture'])) {
    $picture = $_FILES['picture'];
    if (empty($picture['name'])) {
        echo '<div class="alert alert-warning">Please select a picture first.</div>';
    } else {
        try {
            if (changeProfilepicture($picture)) {
                echo '<div class="alert alert-success">Profile picture updated!</div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
        }
    }
}

if (isset($_POST['deletepicture'])) {
    if (deleteProfilepicture()) {
        echo '<div class="alert alert-info">Profile picture deleted.</div>';
    }
}
?>

<div class="container py-4">
    <div class="row g-4">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-4">
                    <h4 class="card-title mb-4">Profile Picture</h4>
                    <form method="post" action="./?page=profile" enctype="multipart/form-data" onsubmit="validateUpload(event)">
                        <div class="mb-4">
                            <input name="picture" type="file" id="profileUpload" hidden accept="image/png, image/jpeg">
                            <label role="button" for="profileUpload" class="position-relative d-inline-block">
                                <img id="previewImage"
                                    src="<?php echo loggedInUser()->profile_pic ?? './assets/images/emptyuser.png' ?>"
                                    class="rounded-circle border shadow-sm"
                                    style="width: 180px; height: 180px; object-fit: cover;">
                            </label>
                        </div>

                        <div class="d-grid gap-2 d-md-block">
                            <button type="submit" class="btn btn-success px-4">Save New Photo</button>

                            <button type="button" class="btn btn-outline-danger px-4" onclick="confirmDelete(event)">
                                Delete
                            </button>
                        </div>
                    </form>
                    <p class="text-muted mt-3 small">Click the image to select a new file.<br>Allowed: JPG, PNG (Max 2MB)</p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4">Account Security</h4>
                    <form method="post" action="./?page=profile">
                        <div class="mb-3">
                            <label class="form-label">Old Password</label>
                            <input name="oldPasswd" type="password" class="form-control <?php echo $oldPasswdErr ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback"><?php echo $oldPasswdErr ?></div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input name="newPasswd" type="password" class="form-control <?php echo $newPasswdErr ? 'is-invalid' : '' ?>">
                            <div class="invalid-feedback"><?php echo $newPasswdErr ?></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm New Password</label>
                            <input name="confirmNewPasswd" type="password" class="form-control <?php echo $newPasswdErr ? 'is-invalid' : '' ?>">
                        </div>

                        <button type="submit" name="changePasswd" class="btn btn-primary w-100 py-2">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>