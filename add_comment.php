<?php
session_start();
// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php"); // Go to Login page
    exit;
}

// Include database connection
include 'db_connect.php';

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get post ID and comment from form
$post_id = $_POST['post_id'];
$comment = $_POST['comment'];

// Insert data into database
$sql = "INSERT INTO Comments (post_id, user_id, comment) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("iis", $post_id, $user_id, $comment);

if ($stmt->execute()) {
    // Go back to the view post page
    echo "success";
} else {
    echo "Failed to post comment.";
}

$stmt->close(); // Close statement
$conn->close(); // Close connection
