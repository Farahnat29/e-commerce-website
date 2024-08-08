<?php
session_start();
header('Content-Type: application/json');

// Database connection parameters
$server = "localhost";
$username = "root";
$password = "";
$db = "journifly";

// Create connection
$conn = new mysqli($server, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit;
}

// SQL to fetch cart items
$query = "SELECT product_image, product_name, price, quantity, total FROM cart WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['UserID']);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($cartItems);
?>
