<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect user to login page
    header("Location: USER-LOGIN.html");
    exit;
}

// Database connection parameters
$host = "127.0.0.1";
$user = "root";
$password = "P@ssW0rd123";
$dbname = "siccplus";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // 'i' indicates the type of parameter (integer)
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $first_name = $user['FirstName'];
} else {
    // Handle error if user is not found
    die("User not found");
}

$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection

// Include the HTML code for the dashboard
require_once '../SITE2/ADMIN-DASHBOARD.html';
?>
