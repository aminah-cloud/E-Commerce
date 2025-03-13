<?php
include 'db.php'; // Include the database connection

// Check if user is logged in (you can improve this with sessions or tokens)
session_start();
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to upload an item.");
}

$user_id = $_SESSION['user_id']; // Assuming you saved the user_id in the session

// Get form data
$itemTitle = $_POST['itemTitle'];
$itemDescription = $_POST['itemDescription'];
$itemLocation = $_POST['itemLocation'];
$itemImage = $_FILES['itemImage'];
$itemVideo = $_FILES['itemVideo'];
$itemDocument = $_FILES['itemDocument'];

// Handle image upload
$itemImagePath = $itemImage['name'] ? "uploads/" . basename($itemImage['name']) : null;
if ($itemImagePath) {
    move_uploaded_file($itemImage['tmp_name'], $itemImagePath);
}

// Handle video upload
$itemVideoPath = $itemVideo['name'] ? "uploads/" . basename($itemVideo['name']) : null;
if ($itemVideoPath) {
    move_uploaded_file($itemVideo['tmp_name'], $itemVideoPath);
}

// Handle document upload
$itemDocumentPath = $itemDocument['name'] ? "uploads/" . basename($itemDocument['name']) : null;
if ($itemDocumentPath) {
    move_uploaded_file($itemDocument['tmp_name'], $itemDocumentPath);
}

// Insert item data into the database
$sql = "INSERT INTO items (title, description, location, image, video, document, user_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $itemTitle, $itemDescription, $itemLocation, $itemImagePath, $itemVideoPath, $itemDocumentPath, $user_id);

if ($stmt->execute()) {
    echo "Item uploaded successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
