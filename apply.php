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

// Fetch clubs and positions from the database
$clubs_query = "SELECT id, name FROM clubs";
$clubs_result = $conn->query($clubs_query);

$positions_query = "SELECT DISTINCT position FROM members";
$positions_result = $conn->query($positions_query);

// Fetch user's name
$uid = $_SESSION["user_id"];
$name_query = "SELECT name FROM people WHERE ID = '$uid'";
$name_result = $conn->query($name_query);
$row = $name_result->fetch_assoc();
$uname = $row["name"];

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $club_id = $_POST["club"];
    $position = $_POST["position"];
    
    // Retrieve club lead email from the database
    $club_lead_query = "SELECT leads.id FROM leads,(SELECT id from clubs where name = '$club_id') as E where E.id = leads.id";
    $club_lead_result = $conn->query($club_lead_query);
    $club_lead_row = $club_lead_result->fetch_assoc();
    $club_lead_email = $club_lead_row["lead_email"];
    $club_lead_email = $club_lead_email . "@iiitdm.ac.in";
    // Compose email
    $to = $club_lead_email;
    $subject = "New Application Submission";
    $message = "A new application has been submitted for the position of $position in $club_id Club.";
    $headers = "From: your@example.com";

    // Send email
    mail($to, $subject, $message, $headers);
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Now</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #c0c0c0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container-wrapper {
            background-color: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .container {
            background-color: #90EE90;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
        }
        .form-group label {
            font-weight: bold;
            color: #007bff;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
        }
        select.form-control,
        input.form-control {
            border-radius: 25px;
            border-color: #007bff;
            padding: 12px;
            height: auto;
            overflow: visible;
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
    <div class="container">
        <h2>Recruitment Drive <?php echo date("Y"); ?></h2>
        <form action="test.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $uname; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="id">ID:</label>
                <input type="text" class="form-control" id="id" name="id" value="<?php echo htmlspecialchars($_SESSION["user_id"]); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="club">Club:</label>
                <select class="form-control" id="club" name="club">
                    <?php
                    if ($clubs_result->num_rows > 0) {
                        while($club = $clubs_result->fetch_assoc()) {
                            echo "<option value='" . $club["id"] . "'>" . $club["name"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="position">Position:</label>
                <select class="form-control" id="position" name="position">
                    <?php
                    if ($positions_result->num_rows > 0) {
                        while($position = $positions_result->fetch_assoc()) {
                            echo "<option value='" . $position["position"] . "'>" . $position["position"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
