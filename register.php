<?php
session_start();
include("includes/header.php");
include("includes/db.php");

$name = $email = $password = "";
$success = $error = "";

// When the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "⚠ Email already exists. Please use a different email.";
    } else {
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $success = "✅ Registered successfully! You can now <a href='login.php'>login</a>.";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - EventEase</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-box">
        <h2>User Registration</h2>

        <?php if ($success) echo "<p class='success-msg'>$success</p>"; ?>
        <?php if ($error) echo "<p class='error-msg'>$error</p>"; ?>

        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required><br><br>
            <input type="email" name="email" placeholder="Email Address" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>

            <button type="submit">Register</button>
        </form>

        <p style="margin-top: 15px;">Already have an account? <a href="login.php">Login here</a></p>
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
</body>
</html>