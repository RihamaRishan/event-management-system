<?php
session_start();
include("includes/db.php");

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['event_id'])) {
    echo "No event selected.";
    exit();
}

$event_id = intval($_GET['event_id']);
$event_query = mysqli_query($conn, "SELECT title FROM events WHERE id = $event_id");
$event = mysqli_fetch_assoc($event_query);
$event_title = $event ? $event['title'] : "Unknown Event";

include("includes/header.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Registrations - EventEase</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="page-wrapper">
    <main class="main-content">
        <h2 class="page-title">👥 Registrations for "<?php echo htmlspecialchars($event_title); ?>"</h2>

        <?php
        $query = "
            SELECT u.name, u.email 
            FROM users u
            INNER JOIN registrations r ON u.id = r.user_id
            WHERE r.event_id = $event_id
            ORDER BY u.name ASC
        ";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='styled-table'>";
            echo "<thead><tr><th>Name</th><th>Email</th></tr></thead><tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No users have registered for this event yet.</p>";
        }
        ?>

        <div class="action-links">
            <a href="export_csv.php?event_id=<?php echo $event_id; ?>">📥 Export to CSV</a>
            <a href="dashboard.php">⬅ Back to Dashboard</a>
        </div>
    </main>

    <?php include("includes/footer.php"); ?>
</div>
</body>
</html>