<?php
session_start();
include('db.php');  // Include the database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

// Fetch the properties for the logged-in user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM properties WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$properties = [];
while ($row = $result->fetch_assoc()) {
    $properties[] = $row;
}

echo json_encode($properties);
?>