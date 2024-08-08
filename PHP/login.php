<?php
session_start();  // Start the session if you plan to use session variables

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

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT full_name, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->bind_result($full_name, $hashed_password);
    $stmt->fetch();
    if (password_verify($password, $hashed_password)) {
        // Set local storage and redirect via JavaScript
        echo "<script>
                localStorage.setItem('userName', '" . addslashes($full_name) . "');
                alert('Login successful');
                window.location.href = '/index-3.html';
              </script>";
    } else {
        echo "<script>alert('Invalid password'); window.location.href = '/login.html';</script>";
    }
} else {
    echo "<script>alert('Email not registered'); window.location.href = '/login.html';</script>";
}
$stmt->close();
$conn->close();
?>
