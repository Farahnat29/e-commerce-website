<?php
session_start();
 // Connect to your database (replace these with your actual database credentials)
 $servername = "localhost";
 $username = "root";
 $password = "";
 $database = "journifly";

 // Create connection
 $conn = new mysqli($servername, $username, $password, $database);

 // Check connection
 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
 }

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $userid = isset($_POST['UserID']) ? $_POST['UserID'] : null;
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phnumber1"];
    $gender = $_POST["gender"];
    $nationality = $_POST["nationality"];
    $message = $_POST["message"];
    $receiveOffers = isset($_POST["receive_offers"]) ? $_POST["receive_offers"] : "No"; // Check if the checkbox is checked

    // Validate and sanitize data (you can add your validation here)

   

    $stmt = $conn->prepare("INSERT INTO flight (UserID, name, email, phone, gender, nationality, message, receive_offers) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssss", $userid, $name, $email, $phone, $gender, $nationality, $message, $receiveOffers);


    if ($stmt->execute()) {
        // Capture the auto-generated CarBookingID
        $flightBookingID = $conn->insert_id;
        // Store CarBookingID in session
        $_SESSION['FlightBookingID'] = $flightBookingID;

        // Redirect to the payment page
        header("Location: http://localhost/payment.html#");
        exit;
    } else {
        echo "Error in prepared statement execution: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
