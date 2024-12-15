<?php
session_start();
include('db.php');  // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: process_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch properties for the logged-in user
$query = "SELECT * FROM properties WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <h1>PropNest!</h1>
            <a href="process_login.php" class="login-btn">Log Out</a>
        </div>
        <h2>My Properties</h2>
        <div id="property-cards" class="property-cards">
            <?php
            if ($result->num_rows > 0) {
                while ($property = $result->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<h3>' . htmlspecialchars($property['location']) . '</h3>';
                    echo '<p>Price: $' . number_format($property['price']) . '</p>';
                    echo '<p>Bedrooms: ' . $property['bedrooms'] . '</p>';
                    echo '<p>Bathrooms: ' . $property['bathrooms'] . '</p>';
                    
                    // Display the uploaded image or a placeholder if no image is available
                    if (!empty($property['image_url'])) {
                        echo '<img src="' . htmlspecialchars($property['image_url']) . '" alt="Property Image" style="width:100%; height:auto; max-width:300px;">';
                    } else {
                        echo '<p>No image available.</p>';
                    }

                    // Buttons: Edit, View Details, Delete
                    echo '<div class="buttons">';
                    echo '<a href="detailed_property.php?id=' . $property['id'] . '" class="btn details-btn">View Details</a>';
                    echo '<a href="edit_property.php?id=' . $property['id'] . '" class="btn edit-btn">Edit</a>';
                    echo '<button onclick="deleteProperty(' . $property['id'] . ')" class="btn delete-btn">Delete</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No properties found.</p>';
            }
            ?>
        </div>
        <button id="add-property" class="add-property-btn" onclick="window.location.href='add_property.php'">+</button>
    </div>

    <script>
        function deleteProperty(propertyId) {
            if (confirm('Are you sure you want to delete this property?')) {
                fetch(`delete_property.php?id=${propertyId}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Property deleted successfully');
                        window.location.reload(); // Reload the page
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
$stmt->close();
$conn->close();
?>