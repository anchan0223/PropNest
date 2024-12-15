<?php
session_start();
include('db.php');  // Include the database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to view property details.');
}

// Fetch the property details based on the provided ID
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];
    
    // Fetch the property from the database
    $query = "SELECT * FROM properties WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $property_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $property = $result->fetch_assoc();
    } else {
        die('Property not found.');
    }
} else {
    die('Invalid property ID.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link rel="stylesheet" href="property.css">
</head>
<body>
    <div class="container">
        <h1>Property Details</h1>
        <div class="property-details">
            <h2><?php echo htmlspecialchars($property['location']); ?></h2>
            <img src="<?php echo htmlspecialchars($property['image_url']); ?>" alt="Property Image" class="property-image">
            <p><strong>Price:</strong> $<?php echo number_format($property['price']); ?></p>
            <p><strong>Bedrooms:</strong> <?php echo $property['bedrooms']; ?></p>
            <p><strong>Bathrooms:</strong> <?php echo $property['bathrooms']; ?></p>
            <p><strong>Garden:</strong> <?php echo $property['garden'] ? 'Yes' : 'No'; ?></p>
            <p><strong>Parking:</strong> <?php echo $property['parking'] ? 'Yes' : 'No'; ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($property['facilities'])); ?></p>

            <!-- Edit and Delete buttons -->
            <div class="buttons">
                
                <button><a href="edit_property.php?id=<?php echo $property['id']; ?>" class="btn edit-btn">Edit Property</a></button>
                <button onclick="deleteProperty(<?php echo $property['id']; ?>)" class="btn delete-btn">Delete Property</button>
            </div>
        </div>
    </div>

    <script>
        // Function to handle property deletion
        function deleteProperty(propertyId) {
            if (confirm('Are you sure you want to delete this property?')) {
                fetch(`delete_property.php?id=${propertyId}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Property deleted successfully');
                        window.location.href = 'dashboard.php'; // Redirect to dashboard
                    } else {
                        alert('Failed to delete property: ' + data.error);
                    }
                })
                .catch(error => console.error('Error deleting property:', error));
            }
        }
    </script>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>