<?php
session_start();
// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php");
    exit;
}

// Include database connection
include 'db_connect.php';

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get form data via POST method
$title = $_POST['title'];
$content = $_POST['content'];
$tags = isset($_POST['tags']) ? implode(", ", $_POST['tags']) : "";

// Array to store all image's paths
$image_paths = array();

// Check if at least one image file is uploaded
if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    
    // Create "uploads" folder if it doesn't exist
    $save_dir = "uploads/";
    if (!is_dir($save_dir)) {
        mkdir($save_dir, 0777, true);
    }

    // Loop through each uploaded image file
    foreach ($_FILES['images']['name'] as $index => $name) {
        $tmp_name = $_FILES['images']['tmp_name'][$index];
        
        // Check if the file was uploaded without error
        if ($_FILES['images']['error'][$index] === UPLOAD_ERR_OK) {
            $filename = time() . "_" . basename($name);
            $save_file = $save_dir . $filename;

            if (move_uploaded_file($tmp_name, $save_file)) {
                $image_paths[] = $save_file;
            }
        }
    }
}

// Combine image paths into a single comma-separated string for database storage
$images_string = implode(",", $image_paths); 

// Insert new post data into "Posts" table using prepared statement
$sql = "INSERT INTO Posts (id, title, content, tags, images, banned) VALUES (?, ?, ?, ?, ?, 0)";
$stmt = $conn->prepare($sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong:  " . $conn->error);
}

$stmt->bind_param("issss", $user_id, $title, $content, $tags, $images_string);

// Execute the SQL query
if ($stmt->execute()) { //If execute successfully
    header("Location: index.php"); // Go back to community page
} else { // If failed to execute
    echo "Post failed: " . $stmt->error; //Display error message
}

$stmt->close(); //Close statement
$conn->close(); //Close database connection

?>