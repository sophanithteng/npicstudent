<?php
require_once("./init/init.php");

$user = loggedInUser();

// ==========================
// 1. PAGE GROUPS
// ==========================
$logged_in_pages = [
    'dashboard',
    'profile',
    'professor',
    'professor/electrical',
    'professor/cs',
    'professor/mechanical',
    'professor/optics',
    'professor/civil',
    'professor/electronics',
    'professor/automotive',
    'professor/tourism'
];

$non_logged_in_pages = ['login', 'register'];
$admin_pages = ['user/list', 'user/create'];
$logout_page = ['logout'];

// ==========================
// 2. ALL AVAILABLE PAGES
// ==========================
$available_pages = array_merge(
    $logged_in_pages,
    $non_logged_in_pages,
    $admin_pages,
    $logout_page
);

// ==========================
// 3. GET PAGE
// ==========================
$page = strtolower(trim($_GET['page'] ?? 'dashboard'));

// ==========================
// 4. SECURITY (FIXED)
// ==========================

// 🔒 Require login
if (in_array($page, $logged_in_pages) && empty($user)) {
    header('Location: ?page=login');
    exit();
}

// 🔒 Prevent logged-in user from accessing login/register
if (in_array($page, $non_logged_in_pages) && !empty($user)) {
    header('Location: ?page=dashboard');
    exit();
}

// 🔒 Admin only pages (FIXED SAFELY)
if (in_array($page, $admin_pages)) {
    if (empty($user) || ($user->level ?? '') !== 'admin') {
        header('Location: ?page=dashboard');
        exit();
    }
}

// ==========================
// 5. RENDER
// ==========================
include("./includes/header.inc.php");
include("./includes/navbar.inc.php");

$file = "./pages/" . $page . ".php";

if (in_array($page, $available_pages) && file_exists($file)) {
    include $file;
} else {
    echo "<div class='container py-5 text-center'>
            <h1 class='display-1 fw-bold text-secondary'>404</h1>
            <p class='lead'>Page not found</p>
            <a href='?page=dashboard' class='btn btn-primary mt-3'>Back to Home</a>
          </div>";
}

include("./includes/footer.inc.php");