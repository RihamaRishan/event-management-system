<?php
session_start();
include("includes/header.php");
include("includes/db.php");

$error = "";
$success = "";

// Handle login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // If "Remember Me" checked, set cookie
            if (!empty($_POST['remember'])) {
                setcookie("user_email", $email, time() + (86400 * 30), "/");
            } else {
                setcookie("user_email", "", time() - 3600, "/"); // delete cookie
            }

            // Redirect
            if ($user['role'] == 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!-- === LOGIN FORM UI === -->
<div class="login-wrapper">
    <div class="login-box">
        <h2>Login</h2>

        <?php if ($error): ?>
            <p class="error-msg"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" 
                   value="<?php echo isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : ''; ?>" 
                   required>

            <div style="position: relative;">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span onclick="togglePassword()" 
                      style="position:absolute; top:10px; right:10px; cursor:pointer; color:#ccc;">
                      👁
                </span>
            </div>

            <label style="display: flex; align-items: center; font-size: 14px; margin-top: 10px;">
                <input type="checkbox" name="remember" style="margin-right: 8px;"> Remember Me
            </label>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
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

<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    pwd.type = pwd.type === "password" ? "text" : "password";
}
</script>

<?php include("includes/footer.php"); ?>
