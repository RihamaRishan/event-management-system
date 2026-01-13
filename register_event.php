<?php
session_start();
include("includes/header.php");
include("includes/db.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Register user for the event
if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
    $user_id = $_SESSION['user_id'];

    // Check if already registered
    $check_query = "SELECT * FROM registrations WHERE user_id = $user_id AND event_id = $event_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Already registered
        echo "<script>alert('You have already registered for this event.'); window.location.href='index.php';</script>";
    } else {
        // Register
        $insert_query = "INSERT INTO registrations (user_id, event_id) VALUES ($user_id, $event_id)";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Registration successful!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error occurred while registering.'); window.location.href='index.php';</script>";
        }
    }
} else {
    echo "<script>alert('Invalid event.'); window.location.href='index.php';</script>";
}

include("includes/footer.php");