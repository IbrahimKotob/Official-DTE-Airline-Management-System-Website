<?php
function VarExist($var) {
    if (isset($var)) {
        return $var;
    } else {
        header("Location: ../admin.html");
    }
}

$flightID = VarExist($_POST["edit_flight_id"]);
$flightName = VarExist($_POST["edit_flight_name"]);
$departureAirport = VarExist($_POST["edit_departure"]);
$arrivalAirport = VarExist($_POST["edit_destination"]);
$departureDate = VarExist($_POST["edit_Departure_Date"]);
$returnDate = VarExist($_POST["edit_Return_Date"]);

editFlight($flightID, $flightName, $departureAirport, $arrivalAirport, $departureDate, $returnDate);

function editFlight($flightID, $flightName, $departureAirport, $arrivalAirport, $departureDate, $returnDate) {
    $dbhost = "127.0.0.1";
    $dbname = "dte";
    $dbuser = "root";
    $dbpass = "1234";
    try {
        $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    // Check if departure and arrival airports exist in the Airport table and are not the same
    $query = "SELECT airportID, name FROM Airport WHERE name = :departureAirport OR name = :arrivalAirport";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':departureAirport', $departureAirport);
    $stmt->bindParam(':arrivalAirport', $arrivalAirport);
    $stmt->execute();
    $airports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($airports) == 2 && $airports[0]['name'] != $airports[1]['name']) {
        $departureAirportID = $airports[0]['airportID'];
        $arrivalAirportID = $airports[1]['airportID'];

        // Prepare an array of columns to be updated and their values
        $updates = array(
            'flightName' => $flightName,
            'departureAirportID' => $departureAirportID,
            'arrivalAirportID' => $arrivalAirportID,
            'departureTime' => $departureDate,
            'returnTime' => $returnDate
        );

        // Remove any NULL values from the updates array
        $updates = array_filter($updates, function($value) {
            return !is_null($value);
        });

        // Generate the SQL query dynamically
        $queryFlight = "UPDATE Flight SET ";
        foreach ($updates as $column => $value) {
            $queryFlight .= "$column = :$column, ";
        }
        $queryFlight = rtrim($queryFlight, ', '); // Remove trailing comma
        $queryFlight .= " WHERE flightID = :flightID";
        
        $stmt = $db->prepare($queryFlight);
        $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);

        // Bind the parameters dynamically
        foreach ($updates as $column => &$value) {
            $stmt->bindParam(":$column", $value);
        }

        $stmt->execute();

        header("Location: admin.html");
    } else {
        // Handle error: airports do not exist or are the same
        echo "Error: Departure and arrival airports must exist in the Airport table and must not be the same";
      }
}?>