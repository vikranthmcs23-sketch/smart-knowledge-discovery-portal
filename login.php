<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['login-email']);
    $password = $_POST['login-password'];

    if (!empty($email) && !empty($password)) {

        // Simple query (traditional way)
        $sql = "SELECT * FROM users 
                WHERE email = '$email' 
                AND password_hash = '$password' 
                LIMIT 1";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['logged_in'] = true;

            header("Location: index.php");
            exit();

        } else {
            echo "Invalid email or password.";
        }

    } else {
        echo "Please fill in all required fields.";
    }
}

mysqli_close($conn);
?>
