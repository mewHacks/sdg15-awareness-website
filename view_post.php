<?php
session_start();

// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LogInPage.php"); // Go to Login page
    exit;
}

// Include database connection
include 'db_connect.php';

// Check whether post_id provided in the URL or not
if (!isset($_GET['post_id'])) {
   die("Post not found.");
}

// Get post ID via GET method
$post_id = $_GET['post_id'];

// Get current data from database
$sql = "SELECT posts.post_id, posts.id, posts.title, posts.content, posts.tags, posts.created_at, posts.images, posts.banned, users.username, 
        (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.post_id) AS like_count, (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.post_id AND banned = 0) AS comment_count
        FROM posts 
        JOIN Users ON posts.id = users.id 
        WHERE posts.post_id = ?";

$stmt = $conn->prepare($sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}

$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->bind_result($post_id, $user_id, $title, $content, $tags, $created_at, $images, $banned, $username, $like_count, $comment_count);

if ($stmt->fetch()) {

    $post = array(
        'post_id' => $post_id,
        'user_id' => $user_id,
        'title' => $title,
        'content' => $content,
        'tags' => $tags,
        'created_at' => $created_at,
        'images' => $images,
        'banned' => $banned,
        'username' => $username,
        'like_count' => $like_count,
        'comment_count' => $comment_count
    );
} else {
    die("Post not found.");
}

 $stmt->close(); // Close statement

// Normal users cannot access the banned post
if ($post['banned'] && $_SESSION['role'] !== 'admin') {
    echo "<div style='color:red; font-weight:bold;'>This post has been banned by admin.</div>";
    echo '<a href="index.php" class="back">
        <i class="fas fa-arrow-left"></i> Back to Community
    </a>';
    exit;
}

// Initialize $has_liked to false, assuming the user has not liked the post
$has_liked = false;

// Check whether user liked the post or not
$sql_check_like = "SELECT 1 FROM Likes WHERE id = ? AND post_id = ?";
$stmt = $conn->prepare($sql_check_like);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}
$stmt->bind_param("ii", $_SESSION['user_id'], $post_id);
$stmt->execute();
$stmt->store_result(); //stores the result of the prepared statement in memory

// If record exist, means user has liked the post
if ($stmt->num_rows > 0) {
    $has_liked = true;
}

