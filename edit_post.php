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

// Function to check if a tag is selected
function is_selected($value, $selected_tags) {
    // If the tag exists in $selected_tags array, return 'selected', otherwise return ''
    return in_array($value, $selected_tags) ? 'selected' : '';
}

// Get current post data from database
$sql = "SELECT post_id, id, title, content, tags, likes, created_at, images FROM Posts WHERE post_id = ? AND id = ?";
$stmt = $conn->prepare($sql);
// If preparation fails, stop the script and show error
if (!$stmt) {
    die("Something went wrong: " . $conn->error);
}

$stmt->bind_param("ii", $post_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($post_id, $user_id, $title, $content, $tags, $likes, $created_at, $images);

if ($stmt->fetch()) {
    $post = array(
        'post_id' => $post_id,
        'user_id' => $user_id,
        'title' => $title,
        'content' => $content,
        'tags' => $tags,
        'likes' => $likes,
        'created_at' => $created_at,
        'images' => $images
    );
  
// Convert the comma-separated tag string from the database into an array
$selected_tags = array_map('trim', explode(",", $tags));
    
} else {
    die("Post not found or you are not authorized to edit.");
}

$stmt->close(); // Close statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/input_post.css">

    <style>

        /* Back button container below sidebar */
        .back-btn-wrapper {
            text-align: left;
            padding-left: 150px;
            margin-top: 20px;
        }

        /* Back button */
        .back-btn {
            background-color: #87b391;
            color: #000000;
            border: 1px solid #c3e6cb;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        /* Back button when hovered */
        .back-btn:hover {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }


        /* Dropdown List*/
        .select2-dropdown {
            border: 1px solid #c8e6c9 !important;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        /* Select Tags Container */
        .select2-selection--multiple {
            border: 1px solid #ddd !important;
            padding: 5px;
        }
        
        /* Selected Tag */
        .select2-selection__choice {
            background-color: var(--secondary-green) !important;
            color: white !important;
            border: none !important;
            border-radius: 16px !important;
            padding: 4px 12px 4px 28px !important; /* Extra left padding for remove button */
            margin: 3px !important;
            font-size: 14px !important;
            align-items: center;
            min-height: 28px; /* Ensure consistent height */
        }

        /* Tag Remove Button */
        .select2-selection__choice__remove {
            color: white !important;
            position: absolute !important;
            left: 8px !important; /* Position from left */
            top: 50% !important; /* Center vertically */
            transform: translateY(-50%) !important; /* Perfect vertical centering */
            margin-right: 0 !important;
            opacity: 1;
            font-size: 14px !important;
            width: 16px !important;
            height: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50% !important;
        }
        
        .select2-selection__choice__remove:hover {
            color: white !important;
            opacity: 1;
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        /* Dropdown Options */
        .select2-results__option {
            padding: 10px 150px;
            border-bottom: 1px solid #f0f0f0;
            font-family: inherit;
            font-size: 16px;
        }
        
        /* Dropdown Options On Hover*/ 
        .select2-results__option--highlighted {
            background-color: var(--secondary-green) !important;
            color: white !important;
        }
        
        /* Group Headers */
        .select2-results__group {
            background-color: var(--light-green) ;
            color: var(--dark-green);
            font-weight: bold;
            padding: 8px 15px;
        }
        
        /* Focus container */
        .select2-container--focus .select2-selection--multiple {
            border-color: var(--secondary-green) !important;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }
        
        /* Scrollbar */
        .select2-results__options::-webkit-scrollbar {
            width: 8px;
        }
        
        .select2-results__options::-webkit-scrollbar-thumb {
            background: var(--secondary-green) !important;
            border-radius: 4px;
        }

    </style>    

</head>

<body>

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
    <!-- Form to edit post --> 
    <div class="form">

        
        
        <h1 class="form-title"><i class="fas fa-edit"></i> Edit Post</h1>
        
        <form action="update_post.php" method="POST" enctype="multipart/form-data">
            
            <!-- Title -->
            <label for="title" class="form-label">
                <i class="fas fa-heading"></i> Title
            </label>
            <input type="text" id="title" name="title" class="form-input" required value="<?php echo htmlspecialchars($post['title']); ?>">
            
            <!-- Image -->
            <?php if (!empty($post['images'])): // Check if the post has images ?>
                
                <div class="current-images">
                    <label for="Images" class="form-label">
                        <i class="fas fa-images"></i> Current Images
                    </label>

                    <?php
                    $image_array = explode(",", $post['images']); // Convert comma-separated image paths into an array
                    
                    foreach ($image_array as $img_path):
                        $img_path = trim($img_path); // Remove extra spaces
                    ?>
                        <div class="image-uploaded">
                            <img src="<?php echo $img_path; ?>" alt="Post image">
                            <div class="image-checkbox">
                                <label>
                                    <input type="checkbox" name="delete_images[]" value="<?php echo $img_path; ?>">
                                    Delete this image
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Upload New Images -->
            <label for="newImages" class="form-label">Upload New Images</label>
            <input type="file" name="new_images[]" class="file-input" multiple accept="image/*">
            <span class="form-note">*You can select multiple images (JPEG, PNG)</span>

            <!-- Content -->
            <label for="content" class="form-label">
                <i class="fas fa-align-left"></i> Content
            </label>
            <textarea id="content" name="content" class="form-input"><?php echo htmlspecialchars($post['content']); ?></textarea>
        
            <!-- Tags Selection -->
            <label for="tags" class="form-label">
                <i class="fas fa-tags"></i> Select Tags
            </label>
            
            <select name="tags[]" id="tags" class="form-input" multiple="multiple">
                <optgroup label="--- Environment & Conservation ---">
                    <option value="reforestation" <?php echo is_selected('reforestation', $selected_tags); ?>>Reforestation</option>
                    <option value="biodiversity" <?php echo is_selected('biodiversity', $selected_tags); ?>>Biodiversity</option>
                    <option value="forest conservation" <?php echo is_selected('forest conservation', $selected_tags); ?>>Forest Conservation</option>
                    <option value="wildlife protection" <?php echo is_selected('wildlife protection', $selected_tags); ?>>Wildlife Protection</option>
                </optgroup>

                <optgroup label="--- Community & Participation ---">
                    <option value="ngo initiatives" <?php echo is_selected('ngo initiatives', $selected_tags); ?>>NGO Initiatives</option>
                    <option value="volunteering" <?php echo is_selected('volunteering', $selected_tags); ?>>Volunteering</option>
                    <option value="community stories" <?php echo is_selected('community stories', $selected_tags); ?>>Community Stories</option>
                </optgroup>

                <optgroup label="--- Education & Insights ---">
                    <option value="education" <?php echo is_selected('education', $selected_tags); ?>>Education</option>
                    <option value="global reports" <?php echo is_selected('global reports', $selected_tags); ?>>Global Reports</option>
                    <option value="case studies" <?php echo is_selected('case studies', $selected_tags); ?>>Case Studies</option>
                    <option value="events" <?php echo is_selected('events', $selected_tags); ?>>Events</option>
                </optgroup>

                <optgroup label="--- Policy & Sustainability ---">
                    <option value="sustainable practices" <?php echo is_selected('sustainable practices', $selected_tags); ?>>Sustainable Practices</option>
                    <option value="policy & law" <?php echo is_selected('policy & law', $selected_tags); ?>>Policy & Law</option>
                </optgroup>
            </select>


            <span class="form-note" >*You can select multiple tags</span>

            <!-- Initialize and Style the Select2 Tag Selector -->
            <script>
                $(document).ready(function() { // Wait until the page is fully loaded
                    $('#tags').select2({ // Initialize Select2 on the #tags <select> element
                        placeholder: "Search or select tags...",
                        allowClear: true, // Allows user to clear all selected tags
                        width: '100%',
                        closeOnSelect: false, // Keep the dropdown open after selecting a tag
                        tags: false, // Disable free-form tag creation
                        theme: 'default',
                        dropdownCssClass: 'sdg-dropdown'
                    });
                    
                    // When the dropdown opens, automatically focus on the search field
                    $(document).on('select2:open', () => {
                        document.querySelector('.select2-search__field').focus();
                    });
                });
            </script>

            <!-- Hidden input field to pass post_id to server -->
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            
            <div class = "btn">
                <!-- Cancel button -->
                <a href="view_post.php?post_id=<?php echo $post_id; ?>" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>

                <!-- Save button -->
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</body>
</html>