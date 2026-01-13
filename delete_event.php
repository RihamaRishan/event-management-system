<?php
session_start();
include("includes/header.php");
include("includes/db.php");

// Only admin can delete events
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<div class='page-wrapper'><p>Invalid request. No event ID provided.</p></div>";
    include("includes/footer.php");
    exit();
}

$event_id = intval($_GET['id']);

// Check if event exists
$check = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
if (mysqli_num_rows($check) == 0) {
    echo "<div class='page-wrapper'><p>Event not found.</p></div>";
    include("includes/footer.php");
    exit();
}

// Delete registrations related to this event first
mysqli_query($conn, "DELETE FROM registrations WHERE event_id = $event_id");

// Delete event
$delete = mysqli_query($conn, "DELETE FROM events WHERE id = $event_id");

echo "<div class='page-wrapper'>";
if ($delete) {
    echo "<p style='color:green;'>✅ Event deleted successfully.</p>";
} else {
    echo "<p style='color:red;'>❌ Error deleting event: " . mysqli_error($conn) . "</p>";
}
echo "<p><a href='dashboard.php'>⬅ Back to Dashboard</a></p>";
echo "</div>";

include("includes/footer.php");
?>