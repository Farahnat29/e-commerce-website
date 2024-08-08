<?php
session_start();
// Database connection parameters
$server = "localhost";
$username = "root";
$password = "";
$db = "journifly";

// Create connection
$conn = new mysqli($server, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$_SESSION['UserID'] = 2; // Example hardcoded value for testing

// Check if the request is POST type and if itemID and UserID are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemID']) && isset($_SESSION['UserID'])) {
    $itemId = $_POST['itemID'];
    $userId = $_SESSION['UserID']; 

    // Fetch product details from the 'shop' table
    $stmt = $conn->prepare("SELECT name, photo, price FROM shop WHERE itemID = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit;
    }

    // Check if product already in cart
    $query = "SELECT quantity FROM cart WHERE UserID = ? AND product_name = ?";
    $check = $conn->prepare($query);
    $check->bind_param("is", $userId, $product['name']);
    $check->execute();
    $result = $check->get_result();
    $existing = $result->fetch_assoc();

    if ($existing) {
        // Update cart
        $newQuantity = $existing['quantity'] + 1; 
        $newTotal = $newQuantity * $product['price'];
        $update = $conn->prepare("UPDATE cart SET quantity = ?, total = ? WHERE UserID = ? AND product_name = ?");
        $update->bind_param("idsi", $newQuantity, $newTotal, $userId, $product['name']);
        $update->execute();
        // Check for errors in update
        if ($update->error) {
            echo json_encode(['status' => 'error', 'message' => 'Update failed: ' . $update->error]);
            exit;
        }
    } else {
        // Add new item to cart
        $insert = $conn->prepare("INSERT INTO cart (UserID, product_image, product_name, price, quantity, total) VALUES (?, ?, ?, ?, 1, ?)");
        $total = $product['price'];
        $insert->bind_param("issdd", $userId, $product['photo'], $product['name'], $product['price'], $total);
        $insert->execute();
        // Check for errors in insert
        if ($insert->error) {
            echo json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $insert->error]);
            exit;
        }
    }

    // Commit changes and check for affected rows
    if ($conn->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No changes made to the cart']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request or UserID not set']);
}

?>
