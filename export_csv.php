<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['event_id'])) {
    die("No event selected.");
}

$event_id = intval($_GET['event_id']);

// Set headers to trigger download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="registrations_event_' . $event_id . '.csv"');

// Open output stream
$output = fopen('php://output', 'w');
fputcsv($output, ['Name', 'Email']);

// Get registered users
$query = "
    SELECT u.name, u.email 
    FROM users u
    INNER JOIN registrations r ON u.id = r.user_id
    WHERE r.event_id = $event_id
";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [$row['name'], $row['email']]);
}

fclose($output);
exit();