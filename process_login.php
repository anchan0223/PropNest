<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Start session to store user data if logged in

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=aharvey30', 'aharvey30', 'aharvey30');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging: Check if the form data is received
    // echo "Username: " . $username . "<br>";
    // echo "Password: " . $password . "<br>";

    // Fetch user data from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging: Check if the user is found in the database
    if ($user) {
        // echo "User found in database.<br>"; // Debugging

        // Check if password is correct
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to listingpage.html
            header('Location: ListingsPage.html');
            exit();
        } else {
            // If password is incorrect
            echo "Incorrect password. Please try again.";
        }
    } else {
        // If user doesn't exist
        echo "No account found with that username.";
    }
}
?>


