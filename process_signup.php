<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $usertype = $conn->real_escape_string($_POST['usertype']);

    $sql = "INSERT INTO Users (username, email, password, usertype)
    VALUES ('$username', '$email', '$password', '$usertype')";


    if ($conn->query($sql) === TRUE) {
        // Redirect to login.php after successful registration
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
