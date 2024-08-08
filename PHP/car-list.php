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

// Check if car_id is set and not empty
if (isset($_POST['car_id']) && !empty($_POST['car_id'])) {
    // Retrieve car ID from the AJAX request
    $car_id = $_POST['car_id'];

    // Prepare SQL statement to fetch car information
    $sql = "SELECT name, madeIn, carType, distance, price, photo FROM car_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there is a matching car
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Construct response data
        $response = array(
            'name' => $row['name'],
            'madeIn' => $row['madeIn'],
            'carType' => $row['carType'],
            'distance' => $row['distance'],
            'price' => $row['price'],
            'photo' => $row['photo'] // Include photo path in response
        );

        // Send JSON response
        echo json_encode(array('status' => 'success', 'data' => $response));
    } else {
        // If no matching car found
        echo json_encode(array('status' => 'error', 'message' => 'Car not found'));
    }
} else {
    // If car_id is not set or empty
    echo json_encode(array('status' => 'error', 'message' => 'Invalid car ID'));
}

// Close prepared statement
$stmt->close();

// Close database connection
$conn->close();
?>
