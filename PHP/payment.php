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

function insertPayment($conn, $carID, $flightID, $trainID, $hotelID, $name, $cardNumber, $expiryDate, $cardType) {
    $stmt = $conn->prepare("INSERT INTO payment (CarBookingID, FlightBookingID, TrainBookingID, HotelBookingID, name, card_number, expiry_date, card_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiisss", $carID, $flightID, $trainID, $hotelID, $name, $cardNumber, $expiryDate, $cardType);
    if (!$stmt->execute()) {
        echo "Failed to insert: " . $stmt->error;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? 'No Name';
    $cardNumber = $_POST['card_number'] ?? '';
    $expiryDate = $_POST['expiry_date'] ?? '';
    $cardType = $_POST['card_type'] ?? '';

    try {
        // Check the priority of booking types and insert payment record accordingly
        if (!empty($_SESSION['CarBookingID'])) {
            insertPayment($conn, $_SESSION['CarBookingID'], null, null, null, $name, $cardNumber, $expiryDate, $cardType);
            unset($_SESSION['CarBookingID']); // Unset the car booking ID after processing
        }
        elseif (!empty($_SESSION['FlightBookingID'])) {
            insertPayment($conn, null, $_SESSION['FlightBookingID'], null, null, $name, $cardNumber, $expiryDate, $cardType);
            unset($_SESSION['FlightBookingID']); // Unset the flight booking ID after processing
        }
        elseif (!empty($_SESSION['TrainBookingID'])) {
            insertPayment($conn, null, null, $_SESSION['TrainBookingID'], null, $name, $cardNumber, $expiryDate, $cardType);
            unset($_SESSION['TrainBookingID']); // Unset the train booking ID after processing
        }
        elseif (!empty($_SESSION['HotelBookingID'])) {
            insertPayment($conn, null, null, null, $_SESSION['HotelBookingID'], $name, $cardNumber, $expiryDate, $cardType);
            unset($_SESSION['HotelBookingID']); // Unset the hotel booking ID after processing
        }

        header("Location:http://localhost/confirmation.html");
        exit;
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}


?>
