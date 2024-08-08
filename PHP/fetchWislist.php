<?php
// fetchWishlist.php
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

$query = "SELECT wishlistID, itemID, name, photo, price FROM wishlist";
$result = $conn->query($query);

if (!$result) {
    error_log("Query failed: " . $conn->error); // Log any query errors
    echo json_encode(['success' => false, 'message' => "Query failed: " . $conn->error]);
    $conn->close();
    exit;
}

$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Ensure price is returned as a float
        $row['price'] = (float) $row['price'];
        $items[] = $row;
    }
    echo json_encode(['success' => true, 'items' => $items]);
} else {
    echo json_encode(['success' => true, 'items' => []]);
}

$conn->close();
?>
