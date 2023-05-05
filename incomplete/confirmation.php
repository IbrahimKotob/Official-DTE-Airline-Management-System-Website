<?php
// confirmation.php

// Database connection
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the booking ID from the query parameter
$booking_id = $_GET['booking_id'];

// Use placeholders and prepared statements for better security
$sql = "SELECT b.id, f.flightNumber, f.departure, f.destination, f.departureTime, f.arrivalTime, b.passengers, b.preferences
        FROM Booking b
        JOIN Flights f ON b.flightID = f.id
        WHERE b.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();

$result = $stmt->get_result();
$booking = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Include the HTML template for the confirmation page
include 'Confirmation.html';
?>
