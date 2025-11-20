<?php
header('Content-Type: text/html; charset=UTF-8');
include 'db_connect.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$amount = floatval($_POST['amount']);

// Validate
if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || $amount <= 0) {
    echo "<div class='error-msg'>❌ Invalid input.</div>";
    exit;
}

$sql = "INSERT INTO donations (name, email, amount) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "<div class='error-msg'>❌ Prepare failed: " . $conn->error . "</div>";
    exit;
}
$stmt->bind_param("ssd", $name, $email, $amount);

if ($stmt->execute()) {
    echo "<div class='success-msg'>🎉 Thank you, $name, for donating RM" . number_format($amount, 2) . "!</div>";
} else {
    echo "<div class='error-msg'>❌ Error: " . $conn->error . "</div>";
}
?>
