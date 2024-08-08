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

// Check if train_id is set and not empty
if (isset($_POST['train_id']) && !empty($_POST['train_id'])) {
    // Retrieve train ID from the AJAX request
    $train_id = $_POST['train_id'];

    // Prepare SQL statement to fetch train information
    $sql = "SELECT name, madeIn, trainType, distance, price, photo FROM train_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $train_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there is a matching train
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Construct response data
        $response = array(
            'name' => $row['name'],
            'madeIn' => $row['madeIn'],
            'trainType' => $row['trainType'],
            'distance' => $row['distance'],
            'price' => $row['price'],
            'photo' => $row['photo'] // Include photo path in response
        );

        // Send JSON response
        echo json_encode(array('status' => 'success', 'data' => $response));
    } else {
        // If no matching train found
        echo json_encode(array('status' => 'error', 'message' => 'Train not found'));
    }
} else {
    // If train_id is not set or empty
    echo json_encode(array('status' => 'error', 'message' => 'Invalid train ID'));
}

// Close prepared statement
$stmt->close();

// Close database connection
$conn->close();
?>
