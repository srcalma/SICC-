<?php
session_start();

// Database credentials (replace these with your actual database credentials)
$host = "localhost";
$user = "root";
$password = "P@ssW0rd123";
$dbname = "siccplus";

// Establish database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!<br>";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Display the received form values for debugging
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password . "<br>";

    // Prepare SQL query with prepared statement to prevent SQL injection
    $sql = "SELECT * FROM Users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // "ss" indicates two string parameters
    
    // Execute the prepared statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Login successful
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['UserID']; // Set session variables
        $_SESSION['username'] = $username;
        //$_SESSION['user_type_id'] = $row['UserTypeID'];

        // Redirect to dashboard page
        header("Location: dashboard.php");
        exit;
    } else {
        // Login failed
        echo "Invalid username or password.";
    }

    // Close statement
    $stmt->close();

    // You can perform further processing (e.g., authentication) here
} else {
    // Redirect to login page if accessed directly without form submission
    header("Location: USER-LOGIN.html");
    exit;
}

// Close database connection
$conn->close();
?>
