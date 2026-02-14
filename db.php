<?php
// Enable error reporting so we never see a blank 500 screen again
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Your InfinityFree Database Details
$servername = "sql111.infinityfree.com";
$username = "if0_41138127";
$password = "keb4M2lpFy";
$dbname = "if0_41138127_knowledgeHub";

// Create connection using a try-catch block for modern PHP compatibility
try {
    // Attempt the connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Fallback check
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // If it fails, print the exact error instead of crashing with a 500 error
    die("Database Error: " . $e->getMessage());
}
?>