<?php
$name = $username = $passwd = '';
$nameErr = $usernameErr = $passwdErr = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $passwd = trim($_POST['passwd'] ?? '');
    
    // Validation
    if (empty($name)) $nameErr = 'Please input name!';
    if (empty($username)) $usernameErr = 'Please input username!';
    if (empty($passwd)) $passwdErr = 'Please input password!';
    
    // Check if username exists (logic depends on your function)
    if (empty($usernameErr) && isset($username) && isUsernameExist($username)) {
        $usernameErr = 'Username already exists!';
    }

    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        // Your Registration Logic Here
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-5">
            <div class="card border-0 shadow-sm bg-body-tertiary">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-body">Create Account</h3>
                        <p class="text-muted small">Fill in the details to register a new user</p>
                    </div>

                    <form method="post" action="./?page=user/create" enctype="multipart/form-data">
                        
                        <div class="d-flex flex-column align-items-center mb-4">
                            <div class="position-relative">
                                <img id="imgPreview" src="./assets/images/emptyuser.png"
                                    class="rounded-circle border border-4 border-primary-subtle shadow-sm" 
                                    style="width: 130px; height: 130px; object-fit: cover;">
                                
                                <label for="profileUpload" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input name="photo" type="file" id="profileUpload" hidden accept="image/*">
                            </div>
                            <small class="text-muted mt-2">Click icon to upload photo</small>
                        </div>

                        <div class="form-floating mb-3">
                            <input name="name" type="text" 
                                class="form-control bg-body <?php echo empty($nameErr) ? '' : 'is-invalid' ?>" 
                                id="floatingName" placeholder="Full Name" value="<?php echo htmlspecialchars($name) ?>">
                            <label for="floatingName" class="text-secondary">Full Name</label>
                            <div class="invalid-feedback"><?php echo $nameErr ?></div>
                        </div>

                        <div class="form-floating mb-3">
                            <input name="username" type="text" 
                                class="form-control bg-body <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>" 
                                id="floatingUser" placeholder="Username" value="<?php echo htmlspecialchars($username) ?>">
                            <label for="floatingUser" class="text-secondary">Username</label>
                            <div class="invalid-feedback"><?php echo $usernameErr ?></div>
                        </div>

                        <div class="form-floating mb-4">
                            <input name="passwd" type="password" 
                                class="form-control bg-body <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>" 
                                id="floatingPass" placeholder="Password">
                            <label for="floatingPass" class="text-secondary">Password</label>
                            <div class="invalid-feedback"><?php echo $passwdErr ?></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                Create User
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="./?page=user/list" class="text-decoration-none small text-muted">
                                <i class="bi bi-arrow-left"></i> Back to User List
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('profileUpload').onchange = evt => {
        const [file] = document.getElementById('profileUpload').files
        if (file) {
            document.getElementById('imgPreview').src = URL.createObjectURL(file)
        }
    }
</script>