<?php
session_start();
header('Content-Type: application/json');

// Reject non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('success' => false, 'message' => 'Invalid request'));
    exit;
}

// Ensure post_id and user_id exist
$post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$post_id || !$user_id) {
    echo json_encode(array('success' => false, 'message' => 'Invalid data'));
    exit;
}

require_once dirname(__FILE__) . '/db_connect.php';

// Check if already liked
$stmt = $conn->prepare("SELECT 1 FROM likes WHERE id = ? AND post_id = ?");
if (!$stmt) {
    echo json_encode(array('success' => false, 'message' => 'Prepare failed'));
    exit;
}
$stmt->bind_param("ii", $user_id, $post_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Unlike
    $stmt_delete = $conn->prepare("DELETE FROM likes WHERE id = ? AND post_id = ?");
    if ($stmt_delete) {
        $stmt_delete->bind_param("ii", $user_id, $post_id);
        $stmt_delete->execute();
        $stmt_delete->close();
    }
} else {
    // Like
    $stmt_insert = $conn->prepare("INSERT INTO likes (id, post_id) VALUES (?, ?)");
    if ($stmt_insert) {
        $stmt_insert->bind_param("ii", $user_id, $post_id);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
}
$stmt->close();

// Get updated like count
$like_count = 0;
$stmt_count = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
if ($stmt_count) {
    $stmt_count->bind_param("i", $post_id);
    $stmt_count->execute();
    $stmt_count->bind_result($like_count);
    $stmt_count->fetch();
    $stmt_count->close();
}

echo json_encode(array('success' => true, 'like_count' => $like_count));
?>
