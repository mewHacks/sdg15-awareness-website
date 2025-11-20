<?php
session_start();

// Check whether user logged in or not and only admin is allowed to access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
     die("Access denied.");
}

// Check if both 'post_id' and 'action' parameters are provided
if (!isset($_GET['post_id']) || !isset($_GET['action'])) {
    die("Invalid request.");
}

// Include database connection
include 'db_connect.php';

// Get post ID and ban action from view post page
$post_id = $_GET['post_id'];
$action = $_GET['action']; 

// Decide which SQL to run based on action
if ($action === 'ban') {
    $sql = "UPDATE posts SET banned = 1 WHERE post_id = ?"; // Ban
} elseif ($action === 'unban') {
    $sql = "UPDATE posts SET banned = 0 WHERE post_id = ?"; // Unban
} else {
    echo "Invalid action.";
    exit;
}

$stmt = $conn->prepare($sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    // Go back to the previous page
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header("Location: " . $redirect_url);
} else {
    echo "Action failed.";
}

$stmt->close(); // Close statement
$conn->close(); // Close connection
