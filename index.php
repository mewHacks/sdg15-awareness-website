<?php

session_start();

// DEBUG: For showing errors if page failed to run
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set is_admin flag
$is_admin = false;
if (isset($_SESSION) && isset($_SESSION['role'])) {
    $is_admin = ($_SESSION['role'] === 'admin');
}

// Connect to database
require_once dirname(__FILE__) . '/db_connect.php'; 

// Check database connection
if (!isset($conn)) {
    die("Database connection not established");
}

// Handle search query from GET request (if any)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_param = $search ? "%$search%" : "%"; // Defaults to "%" which matches everything

// Handle selected filter tags from GET request (if any)
$selected_tags = isset($_GET['tag']) ? (array)$_GET['tag'] : array(); // If got tags, cast to an array; If no, cast to empty array

// Handle sorting logic
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Function to mark selected filter tags as selected in <option>
function tag_selected($tag, $selected_tags) {
    return in_array($tag, $selected_tags) ? 'selected' : '';
}

// Function to mark selected sort option as selected in <option>
function sort_selected($option, $current_sort) {
    return $option === $current_sort ? 'selected' : '';
}

// SQL query to fetch posts - conditionally include banned posts for admin
$sql = "SELECT p.*, u.username, u.role,
        (SELECT COUNT(*) FROM likes WHERE post_id = p.post_id) AS like_count,
        (SELECT COUNT(*) FROM comments WHERE post_id = p.post_id) AS comment_count,
        (SELECT COUNT(*) FROM likes WHERE post_id = p.post_id AND id = ?) AS user_liked
        FROM posts p
        JOIN users u ON p.id = u.id
        WHERE " . ($is_admin ? "1=1" : "p.banned != 1") ."
        AND (p.title LIKE ? OR p.content LIKE ? OR p.tags LIKE ?
        )";

    
// Binds value for ? placeholders in SQL query:
// current user's ID (use 0 for guests), search term for title + content + tag
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$params = array($user_id, $search_param, $search_param, $search_param);

// Note: Search conditions are already included in base query via $search_param
// which defaults to "%" (matches everything) when no search term is provided

// Add tag filter conditions if tags are selected
if (!empty($selected_tags)) {
    $tag_conditions = array();
    foreach ($selected_tags as $tag) {
        $tag_conditions[] = "FIND_IN_SET(?, p.tags)"; // p.tags is a comma separated list
        $params[] = $tag; // Add each tag as a separate ? placeholder
    }

    // Join all conditions with OR and add to SQL
    $sql .= " AND (" . implode(" OR ", $tag_conditions) . ")";
}

// Handle sort's post created date order
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

switch ($sort_option) {
    case 'oldest':
        $sql .= " ORDER BY p.created_at ASC";
        break;
    case 'a_z':
        $sql .= " ORDER BY p.title ASC";
        break;
    case 'z_a':
        $sql .= " ORDER BY p.title DESC";
        break;
    default:
        $sql .= " ORDER BY p.created_at DESC"; // default: newest first
}

// Note: Search parameters were already added in initial $params array
// No need to add them again here

// Prepare final SQL query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$types = str_repeat('s', count($params)); // Assume all parameters are strings
$bind_names = array($types);
foreach ($params as $key => $value) {
    $bind_names[] = &$params[$key];
}
call_user_func_array(array($stmt, 'bind_param'), $bind_names);

// Execute query
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

// Fetch results 
$stmt->store_result();
$meta = $stmt->result_metadata();
$fields = array();
$row = array();

while ($field = $meta->fetch_field()) {
    $fields[] = &$row[$field->name];
}

call_user_func_array(array($stmt, 'bind_result'), $fields);

$posts = array();
while ($stmt->fetch()) {
    $post = array();
    foreach ($row as $key => $val) {
        $post[$key] = $val;
    }
    $posts[] = $post;
}

$stmt->close();

// Set the profile link destination
$profileLink = isset($_SESSION['user_id']) ? 'myprofile.php' : 'LoginPage.php?redirect=myprofile.php';

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <!-- Set title as 'Community' for normal users, 'Community - Admin' for admin -->
    <title>Community <?php echo $is_admin ? '- Admin' : '' ?></title>

    <!-- Custom CSS for community index page-->
    <link rel="stylesheet" href="css/index.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Select2 CSS for enhanced selection dropdown lists for filters -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>

