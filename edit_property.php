<?php
include('db.php');  

// Check if the ID is provided
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    // Fetch the current property details from the database
    $query = "SELECT * FROM properties WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $property_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();

    if (!$property) {
        die('Property not found.');
    }
} else {
    die('Invalid property ID.');
}

// Handle form submission to update the property
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values and sanitize inputs
    $user_id = $_SESSION['user_id'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $parking = isset($_POST['parking']) ? 1 : 0;
    $garden = isset($_POST['garden']) ? 1 : 0;
    $facilities = $_POST['facilities'];

    // Handle image upload (if a new image is provided)
    $image_url = $property['image_url'];  // Keep the old image by default

    if (isset($_FILES['property_image']) && $_FILES['property_image']['error'] === 0) {
        $image_url = 'uploads/' . basename($_FILES['property_image']['name']);
        if (!move_uploaded_file($_FILES['property_image']['tmp_name'], $image_url)) {
            die("Failed to move uploaded file.");
        }
    }

    // Update the property in the database
    $query = "UPDATE properties SET location = ?, price = ?, bedrooms = ?, bathrooms = ?, parking = ?, garden = ?, facilities = ?, image_url = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('siiiiisss', $location, $price, $bedrooms, $bathrooms, $parking, $garden, $facilities, $image_url, $property_id);
    $stmt->execute();
    header('Location: dashboard.php');  // Redirect to the dashboard after updating
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property</title>
    <link rel="stylesheet" href="property.css">
</head>
<body>
    <div class="container">
        <h1>Edit Property</h1>
        <form action="edit_property.php?id=<?php echo $property_id; ?>" method="POST" enctype="multipart/form-data">
        <label for="location">Location:</label>
      <input type="text" name="location" required /><br />

      <label for="price">Price:</label>
      <input type="number" step="0.01" name="price" required /><br />

      <label for="bedrooms">Number of Bedrooms:</label>
      <input type="number" name="bedrooms" required /><br />

      <label for="bathrooms">Number of Bathrooms:</label>
      <input type="number" name="bathrooms" required /><br />

      <label for="parking">Has Parking:</label>
      <input type="checkbox" name="parking" /><br />

      <label for="garden">Has Garden:</label>
      <input type="checkbox" name="garden" /><br />

      <label for="facilities">Proximity to Facilities:</label>
      <input type="text" name="facilities" /><br />

      <label for="property_image">New Property Image:</label>
                <input type="file" id="property_image" name="property_image" accept="image/*">
                <p>Current image: <img src="<?php echo htmlspecialchars($property['image_url']); ?>" alt="Property Image" style="width: 100px; height: 100px;"></p>
                <button type="submit">Update Property</button>
        </form>
</body>
</html>