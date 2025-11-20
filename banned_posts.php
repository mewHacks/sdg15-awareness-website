<?php
session_start();
// Check whether user logged in or not and only admin is allowed to access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: LogInPage.php"); // Go to Login page
    exit;
}

// Include database connection
include 'db_connect.php';

// Get user ID and name from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get banned post data from database
$sql = "SELECT post_id, title, content, created_at, images FROM Posts WHERE banned = 1 ORDER BY created_at DESC";

$result = $conn->query($sql);
// If fails, stop the script and show error
if (!$result) {
    die("Something went wrong: " . $conn->error);
}
// Array to store banned posts
$banned_posts = array();

// Loop through each result row and add to array
while ($post = $result->fetch_assoc()) {
    $banned_posts[] = $post;
}
$conn->close(); // Close connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banned Posts</title>
    <!-- Font Awesome icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styles -->
    <link rel="stylesheet" href="CSS/profile_posts.css">
</head>

<body>

    <!-- Sidebar with icons for navigation to other pages -->
    <div class="sidebar">
    <nav class="sidebar-nav">
        <a href="Homepage.html"> <!-- Homepage -->
            <i class="fas fa-home"></i>
            <span>Homepage</span>
        </a>
        <a href="about.php"> <!-- About page -->
            <i class="fas fa-info-circle"></i>
            <span>About</span>
        </a>
        <a href="initiatives.php"> <!-- Initiatives page -->
            <i class="fas fa-hands-helping"></i>
            <span>Initiatives</span>
        </a>
        <a href="resources.php"> <!-- Resource page -->
            <i class="fas fa-book"></i>
            <span>Resources</span>
        </a>
        <a href="getinvolved.php"> <!-- Get involved page -->
            <i class="fas fa-hand-holding-heart"></i>
            <span>Get Involved</span>
        </a>
        <a href="events.php"> <!-- Events page -->
            <i class="fas fa-calendar-alt"></i>
            <span>Events</span>
        </a>
        <a href="index.php" class="active"> <!-- Community page, which is where we are at rn -->
            <i class="fas fa-comments"></i>
            <span>Community</span>
        </a>
        <a href="contactus.php"> <!-- Contact us page -->
            <i class="fas fa-envelope"></i>
            <span>Contact Us</span>
        </a>
        <a href="<?= $profileLink ?>"> <!-- Profile page -->
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <!-- Back button to navigate back to previous page -->
            <div class="back-btn-wrapper">
                <button class="back-btn" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
    </nav>
    </div> 
    <div class="form">
        
        <div class="profile-header">
            <h1 class="profile-title">Banned Posts</h1>
            <p>Welcome back, <span class="username"><?php echo htmlspecialchars($username); ?></span>!</p>
        </div>
        
        <div class="banned-section">
            <h2 class="section-title">
                <i class="fas fa-ban"></i> Banned Posts
            </h2>
            
            <?php if (!empty($banned_posts)): // Check whether have post or not?>
                <?php foreach ($banned_posts as $post): // Loop through each post?>
                    <div class="post">
                        <!-- Title -->
                        <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>
                        
                        <!-- Content -->
                        <div class="post-content">
                            <!-- Display first 100 characters of the content with line breaks -->
                            <?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 100))); ?>
                            <?php if (strlen($post['content']) > 100) echo '...'; ?>
                        </div>

                        <!-- If post has images, show the first one -->
                        <?php if (!empty($post['images'])): ?>
                            <?php $imgs = explode(",", $post['images']); ?>
                            <img src="<?php echo htmlspecialchars($imgs[0]); ?>" class="post-image" alt="Post image">
                        <?php endif; ?>

                        <!-- Link to view full post -->
                        <a href="view_post.php?post_id=<?php echo $post['post_id']; ?><br>" class="view-link">
                            View full post <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: // If don't have any post?>
                <div class="empty-message">
                    <i class="fas fa-ban"></i>
                    <p>No banned posts found. The community is clean!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>