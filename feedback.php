<?php
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

$user_id = $_SESSION["user_id"];
$uid = $_SESSION["user_id"];
$name_query = "SELECT name FROM people WHERE ID = '$uid'";
$name_result = $conn->query($name_query);
$row = $name_result->fetch_assoc();
$uname = $row["name"];
$event_id = $_GET["event_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = $_POST["query"];
    $date = date('Y-m-d');

    $sql = "INSERT INTO feedback (query, response, date, event_id, id) VALUES ('$query', '', '$date', '$event_id', '$uid')";

    if ($conn->query($sql) === TRUE) {
        // Database insertion successful, redirect to test1.php
        header("Location: test1.php?event_id=" . urlencode($_POST["event_id"]) . "&query=" . urlencode($_POST["query"]));
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #c0c0c0;
        }
        .feedback-container {
            background-color: #90EE90;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: 50px auto;
        }
        .feedback-container h2 {
            color: #343a40;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="feedback-container">
        <h2 class="text-center">Submit Your Feedback/Query</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="user_name">User Name</label>
                <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $uname; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="event_id">Event ID</label>
                <input type="text" class="form-control" id="event_id" name="event_id" value="<?php echo $event_id; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="query">Query</label>
                <textarea class="form-control" id="query" name="query" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
    </div>
</body>
</html>
