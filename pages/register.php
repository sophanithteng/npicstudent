<?php
$name = $username = $passwd = '';
$nameErr = $usernameErr = $passwdErr = '';
$successMsg = '';

if (isset($_POST["name"], $_POST["username"], $_POST['passwd'], $_POST['confirmPasswd'])) {
    $name = trim($_POST["name"]);
    $username = trim($_POST["username"]);
    $passwd = trim($_POST['passwd']);
    $confirmPasswd = trim($_POST['confirmPasswd']);

    if (empty($name)) { $nameErr = 'Please input name!'; }
    if (empty($username)) { $usernameErr = 'Please input your username!'; }
    if (empty($passwd)) { $passwdErr = 'Please input your password!'; }
    
    if ($passwd !== $confirmPasswd && !empty($passwd)) {
        $passwdErr = 'Passwords do not match!';
    }

    if (!empty($username) && isUsernameExist($username)) {
        $usernameErr = 'Username already exists.';
    }

    if (empty($nameErr) && empty($usernameErr) && empty($passwdErr)) {
        if (registerUser($name, $username, $passwd)) {
            $name = $username = $passwd = '';
            $successMsg = 'Successfully registered! <a href="./?page=login" class="alert-link">Click here to Login</a>';
        }
    }
}
?>

<div class="container d-flex align-items-center justify-content-center py-5" style="min-height: 90vh;">
    <div class="card shadow-lg border-0" style="max-width: 500px; width: 100%; border-radius: 15px;">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Create Account</h3>
                <p class="text-muted small">Join NPIC Student community today</p>
            </div>

            <?php if ($successMsg): ?>
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div><?php echo $successMsg; ?></div>
                </div>
            <?php endif; ?>

            <form method="post" action="./?page=register">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input name="name" value="<?php echo htmlspecialchars($name); ?>" type="text"
                        class="form-control <?php echo empty($nameErr) ? '' : 'is-invalid'; ?>" placeholder="John Doe">
                    <div class="invalid-feedback"><?php echo $nameErr; ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><i class="bi bi-at text-muted"></i></span>
                        <input name="username" value="<?php echo htmlspecialchars($username); ?>" type="text"
                            class="form-control <?php echo empty($usernameErr) ? '' : 'is-invalid'; ?>" placeholder="johndoe123">
                        <div class="invalid-feedback"><?php echo $usernameErr; ?></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input name="passwd" type="password"
                        class="form-control <?php echo empty($passwdErr) ? '' : 'is-invalid'; ?>" placeholder="••••••••">
                    <div class="invalid-feedback"><?php echo $passwdErr; ?></div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input name="confirmPasswd" type="password" 
                        class="form-control <?php echo empty($passwdErr) ? '' : 'is-invalid'; ?>" placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">
                    Sign Up
                </button>

                <div class="text-center">
                    <p class="small text-muted">Already have an account? <a href="./?page=login" class="text-decoration-none fw-bold">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</div>