<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "user_portal");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect Form Data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
$role = $_POST['role']; // Buyer, Seller, or Admin

// Check if Email Already Exists
$sql_check_email = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql_check_email);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email already exists, show error message
    echo "This email is already associated with an account. Please use a different email.";
    exit();
}

// Check if Username Already Exists
$sql_check_username = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql_check_username);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Username already exists, show error message
    echo "This username is already taken. Please choose a different username.";
    exit();
}

// Insert Data into Database
$sql_insert = "INSERT INTO users (first_name, last_name, email, username, password, usertype) 
               VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $password, $role);

if ($stmt->execute()) {
    // Registration successful, now start session and redirect based on role
    session_start();
    $_SESSION['user_id'] = $stmt->insert_id; // Store the user ID in session
    $_SESSION['username'] = $username; // Store the username in session
    
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

$stmt->close();
$conn->close();
?>





