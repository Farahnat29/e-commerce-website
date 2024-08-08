<?php
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = "localhost";
$username = "root";
$password = "";
$db = "journifly";

// Create connection
$conn = new mysqli($server, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$sql = "SELECT p.PaymentID, 
               CASE 
                   WHEN p.CarBookingID IS NOT NULL THEN 'Car'
                   WHEN p.TrainBookingID IS NOT NULL THEN 'Train'
                   WHEN p.HotelBookingID IS NOT NULL THEN 'Hotel'
                   WHEN p.FlightBookingID IS NOT NULL THEN 'Flight'
               END AS BookingType, 
               COALESCE(c.name, t.name, h.name, f.name) AS Name,
               COALESCE(c.email, t.email, h.email, f.email) AS Email,
               'Confirmed' AS Status
        FROM payment p
        LEFT JOIN cars c ON p.CarBookingID = c.CarBookingID
        LEFT JOIN train t ON p.TrainBookingID = t.TrainBookingID
        LEFT JOIN hotels h ON p.HotelBookingID = h.HotelBookingID
        LEFT JOIN flight f ON p.FlightBookingID = f.FlightBookingID";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    exit();
}

$bookings = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

$conn->close();

echo json_encode($bookings);
?>
