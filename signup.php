<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>User Registration</h1>

    <form action="process_signup.php" method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="Buyer">Buyer</option>
            <option value="Seller">Seller</option>
            <option value="Admin">Admin</option>
        </select><br>

        <button type="submit">Register</button>
    </form>

    <p><a href="index.php">Go Back to Home</a></p>
</body>
</html>
