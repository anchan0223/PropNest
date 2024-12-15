<?php
session_start();
include('db.php'); // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, usertype FROM Users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['usertype'] = $user['usertype'];

            // Redirect based on user role
            if ($user['usertype'] === 'admin') {
                //admin dashboard if we did that milestone but for right now it directs to seller dashboard
                header('Location: dashboard.php');
            } elseif ($user['usertype'] === 'seller') {
                header('Location: dashboard.php');
            } else {
                //buyer dashboard if we did that milestone but for right now it directs to seller dashboard
                header('Location: dashboard.php');
            }
            exit();
        } else {
            header('Location: process_login.php?error=Incorrect password');
            exit();
        }
    } else {
        header('Location: process_login.php?error=User not found');
        exit();
    }
}
