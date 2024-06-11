<?php
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

$club_id = $_POST["club"];
$position = $_POST["position"];
//echo $club_id;
// Retrieve club lead email from the database
$club_lead_query = "SELECT leads.id as X from leads where leads.club_id = '$club_id'";
$club_lead_result = $conn->query($club_lead_query);
$club_lead_row = $club_lead_result->fetch_assoc();
$club_lead_email = $club_lead_row["X"];
$club_lead_email = $club_lead_email . "@iiitdm.ac.in";

//echo $club_lead_email;





include('smtp/PHPMailerAutoload.php');

echo smtp_mailer($club_lead_email, 'Recruitment Drive 2024 Application', 
    'Name: ' . $uname . '<br>' . 
    'ID: ' . $uid . '<br>' . 
    'Club: ' . $club_id . '<br>' . 
    'Position: ' . $position
);

function smtp_mailer($to,$subject, $msg){
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
	$mail->Body =$msg;
	$mail->AddAddress($to);
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	if(!$mail->Send()){
		echo $mail->ErrorInfo;
	}else{
		return 'Your Application has been submitted';
	}
}
?>
