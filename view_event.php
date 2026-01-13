<?php
session_start();
include("includes/header.php");
include("includes/db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid event.";
    exit();
}

$event_id = intval($_GET['id']);

$query = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $query);
$event = mysqli_fetch_assoc($result);

if (!$event) {
    echo "Event not found.";
    exit();
}
?>

<div class="page-wrapper">
    <main class="main-content">
        <h2><?php echo htmlspecialchars($event['title']); ?></h2>

        <?php if (!empty($event['poster'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($event['poster']); ?>" width="400" class="poster-preview"><br><br>
        <?php endif; ?>

        <p><strong>Date:</strong> <?php echo $event['date']; ?></p>
        <p><strong>Time:</strong> <?php echo $event['time']; ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($event['category']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>

        <p id="countdown" style="font-weight:bold; color:#d9534f;"></p>

        <br>
        <a href="register_event.php?event_id=<?php echo $event['id']; ?>">📩 Register for this Event</a><br>
        <a href="index.php">⬅ Back to All Events</a>
    </main>
</div>

<script>
    var eventDate = new Date("<?php echo $event['date'] . ' ' . $event['time']; ?>").getTime();

    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = eventDate - now;

        if (distance < 0) {
            document.getElementById("countdown").innerHTML = "🎉 Event Started!";
            clearInterval(x);
            return;
        }

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML =
            "⏳ Event starts in: " + days + "d " + hours + "h " + minutes + "m " + seconds + "s";
    }, 1000);
</script>

<?php include("includes/footer.php"); ?>