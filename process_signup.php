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
$role = $_POST['role']; // Buyer, Seller, or Admin

// Check if Email Already Exists
$sql_check_email = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql_check_email);

if ($result->num_rows > 0) {
    // Email already exists, show error message
    echo "This email is already associated with an account. Please use a different email.";
} else {
    // Insert Data into Database
    $sql_insert = "INSERT INTO users (first_name, last_name, email, username, password, role) 
                   VALUES ('$first_name', '$last_name', '$email', '$username', '$password', '$role')";

    if ($conn->query($sql_insert) === TRUE) {
        // Registration successful, now redirect based on role
        if ($role == 'Seller') {
            header('Location: ListingsPage.html');
        } elseif ($role == 'Buyer') {
            header('Location: buyer_dashboard.html');
        } elseif ($role == 'Admin') {
            header('Location: admin_dashboard.html');
        }
        exit(); // Make sure no further code is executed
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

$conn->close();
?>



