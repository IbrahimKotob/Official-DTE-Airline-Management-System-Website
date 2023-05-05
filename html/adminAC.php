<?php
function VarExist($var) {
    if (isset($var)) {
        return $var;
    } else {
        header("Location: ../admin.html");
    }
}

$flightID = VarExist($_POST["assign_flight_id"]);
$crewID = VarExist($_POST["assign_cabin_crew"]);

assignCrewToFlight($flightID, $crewID);

function assignCrewToFlight($flightID, $crewID) {
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

    // Update the FlightID in the Crew_Member table
    $queryUpdate = "UPDATE Crew_Member SET FlightID = :flightID WHERE CrewID = :crewID";
    $stmt = $db->prepare($queryUpdate);
    $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
    $stmt->bindParam(':crewID', $crewID, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: admin.html");
}
?>
