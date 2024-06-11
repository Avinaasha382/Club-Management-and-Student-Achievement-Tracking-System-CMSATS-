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

// Fetch user's queries and feedback from the feedback table
$user_id = $_SESSION["user_id"];
$sql = "SELECT query, response FROM feedback WHERE id = '$user_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{
            background-color: #c0c0c0;
        }
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body, .card-footer {
            padding: 20px;
            flex: 1; /* Make card body and footer flexible */
        }

        .query-card {
            background-color: #9370DB;
        }

        .response-card {
            background-color: #d4edda;
        }

        .card-title {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Your Queries</h2>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body query-card">
                                <h5 class="card-title">Query</h5>
                                <p class="card-text"><?php echo $row["query"]; ?></p>
                            </div>
                            <div class="card-footer response-card">
                                
                                <?php if (!empty($row["response"])) { ?>
                                    <h5 class="card-title">Response</h5>
                                    <p class="card-text"><?php echo $row["response"]; ?></p>
                                <?php } else { ?>
                                    <p class="card-text">Your query is currently under review, and we're committed to providing you with a timely response. Thank you for your patience.</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="col">
                    <div class="alert alert-info mt-4" role="alert">
                        No queries and responses found.
                    </div>
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