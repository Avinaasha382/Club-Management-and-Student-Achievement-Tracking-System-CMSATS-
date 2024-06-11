<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Fetch user information
$user_id = $_SESSION["user_id"];
$sql = "SELECT name, points FROM people WHERE ID = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
$uname = $user["name"];
// Calculate user's rank based on points
$points = $user["points"];
$rank_query = "SELECT COUNT(*) AS rank FROM people WHERE points > '$points'";
$rank_result = $conn->query($rank_query);
$rank = $rank_result->fetch_assoc()["rank"] + 1;

// Fetch user's position in the club
// Fetch user's positions in the club
// Fetch user's positions and corresponding club names
$positions_query = "SELECT members.position, clubs.name AS club_name 
                    FROM members 
                    INNER JOIN clubs ON members.club_id = clubs.id 
                    WHERE members.id = '$user_id'";
$positions_result = $conn->query($positions_query);
$positions = [];
if ($positions_result) {
    while ($row = $positions_result->fetch_assoc()) {
        $positions[] = [
            "club_name" => $row["club_name"],
            "position" => $row["position"]
        ];
    }
}


// Count the number of events the user participated in
$events_query = "SELECT COUNT(*) AS num_events FROM participates WHERE id = '$user_id'";
$events_result = $conn->query($events_query);
$num_events = $events_result->fetch_assoc()["num_events"];

$eq = "SELECT COUNT(*) as te FROM events";
$er = $conn->query($eq);
$total_events = $er->fetch_assoc()["te"];

// Fetch all points from the people table
$points_query = "SELECT points FROM people";
$points_result = $conn->query($points_query);

// Store points in an array
$points_data = [];
if ($points_result->num_rows > 0) {
    while ($row = $points_result->fetch_assoc()) {
        $points_data[] = $row["points"];
    }
}

// Close connection


// Calculate mean and standard deviation for normal distribution
$mean = array_sum($points_data) / count($points_data);
$std_dev = sqrt(array_sum(array_map(function($x) use ($mean) { return pow($x - $mean, 2); }, $points_data)) / count($points_data));


// Generate normal distribution data points
$normal_distribution_data = [];
$x_values = range(min($points_data), max($points_data), 1);

foreach ($x_values as $x) {
    $y = (1 / ($std_dev * sqrt(2 * pi()))) * exp(-0.5 * pow(($x - $mean) / $std_dev, 2));
    $normal_distribution_data[] = ['x' => $x, 'y' => $y];
}

// Encode data as JSON
$normal_distribution_json = json_encode($normal_distribution_data);

function normal_cdf($x, $mean, $std_dev) {
    $z = ($x - $mean) / $std_dev;
    return 0.5 * (1 + erf($z / sqrt(2)));
}

function erf($x) {
    // constants
    $a1 =  0.254829592;
    $a2 = -0.284496736;
    $a3 =  1.421413741;
    $a4 = -1.453152027;
    $a5 =  1.061405429;
    $p  =  0.3275911;

    // Save the sign of x
    $sign = $x >= 0 ? 1 : -1;
    $x = abs($x);

    // A&S formula 7.1.26
    $t = 1.0 / (1.0 + $p * $x);
    $y = 1.0 - ((((( $a5 * $t + $a4) * $t) + $a3) * $t + $a2) * $t + $a1) * $t * exp(-$x * $x);

    return $sign * $y;
}

// Calculate the area under the curve from 25 to $points
$area = normal_cdf($points, $mean, $std_dev) - normal_cdf(25, $mean, $std_dev);
$area1 = normal_cdf(105, $mean, $std_dev) - normal_cdf(25, $mean, $std_dev);
$frac = $area/$area1;
$frac = 100 - 100*$frac;
$frac = round($frac,1);

