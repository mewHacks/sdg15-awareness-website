<?php
session_start();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_param = $search ? "%$search%" : "%"; 

$profileLink = isset($_SESSION['user_id']) ? 'myprofile.php' : 'LogInPage.php?redirect=myprofile.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Initiative 1: Education</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
    color: #333;
    line-height: 1.6;   
}
.containerabout { 
    display: flex;
    width: 100%;
    height: auto;
    overflow-x: hidden;
}
    
.main-content {
    margin-left: 270px; 
    padding: 20px;
    flex: 1;
    background: linear-gradient(#DAEFCF, white);
    padding-top: 20px;
}


/* Left sidebar */
 .sidebar {
    width: 270px;
    background-color: #12372A;
    color: white;
    position: fixed;
    height: 100%;
    left: 0;
    top: 0;
    z-index: 100;
    display: flex;
    flex-direction: column;
}

.sidebar-nav {
    padding: 15px 10px;
}

.sidebar a {
    display: flex;
    align-items: center;
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    border-bottom: 2px solid #ddffe848; 
    padding: 10px 12px;
    border-radius: 0px;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    margin-bottom: 2px;
}

.sidebar a i {
    width: 22px;
    text-align: center;
    font-size: 1rem;
    margin-right: 10px;
}

.sidebar a.active {
    background: rgba(118,224,54,0.2);
    color: #76E036;
    font-weight: 500;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.1);
}

 /* Back button below sidebar */
.back-btn-wrapper {
    text-align: left;
    padding-left: 150px;
    margin-top: 20px;
}

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

.back-btn:hover {
    background-color: #28a745;
    color: white;
    border-color: #28a745;
}
 .profile-sidebar-link {
    position: absolute;
    bottom: 80px;   
    left: 15%;
    transform: translateX(-50%);
    text-align: center;
 }

 .profile-sidebar-link i {
    font-size: 35px;
    color: white;
    transition: transform 0.3s ease;
 }

 .profile-sidebar-link i:hover {
    transform: scale(1.1);
    color: #76E036;
    cursor: pointer;
 }
    
/* "Content" include images + text */
 .content {
    background-color: #194e3b; 
    padding: 20px;
    width: 100%;
    text-align: center;
    position: relative;
    box-sizing: border-box;
}
    

