<?php
// logout.php
session_start();

// Set cache control headers to prevent back button from showing cached pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Clear any other cookies your application might have set
setcookie('user_id', '', time() - 3600, '/');
setcookie('user_pref', '', time() - 3600, '/');

// Redirect to login page
header("Location: index.php");
exit();
?>
