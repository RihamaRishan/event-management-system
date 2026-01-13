<?php
include("includes/header.php");
include("includes/db.php");

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $created_by = $_SESSION['user_id'];

    $poster_name = $_FILES['poster']['name'];
    $poster_tmp = $_FILES['poster']['tmp_name'];
    $poster_path = "uploads/" . $poster_name;

    if (move_uploaded_file($poster_tmp, $poster_path)) {
        $query = "INSERT INTO events (title, description, date, time, location, category, poster, created_by)
                  VALUES ('$title', '$description', '$date', '$time', '$location', '$category', '$poster_name', '$created_by')";

        if (mysqli_query($conn, $query)) {
            $success = "Event added successfully!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } else {
        $error = "Failed to upload poster image.";
    }
}
?>

<div class="page-wrapper">
    <div class="main-content">
        <h2>Add New Event</h2>

        <?php if ($success) echo "<p class='success-msg'>$success</p>"; ?>
        <?php if ($error) echo "<p class='error-msg'>$error</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label><br>
            <input type="text" name="title" required><br><br>

            <label>Description:</label><br>
            <textarea name="description" required></textarea><br><br>

            <label>Date:</label><br>
            <input type="date" name="date" required><br><br>

            <label>Time:</label><br>
            <input type="time" name="time" required><br><br>

            <label>Location:</label><br>
            <input type="text" name="location" required><br><br>

            <label>Category:</label><br>
            <input type="text" name="category" required><br><br>

            <label>Poster Image:</label><br>
            <input type="file" name="poster" accept="image/*" required><br><br>

            <button type="submit">Add Event</button>
        </form>

        <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
    </div>
</div>
<div class="loader-overlay" id="loader">
    <div class="loader"></div>
</div>
<script>
  const forms = document.querySelectorAll("form");
  forms.forEach(form => {
    form.addEventListener("submit", () => {
      const loader = document.getElementById("loader");
      if (loader) loader.style.display = "flex";
    });
  });
</script>

<?php include("includes/footer.php"); ?>