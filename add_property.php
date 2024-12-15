<?php
session_start();
include('db.php');  // Include the database connection

// Temporary: Set a default user ID for testing
$_SESSION['user_id'] = 1; // Replace 1 with an appropriate user ID from your database

// Handle form submission to add a property
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $bedrooms = $_POST['bedrooms'];
    $bathrooms = $_POST['bathrooms'];
    $parking = isset($_POST['parking']) ? 1 : 0;
    $garden = isset($_POST['garden']) ? 1 : 0;
    $facilities = $_POST['facilities'];

    // Handle image upload
    $image_url = null;
    if (isset($_FILES['property_image']) && $_FILES['property_image']['error'] === UPLOAD_ERR_OK) {
        // Ensure the file is an image
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_extension = pathinfo($_FILES['property_image']['name'], PATHINFO_EXTENSION);
        
        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            // Generate a unique name for the image to avoid overwriting
            $image_name = uniqid() . '.' . $image_extension;
            $image_path = 'uploads/' . $image_name;

            // Move the uploaded image to the uploads directory
            if (move_uploaded_file($_FILES['property_image']['tmp_name'], $image_path)) {
                $image_url = $image_path;  // Save the path to the database
            }
        } else {
            echo "Invalid image type. Allowed types are: jpg, jpeg, png, gif.";
        }
    }


    // Insert property into the database
    $query = "INSERT INTO properties (user_id, location, price, bedrooms, bathrooms, parking, garden, facilities, image_url) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('isiiiiiss', $user_id, $location, $price, $bedrooms, $bathrooms, $parking, $garden, $facilities, $image_url);
$stmt->execute();


    
    // Redirect to dashboard after adding property
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Property</title>
    <link rel="stylesheet" href="css/property.css" />
  </head>
  <body>
    <h1>Add Property</h1>
    <form action="add_property.php" method="post" enctype="multipart/form-data">
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

      <label for="property_image">Image:</label>
      <input type="file" id="property_image" name="property_image" accept="image/*">

      <input type="submit" value="Add Property" />
    </form>
  </body>
</html>


