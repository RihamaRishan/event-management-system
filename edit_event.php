<?php
session_start();
include("includes/header.php");
include("includes/db.php");

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No event ID specified.";
    exit();
}

$event_id = intval($_GET['id']);
$error = $success = "";

// Fetch event data
$result = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
$event = mysqli_fetch_assoc($result);

if (!$event) {
    echo "Event not found.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $poster_sql = "";

    if (!empty($_FILES['poster']['name'])) {
        $poster_name = $_FILES['poster']['name'];
        $poster_tmp = $_FILES['poster']['tmp_name'];
        $poster_path = "uploads/" . $poster_name;

        if (move_uploaded_file($poster_tmp, $poster_path)) {
            $poster_sql = ", poster='$poster_name'";
        } else {
            $error = "Failed to upload new poster.";
        }
    }

    $query = "UPDATE events SET 
                title='$title', 
                description='$description', 
                date='$date', 
                time='$time', 
                location='$location', 
                category='$category'
                $poster_sql 
              WHERE id = $event_id";

    if (mysqli_query($conn, $query)) {
        $success = "Event updated successfully!";
        $result = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
        $event = mysqli_fetch_assoc($result);
    } else {
        $error = "Error updating event.";
    }
}
?>

<div class="page-wrapper">
    <main class="main-content">
        <h2>Edit Event</h2>

        <?php if ($success) echo "<p class='success-msg'>$success</p>"; ?>
        <?php if ($error) echo "<p class='error-msg'>$error</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label><br>
            <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required><br><br>

            <label>Description:</label><br>
            <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea><br><br>

            <label>Date:</label><br>
            <input type="date" name="date" value="<?php echo $event['date']; ?>" required><br><br>

            <label>Time:</label><br>
            <input type="time" name="time" value="<?php echo $event['time']; ?>" required><br><br>

            <label>Location:</label><br>
            <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required><br><br>

            <label>Category:</label><br>
            <input type="text" name="category" value="<?php echo htmlspecialchars($event['category']); ?>" required><br><br>

            <?php if (!empty($event['poster'])): ?>
                <p>Current Poster:</p>
                <img src="uploads/<?php echo htmlspecialchars($event['poster']); ?>" alt="Poster" class="poster-preview"><br><br>
            <?php endif; ?>

            <label>New Poster (Optional):</label><br>
            <input type="file" name="poster" accept="image/*"><br><br>

            <button type="submit">Update Event</button>
        </form>

        <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
    </main>
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