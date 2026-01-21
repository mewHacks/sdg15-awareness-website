<?php
session_start();
// Check whether user logged in or not
if (!isset($_SESSION['user_id'])) {
    header("Location: LoginPage.php"); // Go to Login page
    exit;
}

// Define an empty array for selected tags
$selected_tags = array(); 
// Function to check if a tag is selected
function is_selected($value, $selected_tags) {
    // If the tag exists in $selected_tags array, return 'selected', otherwise return ''
    return in_array($value, $selected_tags) ? 'selected' : '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post</title>
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Font Awesome icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styles -->
    <link rel="stylesheet" href="CSS/input_post.css">

    <style>

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

        .btn-submit {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    </style>    

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
    
    <!-- Form to create post --> 
    <div class="form">

        <h1 class="form-title">Create New Post</h1>

        <form action="insert_post.php" method="POST" enctype="multipart/form-data">
            
            <!-- Title -->
            <label for="title" class="form-label">
                <i class="fas fa-heading"></i> Title
            </label>
            <input type="text" id="title" name="title" class="form-input" required placeholder="Enter your post title...">

            <!-- Image Upload -->
            <label for="images" class="form-label">
                <i class="fas fa-images"></i> Upload Images
            </label>
            <input type="file" name="images[]" class="file-input" accept="image/*" multiple onchange="checkImageCount(this)">

            <!--Limit maximum 9 images can be uploaded-->
            <script>
            function checkImageCount(img) {
                if (img.files.length > 9) {
                    alert("You can upload a maximum of 9 images.");
                    img.value = ""; // clear all choosen images
                }
            }
            </script>
            <span class="form-note">*You can select multiple images (JPEG, PNG)</span>

            <!-- Content -->
            <label for="content" class="form-label">
                <i class="fas fa-align-left"></i> Content
            </label>
            <textarea id="content" name="content" class="form-input" placeholder="Share your thoughts..."></textarea>

            <!-- Tags Selection -->
            <label for="tags" class="form-label">
                <i class="fas fa-tags"></i> Select Tags
            </label>
            
            <select name="tags[]" id="tags" class="form-input" multiple="multiple">
                <!-- ── Environment & Conservation ── -->
                <optgroup label="--- Environment & Conservation ---">
                    <option value="reforestation"        <?= is_selected('reforestation',        $selected_tags) ?>>Reforestation</option>
                    <option value="biodiversity"         <?= is_selected('biodiversity',         $selected_tags) ?>>Biodiversity</option>
                    <option value="forest conservation"  <?= is_selected('forest conservation',  $selected_tags) ?>>Forest Conservation</option>
                    <option value="wildlife protection"  <?= is_selected('wildlife protection',  $selected_tags) ?>>Wildlife Protection</option>
                </optgroup>

                <!-- ── Community & Participation ── -->
                <optgroup label="--- Community & Participation ---">
                    <option value="ngo initiatives"      <?= is_selected('ngo initiatives',      $selected_tags) ?>>NGO Initiatives</option>
                    <option value="volunteering"         <?= is_selected('volunteering',         $selected_tags) ?>>Volunteering</option>
                    <option value="community stories"    <?= is_selected('community stories',    $selected_tags) ?>>Community Stories</option>
                </optgroup>

                <!-- ── Education & Insights ── -->
                <optgroup label="--- Education & Insights ---">
                    <option value="education"            <?= is_selected('education',            $selected_tags) ?>>Education</option>
                    <option value="global reports"       <?= is_selected('global reports',       $selected_tags) ?>>Global Reports</option>
                    <option value="case studies"         <?= is_selected('case studies',         $selected_tags) ?>>Case Studies</option>
                    <option value="events"               <?= is_selected('events',               $selected_tags) ?>>Events</option>
                </optgroup>

                <!-- ── Policy & Sustainability ── -->
                <optgroup label="--- Policy & Sustainability ---">
                    <option value="sustainable practices"<?= is_selected('sustainable practices',$selected_tags) ?>>Sustainable Practices</option>
                    <option value="policy & law"         <?= is_selected('policy & law',         $selected_tags) ?>>Policy & Law</option>
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

            <!-- Share post button -->
            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Share Post
            </button>
        </form>
    </div>
</body>
</html>