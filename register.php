<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['reg-name']);
    $email = trim($_POST['reg-email']);
    $password = $_POST['reg-password'];

    if (!empty($name) && !empty($email) && !empty($password)) {

        // Check if email already exists
        $checkQuery = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "Email already registered.";
        } else {

            // Insert new user (plain text password)
            $sql = "INSERT INTO users (full_name, email, password_hash) 
                    VALUES ('$name', '$email', '$password')";

            if (mysqli_query($conn, $sql)) {
                echo "Registration successful! You can now log in.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }

        }

    } else {
        echo "Please fill in all required fields.";
    }
}

mysqli_close($conn);
?>
