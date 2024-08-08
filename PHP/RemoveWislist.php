<?php
// RemoveWishlist.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "journifly";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log the connection error
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

if (isset($_GET['id'])) {
    $wishlistID = $_GET['id'];
    error_log("Received wishlistID: " . $wishlistID); // Log received ID

    $query = "DELETE FROM wishlist WHERE wishlistID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $wishlistID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Item removed from wishlist"]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error removing item from wishlist"]);
    }

    $stmt->close();
} else {
    error_log("Invalid request: id parameter missing"); // Log invalid request
    echo json_encode(['success' => false, 'message' => "Invalid request"]);
}

$conn->close();
?>
