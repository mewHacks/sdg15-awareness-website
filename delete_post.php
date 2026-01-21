<?php
session_start();
// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php"); // Go to Login page
    exit;
}

// Include database connection
include 'db_connect.php';

// Check whether post_id provided in the URL or not
if (!isset($_GET['post_id'])) {
    die("Post not found.");
}

// Get post ID via GET method
$post_id = $_GET['post_id'];

// Get current post data from database
$sql = "SELECT post_id, id, images FROM Posts WHERE post_id = ? AND id = ?";
$stmt = $conn->prepare($sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($id, $user_id, $images);

if (!$stmt->fetch()) {
    die("Post not found or you are not authorized to delete.");
}
$stmt->close();

// Delete comments
$delete_comments_sql = "DELETE FROM Comments WHERE post_id = ?";
$stmt = $conn->prepare($delete_comments_sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->close();

// Delete images
if (!empty($images) && file_exists($images)) {
    unlink($images);
}

// Delete post
$delete_post_sql = "DELETE FROM Posts WHERE post_id = ?";
$stmt = $conn->prepare($delete_post_sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("i", $post_id);

// Execute the SQL query
if ($stmt->execute()) { //If execute successfully
    header("Location: index.php"); // Go back to community page
    exit;
} else {
    echo "Delete failed: " . $stmt->error;
}

$stmt->close(); //Close statement
$conn->close(); //Close database connection

?>