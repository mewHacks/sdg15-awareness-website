<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO users (username, email, password, role) 
VALUES (
  'Admin', 
  'admin@admin.com', 
  'admin123', 
  'admin'
)
ON DUPLICATE KEY UPDATE
username='admin', password='hashedpassword', role='admin'";


if ($conn->query($sql) === TRUE) {
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>