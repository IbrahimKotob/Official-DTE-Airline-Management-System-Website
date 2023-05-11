<?php
session_start();

if (!isset($_SESSION['email_or_username'])) {
<<<<<<< HEAD
    echo "Failure: Please log in to book a flight";
=======
    $_SESSION['warning'] = "Please log in to book a flight";
    header("Location: login.html"); // replace with your login page
>>>>>>> devbranch
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_username = $_SESSION['email_or_username'];
    $meal = $_POST['meal'];
    $seat_type = $_POST['seat'];
    $accommodation = $_POST['accommodation'];
    $flightID = $_POST['flightID'];

<<<<<<< HEAD
    echo "email_or_username: " . $email_or_username . "\n";
    echo "meal: " . $meal . "\n";
    echo "seat_type: " . $seat_type . "\n";
    echo "accommodation: " . $accommodation . "\n";
    echo "flightID: " . $flightID . "\n";

=======
>>>>>>> devbranch
    // Database connection parameters
    $dbhost = "127.0.0.1";
    $dbname = "dte";
    $dbuser = "root";
    $dbpass = "1234";

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
<<<<<<< HEAD
        $specialNeeds = $customer['SpecialNeeds'];

        echo "customerID: " . $customerID . "\n";
        echo "specialNeeds: " . $specialNeeds . "\n";
=======
>>>>>>> devbranch

        // Find an available seat matching the selected preferences
        $stmt = $db->prepare("SELECT * FROM seat WHERE flightID = :flightID AND type = :seat_type AND seatClass = :accommodation AND occupied = 0 LIMIT 1");
        $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
        $stmt->bindParam(':seat_type', $accommodation, PDO::PARAM_STR);
        $stmt->bindParam(':accommodation', $seat_type, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $seat = $stmt->fetch(PDO::FETCH_ASSOC);
            $seatID = $seat['seatID'];

<<<<<<< HEAD
            echo "seatID: " . $seatID . "\n";

=======
>>>>>>> devbranch
            // Fetch departure time and price from the flight table using the flightID
            $stmt = $db->prepare("SELECT departureTime, price FROM flight WHERE flightID = :flightID");
            $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
            $stmt->execute();
            $flightDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            $departureTime = $flightDetails['departureTime'];
            $price = $flightDetails['price'];
            $paymentAmount = $price;

<<<<<<< HEAD
            echo "departureTime: " . $departureTime . "\n";
            echo "price: " . $price . "\n";
            echo "paymentAmount: " . $paymentAmount . "\n";
              // Mark the seat as occupied
              $stmt = $db->prepare("UPDATE seat SET occupied = 1 WHERE seatID = :seatID");
              $stmt->bindParam(':seatID', $seatID, PDO::PARAM_INT);
              $stmt->execute();
            // Insert the flight reservation into the database
            $stmt = $db->prepare("INSERT INTO flightreservation (meals, CustomerID, flightID, `payment Amount`, SpecialNeeds, reservation, Seat, Accomodation) VALUES (:meals, :customerID, :flightID, :paymentAmount, :specialNeeds, :departureTime, :seatID, :accommodation)");
$stmt->bindParam(':meals', $meal, PDO::PARAM_STR);
$stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
$stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
$stmt->bindParam(':paymentAmount', $paymentAmount, PDO::PARAM_INT);
$stmt->bindParam(':specialNeeds', $specialNeeds, PDO::PARAM_STR);
$stmt->bindParam(':seatID', $seatID, PDO::PARAM_INT);
$stmt->bindParam(':departureTime', $departureTime, PDO::PARAM_STR);
$stmt->bindParam(':accommodation', $accommodation, PDO::PARAM_STR); // Bind the accommodation variable
$stmt->execute();

echo "Success: Flight reservation successfully created";
    }} catch (PDOException $e) {
        // Display an error message if there's an issue with the database connection
        echo "Failure: " . $e->getMessage();
    }
    } else {
    // Display an error message if the request method is invalid
    echo "Failure: Invalid request method";
    }
    ?>
=======
            // Mark the seat as occupied
            $stmt = $db->prepare("UPDATE seat SET occupied = 1 WHERE seatID = :seatID");
            $stmt->bindParam(':seatID', $seatID, PDO::PARAM_INT);
            $stmt->execute();

            // Insert the flight reservation into the database
            $stmt = $db->prepare("INSERT INTO flightreservation (meals, CustomerID, flightID, `payment Amount`, reservation, Seat, Accomodation) VALUES (:meals, :customerID, :flightID, :paymentAmount,:departureTime, :seatID, :accommodation)");
            $stmt->bindParam(':meals', $meal, PDO::PARAM_STR);
            $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
            $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
            $stmt->bindParam(':paymentAmount', $paymentAmount, PDO::PARAM_STR);
            $stmt->bindParam(':departureTime', $departureTime, PDO::PARAM_STR);
            $stmt->bindParam(':seatID', $seatID, PDO::PARAM_INT);
            $stmt->bindParam(':accommodation', $accommodation, PDO::PARAM_STR);
            $stmt->execute();

            // Calculate points earned from this flight and update customer's points
            $pointsEarned = $price / 10;  // 1 point for every $10 spent, adjust as needed
            $newPoints = $customer['Points'] + $pointsEarned;
            $stmt = $db->prepare("UPDATE Customer SET Points = :newPoints WHERE CustomerID = :customerID");
            $stmt->bindParam(':newPoints', $newPoints, PDO::PARAM_INT);
            $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success'] = "Flight booked successfully!";
            header("Location: schedule.php"); // replace with your confirmation page
            exit;
        } else {
            $_SESSION['error'] = "No available seats matching your preferences. Please select different options.";
            header("Location: pref.php"); // replace with your booking page
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: pref.php"); // replace with your booking page
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request. Please make a booking through the booking page.";
    header("Location: pref.php"); // replace with your booking page
    exit;
}
?>
>>>>>>> devbranch
