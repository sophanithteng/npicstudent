<?php
$username = '';
$usernameErr = $passwdErr = '';
$loginErr = ''; // New variable for general login failures

if (isset($_POST['username'], $_POST['passwd'])) {
    $username = trim($_POST['username']);
    $passwd = trim($_POST['passwd']);
    
    if (empty($username)) {
        $usernameErr = 'Please input username!';
    }
    if (empty($passwd)) {
        $passwdErr = 'Please input password!';
    }
    
    if (empty($usernameErr) && empty($passwdErr)) {
        $user = logUserIn($username, $passwd);
        if ($user !== false) {
            $_SESSION['user_id'] = $user->id;
            header('Location: ./?page=dashboard');
            exit; // Always exit after a header redirect
        } else {
            $loginErr = 'Invalid username or password. Please try again.';
        }
    }
}
?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card shadow-lg border-0" style="max-width: 450px; width: 100%; border-radius: 15px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <img src="assets/images/npic_logo.png" alt="Logo" class="mb-3" style="width: 70px;">
                <h3 class="fw-bold">Welcome Back</h3>
                <p class="text-muted">Login to manage your account</p>
            </div>

            <?php if ($loginErr): ?>
                <div class="alert alert-danger d-flex align-items-center py-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <small><?php echo $loginErr; ?></small>
                </div>
            <?php endif; ?>

            <form method="post" action="./?page=login">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input name="username" value="<?php echo htmlspecialchars($username) ?>" type="text" 
                               class="form-control border-start-0 <?php echo empty($usernameErr) ? '' : 'is-invalid' ?>" 
                               placeholder="Enter your username">
                        <div class="invalid-feedback"><?php echo $usernameErr ?></div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                        <input name="passwd" type="password" 
                               class="form-control border-start-0 <?php echo empty($passwdErr) ? '' : 'is-invalid' ?>" 
                               placeholder="••••••••">
                        <div class="invalid-feedback"><?php echo $passwdErr ?></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" style="border-radius: 8px;">
                    Sign In
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted mb-0">Don't have an account? <a href="./?page=register" class="text-decoration-none">Register</a></p>
                <a href="./" class="small text-decoration-none mt-2 d-inline-block text-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>