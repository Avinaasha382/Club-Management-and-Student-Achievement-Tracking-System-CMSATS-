<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background-color: #c0c0c0;
        }
        .protrude-card {
            transition: transform 0.3s ease;
        }
        .protrude-card:hover {
            transform: translateY(-5px);
        }
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
            background-color: #0000; /* Darker background color on hover */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Events</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <h2>Events</h2>
        <div class="row">
            <?php
            // Connect to the database
            $servername = "sql6.freesqldatabase.com";
            $username = "sql6705819";
            $password = "hLLrdKwXFB";
            $dbname = "sql6705819";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Fetch events from the database
            $sql = "SELECT * FROM events";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $card_color = ''; // Variable to store card color
                    $certifiability_message = ''; // Variable to store certificate availability message
                    if ($row["certifiability"] == 'Y') {
                        $card_color = 'bg-success'; // Green background color for events with certificate availability
                        $certifiability_message = '<p class="card-text text-white">Certificates available</p>';
                    } else {
                        $card_color = 'bg-primary'; // Blue background color for events without certificate availability
                        $certifiability_message = '<p class="card-text text-white">Certificates not available</p>';
                    }
            ?>      <div class="sidebar">
                        <h1 class="text-center"></h1>
                        <h4 class="text-center"></h4>
                        <a href="./dashboard.html" class="sidebar-link">Dashboard</a>
                        <a href="resp.php" class="sidebar-link">Your Queries</a>
                        <a href="#" class="sidebar-link">Settings</a>
                        <a href="./index2.html" class="sidebar-link">Logout</a>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 <?php echo $card_color; ?> text-white protrude-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row["event_name"]; ?></h5>
                                <p class="card-text"><?php echo $row["start_time"]; ?> - <?php echo $row["end_time"]; ?></p>
                                <p class="card-text"><?php echo $row["venue"]; ?></p>
                                <?php echo $certifiability_message; ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No events found.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
