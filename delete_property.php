<?php
include('db.php');  // Include the database connection

// Check if the ID is provided
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // Prepare the DELETE query
    $query = "DELETE FROM properties WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        // Prepare failed, return an error
        echo json_encode(['success' => false, 'error' => 'Database prepare failed']);
        exit();
    }

    $stmt->bind_param('i', $property_id);

    if ($stmt->execute()) {
        // Respond with success
        echo json_encode(['success' => true]);
    } else {
        // Respond with failure and provide error information
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    // No property ID provided
    echo json_encode(['success' => false, 'error' => 'No property ID provided']);
}

$conn->close();
?>