<?php
session_start();

// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LogInPage.php");
    exit;
}

// Include database connection
include 'db_connect.php';

// Check whether post ID and comment ID provided in the URL or not
if (!isset($_GET['comment_id']) || !isset($_GET['post_id'])) {
    die("Invalid request.");
    exit;
}

// Get comment ID and post ID via GET method
$comment_id = $_GET['comment_id'];
$post_id = $_GET['post_id'];

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Delete comment from database
$sql = "DELETE FROM Comments WHERE comment_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("ii", $comment_id, $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {  // If delete successfully
    header("Location: view_post.php?post_id=" . $post_id);
} else {
    echo "You are not allowed to delete this comment or it doesn't exist.";
}

$stmt->close(); // Close statement
$conn->close(); // Close connection
?>
