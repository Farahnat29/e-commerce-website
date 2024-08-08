<?php
// wishlist.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "journifly";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemID = $_POST['itemID'];

    // Check if the item is already in the wishlist
    $checkQuery = "SELECT * FROM wishlist WHERE itemID = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $itemID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => "Item already in wishlist"]);
    } else {
        // Fetch item details from the shop table
        $itemQuery = "SELECT name, photo, price FROM shop WHERE itemID = ?";
        $stmt = $conn->prepare($itemQuery);
        $stmt->bind_param("i", $itemID);
        $stmt->execute();
        $itemResult = $stmt->get_result();

        if ($itemResult->num_rows > 0) {
            $item = $itemResult->fetch_assoc();

            // Insert the item into the wishlist
            $insertQuery = "INSERT INTO wishlist (itemID, name, photo, price) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("isss", $itemID, $item['name'], $item['photo'], $item['price']);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => "Item added to wishlist"]);
            } else {
                echo json_encode(['success' => false, 'message' => "Error adding item to wishlist"]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => "Item not found in shop"]);
        }
    }

    $stmt->close();
}
$conn->close();
?>
