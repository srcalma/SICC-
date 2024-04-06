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

require_once '../SITE2/RESERVE-A-SEAT.html';
//header("Location: reservation.php");

?>