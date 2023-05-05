<?php
// Retrieve the submitted accountID and password from the form
$accountID = $_POST['email_or_username'];
$password = $_POST['password'];

// Database connection parameters
$dbhost = "127.0.0.1";
$dbname = "dte";
$dbuser = "root";
$dbpass = "1234";

try {
    // Connect to the database
    $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement to retrieve account information
    $stmt = $db->prepare("SELECT * FROM Account WHERE accountID = :accountID");
    $stmt->bindParam(':accountID', $accountID, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the account is found
    if ($stmt->rowCount() > 0) {
        // Fetch account information
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the entered password against the password in the database
        if (password_verify($password, $account['password'])) {
            // Check account level and redirect accordingly
            if ($account['level'] === 'Admin') {
                header("Location: admin.html");
            } elseif ($account['level'] === 'Customer') {
                header("Location: booking.html");
            } else {
                // Redirect back to the login page with an error message
                header("Location: login.html?error=Invalid account level");
            }
        } else {
            // Redirect back to the login page with an error message
            header("Location: login.html?error=Invalid password");
        }
    } else {
        // Redirect back to the login page with an error message
        header("Location: login.html?error=Invalid accountID");
    }

} catch (PDOException $e) {
    // Display an error message if there's an issue with the database connection
    echo "Error: " . $e->getMessage();
}
?>
