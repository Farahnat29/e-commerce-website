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

$full_name = $_POST['full_name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypting the password

$sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $full_name, $email, $password);
if ($stmt->execute()) {
    echo "<script>
        localStorage.setItem('userName', '" . addslashes($full_name) . "'); // Store the full name in local storage
        alert('Registered successfully');
        window.location.href = 'http://localhost/index-3.html';
         // Full URL
         // Redirect to the homepage
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
