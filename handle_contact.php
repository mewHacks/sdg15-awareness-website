<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
include 'db_connect.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$message = trim($_POST['message']);

// Basic validation
if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<div class='error-msg'>❌ Invalid input. Please check all fields.</div>");
}

// Insert into DB
$sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("<div class='error-msg'>❌ Prepare failed: " . $conn->error . "</div>");
}
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    // ✅ Redirect to thank-you page
    header("Location: message.php");
    exit();
} else {
    echo "<div class='error-msg'>❌ Submission failed: " . $conn->error . "</div>";
}

$conn->close();
?>
