<?php
function VarExist($var) {
    if (isset($var)) {
        return $var;
    } else {
        header("location:../admin.html");
    }
}

$user = new stdClass();
//creating the variables for user
$user->firstName = VarExist($_POST["firstName"]);
$user->lastName = VarExist($_POST["lastName"]);
$user->email = VarExist($_POST["email"]);
$user->username = VarExist($_POST["username"]);
$user->password = VarExist($_POST["password"]);
$user->address = VarExist($_POST["address"]);
$user->phone = VarExist($_POST["phone"]);
$user->city = VarExist($_POST["city"]);
$user->country = VarExist($_POST["country"]);
$user->postalCode = VarExist($_POST["postalCode"]);
$user->birthdate = VarExist($_POST["birthdate"]);
$user->level = VarExist($_POST["level"]);

InsertUserToDBfromObject($user);

function InsertUserToDBfromObject($user) {
    // Hash the password
    $hashedPassword = password_hash($user->password, PASSWORD_DEFAULT);

    // Calculate the age
    $today = new DateTime();
    $birthdate = new DateTime($user->birthdate);
    $age = $birthdate->diff($today)->y;
    $formattedBirthdate = $birthdate->format('Y-m-d');

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

    // Insert data into the Person table
    $queryPerson = "INSERT INTO Person (name, address, email, phone)
    VALUES ('$user->firstName $user->lastName', '$user->address', '$user->email', '$user->phone')";

    $db->query($queryPerson);

    // Get the last inserted personID
    $personID = $db->lastInsertId();

    // Insert data into the Account table
    $queryAccount = "INSERT INTO Account (personID, password, status, level)
    VALUES ('$personID', '$hashedPassword', 'active', '$user->level')";

    $db->query($queryAccount);

    // Get the last inserted accountID
    $accountID = $db->lastInsertId();

    // Check the user's level to insert data into the Pilot or Crew_Member table
    if ($user->level === 'pilot') {
        $queryPilot = "INSERT INTO Pilot (accountID, FirstName, LastName, DOB, Age, Email, Username, Password, Address, PhoneNumber, City, Country, PostalCode)
        VALUES ('$accountID', '$user->firstName', '$user->lastName', '$formattedBirthdate', '$age', '$user->email', '$user->username', '$hashedPassword', '$user->address', '$user->phone', '$user->city', '$user->country', '$user->postalCode')";
        $stmt = $db->query($queryPilot);
    } elseif ($user->level === 'crew') {
        $queryCrewMember = "INSERT INTO Crew_Member (accountID, FirstName, LastName, DOB, Age, Email, Username, Password, Address, PhoneNumber, City, Country, PostalCode)
        VALUES ('$accountID', '$user->firstName', '$user->lastName', '$formattedBirthdate', '$age', '$user->email', '$user->username', '$hashedPassword', '$user->address', '$user->phone', '$user->city', '$user->country', '$user->postalCode')";
        $stmt = $db->query($queryCrewMember);
    }

    header("Location: admin.html");
}
?>