<div class="container">

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

    <!-- Main content (everything except sidebar) -->
    <div class="main-content">

    <!-- Filter + Search -->
    <form id="content-query-form" method="get" action="">

        <!-- Filter and search is at same top row -->
        <div class="filter-search-row">

            <!--Filter tags container -->
            <div class="tag-filter-container">

                <!-- User can select multiple tags -->
                <select id="tag-filter" name="tag[]" multiple="multiple"> 
                    
                    <!-- Group tags using optgroup, and provide options-->
                    <optgroup label="Environment & Conservation">
                        <option value="reforestation" <?php echo tag_selected('reforestation', $selected_tags); ?>>Reforestation</option>
                        <option value="biodiversity" <?php echo tag_selected('biodiversity', $selected_tags); ?>>Biodiversity</option>
                        <option value="forest conservation" <?php echo tag_selected('forest conservation', $selected_tags); ?>>Forest Conservation</option>
                        <option value="wildlife protection" <?php echo tag_selected('wildlife protection', $selected_tags); ?>>Wildlife Protection</option>
                    </optgroup>

                    <optgroup label="Community & Participation">
                        <option value="ngo initiatives" <?php echo tag_selected('ngo initiatives', $selected_tags); ?>>NGO Initiatives</option>
                        <option value="volunteering" <?php echo tag_selected('volunteering', $selected_tags); ?>>Volunteering</option>
                        <option value="community stories" <?php echo tag_selected('community stories', $selected_tags); ?>>Community Stories</option>
                    </optgroup>

                    <optgroup label="Education & Insights">
                        <option value="education" <?php echo tag_selected('education', $selected_tags); ?>>Education</option>
                        <option value="global reports" <?php echo tag_selected('global reports', $selected_tags); ?>>Global Reports</option>
                        <option value="case studies" <?php echo tag_selected('case studies', $selected_tags); ?>>Case Studies</option>
                        <option value="events" <?php echo tag_selected('events', $selected_tags); ?>>Events</option>
                    </optgroup>

                    <optgroup label="Policy & Sustainability">
                        <option value="sustainable practices" <?php echo tag_selected('sustainable practices', $selected_tags); ?>>Sustainable Practices</option>
                        <option value="policy & law" <?php echo tag_selected('policy & law', $selected_tags); ?>>Policy & Law</option>
                    </optgroup>

                </select>
            </div>

            <!-- Sort dropdown container -->
            <div class="sort-container">
                <select id="sort-select" name="sort">
                    <option value="newest" <?php echo sort_selected('newest', $sort_option); ?>>Newest Posts First</option>
                    <option value="oldest" <?php echo sort_selected('oldest', $sort_option); ?>>Oldest Posts First</option>
                    <option value="a_z" <?php echo sort_selected('a_z', $sort_option); ?>>A-Z</option>
                    <option value="z_a" <?php echo sort_selected('z_a', $sort_option); ?>>Z-A</option>
                </select>
            </div>

            <!-- Search input field -->
            <div class="search-bar">
                <div class="search-container">
                    <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>

        </div>
    </form>
        

        <!-- Page title (Community)-->
        <h1 class="community-title">Community </h1>

        <!-- Posts -->
        <div class="posts-container">

        <!-- If no posts matched the search -->
        <?php if (empty($posts)): ?> 
            <div class="no-posts">No posts found. <?php echo !$is_admin ? 'Be the first to create one!' : ''; ?></div>
        
        <!-- Else if got posts matched the search -->
        <?php else: ?>

            <!-- Loop through the posts -->
            <?php foreach ($posts as $post): ?> 

                <!-- Banned posts can only be shown to admin -->
                <?php 
                    $is_post_by_admin = (isset($post['role']) ? $post['role'] : '') === 'admin';
                    $is_post_banned = isset($post['banned']) && $post['banned'] == 1;
                    $should_show_post = !$is_post_banned || $is_admin;
                ?>

                <?php if ($should_show_post): ?>

                    <!-- Link post to respective expanded posts -->
                    <div class="post-link" data-href="view_post.php?post_id=<?php echo $post['post_id']; ?>">    

                        <!-- Set UI/colors based on if post is banned (red) or posted by admin (blue)-->
                        <div class="post 
                            <?php echo $is_post_banned ? 'banned-post' : ''; ?> 
                            <?php echo $is_post_by_admin ? 'admin-post-highlight' : ''; ?>">

                            <!-- Admin actions: show ban/unban if viewing normal users' post -->
                            <?php if ($is_admin && $post['id'] != $_SESSION['user_id']): ?>
                                
                                <div class="admin-actions">
                                    <!-- If post is not banned yet, show 'Ban' button on top right -->
                                    <?php if (!$is_post_banned): ?>
                                        <a href="ban.php?post_id=<?php echo $post['post_id']; ?>&action=ban" 
                                        class="ban-btn" 
                                        onclick="return confirm('Are you sure you want to ban this post?');">
                                            <i class="fas fa-ban"></i> Ban
                                        </a>
                                        </form>

                                    <!-- Else if post is banned, show 'Unban' button on top right -->
                                    <?php else: ?>
                                        <a href="ban.php?post_id=<?php echo $post['post_id']; ?>&action=unban" 
                                        class="ban-btn"     
                                        onclick="return confirm('Are you sure you want to unban this post?');">
                                            <i class="fas fa-check-circle"></i> Unban
                                        </a>

                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Post title -->
                            <h3><?php echo htmlspecialchars($post['title']); ?></h3>

                            <!-- Post meta data (Username + created date)-->
                            <p class="meta">
                                Posted by <?php echo htmlspecialchars($post['username']); ?> 
                                on <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                <!-- Remark if post is banned -->
                                <?php if ($is_post_banned && $is_admin): ?>
                                    <span class="banned-label" style="color: red; font-weight: bold;">BANNED</span>
                                <?php endif; ?>
                            </p>

                            <!-- Post content -->
                            <?php
                            $content = trim($post['content']); // Trim content to remove whitespace
                            $is_long = strlen($content) > 300; // Check if content is too long
                            $short_content = $is_long ? substr($content, 0, 300) . '...' : $content; // If too long, show first 300 char only
                            ?>
                            <div class="post-content" data-post-id="<?php echo $post['post_id']; ?>">
                                <div class="short-content"><?php echo nl2br(htmlspecialchars($short_content)); ?></div>
                               <!-- If content exceeds 300 char, there will be a 'Show more' button -->
                                <?php if ($is_long): ?>
                                    <div class="full-content" style="display: none;"><?php echo nl2br(htmlspecialchars($content)); ?></div>
                                    <button class="show-more-btn">Show more</button>
                                <?php endif; ?>
                            </div>

                            <!-- Post images -->

                            <?php // Process image list from post
                            $image_string = isset($post['images']) ? $post['images'] : ''; // Get the image string, handle missing image
                            $image_array = explode(',', $image_string); // Split the string by comma into array
                            $trimmed_images = array_map('trim', $image_array); // Remove whitespace
                            $images = array_filter($trimmed_images); // Remove empty strings 
                            ?>

                            <!-- Check if $images array has any image(s) -->
                            <?php if (!empty($images)): ?>
                                <!-- Display images in a neat format -->
                                <div class="post-image-strip">
                                    <?php foreach ($images as $img_src): ?>
                                        <img src="<?php echo htmlspecialchars($img_src); ?>" alt="Post image"> <!-- Alt text for accessibility -->
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Tags -->
                            <?php if ($post['tags']): ?> <!-- Check if post have tag(s) -->
                                <div class="tags">
                                    <!-- Split the string by comma into array -->
                                    <?php foreach (explode(',', $post['tags']) as $tag): ?>
                                        <!-- urlencode escapes tag name for use in URL -->
                                        <a href="?tag=<?php echo urlencode(trim($tag)); ?>" class="tag">
                                        <i class="fas fa-tag"></i> <?php echo htmlspecialchars(trim($tag)); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Post actions (Like + comment)-->
                            <div class="post-actions">

                                <!-- Display like icon button + like count -->
                                <button type="button"
                                    class="icon-btn like-btn <?php echo $post['user_liked'] ? 'liked' : ''; ?>"
                                    data-post-id="<?php echo $post['post_id']; ?>">
                                    <i class="fas fa-heart"></i> 
                                    <span class="count"><?php echo $post['like_count']; ?></span>
                                </button>

                                <!-- Display comment icon button + comment count -->
                                <a href="view_post.php?post_id=<?php echo $post['post_id']; ?>" 
                                class="icon-btn comment-btn">
                                    <i class="fas fa-comment"></i>
                                    <span class="count"><?php echo $post['comment_count']; ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    </div>
