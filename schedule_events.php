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

// Fetch club_id from leads table
$user_id = $_SESSION["user_id"];
$sql_club_id = "SELECT club_id FROM leads WHERE id = '$user_id'";
$result_club_id = $conn->query($sql_club_id);
$row_club_id = $result_club_id->fetch_assoc();
$club_id = $row_club_id["club_id"];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST["event_id"];
    $event_name = $_POST["event_name"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $certifiability = $_POST["certifiability"];
    $credits = $_POST["credits"];
    $venue = $_POST["venue"];

    // Insert values into events table
    $sql_insert = "INSERT INTO events (club_id, event_id, event_name, start_time, end_time, certifiability, credits, venue) VALUES ('$club_id', '$event_id', '$event_name', '$start_time', '$end_time', '$certifiability', '$credits', '$venue')";
    
    if ($conn->query($sql_insert) === TRUE) {
        echo "Event added successfully!";
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
</head>
<body>
    <div class="container mt-5">
        <h2>Schedule Events</h2>
        <form method="post">
            <div class="form-group">
                <label for="club_id">Club ID (Read-only)</label>
                <input type="text" class="form-control" id="club_id" name="club_id" value="<?php echo $club_id; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="event_id">Event ID</label>
                <input type="text" class="form-control" id="event_id" name="event_id" required>
            </div>
            <div class="form-group">
                <label for="event_name">Event Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" required>
            </div>
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
            </div>
            <div class="form-group">
                <label for="certifiability">Certifiability</label>
                <select class="form-control" id="certifiability" name="certifiability" required>
                    <option value="Y">Yes</option>
                    <option value="N">No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="credits">Credits</label>
                <input type="number" class="form-control" id="credits" name="credits" required>
            </div>
            <div class="form-group">
                <label for="venue">Venue</label>
                <input type="text" class="form-control" id="venue" name="venue" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