$stmt->close(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <!-- Font Awesome icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styles -->
    <link rel="stylesheet" href="CSS/view_post.css"> 
</head>

<body>

<!--sidebar -->
    <!-- Sidebar with icons for navigation to other pages -->
    <div class="sidebar">
        <nav class="sidebar-nav">
            <a href="Homepage.php"> <!-- Homepage -->
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
    
    <div class="container">

        <div class="post-header">
            <div class="action-buttons">

                <!-- Only post owner can edit and delete the post -->
                <?php if ($_SESSION['user_id'] == $post['user_id']): ?>
                    <!-- Edit Button-->
                    <a href="edit_post.php?post_id=<?php echo $post_id; ?>" class="action-btn edit-btn">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <!-- Delete Button -->
                    <a href="delete_post.php?post_id=<?php echo $post_id; ?>" 
                       class="action-btn delete-btn" 
                       onclick="return confirm('Are you sure you want to delete this post?');">
                        <i class="fas fa-trash-alt"></i> Delete
                    </a>
                <?php endif; ?>

                <!-- Only admin can ban post -->
                <?php if ($_SESSION['role'] === 'admin' && $_SESSION['user_id'] != $post['user_id']): ?>

                    <!-- If the post is already banned, show unban button-->
                    <?php if ($post['banned']): ?>
                        <a href="ban.php?post_id=<?php echo $post['post_id']; ?>&action=unban" 
                        class="action-btn ban-btn"     
                        onclick="return confirm('Are you sure you want to unban this post?');">
                            <i class="fas fa-check-circle"></i> Unban
                        </a>

                    <!-- If the post is not being banned, show ban button-->    
                    <?php else: ?>
                        <a href="ban.php?post_id=<?php echo $post['post_id']; ?>&action=ban" 
                        class="action-btn ban-btn" 
                        onclick="return confirm('Are you sure you want to ban this post?');">
                            <i class="fas fa-ban"></i> Ban
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <!-- Post title -->
            <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>

            <!-- User name and post date -->
            <div class="post-info">
                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($post['username']); ?></span>
                <span><i class="fas fa-calendar-alt"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
            </div>
        </div>

        <!-- Post images -->
        <?php if (!empty($post['images'])): // If the post has images?>
            <?php $image_array = explode(",", $post['images']); ?>
            
            <div class="carousel">
                <!-- Button to switch to the previous image -->
                <button class="carousel-btn left" onclick="changeImage(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <!-- Current image -->
                <img id="carouselImage" src="<?php echo htmlspecialchars(trim($image_array[0])); ?>" alt="Post Image" class="post-image">

                <!-- Image counter -->
                <div id="image-counter" class="image-counter">1 / <?php echo count($image_array); ?></div>

                <!-- Button to switch to the next image -->
                <button class="carousel-btn right" onclick="changeImage(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        <?php endif; ?>

        <!-- Post content -->
        <div class="post-content">
            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
        </div>

        <!-- Post tags -->
        <?php if (!empty($post['tags'])): // If post has tags?>
            <div class="tags-container">
                <?php 
                    $tags = explode(',', $post['tags']);
                    foreach ($tags as $tag): 
                        $trimmed_tag = trim($tag);
                ?>

                <!-- Click tag and go to filtered community page -->
                <a href="index.php?tag=<?php echo urlencode($trimmed_tag); ?>" class="tag">
                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($trimmed_tag); ?>
                </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="post-actions">
            <!-- Like post -->
            <!-- Check user's action is like or unlike -->
            <!-- Prevents the link from navigating anywhere when clicked -->
            <a href="javascript:void(0);" 
            id="likeBtn"
            data-post-id="<?php echo $post_id; ?>"
            data-has-liked="<?php echo $has_liked ? '1' : '0'; ?>"
            class="like-btn <?php echo $has_liked ? ' liked' : ''; // If liked, become red?>">
            <i class="fas fa-heart"></i>
            <span id="likeCount"><?php echo $post['like_count']; // Display the like count?></span>likes
            </a>
        </div>

        <!-- Post comment -->
        <div class="comments-section">
            <!-- Add comment -->
            <div class="comment-form">
                <h3><i class="fas fa-edit"></i> Add Comment</h3>
                <form id="commentForm">
                    <textarea name="comment" placeholder="Share your thoughts..." required></textarea>
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                    <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Post</button>
                </form>
                <div id="commentMessage"></div>

            </div>

            <h3 class = "comment-title"><i class="fas fa-comment"></i> Comments (<?php echo $comment_count;?>)</h3>
            <?php 
                // Get comment data from database
                $sql_comments = "SELECT Comments.comment_id, Comments.comment, Comments.created_at, Users.username, Users.id
                    FROM Comments 
                    JOIN Users ON Comments.id = Users.id 
                    WHERE Comments.post_id = ? AND Comments.banned = 0
                    ORDER BY Comments.created_at DESC";

                $comment_stmt = $conn->prepare($sql_comments);
                // If preparation fails, stop the script and show error
                if (!$comment_stmt) {
                    die("Something went wrong: " . $conn->error);
                }
                $comment_stmt->bind_param("i", $post_id);
                $comment_stmt->execute();
                $comment_stmt->bind_result($comment_id, $comment_text, $comment_time, $comment_author, $comment_user_id);

                // Store comments in array
                $comments = array();
                while ($comment_stmt->fetch()) {
                    $comments[] = array(
                        'id' => $comment_id,
                        'text' => $comment_text,
                        'time' => $comment_time,
                        'author' => $comment_author,
                        'user_id' => $comment_user_id
                    );
                }

                $comment_stmt->close(); // Close statement

                $has_comments = count($comments) > 0; // Check whether the post has comment

                // Loop through each comment
                foreach ($comments as $comment):
                    $comment_id = $comment['id'];
                    $liked_comment = false;

                    // Check whether the comment has like
                    $check_like = $conn->prepare("SELECT 1 FROM CommentLikes WHERE user_id = ? AND comment_id = ?");
                    // If preparation fails, stop the script and show error
                    if (!$check_like) {
                        die("Something went wrong: " . $conn->error);
                    }
                    $check_like->bind_param("ii", $_SESSION['user_id'], $comment_id);
                    $check_like->execute();
                    $check_like->store_result(); //stores the result in memory

                    // If record exist, means user has liked the comment
                    if ($check_like->num_rows > 0) {
                        $liked_comment = true;
                    }    

                    $check_like->close(); // Close statement

                    // The amount of likes
                    $likes_count = 0; 
                    $count_like_stmt = $conn->prepare("SELECT COUNT(*) FROM CommentLikes WHERE comment_id = ?");
                    $count_like_stmt->bind_param("i", $comment_id);
                    $count_like_stmt->execute();
                    $count_like_stmt->bind_result($likes_count);
                    $count_like_stmt->fetch();
                    $count_like_stmt->close();
                ?>
                    <div class="comment">
                        <!-- Like button -->
                        <!-- Check user's action is like or unlike -->
                        <a href="javascript:void(0);"
                        class="comment-like-btn like-btn<?php echo $liked_comment ? ' liked' : ''; ?>"
                        data-comment-id="<?php echo $comment_id; ?>"
                        data-post-id="<?php echo $post_id; ?>"
                        data-action="<?php echo $liked_comment ? 'unlike' : 'like'; ?>">
                        <i class="fas fa-heart"></i>
                        <span class="like-count"><?php echo $likes_count; ?></span>
                        </a>


                        <!-- Delete or ban button -->
                        <!-- Only comment owner or post owner can delete comment -->
                        <?php if ($comment['user_id'] == $_SESSION['user_id'] || $_SESSION['user_id'] == $post['user_id'] && $_SESSION['role'] !== 'admin'): ?>
                            <a href="delete_comment.php?comment_id=<?php echo $comment_id; ?>&post_id=<?php echo $post_id; ?>" 
                            class="action-btn comment-delete-btn"
                            onclick="return confirm('Are you sure you want to delete this comment?');">
                            <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        <?php endif; ?>

                        <!-- Only admin can ban comment -->
                        <?php if ($_SESSION['role'] === 'admin' && $comment['user_id'] != $_SESSION['user_id']): ?>
                            <a href="ban_comment.php?comment_id=<?php echo $comment_id; ?>&post_id=<?php echo $post_id; ?>" 
                            class="action-btn comment-ban-btn"
                            onclick="return confirm('Are you sure you want to ban this comment?');">
                            <i class="fas fa-ban"></i> Ban
                            </a>
                        <?php endif; ?>

                        <!-- Display comment info -->
                        <div class="comment-author"><?php echo htmlspecialchars($comment['author']); ?></div>
                        <small class="comment-time"><?php echo date('Y-m-d', strtotime($comment['time'])); ?></small>
                        <p><?php echo nl2br(htmlspecialchars($comment['text'])); ?></p>
                    </div>

                <?php endforeach;  
                
                ?>
                <!-- If no any comment -->
                <?php if (!$has_comments): ?>
                    <div class="no-comments">
                        <i class="fas fa-comment"></i>
                        <p>No comments yet. Be the first to comment!</p>
                    </div>
                <?php endif;
            ?>
        </div>
    </div>

    <!-- Java script to implement image carousel function -->
    <script>
        const images = <?php echo json_encode($image_array); ?>; // Converts PHP image array to JavaScript array
        let currentIndex = 0; // Current image index

        function changeImage(direction) { // Change image based on direction
            currentIndex += direction;

            if (currentIndex < 0) { // First image to last image
                currentIndex = images.length - 1;
            } else if (currentIndex >= images.length) { // Last image to first image
                currentIndex = 0;
            }

            // Update current image
            document.getElementById("carouselImage").src = images[currentIndex].trim();

            // Update counter text
            document.getElementById("image-counter").textContent = (currentIndex + 1) + " / " + images.length;
        }
    </script>

    <!-- Java script to implement like without reload page -->
    <script>   
    document.getElementById('likeBtn').addEventListener('click', function () { // Add click event listener to the like button
        const btn = this;
        const postId = btn.getAttribute('data-post-id');
        const hasLiked = btn.getAttribute('data-has-liked') === '1';
        const action = hasLiked ? 'unlike' : 'like';

        // Send AJAX GET request to like.php with post ID and action
        fetch(`like.php?post_id=${postId}&action=${action}`, {
            method: 'GET'
        })
        .then(response => {
            if (!response.ok) { // If response is not OK, throw an error
                throw new Error('Network response was not OK');
            }
            return response.text(); // handle server message
        })
        .then(() => {
            // Update like count and button status on the page
            const countSpan = document.getElementById('likeCount');
            let count = parseInt(countSpan.textContent);

            if (action === 'like') {
                count += 1; // Increase count
                btn.classList.add('liked'); // Add CSS class
                btn.setAttribute('data-has-liked', '1'); // Marked as liked
            } else {
                count = Math.max(0, count - 1); // Decrease count but not below 0
                btn.classList.remove('liked'); // Remove CSS class
                btn.setAttribute('data-has-liked', '0'); // Marked as unlike
            }

            // Update the number shown in the like count span
            countSpan.textContent = count;
        })
        .catch(error => {  // Log any error that occurred during fetch
            console.error('Like failed:', error);
        });
    });
    </script>

    <!-- Java script to implement comment like without reload page -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.comment-like-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const commentId = btn.getAttribute('data-comment-id');
                const postId = btn.getAttribute('data-post-id');
                const action = btn.getAttribute('data-action');
                const url = `like_comment.php?comment_id=${commentId}&action=${action}&post_id=${postId}`;

                // Send the GET request using fetch API
                fetch(url, { method: 'GET' })
                    .then(response => {
                        if (!response.ok) { // If response is not OK, throw an error
                            throw new Error('Network response was not OK');
                        }
                        return response.text(); // handle server message
                    })
                    .then(() => { // Update like count and button status on the page
                        const likeCountSpan = btn.querySelector('.like-count');
                        let currentCount = parseInt(likeCountSpan.textContent);

                        if (action === 'like') {
                            currentCount += 1; // Increase count
                            btn.classList.add('liked'); // Add CSS class
                            btn.setAttribute('data-action', 'unlike'); // Next action will be 'unlike'
                        } else {
                            currentCount = Math.max(0, currentCount - 1); // Decrease count but not below 0
                            btn.classList.remove('liked'); // Remove CSS class
                            btn.setAttribute('data-action', 'like'); // Next action will be 'like'
                        }

                        // Update the number shown in the like count span
                        likeCountSpan.textContent = currentCount;
                    })
                    .catch(error => { // Log any error that occurred during fetch
                        console.error('Like failed:', error);
                    });
            });
        });
    });
    </script>

    <!-- Java script to implement submit comment without reload page -->
    <script>
    document.getElementById("commentForm").addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent page reload after submission

        const form = this;
        const formData = new FormData(form);

        // Send form data to the server using fetch and POST method
        fetch("add_comment.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text()) // Convert server response to plain text
        .then(data => {
            location.reload(); // Reload the page to show the new comment
        })
        .catch(error => { // Log any error that occurred during fetch
            console.error("Comment submission failed:", error);
            document.getElementById("commentMessage").textContent = "Failed to post comment.";
        });
    });
    </script>



    

</body>
</html>