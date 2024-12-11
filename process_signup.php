<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "user_portal");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect Form Data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = $_POST['role'];

// Insert Data into Database
$sql = "INSERT INTO users (first_name, last_name, email, username, password, role) 
        VALUES ('$first_name', '$last_name', '$email', '$username', '$password', '$role')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful! <a href='index.php'>Go back to Home</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
