<?php
$name = $username = $passwd = '';
$nameErr = $usernameErr = $passwdErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $passwd = trim($_POST['passwd'] ?? '');
    
    if (empty($name)) $nameErr = 'Please input name!';
    if (empty($username)) $usernameErr = 'Please input username!';
    if (empty($passwd)) $passwdErr = 'Please input password!';
    
    if (empty($usernameErr) && isUsernameExist($username)) {
        $usernameErr = 'Username exists!';
    }

    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        try {
            // FIX: Match the 'name' attribute of your file input ("photo")
            $userPhoto = (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) 
                         ? $_FILES['photo'] 
                         : null;

            // FIX: Pass the correct variable ($userPhoto) to the function
            if (createUser($name, $username, $passwd, $userPhoto)) {
                $name = $username = $passwd = '';
                echo '<div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> User created successfully!
                      </div>';
            } else {
                echo '<div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">Create failed!</div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">' . htmlspecialchars($e->getMessage()) . '</div>';
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
                    <h3 class="fw-bold text-body">New User Account</h3>
                    <p class="text-muted small">The photo is optional; you can add it later.</p>
                </div>
                
                <form method="post" action="./?page=user/create" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <div class="profile-wrapper">
                            <img id="preview" src="./assets/images/emptyuser.png" 
                                 class="rounded-circle border border-4 border-primary-subtle shadow-sm w-100 h-100" 
                                 style="object-fit: cover; cursor: pointer;">
                            
                            <label for="profileUpload" class="camera-icon rounded-circle position-absolute bottom-0 end-0 cursor-pointer">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                        </div>
                        <input name="photo" type="file" id="profileUpload" hidden accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Full Name</label>
                        <input name="name" value="<?php echo htmlspecialchars($name) ?>" type="text" 
                               class="form-control form-control-lg bg-body <?php echo empty($nameErr) ? '' : 'is-invalid' ?>"
                               placeholder="Name">
                        <div class="invalid-feedback"><?php echo $nameErr ?></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Username</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text bg-body border-end-0 text-muted"><i class="bi bi-at"></i></span>
                            <input name="username" value="<?php echo htmlspecialchars($username) ?>" type="text" 
                                   class="form-control form-control-lg bg-body border-start-0 <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>"
                                   placeholder="Username">
                            <div class="invalid-feedback"><?php echo $usernameErr ?></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase tracking-wider">Password</label>
                        <input name="passwd" type="password" 
                               class="form-control form-control-lg bg-body <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>"
                               placeholder="••••••••">
                        <div class="invalid-feedback"><?php echo $passwdErr ?></div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold shadow-sm">
                            Confirm Registration
                        </button>
                        <a href="?page=user/list" class="btn btn-link btn-sm text-decoration-none text-muted mt-2">
                            Cancel and Return
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profileUpload').onchange = function (evt) {
    const [file] = this.files;
    if (file) {
        document.getElementById('preview').src = URL.createObjectURL(file);
    }
}
</script>