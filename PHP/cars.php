<?php
session_start();

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "journifly";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = isset($_POST['UserID']) ? $_POST['UserID'] : null;
    $name = $_POST['name'] ?? 'No Name';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phnumber1'] ?? '';
    $arrivalDate = $_POST['arrival-date'] ?? '';
    $departureDate = $_POST['departure-date'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $nationality = $_POST['nationality'] ?? '';
    $message = $_POST['message'] ?? '';
    $receiveOffers = isset($_POST['receive_offers']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO cars (UserID, name, email, phone, arrival_date, departure_date, gender, nationality, message, receive_offers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssi", $userid, $name, $email, $phone, $arrivalDate, $departureDate, $gender, $nationality, $message, $receiveOffers);

    if ($stmt->execute()) {
        // Capture the auto-generated CarBookingID
        $carBookingID = $conn->insert_id;
        // Store CarBookingID in session
        $_SESSION['CarBookingID'] = $carBookingID;

        // Redirect to the payment page
        header("Location: http://localhost/payment.html#");
        exit;
    } else {
        echo "Error in prepared statement execution: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
