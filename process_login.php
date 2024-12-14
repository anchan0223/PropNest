<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "user_portal");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Query to check if username or email exists
    $sql = "SELECT * FROM Users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail); // binding username/email

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['usertype'] = $user['usertype'];

            // Redirect based on user type
            if ($user['usertype'] == 'seller') {
                header('Location: ListingsPage.html');
            } elseif ($user['usertype'] == 'buyer') {
                header('Location: ListingsPage.html');
            } elseif ($user['usertype'] == 'admin') {
                header('Location: ListingsPage.html');
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}

$conn->close();
?>

<form method="POST" action="login.php">
    <input type="text" name="username_or_email" placeholder="Username or Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>








