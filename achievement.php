<?php
// Start session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION["user_id"])) 
{
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

// Fetch events in which the user has participated
$user_id = $_SESSION["user_id"];
$sql = "SELECT people.name AS user_name, events.event_name, events.start_time, events.end_time, events.venue, events.certifiability,events.event_id
        FROM events
        INNER JOIN participates ON events.event_id = participates.event_id
        INNER JOIN people ON participates.id = people.id
        WHERE participates.id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS for Bootstrap cards */
        .custom-card {
            background-color: #85D332;
            transition: transform 0.3s;
            height: 100%; /* Ensures all cards have the same height */
        }

        .custom-card:hover {
            transform: translateY(-5px);
        }

        /* Set max height for card body to ensure consistent appearance */
        .card-body {
            max-height: 234px; /* Adjust as needed */
            overflow: auto; /* Enable scrolling if content exceeds max height */
        }
         /* Custom styling for sidebar */
        .sidebar {
            height: 100vh; /* Set sidebar to full height of viewport */
            background-color: #333; /* Dark background color */
            color: #fff; /* Light text color */
            padding-top: 20px;
            position: fixed; /* Fixed position */
            left: 0;
            top: 0;
            bottom: 0;
            width: 250px; /* Set width of sidebar */
        }

        .sidebar-link {
            color: #fff;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s; /* Smooth transition on hover */
        }

        .sidebar-link:hover {
            background-color: #555; /* Darker background color on hover */
        }

        .main-content {
            margin-left: 250px; /* Adjust margin to account for sidebar width */
            padding: 20px; /* Add padding to main content area */
        }
        body{
            background-color: #c0c0c0;
        }
        .btn-primary{
            background-color: orange;
            
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 class="text-center">Navigation</h3>
        <a href="./dashboard.html" class="sidebar-link">Dashboard</a>
        <a href="track.php" class="sidebar-link">Profile</a>
        <a href="apply.php" class="sidebar-link">Apply Now</a>
        <a href="resp.php" class="sidebar-link">Your Queries</a>
        <?php
    // Check if the user is present in the leads table
    $sql_check_leads = "SELECT * FROM leads WHERE id = '$user_id'";
    $result_check_leads = $conn->query($sql_check_leads);
    if ($result_check_leads->num_rows > 0) {
        echo '<a href="schedule_events.php" class="sidebar-link">Schedule Events</a>';
        echo '<a href="delete_events.php" class="sidebar-link">Delete Events</a>';
    }
    ?>
        <a href="#" class="sidebar-link">Settings</a>
        <a href="./index2.html" class="sidebar-link">Logout</a>
    </div>
    <div class="main-content">
        <?php $row = $result->fetch_assoc()?>
        <?php $uname =$row["user_name"];?>
        <h2>Welcome <?php echo $uname?></h2>
        <h3>Events Participated:</h3>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card custom-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row["event_name"]; ?></h5>
                                <p class="card-text">Start Time: <?php echo $row["start_time"]; ?></p>
                                <p class="card-text">End Time: <?php echo $row["end_time"]; ?></p>
                                <p class="card-text">Venue: <?php echo $row["venue"]; ?></p>
                                <?php if ($row["certifiability"] === 'Y') { ?>
                                <a href="generate_certificate.php?user_name=<?php echo urlencode($row["user_name"]); ?>&event_name=<?php echo urlencode($row["event_name"]); ?>" target="_blank" class="btn btn-dark">Click to download Certificate</a>

                                <?php } ?>
                                <a href="feedback.php?event_id=<?php echo $row['event_id']; ?>&user_id=<?php echo $user_id; ?>" class="btn btn-primary">+ Feedback/Query</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col">
                    <p>No events participated yet.</p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();


?>
