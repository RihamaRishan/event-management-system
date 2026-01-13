<?php
include("includes/header.php"); 
include("includes/db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];
?>

<div class="page-wrapper">
    <main class="main-content">
        <h2>Welcome, <?php echo $name; ?> (Admin)</h2>

        <ul class="dashboard-links">
            <li><a href="add_event.php">➕ Add New Event</a></li>
            <li><a href="profile.php">👤 Edit Profile</a></li>
            <li><a href="export_csv.php">📥 Export Users</a></li>
            <li><a href="logout.php">🚪 Logout</a></li>
        </ul>

        <hr>

        <h3>All Events</h3>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM events ORDER BY date ASC");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='event-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p><strong>Date:</strong> " . $row['date'] . " | <strong>Time:</strong> " . $row['time'] . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
                echo "<a href='view_event.php?id=" . $row['id'] . "'>🔍 View</a><br>";

                // Count registrations for this event
                $event_id = $row['id'];
                $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM registrations WHERE event_id = $event_id");
                $count_row = mysqli_fetch_assoc($count_result);
                $total_registered = $count_row['total'];

                echo "<p><strong>🎟 Registered Users:</strong> $total_registered</p>";
                echo "<a href='view_registrations.php?event_id=" . $row['id'] . "'>👥 View Registrations</a><br>";
                echo "<a href='edit_event.php?id=" . $row['id'] . "'>✏ Edit</a><br>";
                echo "<a href='delete_event.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this event?')\">🗑 Delete</a>";
                echo "</div><hr>";
            }
        } else {
            echo "<p>No events available.</p>";
        }
        ?>
    </main>
</div>

<?php include("includes/footer.php"); ?>