$top_users_query = "SELECT name, points FROM people ORDER BY points DESC LIMIT 5";
$top_users_result = $conn->query($top_users_query);
$top_users = [];
if ($top_users_result) {
    while ($row = $top_users_result->fetch_assoc()) {
        $top_users[] = $row;
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.7/chartjs-plugin-annotation.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>


    <style>
        body {
            background-color: rgb(26 26 26);
            display: flex; /* Use flexbox layout */
            flex-direction: row; /* Arrange items horizontally */
            justify-content: flex-start; /* Align items at the start of the container */
            align-items: stretch; /* Stretch items to fill the container vertically */
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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
        .card {
            background-color: #c0c0c0;
            margin-top: 20px;
            transition: transform 0.3s ease-in-out;
        }
        #x{
            background-color: #FFD700;
        }
        .card:hover {
        transform: scale(1.05); /* Increase scale on hover */
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <h1 class="text-center"><?php echo $uname ?></h1>
        <h4 class="text-center"><?php echo $user_id ?></h4>
        <a href="./dashboard.html" class="sidebar-link">Dashboard</a>
        <a href="resp.php" class="sidebar-link">Your Queries</a>
        <a href="#" class="sidebar-link">Settings</a>
        <a href="./index2.html" class="sidebar-link">Logout</a>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Participation this Year</h5>
                        <canvas id="eventsPieChart" width="400" height="400"></canvas>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Your Standings: Top <?php echo $frac ?>%</h5>
                        <canvas id="normalDistributionChart" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">User Rank</h5>
                                <p class="card-text">Rank: <?php echo $rank; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">User Points</h5>
                                <p class="card-text">Points: <?php echo $points; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Events Participated</h5>
                                <p class="card-text">Number of Events: <?php echo $num_events; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
    <div class="col-md-12">
        <div class="card" id="x">
            <div class="card-body">
                <h5 class="card-title">Position(s) in Club</h5>
                <ul class="list-unstyled">
                    <?php foreach ($positions as $position) { ?>
                        <li><?php echo $position["club_name"] . "-" . $position["position"]; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Leaderboard</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Rank</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($top_users as $index => $user) { ?>
                                            <tr>
                                                <th scope="row"><?php echo $index + 1; ?></th>
                                                <td><?php echo $user["name"]; ?></td>
                                                <td><?php echo $user["points"]; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Get the canvas element
        var ctx = document.getElementById('eventsPieChart').getContext('2d');

        // Prepare data
        var eventsParticipated = <?php echo $num_events; ?>;
        var totalEvents = <?php echo $total_events; ?>;
        var eventsNotParticipated = totalEvents - eventsParticipated;

        // Calculate percentages
        var participatedPercentage = (eventsParticipated / totalEvents) * 100;
        var notParticipatedPercentage = 100 - participatedPercentage;

        // Create pie chart
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Events Participated', 'Events Not Participated'],
                datasets: [{
                    label: 'Events Participated',
                    data: [participatedPercentage, notParticipatedPercentage],
                    backgroundColor: ['#36a2eb', '#ff6384']
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Events Not Participated'
                }
            }
        });
    </script>
<script>
    // Retrieve normal distribution data from PHP
    var normalDistributionData = <?php echo $normal_distribution_json; ?>;
    
    // Extract x and y values
    var xValues = normalDistributionData.map(function(point) { return point.x; });
    var yValues = normalDistributionData.map(function(point) { return point.y; });
    
    // Get the user's points
    var userPoints = <?php echo $points; ?>;
    
    // Get canvas context
    var ctx = document.getElementById('normalDistributionChart').getContext('2d');
    
    // Create Chart.js chart
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: xValues,
            datasets: [{
                label: 'Standings',
                data: yValues,
                borderColor: 'red',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
    scales: {
        xAxes: [{
            scaleLabel: {
                display: true,
                labelString: 'Points'
            }
        }],
        yAxes: [{
            scaleLabel: {
                display: true,
                labelString: 'Probability Density'
            }
        }]
    },
    annotation: {
        annotations: [{
            type: 'line',
            mode: 'vertical',
            scaleID: 'x-axis-0',
            value: <?php echo $points; ?>, // Ensure $points contains the user's point value
            borderColor: 'red',
            borderWidth: 4,
            label: {
                content: 'You are here',
                enabled: true,
                backgroundColor: 'red',
                fontColor: 'black',
                fontStyle: 'bold',
                position: 'top'
            }
        }]
    }
}

    });
</script>

</body>
</html>
