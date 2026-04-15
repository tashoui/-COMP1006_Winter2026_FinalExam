<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require 'db.php';

// Get the resume id from the URL
$id = $_GET['id'];

// Delete the image from the database
$sql = "DELETE FROM uploads WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    // Redirect to view page after successful deletion
    header("Location: view.php");
    exit();
} 
else {
    echo "Error: " . mysqli_error($conn);
}
?>