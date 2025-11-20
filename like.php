<?php
session_start();
// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LogInPage.php"); // Go to login page
    exit;
}

// Check if both 'post_id' and 'action' parameters are provided
if (!isset($_GET['post_id']) || !isset($_GET['action'])) {
    die("Invalid request.");
}

// Include database connection
include 'db_connect.php';

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get post ID and like action from view post page
$post_id = $_GET['post_id'];
$action = $_GET['action'];

// Decide which SQL to run based on action
if ($action == 'like') {

    //Insert into Likes table
    $stmt = $conn->prepare("INSERT IGNORE INTO Likes (user_id, post_id) VALUES (?, ?)");
    // If preparation fails, stop the script and show error
    if (!$stmt) {
        die("Something went wrong: " . $conn->error);
    }
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();

    // Update the Posts table to increase the like count by 1
    $stmt = $conn->prepare("UPDATE Posts SET likes = likes + 1 WHERE post_id = ?");
    // If preparation fails, stop the script and show error
    if (!$stmt) {
        die("Something went wrong: " . $conn->error);
    }
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

} elseif ($action == 'unlike') {

    // Delete from Likes table
    $stmt = $conn->prepare("DELETE FROM Likes WHERE user_id = ? AND post_id = ?");
    if (!$stmt) {
        die("Something went wrong: " . $conn->error);
    }
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();

    // Update the Posts table to decrease the like count, but not below 0
    $stmt = $conn->prepare("UPDATE Posts SET likes = GREATEST(likes - 1, 0) WHERE post_id = ?");
    if (!$stmt) {
        die("Something went wrong: " . $conn->error);
    }
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

} else {
    echo "Invalid action.";
    exit;
}

$conn->close(); // Close connection

echo "success";
exit;

?>
