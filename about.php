<?php
session_start();
include 'db_connect.php';

// Set is_admin flag
//$is_admin = ($_SESSION['role'] ?? '') === 'admin';

// Connect to database
//require_once __DIR__.'/db.php';

// Check database connection
//if (!isset($conn)) {
    //die("Database connection not established");
//}

// Handle search query from GET request (if any)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_param = $search ? "%$search%" : "%"; // Defaults to "%" which matches everything

// SQL query if have

// Set the profile link destination
$profileLink = isset($_SESSION['user_id']) ? 'myprofile.php' : 'LoginPage.php?redirect=myprofile.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title> About </title>
 <style>
 
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
    color: #333;
    line-height: 1.6;   
}

/* Main container in "about" section to hold entire layout */
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

p {
 padding-left: 25px;
}   
 

li p {
 text-align: left;
}    


.inner h1 {
 font-family: Copperplate, Fantasy;
 color:#395B39;
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

/* Page title */
.title {
    text-align: center;
    margin: 10px 0 20px 0;
    color: #2E7D32;
    width: 100%;
}
 
.inner h1.subsubtitle {
 background-color: #31483f; 
 color: white;
 font-family: "Arial";
 display: inline-block;
 margin-top: 60px;
 padding: 10px 15px;
 border-radius: 15px;
 text-align: center;
 width: auto;
}

    
.inner .deforest img  {
 max-width: 80%;
 height: auto;
 margin-top: 2%;
 margin-left: 12%;
 display: block;
 border-radius: 10px;
 border:5px solid #1D4319;
}

    
.fadein {
 opacity: 1;
 animation-name: Fadein;
 animation-iteration-count: 1;
 animation-timing-function: ease-in;
 animation-duration: 2s;
}
    

@keyframes Fadein {
 0% {opacity: 0;}
 100% {opacity: 1;}
}
    

.statis {
 align-items: center;
 display: flex;
}


.globalfor {
 width: 90%;
 height: auto;
}

    
.globalfor button{
 background-image: linear-gradient(#12372a, #3A5430);
 font-size: 20px;
 font-family: Verdana,sans-serif;
 font-weight: bold;
 color: white;
 border-radius: 10px;
 border: none;
 padding: 90px;
}
    

.globalfor button:hover{
 background-image:linear-gradient(#76E036,#76E036);
 border: none;
 cursor: pointer;
}

    
.impacttext a {
 font-size: 20px;
 color:#09375F;
 background-color: #E8E8E8;
 border-radius: 5px;
 font-weight: bold;
 text-decoration: none;
 padding: 10px;
}
    

.impacttext a:visited {
 background-color:#749169;
 color:#395B39;
}
   

.impacttext a:hover {
 color:white ;
 background-color: black;
}
    

.mission marquee img { 
 display: inline-block;
 width: 20vw;
 height: auto; 
 max-height: 40vw;
 padding: 15px;
 border: 4px solid #1D4319;  
 margin: 4px;
}
 
.impact marquee img { 
  display: inline-block; 
  width: 22vw;         
  height: auto;      
  max-height: 20vw;  
  padding: 15px;
  border: 4px solid #1D4319;  
  margin: 0 20px;     
  vertical-align: middle; 
  object-fit: cover;  
}

    
video {
 border:5px solid #1D4319;
 border-style: dashed;
}
 </style> 
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
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
        <a href="about.php" class="active"> <!-- About page -->
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
<div id="article-content">
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

        <!-- Page title -->
        <h1 class="title"> About </h1>  
        


 <div class="content">
  
 <div class="inner">
  
  <h1 class="subsubtitle"> Our Mission </h1>
  
  <h1> Mission:</h1> 

  <p> To protect, restore and promote sustainable use of terrestrial ecosystems, sustainably manage forests, combat desertification,<br><br>  halt and reverse land degradation, and halt biodiversity loss. </p> 

  <div class="mission">
   
   <marquee>
    <img src="images/forest.jpg" alt="Mission1" > 
    <img src="images/landscape.jpg" alt="Mission2" > 
    <img src="images/elephant.jpg" alt="Mission3" > 
   </marquee>

  </div> <!--mission-->
  
  <h1 class="subtitle"> Overview </h1>

  <div class="deforest">
   <img src="images/treetext.png" alt="Deforestation and Forest Degradation Image" class="fadein"  >
  </div> <!--deforest--> <br>
  
 <p>Despite progress in protecting, restoring and managing terrestrial ecosystems, achieving SDG 15 remains challenging. </p>
 <p>Forests, known as the Earth's lungs, continue to face threats from <strong style="color:#253D1A;"> deforestation and degradation</strong>. </p> <br>
  
  
 <div style="text-align:center">
  <video style="width:50vw; height:auto;" controls>
  <source src="videos/deforestvid.mp4" type="video/mp4">
  </video>
 </div> <br>
  
 <h1>Definition and Causes: </h1>
  
 <img src ="images/deforestation.gif" alt="Deforestation Animation" width="300vw" height="200vw" style="float:left; margin-left:90px; margin-right: 90px; border-radius: 20px;">
  
 <div style="overflow:hidden">
  <ul>
  <li><p>Deforestation happens when forests are cleared for <br>
  non-forest uses like agriculture and roads.</p></li>  
  
  <li><p>Forest degradation occurs when ecosystems lose their<br>
  ability to provide essential goods and services.</p></li> 

  <li><p>Rapid deforestation is driven by agricultural expansion <br>
  and infrastructure development, while forest degradation<br>
  is primarily caused by logging, grazing and road construction. </p></li> 
  </ul>
 </div>
  
 <br>
  
 <h1> Statistics: </h1>

 <div class="statis">
  
 <div style="margin-left:70px">
  
  <ul>
  <li><p>The World Bank reports a global net forest loss of 4.7 million hectares annually from 2010 to 2020.</p></li>
  <li><p>The UN noted a 100 million hectare decline in forest area between 2000 and 2020, with agricultural expansion driving 90% of deforestation.  </p> </li> 
  <li><p>India lost about 38,000 hectares between 2019 and 2021, while the world  lost over 16 million acres in 2022, according to the 2023 Forest Declaration <br> Assessment. </p> </li> 
  <li><p>Malaysia lost 133,000 hectares from 2010 to 2023, emitting 146 million tons of CO<sub>2</sub>. </p></li> 
  <li><p> Over half of the tropical forests have been destroyed since the 1960s, with more than one hectare lost every second.</p> </li> 
  </ul>
 </div>
  
 <br>

 <div class="globalfor">
  <a href= "https://www.globalforestwatch.org/map/?mapMenu=eyJtZW51U2VjdGlvbiI6ImRhdGFzZXRzIiwiZGF0YXNldENhdGVnb3J5IjoibGFuZFVzZSJ9"> 
  <button type="button" > Explore more... </button></a>
 </div> 

 </div>
  
 <br>
  
 <h1> Impacts: </h1>

 <div class="impact">
  <marquee>
   <img src="images/biod.jpg" alt="Biodiversity loss" > 
   <img src="images/liveli.jpg" alt="Livelihood disruption" > 
   <img src="images/climate.jpg" alt="Climate change" > 
  </marquee>
 </div> 

 <br>

 <div class="impacttext">
  <p> <a href="https://www.roundsquare.org/ideals-challenge/round-square-environmentalism-sessions/task-five/biodiversity-and-deforestation/#:~:text=Deforestation%20can%20directly%20lead%20to,plant%20species%20in%20an%20environment."> Biodiversity Loss </a> </p> 
  
  <p> Forest degradation threatens species survival and reduces forests' ability to provide <br> essential services like habitats, ecosystem maintenance and soil protection.</p><br><br>
  
  <p> <a href="https://earth.org/the-silent-cry-of-the-forest-how-deforestation-impacts-indigenous-communities/#:~:text=Loss%20of%20Subsistence%20and%20Livelihoods&text=These%20resources%20are%20directly%20endangered,of%20their%20self%2Dsufficient%20economy."> Livelihood Disruption </a> </p>
  
  <p> Deforestation affects 1.6 billion people who rely on forests, including 1 billion of the world's poorest. <br> It also leads to the loss of resources like medicinal plants, fruits and fuelwood. </p> <br><br>
  
  <p> <a href="https://www.nature.org/en-us/about-us/where-we-work/latin-america/brazil/stories-in-brazil/deforestation-and-climate-change/#:~:text=Trees%20not%20only%20absorb%20carbon,released%20and%20less%20is%20absb"> Climate Change </a></p>
  
  <p> Forest destruction emits 4.3-5.5 Gt CO <sub> 2 </sub> eq annually, mainly from deforestation and degradation. Fewer trees limit the planet's <br> ability to regulate greenhouse gases, increasing global temperatures, melting polar ice and raising sea levels. </p> <br><br>
 </div>
  
  
 </div> <!--inner-->
  
 </div><!--content-->

 </div> <!-- article-content-->

  <!-- Javascript -->
  <!-- Handles search -->
  <script src="article_search.js" ></script>

</div>
 </body>

 </html>



