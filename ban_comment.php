<?php
session_start();
// Check whether user logged in or not and only admin is allowed to access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

// Check if both 'post_id' and 'comment_id' parameters are provided
if (!isset($_GET['comment_id']) || !isset($_GET['post_id'])) {
    die("Invalid request.");
}

// Include database connection
include 'db_connect.php';

// Get post ID and comment ID from view post page
$comment_id = $_GET['comment_id'];
$post_id = $_GET['post_id'];

// Update Comments table to set banned = 1
$stmt = $conn->prepare("UPDATE Comments SET banned = 1 WHERE comment_id = ?");
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("i", $comment_id);
$stmt->execute();

$stmt->close(); // Close statement

header("Location: view_post.php?post_id=" . $post_id); // Go back to the view post page
exit;

?>