.inner {
    width: 100%;
    min-height: 100vh;
    background-image: linear-gradient(#daefcf, white);
    padding: 60px 40px;
    box-sizing: border-box;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}
/* Search top row */
.search-row {
    display: flex;
    justify-content: flex-end;  /* This pushes search bar to the right */
    align-items: center;
    margin: 20px 0;
    padding: 0;
    gap: 20px;
    width: 100%;
}

/* Search field wrappers */
.search-form {
    display: flex;
    align-items: center;
    position: relative;
}

/* Search bar */
.search-bar {
    display: flex;
    align-items: center;
}

.search-form {
    display: flex;
    align-items: center;
}

.search-form input {
    height: 40px; /* Match filter height */
    width: 220px;
    border: 1px solid #ccc;
    border-right: none;
    border-radius: 8px 0 0 8px; /* Match filter border radius */
    padding: 0 12px;
    font-size: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

/* Highlighted searched keyword  */
.search-highlight {
    background-color: #ffeb3b;
    color: #000;
    font-weight: bold;
    border-radius: 2px;
    padding: 0 2px;
}

.search-highlight.active {
    background-color: #76E036;
    box-shadow: 0 0 0 2px rgba(118, 224, 54, 0.5);
}

.search-form button {
    height: 40px; /* Match filter height */
    background-color: #31483f;
    color: white;
    border: 1px solid #ccc;
    border-left: none;
    border-radius: 0 8px 8px 0; /* Match filter border radius */
    padding: 0 15px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.search-form button i {
    margin: 0;
}

/* Focus state for search input */
.search-form input:focus {
    outline: none;
    border-color: #76E036;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1), 0 0 0 2px rgba(118, 224, 54, 0.2);
}


h1{
 margin-top: 70px;
 background-color: #558B2F;
 font-family: Courier New, monospace;
 color: #ffffff;
 text-shadow: 2px 2px 5px #000000;
 text-align: center;
 padding: 10px;
 border-radius: 10px;
 width: 50%;
 margin-left: auto;
 margin-right: auto;
}

.image-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center; 
  gap: 20px; 
  margin-top: 20px;
}

.image-grid img {
  border-radius: 8px;
  border: 3px solid #7a9e06;
  box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
  transition: transform 0.3s;
  width: 300px;
  height: 200px;
  object-fit: cover;
}

.image-grid img:hover {
  transform: scale(1.05);
}

ul {
  list-style: none;
  padding-left: 0;
  margin-top: 20px;
}

ul li {
  background: linear-gradient(90deg, #A4CE8D, #d5f5c1);
  padding: 12px 15px;
  margin-bottom: 10px;
  border-radius: 8px;
  border-left: 6px solid #558B2F;
  font-size: 1.1em;
  color: #1B5E20;
  box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
  position: relative;
  padding-left: 40px;
}

ul li::before {
  content: "\f058";
  font-family: "Font Awesome 6 Free";
  font-weight: 900;
  position: absolute;
  left: 12px;
  color: #4CAF50;
}

.text-content {
  margin-left: 50px; 
  margin-top: 30px;
}
  </style>
</head>
<body>

<div class="containerabout">

<!-- Sidebar with icons for navigation to other pages -->
<div class="sidebar">
    <nav class="sidebar-nav">
        <a href="Homepage.php"> <!-- Homepage -->
            <i class="fas fa-home"></i>
            <span>Homepage</span>
        </a>
        <a href="about.php" > <!-- About page -->
            <i class="fas fa-info-circle"></i>
            <span>About</span>
        </a>
        <a href="initiatives.php"class="active"> <!-- Initiatives page -->
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
        <a href="index.php"> <!-- Community page -->
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

    <!-- Search -->
    <form id="search-form" method="get" action="">

        <!-- Search -->
    <form id="search-form" method="get" action="">

        <!-- Search top row -->
        <div class="search-row">

            <!-- Search input field -->
            <div class="search-bar">
                <div class="search-form">
                <input type="text" id="live-search" name="search" placeholder="Search..." value=<?php echo htmlspecialchars($search); ?>>
                <button type="button" id="search-button"><i class="fas fa-search"></i></button>
                </div>
            </div>

            </div>
    </form>

<!-- JavaScript functions-->
<script>
$(document).ready(function() {

    // DEBUG: to verify input is detected, value is updated in live
    $('input[name="search"]').on('input', function() {
        console.log('Search input:', $(this).val()); 
    });

});   

</script>        
<div id="article-content">
    <h1>🌱 Initiative 1: Education</h1>

    <div class="image-grid">
      <img src="https://th.bing.com/th/id/OIP.QV-JCgYXQnV4xXQ2Qb9oBwAAAA?rs=1&pid=ImgDetMain" alt="Student">
      <img src="https://i.pinimg.com/originals/fc/2a/f9/fc2af9302341e39ea2ac94ba81e2bf02.jpg" alt="Educator">
    </div>

    <div class="text-content">
      <h2><u>Student Actions</u></h2>
      <ul>
        <li>Join school replanting activities 🌳</li>
        <li>Organize clean-up days with friends 🧹</li>
        <li>Learn about conservation in fun ways 📚</li>
      </ul>

      <h2><u>Educator Actions</u></h2>
      <ul>
        <li>Host weekly environmental quizzes 📝</li>
        <li>Make eco-topics part of daily lessons 🌍</li>
        <li>Encourage students to protect nature 🦋</li>
      </ul>
    </div>

    </div>

</div>
</div>
<!-- Javascript -->
<!-- Handles search -->
<script src="article_search.js" ></script>
</body>
</html>
