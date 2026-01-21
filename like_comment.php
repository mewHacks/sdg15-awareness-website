<?php
session_start();

// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php");
    exit;
}

// Check if both parameters are provided
if (!isset($_GET['comment_id']) || !isset($_GET['action']) || !isset($_GET['post_id'])) {
    die("Invalid request.");
}

// Include database connection
include 'db_connect.php';

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get comment ID, like action and post ID from view post page
$comment_id = $_GET['comment_id'];
$action = $_GET['action']; 
$post_id = $_GET['post_id'];

// Decide which SQL to run based on action
if ($action === 'like') {

    //Insert into CommentLikes table
    $stmt = $conn->prepare("INSERT IGNORE INTO CommentLikes (user_id, comment_id) VALUES (?, ?)");
    // If preparation fails, stop the script and show error
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("ii", $user_id, $comment_id);
    $stmt->execute();

    // Update the Comments table to increase the like count by 1
    $stmt = $conn->prepare("UPDATE Comments SET comment_like = comment_like + 1 WHERE comment_id = ?");
    // If preparation fails, stop the script and show error
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
}

elseif ($action === 'unlike') {

    // Delete from CommentLikes table
    $stmt = $conn->prepare("DELETE FROM CommentLikes WHERE user_id = ? AND comment_id = ?");
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("ii", $user_id, $comment_id);
    $stmt->execute();

    // Update the Comments table to decrease the like count, but not below 0
    $stmt = $conn->prepare("UPDATE Comments SET comment_like = GREATEST(comment_like - 1, 0) WHERE comment_id = ?");
    if (!$stmt) {
        die("Error: " . $conn->error);
    }
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
} else {
    echo "Invalid action.";
    exit;
}

$conn->close(); // Close connection
header("Location: view_post.php?post_id=" . $post_id); // Go back to view post page
exit;

?>
