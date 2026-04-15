<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require 'db.php';

// Get all uploads from the database
$sql = "SELECT * FROM uploads";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Images</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
  <nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
      <a class="navbar-brand" href="view.php">Images</a>
      <div class="d-flex align-items-center">
        <span class="text-white me-3">Welcome, <?php echo $_SESSION['user_name']; ?>!</span>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
      </div>
    </div>
  </nav>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <div class="card mb-3">
        <div class="card-body">
          <img src="uploads/<?php echo $row['uploaded_image']; ?>" alt="Uploaded Image" class="img-thumbnail mb-3" style="max-width: 150px;">
          <h5 class="card-title"><?php echo $row['first_name'] . " " . $row['last_name'] . " " > ['image_title']; ?></h5>
          <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No images found. <a href="index.html">Add one!</a></p>
  <?php endif; ?>
</body>
</html>
