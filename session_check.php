<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Your session timeout logic
define('INACTIVITY_TIMEOUT', 1800); // Example timeout in seconds (30 minutes)

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > INACTIVITY_TIMEOUT)) {
    // Last request was more than timeout seconds ago
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity time stamp

// Check for session expiration

define('SESSION_TIMEOUT', 1800); // 1800 seconds = 30 minutes

if (isset($_SESSION['CREATED'])) {
    if (time() - $_SESSION['CREATED'] > SESSION_TIMEOUT) {
        // Session timeout: destroy the session
        session_unset();
        session_destroy();
        header("Location: welcome.php?message=Session expired.");
        exit();
    }
} else {
    // Set session creation time
    $_SESSION['CREATED'] = time();
}

?>