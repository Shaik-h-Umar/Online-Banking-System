<?php
session_start();

// Remove all session variables
session_unset();

// Destroy the session
session_destroy();

// Remove session cookie from browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Finally redirect to login page
header("Location: ../pages/login.php");
exit();
