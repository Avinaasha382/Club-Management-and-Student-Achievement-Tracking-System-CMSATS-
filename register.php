<?php
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

// Fetch values from the registration form
$name = $_POST['name'];
$id = $_POST['ID'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    echo "Passwords don't match!";
} else {
    // Construct email by appending "@iiitdm.ac.in" to the ID
    $email = $id . "@iiitdm.ac.in";

    // Prepare SQL statement to insert data into the table
    $sql = "INSERT INTO people (id, password, email, points, name) VALUES (?, ?, ?, 0, ?)";

    // Prepare and bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $id, $password, $email, $name);

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        echo "New record created successfully!";
        echo '<a href="login.html">Go back to login page</a>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
