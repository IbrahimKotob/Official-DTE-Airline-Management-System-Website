<?php
function VarExist($var) {
    if (isset($var)) {
        return $var;
    } else {
        header("Location: ../admin.html");
    }
}

$flightID = VarExist($_POST["assign_flight_id"]);
$pilotID = VarExist($_POST["assign_pilot"]);

assignPilotToFlight($flightID, $pilotID);

function assignPilotToFlight($flightID, $pilotID) {
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

    // Update the FlightID in the Pilot table
    $queryUpdate = "UPDATE Pilot SET FlightID = :flightID WHERE PilotID = :pilotID";
    $stmt = $db->prepare($queryUpdate);
    $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
    $stmt->bindParam(':pilotID', $pilotID, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: admin.html");
}
?>
