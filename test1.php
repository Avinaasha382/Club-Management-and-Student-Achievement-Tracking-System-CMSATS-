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

// Check if event_id and query are set in POST data
if (isset($_GET["event_id"]) && isset($_GET["query"])) {
	$evid = $_GET["event_id"];
    $query = $_GET["query"];

    // Fetch user's name
    $uid = $_SESSION["user_id"];
    $name_query = "SELECT name FROM people WHERE ID = '$uid'";
    $name_result = $conn->query($name_query);
    $row = $name_result->fetch_assoc();
    $uname = $row["name"];

    // Retrieve club lead email from the database
    $club_lead_query = "
        SELECT leads.id as X 
        FROM leads 
        JOIN events ON leads.club_id = events.club_id 
        WHERE events.event_id = '$evid'";
    $club_lead_result = $conn->query($club_lead_query);
    $club_lead_row = $club_lead_result->fetch_assoc();
    $club_lead_email = $club_lead_row["X"];
    $club_lead_email = $club_lead_email . "@iiitdm.ac.in";

    // Send email
    include('smtp/PHPMailerAutoload.php');
    echo smtp_mailer($club_lead_email, 'Feedback/Query for ' . $evid, 
        'Name: ' . $uname . '<br>' . 
        'ID: ' . $uid . '<br>' . 
        'Event ID: ' . $evid . '<br>' . 
        'Feedback/Query: ' . $query
    );
} else {
    echo "Event ID and query are required.";
}

function smtp_mailer($to, $subject, $msg) {
    $mail = new PHPMailer(); 
    $mail->IsSMTP(); 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587; 
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    //$mail->SMTPDebug = 2; 
    $mail->Username = "adityapillai786@gmail.com";
    $mail->Password = "1234 1234 1234 1234";
    $mail->SetFrom("adityapillai786@gmail.com");
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($to);
    $mail->SMTPOptions = array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    ));
    if (!$mail->Send()) {
        return $mail->ErrorInfo;
    } else {
        return 'Your Application has been submitted';
    }
}
?>
