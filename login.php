<?php
// Start the session
session_start();

//Includes database connection
require 'db.php';

$error = "";

//Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Server-side validation
    if (empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } 

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } 

    else {
        // Sanitize email
        $email = mysqli_real_escape_string($conn, $email);

        // Check if user exists in database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Store user info in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'];

                // Redirect to view page
                header("Location: view.php");
                exit();
            } 
            else {
                $error = "Incorrect password.";
            } 
            else {
                $error = "No account found with that email.";
            }
        }  
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

  <h1>Login</h1>
  <p>Don't have an account? <a href="register.php">Register here</a></p>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>
</body>
</html>