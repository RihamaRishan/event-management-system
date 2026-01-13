<?php
session_start();
include("includes/header.php");
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success = $error = "";

// Fetch current user
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($result);

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = "UPDATE users SET name='$name', email='$email', password='$hashed' WHERE id=$user_id";
    } else {
        $update = "UPDATE users SET name='$name', email='$email' WHERE id=$user_id";
    }

    if (mysqli_query($conn, $update)) {
        $success = "Profile updated successfully!";
        $_SESSION['name'] = $name;
    } else {
        $error = "Update failed: " . mysqli_error($conn);
    }
}
?>

<div class="page-wrapper">
    <div class="main-content">
        <h2 class="page-title">👤 Edit Profile</h2>

        <?php if ($success) echo "<p style='color:limegreen;'>$success</p>"; ?>
        <?php if ($error) echo "<p style='color:#ff6b6b;'>$error</p>"; ?>

        <form method="POST" action="" class="form-box">
            <label>Name:</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

            <label>New Password (leave blank to keep current):</label><br>
            <input type="password" name="password"><br><br>

            <button type="submit">Update Profile</button>
        </form>

        <div class="action-links" style="margin-top: 20px;">
            <a href="<?php echo ($_SESSION['role'] === 'admin') ? 'dashboard.php' : 'index.php'; ?>">⬅ Back</a>
        </div>
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