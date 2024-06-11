<?php
session_start();
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

// Retrieve form data
$email = $_POST['email'];
$password = $_POST['password'];
#$role = $_POST['role'];

// Perform authentication (example query)
$sql = "SELECT * FROM people WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Fetch user data
    $user_data = $result->fetch_assoc();

    // Extract the first 9 characters of email as pseudo-id
    $pseudo_id = substr($user_data['email'], 0, 9);

    // Store user information in session variables
    $_SESSION['user_id'] = $pseudo_id;
    header("Location: dashboard.html");
    exit();
} else {
    // Display error message if authentication fails
    echo "Invalid email or password.";
}

$conn->close();
?>
