<?php
session_start();
$server = "localhost";
$username = "root";
$password = "";
$db = "journifly";

$conn = new mysqli($server, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is coming from a POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST data with input validation/sanitization
    $userID = isset($_POST['UserID']) ? filter_var($_POST['UserID'], FILTER_SANITIZE_NUMBER_INT) : null;
    if (!$userID) {
        die("Error: userID is not set in the form data. Please log in.");
    }

    $firstName = isset($_POST['FirstName']) ? filter_var($_POST['FirstName'], FILTER_SANITIZE_STRING) : '';
    $lastName = isset($_POST['LastName']) ? filter_var($_POST['LastName'], FILTER_SANITIZE_STRING) : '';
    $country = isset($_POST['Country']) ? filter_var($_POST['Country'], FILTER_SANITIZE_STRING) : '';
    $city = isset($_POST['City']) ? filter_var($_POST['City'], FILTER_SANITIZE_STRING) : '';
    $streetAddress = isset($_POST['StreetAddress']) ? filter_var($_POST['StreetAddress'], FILTER_SANITIZE_STRING) : '';
    $postcode = isset($_POST['Postcode']) ? filter_var($_POST['Postcode'], FILTER_SANITIZE_STRING) : '';
    $phone = isset($_POST['Phone']) ? filter_var($_POST['Phone'], FILTER_SANITIZE_STRING) : '';
    $email = isset($_POST['Email']) ? filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL) : '';
    $cardNumber = isset($_POST['CardNumber']) ? filter_var($_POST['CardNumber'], FILTER_SANITIZE_NUMBER_INT) : null;
    $cvv = isset($_POST['CVV']) ? filter_var($_POST['CVV'], FILTER_SANITIZE_STRING) : '';
    $expiryMonth = isset($_POST['ExpiryMonth']) ? filter_var($_POST['ExpiryMonth'], FILTER_SANITIZE_NUMBER_INT) : null;
    $expiryYear = isset($_POST['ExpiryYear']) ? filter_var($_POST['ExpiryYear'], FILTER_SANITIZE_NUMBER_INT) : null;

    // Generate a unique checkoutID
    $checkoutID = uniqid('checkout_');
    $_SESSION['checkoutID'] = $checkoutID;

    // SQL to insert data into checkoutdetails
    $sql = "INSERT INTO checkoutdetails (checkoutID, UserID, FirstName, LastName, Country, City, StreetAddress, Postcode, Phone, Email, CardNumber, CVV, ExpiryMonth, ExpiryYear) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the prepared statement as integers or strings
        $stmt->bind_param("sissssssssisii", $checkoutID, $userID, $firstName, $lastName, $country, $city, $streetAddress, $postcode, $phone, $email, $cardNumber, $cvv, $expiryMonth, $expiryYear);
        
        // Execute the statement
        if ($stmt->execute()) {
            echo "Checkout successfully processed!";
            // Redirect to a confirmation page or similar
            header('Location: http://localhost/confirmation.html');
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
} else {
    // Redirect or handle invalid access appropriately
    echo "Invalid request method.";
}
?>
