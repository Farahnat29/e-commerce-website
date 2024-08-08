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

// Check if flight_id is set and not empty
if (isset($_POST['flight_id']) && !empty($_POST['flight_id'])) {
    // Retrieve flight ID from the AJAX request
    $flight_id = $_POST['flight_id'];

    // Prepare SQL statement to fetch flight information
    $sql = "SELECT name, madeIn, photo FROM flight_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there is a matching flight
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Construct response data
        $response = array(
            'name' => $row['name'],
            'madeIn' => $row['madeIn'],
            'photo' => $row['photo'] // Include photo path in response
        );

        // Send JSON response
        echo json_encode(array('status' => 'success', 'data' => $response));
    } else {
        // If no matching flight found
        echo json_encode(array('status' => 'error', 'message' => 'Flight not found'));
    }
} else {
    // If flight_id is not set or empty
    echo json_encode(array('status' => 'error', 'message' => 'Invalid flight ID'));
}

// Close prepared statement
$stmt->close();

// Close database connection
$conn->close();
?>
