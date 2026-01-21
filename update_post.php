<?php
session_start();
// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php");
    exit;
}

// Include database connection
include 'db_connect.php';

// Check if the request method is POST, avoid user access the website directly via URL
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request."); // Reject the request
}

// Get post data via POST method
$post_id = $_POST['post_id'];
$title = $_POST['title'];
$content = $_POST['content'];
$tags = isset($_POST['tags']) ? implode(",", $_POST['tags']) : "";
$delete_images = isset($_POST['delete_images']) ? $_POST['delete_images'] : array();

// Get images from database
$sql = "SELECT images FROM Posts WHERE post_id = ? AND id = ?";
$stmt = $conn->prepare($sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($existing_images_str);

// Only proceed if the post exists and belongs to the current user
if (!$stmt->fetch()) {
    die("Post not found or you are not authorized to edit.");
}

$stmt->close(); // Close statement

// Process images uploaded before
// Convert the comma-separated image string into an array and remove empty entries
$existing_images = array_filter(array_map('trim', explode(',', $existing_images_str)));

// Define an array to store images that are not selected for deletion
$remaining_images = array();

//Loop through each images
foreach ($existing_images as $img_path) {
    if (!in_array($img_path, $delete_images)) {// If image is not in the list to be deleted
        $remaining_images[] = $img_path; // keep it
    } else { // Image is selected for deletion
        if (file_exists($img_path)) { // If image file is exists on the server
            unlink($img_path); // Delete the file from server
        }
    }
}

// Process new images to be uploaded 
// Create "uploads" folder if it doesn't exist
$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
// Check if new images are uploaded
if (!empty($_FILES['new_images']['name'][0])) {

    // Loop through each uploaded image file
    foreach ($_FILES['new_images']['tmp_name'] as $index => $tmp_name) {
        
        // Check if the file was uploaded without error
        if ($_FILES['new_images']['error'][$index] === UPLOAD_ERR_OK) {
            $original_name = basename($_FILES['new_images']['name'][$index]);
            $target_path = $upload_dir . time() . "_" . $original_name;

            if (move_uploaded_file($tmp_name, $target_path)) {
                $remaining_images[] = $target_path;
            }
        }
    }
}

// Combine remaining images into a single comma-separated string for database storage
$new_images_str = implode(',', $remaining_images);

// Update post data into "Posts" table using prepared statement
$update_sql = "UPDATE Posts SET title = ?, content = ?, tags = ?, images = ? WHERE post_id = ? AND id = ?";
$stmt = $conn->prepare($update_sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("ssssii", $title, $content, $tags, $new_images_str, $post_id, $_SESSION['user_id']);

// Execute the SQL query
if ($stmt->execute()) { //If execute successfully
    header("Location: view_post.php?post_id=" . $post_id); // Go back to view post page
} else { // If failed to execute
    echo "Failed to update post: " . $conn->error; //Display error message
}

$stmt->close(); //Close statement
$conn->close(); //Close database connection

?>
