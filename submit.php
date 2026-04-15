<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//require database
require 'db.php';

// Get form data
$first_name = trim($_POST["first_name"]);
$last_name  = trim($_POST["last_name"]);
$image_title = trim($_POST["image_title"]);

// Check required fields
if (empty($first_name) || empty($last_name)) {
        die("Please fill in all required fields.");
}


 // Sanitize inputs to prevent SQL injection
    $first_name = mysqli_real_escape_string($conn, $first_name);
    $last_name  = mysqli_real_escape_string($conn, $last_name);
    $image_title    = mysqli_real_escape_string($conn, $image_title);

    $uploaded_image = "";

    if (isset($_FILES["uploaded_image"]) && $_FILES["uploaded_image"]["error"] == 0) {

        $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/webp"];
        $file_type = $_FILES["uploaded_image"]["type"];
        $file_size = $_FILES["uploaded_image"]["size"];

        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            die("Invalid file type. Only JPG, PNG, GIF and WEBP are allowed.");
        }

        // Validate file size (max 2MB)
        if ($file_size > 2097152) {
            die("File size too large. Maximum size is 2MB.");
        }

        // Create a unique file name to avoid duplicates
        $file_extension = pathinfo($_FILES["uploaded_image"]["name"], PATHINFO_EXTENSION);
        $new_file_name  = uniqid() . "." . $file_extension;
        $upload_path    = dirname(__FILE__) . "/uploads/" . $new_file_name;

        // Move the file to the uploads folder
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $upload_path)) {
            $image = $new_file_name;
        } else {
            die("Error uploading file.");
        }
    }

     // Insert data into database
    $sql = "INSERT INTO uploads (first_name, last_name, image_title, uploaded_image)
            VALUES ('$first_name', '$last_name', $image_title, '$uploaded_image')";

?>