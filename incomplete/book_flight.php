<?php
// book_flight.php

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "1234";
$dbname = "dte";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$selected_flight = $_POST['selected_flight'];
$passengers = $_POST['passengers'];
$preferences = $_POST['preferences'];

// Use placeholders and prepared statements for better security
$sql = "INSERT INTO Booking (flightID, userID, passengers, preferences)
        VALUES (?, ?, ?, ?)";

// Replace 'userID' with the actual user ID from your authentication system
$userID = 1;

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $selected_flight, $userID, $passengers, $preferences);
$stmt->execute();

// Redirect to the confirmation page
if ($stmt->affected_rows > 0) {
  $booking_id = $conn->insert_id;
  header("Location: Confirmation.html?booking_id=" . $booking_id);
} else {
  echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>
