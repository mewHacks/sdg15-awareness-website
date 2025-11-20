<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_system";

// Step 1: Connect to MySQL (without selecting a DB)
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Step 2: Create the database
$createDbSql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8 COLLATE utf8_unicode_ci";
if (!$conn->query($createDbSql)) {
    die("❌ Failed to create database: " . $conn->error);
}

// Step 3: Now select the database
$conn->select_db($dbname);

// Step 4: Create tables
$tableSql = "
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    donated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    content TEXT,
    tags VARCHAR(255),
    images TEXT,
    likes INT DEFAULT 0,
    banned TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    id INT NOT NULL,
    comment TEXT NOT NULL,
    comment_like INT DEFAULT 0,
    banned TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS likes (
    like_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id) REFERENCES Users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES Posts(post_id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (id, post_id)
);
";

// Run table creation queries
if ($conn->multi_query($tableSql)) {
    do {
        // flush any results (required for multi_query)
    } while ($conn->more_results() && $conn->next_result());
} else {
    die("❌ Error creating tables: " . $conn->error);
}

$hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);

// Step 5: Insert admin user if not exists
$insertAdminSql = "
INSERT INTO users (username, email, password, role)
VALUES ('Admin', 'admin@admin.com', '$hashedPassword', 'admin')
ON DUPLICATE KEY UPDATE username='Admin', password='$hashedPassword', role='admin';
";

if ($conn->query($insertAdminSql) === TRUE) {
} else {
    echo "❌ Error inserting admin: " . $conn->error;
}

?>