</div>

<!-- Floating '+' button to add post on bottom right -->
<a href="add_post.php" class="fab-btn" title="Add Post">
    <i class="fas fa-plus"></i>
</a>

<!-- External jQuery library for DOM, event handling, AJAX,etc..-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 

<!-- External select2 library for enhanced dropdown lists for filters -->
<!-- Supports searchable dropdown, multi-select, etc.. -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 


<!-- JavaScript functions-->
<script>
$(document).ready(function() {

    // Initialize Select2 plugin for better styling in tag filtering
    $('#tag-filter').select2({
                placeholder: 'Filter by tags...',
                width: '100%',
                closeOnSelect: false, // Prevent dropdown close after selecting a tag
                allowClear: true, // Let users remove selected tag by clicking 'x'
                escapeMarkup: function(m) { return m; } // Overrides select2 default escaping HTML, allows HTML text in placeholder
    });

    // Initialize Select2 plugin for sort dropdown
    $('#sort-select').select2({
                placeholder: 'Sort by...',
                width: '100%',
                minimumResultsForSearch: -1, // Disable search for sort dropdown
                allowClear: false, // Don't allow clearing sort option, because there is always a sequence
                escapeMarkup: function(m) { return m; }
    });
            
    // Auto submit form when tags are changed
    $('#tag-filter').on('change', function() { // Attach event listener to <select>, 
                                               // 'change' triggered when user select/deselect tag
        console.log('Tags changed:', $(this).val()); // DEBUG: to see what tags are selected b4 submit
        $('#content-query-form').submit(); // Auto submit
     });

    // Auto submit form when sort option is changed
    $('#sort-select').on('change', function() {
        console.log('Sort changed:', $(this).val()); // DEBUG: to see what sort option is selected b4 submit
        $('#content-query-form').submit(); // Auto submit
    });
            
    // DEBUG: to verify input is detected, value is updated in live
    $('input[name="search"]').on('input', function() {
        console.log('Search input:', $(this).val()); 
    });

    // Show more/ show less toggle if post content is too long
    $(document).on('click', '.show-more-btn', function(e) { // Attach a click handler to show more btn

        e.stopPropagation();  // Prevent linking to respective post page
        const $btn = $(this);
        const $container = $btn.closest('.post-content'); // Find the closest post

        // Check if full content is being shown, and show the opposite visibility on click
        const isExpanded = $container.find('.full-content').is(':visible');
        $container.find('.short-content').toggle(isExpanded);
        $container.find('.full-content').toggle(!isExpanded);

        // Set 'Show(more/less)' text based on expanded state
        $btn.text(isExpanded ? 'Show more' : 'Show less');
    });

    // Handle like post icon button clicks using AJAX
    $('.like-btn').on('click', function(e) {
        e.preventDefault(); // Prevent page from jumping to top
        const $btn = $(this);
        const postId = $btn.data('post-id'); // Read post id from clicked button

        // Send AJAX request without reloading page
        $.ajax({ 
            url: 'like_index.php',
            type: 'POST', // Change data on server
            dataType: 'json', // JQuery return key-value pairs
            data: {
                post_id: postId // Send post_id of post being liked/unliked
            },

            // Runs before sending request
            beforeSend: function() { 
                $btn.addClass('processing').prop('disabled', true);
            }, // Disable button to prevent double clicking

            // Runs after request success
            success: function(data) { 
                if (data.success) {
                    $btn.toggleClass('liked'); // Toggle liked effect
                    $btn.find('.count').text(data.like_count); // Update like count

                    // Animate heart using CSS transform
                    $btn.find('i').css('transform', 'scale(1.3)');
                    setTimeout(() => {
                        $btn.find('i').css('transform', 'scale(1)');
                    }, 300);
                }
            },

            // Alert users if server fail to respond or return error
            error: function() {
                alert('Failed to like post. Try again.');
            },

            // Always runs despite success/not
            // Re enable button
            complete: function() {
                $btn.removeClass('processing').prop('disabled', false);
            }
        });
    });

    });

    // Handle comment like/unlike
    $(document).ready(function() {
        $(document).on('click', '.comment-like-btn', function(e) {
            
            e.preventDefault();
            const $btn = $(this);
            const commentId = $btn.data('comment-id');
            
            $.ajax({
                url: '../php/like_comment.php',
                type: 'POST',
                data: {
                    comment_id: commentId,
                },
                dataType: 'json',

                // If successful, 
                success: function(data) {
                    if (data.success) {
                        $btn.toggleClass('liked'); // Toggle comment liked effect
                        $btn.html(`<i class="fas fa-heart"></i> ${data.like_count}`); // Update comment like count
                    }
                }
            });
        });
});


// Navigate to individual post if clicked on post
$(document).on('click', '.post-link', function(e) {

    if ( // Prevent navigating when clicking on button, link, textarea, etc.
        $(e.target).closest('button, .show-more-btn, .comment-btn, .like-btn, .tag, textarea, form').length > 0
    ) return;

    // If safe, redirect to URL set in data-href of post container
    const url = $(this).data('href');
    if (url) window.location.href = url;
});

</script>

</body>
</html>