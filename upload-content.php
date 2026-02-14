<?php
session_start();
require_once 'db.php';

// Check if the form was submitted
if (isset($_POST['title'], $_POST['genre'], $_POST['content'])) {

    // 1. Get the data from the HTML form
    // We use real_escape_string so apostrophes don't break the database query
    $title   = mysqli_real_escape_string($conn, $_POST['title']);
    $genre   = mysqli_real_escape_string($conn, $_POST['genre']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // 2. Write the traditional SQL Insert Query
    $sql = "INSERT INTO posts (title, genre, content) 
            VALUES ('$title', '$genre', '$content')";

    // 3. Run the query
    if (mysqli_query($conn, $sql)) {
        // Success! Go back to the dashboard
        header("Location: index.php");
        exit();
    } else {
        // Show error if it fails
        echo "Error saving post: " . mysqli_error($conn);
    }

} else {
    echo "Please fill out all fields.";
}

mysqli_close($conn);
?>