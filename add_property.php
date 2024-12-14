<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

$userID = $_SESSION['user_id'];
$host = "localhost";
$user = "aharvey30";
$pass = "aharvey30";
$dbname = "aharvey30";

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the form data
    $location = trim($_POST['location']);
    $price = trim($_POST['price']);
    $bedrooms = trim($_POST['bedrooms']);
    $bathrooms = trim($_POST['bathrooms']);
    $hasGarden = isset($_POST['hasGarden']) ? 1 : 0;
    $hasParking = isset($_POST['hasParking']) ? 1 : 0;
    $proximityFacilities = trim($_POST['proximityFacilities']);
    
    // Get the image URL from the form
    $imageURL = isset($_POST['image_url']) ? trim($_POST['image_url']) : null;

    // Validate the image URL (optional)
    if ($imageURL && !filter_var($imageURL, FILTER_VALIDATE_URL)) {
        die("Invalid image URL provided.");
    }

    // Insert property details into the database
    $sql = "INSERT INTO properties (user_id, location, price, bedrooms, bathrooms, parking, garden, facilities, image_url)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiiissss", $userID, $location, $price, $bedrooms, $bathrooms, $hasParking, $hasGarden, $proximityFacilities, $imageURL);

    // Execute the query and check for success
    if ($stmt->execute()) {
        header("Location: ListingsPage.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>


