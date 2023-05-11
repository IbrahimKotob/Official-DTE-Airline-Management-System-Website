<?php //Adjusting flight details in the schedule
// Check if the necessary POST parameters are set
if (isset($_POST['flightID']) && isset($_POST['customerID']) && isset($_POST['meal'])) {
    // Database connection parameters
    $dbhost = "127.0.0.1";
    $dbname = "dte";
    $dbuser = "root";
    $dbpass = "1234";

    // Get the POST parameters
    $flightID = $_POST['flightID'];
    $customerID = $_POST['customerID'];
    $meal = $_POST['meal'];

    try {
        // Connect to the database
        $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement to update the meal type for the given flightID and customerID
        $stmt = $db->prepare("UPDATE flightreservation SET meals = :meal WHERE flightID = :flightID AND CustomerID = :customerID");
        $stmt->bindParam(':flightID', $flightID, PDO::PARAM_INT);
        $stmt->bindParam(':customerID', $customerID, PDO::PARAM_INT);
        $stmt->bindParam(':meal', $meal, PDO::PARAM_STR);

        // Execute the SQL statement
        $stmt->execute();

        // Check if any rows were updated
        if ($stmt->rowCount() > 0) {
            echo "Meal type updated successfully!";
        } else {
            throw new Exception('No rows were updated. Check if the flightID and customerID are correct.');
        }
    } catch (PDOException $e) {
        // Display an error message if there's an issue with the database connection
        echo "Error: " . $e->getMessage();
    } catch (Exception $e) {
        // Display an error message if no rows were updated
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Missing POST parameters.";
}
?>
