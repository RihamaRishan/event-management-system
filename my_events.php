<?php
session_start();
include("includes/header.php");
include("includes/db.php");

// Only allow logged-in users (role = user)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<div class="page-wrapper">
    <div class="main-content">
        <h2 style="text-align: center;">📅 My Registered Events</h2>
        <p style="text-align: center;">Welcome, <?php echo $_SESSION['name']; ?> | <a href="logout.php">Logout</a></p>
        <p style="text-align: center;"><a href="index.php">⬅ Back to All Events</a></p>
        <hr style="margin: 20px 0;">

        <?php
        $query = "
            SELECT e.*
            FROM events e
            INNER JOIN registrations r ON e.id = r.event_id
            WHERE r.user_id = $user_id
            ORDER BY e.date ASC
        ";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='event-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";

                if (!empty($row['poster'])) {
                    echo "<img src='uploads/" . htmlspecialchars($row['poster']) . "' alt='Event Poster'>";
                }

                echo "<p><strong>Date:</strong> " . $row['date'] . " | <strong>Time:</strong> " . $row['time'] . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
                echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align: center;'>You haven’t registered for any events yet.</p>";
        }
        ?>
    </div>
</div>

<?php include("includes/footer.php"); ?>