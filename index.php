<?php
include("includes/header.php"); 
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Browse Events - EventEase</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="page-wrapper">
    <main class="main-content">

        <form method="GET" action="" style="margin-bottom: 30px;" class="filter-form">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category">
                <option value="">-- All --</option>
                <?php
                $cat_result = mysqli_query($conn, "SELECT DISTINCT category FROM events ORDER BY category ASC");
                while ($cat = mysqli_fetch_assoc($cat_result)) {
                    $selected = (isset($_GET['category']) && $_GET['category'] == $cat['category']) ? 'selected' : '';
                    echo "<option value='{$cat['category']}' $selected>{$cat['category']}</option>";
                }
                ?>
            </select>

            <label for="date">Date:</label>
            <input type="date" name="date" id="date" value="<?php echo isset($_GET['date']) ? $_GET['date'] : ''; ?>">

            <button type="submit">🔍 Filter</button>
            <a href="index.php" style="margin-left: 10px;">Reset</a>
        </form>

        <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
        <h3>Upcoming Events</h3>

        <?php
        $query = "SELECT * FROM events WHERE 1=1";

        if (!empty($_GET['category'])) {
            $category = mysqli_real_escape_string($conn, $_GET['category']);
            $query .= " AND category = '$category'";
        }

        if (!empty($_GET['date'])) {
            $date = $_GET['date'];
            $query .= " AND date = '$date'";
        }

        $query .= " ORDER BY date ASC";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $event_id = $row['id'];

                // Check registration
                $reg_check = mysqli_query($conn, "SELECT * FROM registrations WHERE user_id = $user_id AND event_id = $event_id");
                $is_registered = mysqli_num_rows($reg_check) > 0;

                echo "<div class='event-card'>";
                echo "<h3>" . htmlspecialchars($row['title']);
                if ($is_registered) {
                    echo " <span class='badge-registered'>✔ Registered</span>";
                }
                echo "</h3>";

                if (!empty($row['poster'])) {
                    echo "<img src='uploads/" . htmlspecialchars($row['poster']) . "' alt='Event Poster' class='poster-image'>";
                }

                echo "<p><strong>Date:</strong> " . $row['date'] . " | <strong>Time:</strong> " . $row['time'] . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>";
                echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";

                echo "<a href='view_event.php?id=" . $row['id'] . "'>🔍 View</a> ";
                echo "<a href='register_event.php?event_id=" . $row['id'] . "'>📩 Register</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>

        <p style="margin-top: 30px;"><a href="logout.php">🚪 Logout</a></p>
    </main>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>