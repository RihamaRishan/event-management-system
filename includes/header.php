<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EventEase</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body  class="login-page">
    <header class="main-header">
        <div class="header-container">
            <div class="logo">
                <h1><i class="fa-solid fa-calendar-days"></i> EventEase</h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="dashboard.php">Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="my_events.php">My Events</a></li>
                        <?php endif; ?>
                        <li><a href="profile.php">Profile</a></li>
                        
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="contact_us.php">Contact Us</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>