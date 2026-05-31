<?php
/*
 * logout.php - Session Termination
 * Destroys the manager's session and redirects to login.php.
 * Linked from the Logout button in manage.php and eoi_detail.php.
 * Four lines, one responsibility - intentionally simple by design.
 */

// session_start() must be called before session_destroy()
// PHP needs to find and load the session before it can destroy it
session_start();

// Wipe all session data completely - $_SESSION['username'],
// $_SESSION['last_activity'] and anything else stored in the session
// After this the manager is fully logged out and manage.php will
// redirect them back here if they try to access it directly
session_destroy();

// Send the manager back to the login page
header("Location: login.php");

// Always exit after header() to prevent any further code execution
// Without this PHP would continue running even after redirecting
exit();
?>