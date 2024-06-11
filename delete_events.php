<?php
// Start session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection parameters
$servername = "sql6.freesqldatabase.com";
$username = "sql6705819";
$password = "hLLrdKwXFB";
$dbname = "sql6705819";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get event ID from the form
    $event_id = $_POST["event_id"];

    // Delete event from the events table
    $sql_delete_event = "DELETE FROM events WHERE event_id = '$event_id'";
    if ($conn->query($sql_delete_event) === TRUE) {
        echo "<script>
                alert('Event deleted successfully!');
                window.location.href = 'delete_events.php';
              </script>";
    } else {
        echo "Error deleting event: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Delete Event</h2>
        <form method="post">
            <div class="form-group">
                <label for="event_id">Event ID</label>
                <input type="text" class="form-control" id="event_id" name="event_id" required>
            </div>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
