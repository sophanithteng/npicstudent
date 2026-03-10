<?php
require_once("./init/init.php");
include("./includes/header.inc.php");
include("./includes/navbar.inc.php");

$available_pages = ['dashboard', 'profile', 'login', 'register'];
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

if (in_array($page, $available_pages) && file_exists("./pages/$page.php")) {
    include("./pages/$page.php");
} else {
    include("./pages/error404.php");
}

include("./includes/footer.inc.php");
?>