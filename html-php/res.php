<?php
session_start();

if (!isset($_SESSION['email_or_username'])) {
    header("Location: login.php"); 
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_username = $_SESSION['email_or_username'];
    $meal = $_POST['meal'];
    $seat_type = $_POST['seat'];
    $accommodation = $_POST['accommodation'];
    $flightID = $_POST['flightID'];

    // Database connection parameters

    $dbhost = "localhost";
    $dbname = "id20739167_dte";
    $dbuser = "id20739167_root";
    $dbpass = "=U#Wq|Yfvtd2nd>r";

    try {
               // Connect to the database

        $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Retrieve customer information using the email or username

        $stmt = $db->prepare("SELECT Customer.* FROM Customer INNER JOIN Account ON Customer.accountID = Account.accountID INNER JOIN Person ON Account.personID = Person.personID WHERE Account.`User name` = :accountID OR Person.email = :accountID");
        $stmt->bindParam(':accountID', $email_or_username, PDO::PARAM_STR);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        $customerID = $customer['CustomerID'];

     
        // Find an available seat matching the selected preferences

        
        $stmt = $db->prepare("SELECT * FROM Seat WHERE flightID = :flightID AND type = :seat_type AND seatClass = :accommodation AND occupied = 0 LIMIT 1");
        $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
        $stmt->bindParam(':seat_type', $accommodation, PDO::PARAM_STR);
        $stmt->bindParam(':accommodation', $seat_type, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $seat = $stmt->fetch(PDO::FETCH_ASSOC);
            $seatID = $seat['seatID'];

                      // Fetch departure time and price from the flight table using the flightID

  
            $stmt = $db->prepare("SELECT departureTime, price FROM Flight WHERE flightID = :flightID");
            $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
            $stmt->execute();
            $flightDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            $departureTime = $flightDetails['departureTime'];
            $price = $flightDetails['price'];
            $paymentAmount = $price;

                     // Mark the seat as occupied

            $stmt = $db->prepare("UPDATE Seat SET occupied = 1 WHERE seatID = :seatID");
            $stmt->bindParam(':seatID', $seatID, PDO::PARAM_INT);
            $stmt->execute();

                      // Insert the flight reservation into the database

            $stmt = $db->prepare("INSERT INTO Flightreservation (meals, CustomerID, flightID, `payment Amount`, reservation, SeatID, Accomodation) VALUES (:meals, :customerID, :flightID, :paymentAmount, :departureTime, :seatID, :accommodation)");
            $stmt->bindParam(':meals', $meal, PDO::PARAM_STR);
            $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
            $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
            $stmt->bindParam(':paymentAmount', $paymentAmount, PDO::PARAM_INT);
            $stmt->bindParam(':seatID', $seatID, PDO::PARAM_INT);
            $stmt->bindParam(':departureTime', $departureTime, PDO::PARAM_STR);
            $stmt->bindParam(':accommodation', $accommodation, PDO::PARAM_STR); 
            $stmt->execute();
        // Calculate points earned from this flight and update customer's points for the loyalty program
        $pointsEarned = $price / 10;  // 1 point for every $10 spent
        $newPoints = $customer['Points'] + $pointsEarned;
        $stmt = $db->prepare("UPDATE Customer SET Points = :newPoints WHERE CustomerID = :customerID");
        $stmt->bindParam(':newPoints', $newPoints, PDO::PARAM_INT);
        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['success'] = "Flight booked successfully!";
        header("Location: schedule.php"); // In case of successfully booking, Redirect to Schedule Page
        exit;
    } else {
        $_SESSION['error'] = "No available seats matching your preferences. Please select different options.";
        header("Location: pref.php"); // Error handling , redirect to preferences Page
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: pref.php"); // Error handling , redirect to preferences Page
    exit;
}
} else {
$_SESSION['error'] = "Invalid request. Please make a booking through the booking page.";
header("Location: pref.php"); // Error handling , redirect to preferences Page
exit;
}
?>