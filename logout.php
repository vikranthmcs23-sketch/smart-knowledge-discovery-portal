<?php
// 1. Initialize the session so PHP knows which session to destroy
session_start();

// 2. Unset all of the session variables
$_SESSION = array();

// 3. Destroy the session cookie securely
// This ensures the browser doesn't hold onto a dead session ID
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Completely destroy the session on the server
session_destroy();

// 5. Redirect the user back to the login page
header("Location: login.html");
exit();
?>