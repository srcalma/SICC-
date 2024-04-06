<?php
session_start();

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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect user to login page
    header("Location: USER-LOGIN.html");
    exit;
}

// Retrieve form data from POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from session (assuming you have set this during login)
    $user_id = $_SESSION['user_id'];
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $departure_date = $_POST['departure_date'];
    $departure_time = $_POST['departure_time'];

    // Convert departure_date to proper date format
    $timestamp = strtotime($departure_date);
    $formatted_departure_date = date('Y-m-d', $timestamp);

    // Convert departure_time to proper time format
    $timestamp = strtotime($departure_time);
    $formatted_departure_time = date('H:i:s', $timestamp);

    // Find ShuttleID based on DepartureLocation and ArrivalLocation
    $sql = "SELECT RouteID FROM ShuttleDetails WHERE DepartureLocation = ? AND ArrivalLocation = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $pickup, $dropoff);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Shuttle found, fetch RouteID
        $row = $result->fetch_assoc();
        $RouteID = $row['RouteID'];

        // Insert reservation into Reservations table
        $status = "Reserved"; // Default status
        $sql2 = "INSERT INTO Reservations (RouteID, UserID, ReservationDate, ReservationTime, SeatNumber, Status)
            VALUES (?, ?, ?, ?, NULL, ?)";
        $stmt = $conn->prepare($sql2);
        $stmt->bind_param("iisss", $RouteID, $user_id, $formatted_departure_date, $formatted_departure_time, $status);
 
        if ($stmt->execute()) {
            // Reservation successfully inserted
            echo "Reservation successfully created!";
            // Redirect user to a success page or perform further actions
            //header("Location: dashboard.php");
            exit;
        } else {
            // Error occurred while inserting reservation
            echo "Error: " . $stmt->error;
        }

        // Close statement and database connection
        $stmt->close();
    }
    
    $conn->close();

} else {
    // Redirect user if accessed directly without form submission
    //header("Location: RESERVE-A-SEAT.html");
    header("Location: dashboard.php");
    exit;
}
?>
