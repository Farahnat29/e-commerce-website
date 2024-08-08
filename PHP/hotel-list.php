<?php
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

// Check if hotel_id is set and not empty
if (isset($_POST['hotel_id']) && !empty($_POST['hotel_id'])) {
    // Retrieve hotel ID from the AJAX request
    $hotel_id = $_POST['hotel_id'];

    // Prepare SQL statement to fetch hotel information
    $sql = "SELECT name, place, hotelRate, hotelChain, price, photo FROM hotels_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there is a matching hotel
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Construct response data
        $response = array(
            'name' => $row['name'],
            'place' => $row['place'],
            'hotelRate' => $row['hotelRate'],
            'hotelChain' => $row['hotelChain'],
            'price' => $row['price'],
            'photo' => $row['photo'] // Include photo path in response
        );

        // Send JSON response
        echo json_encode(array('status' => 'success', 'data' => $response));
    } else {
        // If no matching hotel found
        echo json_encode(array('status' => 'error', 'message' => 'hotel not found'));
    }
} else {
    // If hotel_id is not set or empty
    echo json_encode(array('status' => 'error', 'message' => 'Invalid hotel ID'));
}

// Close prepared statement
$stmt->close();

// Close database connection
$conn->close();
?>
