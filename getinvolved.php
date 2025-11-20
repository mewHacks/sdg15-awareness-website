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
$profileLink = isset($_SESSION['user_id']) ? 'myprofile.php' : 'LogInPage.php?redirect=myprofile.php';
?>

<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Get Involved</title>

<style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
    color: #333;
    line-height: 1.6;   
}

/* Main container in "resources" section to hold entire layout */
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


/* "Content"*/
 .content {
    background-color: #194e3b; 
    padding: 20px;
    width: 100%;
    text-align: center;
    position: relative;
    box-sizing: border-box;
 }


.inner {
 	background-color: #eafce6; 
    padding: 20px;
    width: 100%;
    text-align: center;
    position: relative;
    box-sizing: border-box;
}


.inner h1.subsubtitle {
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
 	color: grey;
 	font-family: "Fantasy"; 
}


h1.inner .sub {
 	background-color: lightgreen; 
 	color: grey;
 	font-family: "Fantasy"; 
 	display: inline-block;
 	margin-top: 60px;
 	padding: 10px 15px;
 	border-radius: 15px;
 	text-align: center;
 	width: auto;
 	font-size: 40px;
}


ul {
 	list-style-type: none;
}


h2.extra {
 	color: #153f00;
 	font-family: Gill Sans;
 	text-align: center;
 	font-size: 33px;
}


/* Donation form */
form#donationForm {
 	margin: 20px auto;
 	padding: 20px;
    padding-left: 10px;
 	border: 3px solid white;
 	background-color: lightgreen;
 	font-size: 20px;
 	color: black;
    width:50%;
 	font-family: Lucida Console monospace;
}


label {
 	display: block;
 	margin-bottom: 5px;
}


input {
 	padding: 10px;
 	border: 1px solid #ccc;
 	border-radius: 20px;
}


input[type="submit"] {
 	background-color: #4CAF50;
 	color: black;
 	border: none;
 	padding: 15px 32px;
 	text-decoration: none;
 	cursor: pointer;
}


input[type="submit"]:hover {background-color: #3e8e41;}


/* Image overlayed */
.img {
 	position: relative;
 	width: 50%;
 	margin-left: 270px;
}

.overlay {
 	position: absolute;
 	top: 0;
 	bottom: 0;
 	left: 0;
 	right: 0;
 	opacity: 0;
 	transition: .5s ease;
 	background-color: #90EE90;
    width: 500px; 
    height:300px;
    margin-left: 22px;
}


.img:hover .overlay {opacity: 1;}


.text {
 	color: green;
 	font-size: 20px;
 	position: absolute;
 	top: 50%;
 	left: 50%;
 	-webkit-transform: translate(-50%, -50%);
 	-ms-transform: translate(-50%, -50%);
 	transform: translate(-50%, -50%);
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

u:hover{color:#76E036;}

iframe {
	border: 4px solid #83c702; /* Change the border color */
	background-color: #f0f0f0; /* Sets a background color around the content inside */
}

#donationMessage {
    padding: 12px 20px;
    margin: 15px auto; /* Centered */
    border-radius: 5px;
    background: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #4caf50;
    transition: opacity 0.5s ease;
    display: none;
    width: fit-content; /* Auto width based on content */
    max-width: 80%; /* Prevents too wide on mobile */
    text-align: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
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
        <a href="getinvolved.php"class="active"> <!-- Get involved page -->
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

<div id="article-content">

<!-- Page title -->
<h1 class="title"> Get Involved </h1>

<div class="content">

<div class="inner">

	<h1 class="subtitle" style="margin-top:100px;">Ways to Get Involved</h1>
	<ul>
	<li>         
		<h2><i>1. Participate in Tree Planting Events</i></h2>
		<a href="https://plantgrowsave.org/plant-a-tree/join-an-existing-tree-planting-project/" target="_blank"><u>Join a Tree Planting Event</u></a>		          
		<div class="img">
  			<img src="https://media.istockphoto.com/id/1248915720/photo/farmers-hand-watering-a-young-plant.jpg?s=612x612&w=0&k=20&c=kip26_08vy0zT90x2bA9frWUD6ZEuzPkw8_9uv8cfrw=" style="width: 500px; height:300px;" class="image">

  				<div class="overlay">
    					<div class="text">Get your hands dirty by planting trees in deforested areas or urban parks. Many organizations host tree-planting events.</div>
  		</div>     
		 
	</li>
          
	<li>
		<h2><i>2. Spread Awareness</i></h2>
		<a href="https://www.globalgoals.org/goals/15-life-on-land/" target="_blank"><u>Learn More About SDG 15<br><br></u></a> 
		
		<div class="img">
  			<img src="https://public-library.safetyculture.io/media/5FE9A74F-A5EB-4CED-B79A-C365BEA96156" style="width: 500px;height:300px;" class="image">

  				<div class="overlay">
    					<div class="text">Share information about SDG 15 and the importance of biodiversity on your social media channels. You can also organize awareness events.</div>
		                
		           
	</li>        

	<li>              
		<h2><i>3. Donate to Environmental Organizations</i></h2>
		<a href="https://foe-malaysia.org/product/donate/" target="_blank"><u>Donate to Conservation Efforts</u></a>
		
		<div class="img">
  			<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRc0unl55UqUS9_3b7orkp46KG4_DsCaLh7Hg&s" style="width: 500px; height:300px;" class="image">

  				<div class="overlay">
    					<div class="text">Your donations can support critical conservation projects and help protect endangered species.</div>
	</li>

		<div>
		<h2><i>3.1 Another way of Donation</i></h2>
		<h3 style="color: black; font-family: Lucida Console. monospace">Your generous contribution means a lot to us.</h3>
    			
		<form id="donationForm">
    		 <label for="amount">Donation Amount:</label>
    		 <input type="number" id="amount" name="amount" min="1" required><br>

    		 <label for="name">Your Name:</label>
    		 <input type="text" id="name" name="name" required><br>

    		 <label for="email">Your Email:</label>
    		 <input type="email" id="email" name="email" required><br><br>

    		 <input type="submit" value="Donate">
		</form>

		<!-- Success/Error Message Container -->
		<div id="donationMessage" style="display:none;"></div>

		</div>

	<li>
		<h2><i>4. Dedicated Volunteer with a Passion for Service</i></h2>
		<p>Volunteering allows you to connect to your community and make it a better place. <br> Even helping out with the smallest tasks can make a real 		difference to the lives of people, animals, and organizations in need.</p>
		<a href="https://forms.gle/SeU9Jw3RooGGdMmA9" target="_blank"><u>Volunteers can Sign Up Here</u></a>	
	</ul>


	<h1 class="sub">More of It</h1>

	<div>
		<article>
			<h2 class="extra"><b><i>Reforestation in the Amazon</i></b></h2>
			<h3 style="text-align: center">In 2024, our volunteers planted over 10,000 trees in the Amazon rainforest,<br> helping restore vital habitats 			for endangered species.</h3>
			<iframe src="https://believe.earth/en/greatest-restoration-effort-ever-made-amazon-rainforest/" name="SS" height="565px" width="80%"></iframe>
		</article>
	</div>

	<div>
		<h2 class="extra"><b><i>Watch: Forging a Future for Our Forests</i></b></h2>
		<iframe width="560" height="315" src="https://www.youtube.com/embed/GTFPAWy61Uc?si=Rjk0RZtMm4xpPdZu" title="YouTube video player" frameborder="0" 		allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-		origin" allowfullscreen></iframe>
	</div>

	<div>
		<h2 class="extra"><b><i>What People Are Saying</i></b></h2>
		<blockquote>
        		<h3>"Volunteering in the Life on Land projects has been life-changing. It feels great to contribute to the planet's future!"</h3>
        		<cite>- Sarah T., Volunteer</cite>
		</blockquote>

		<blockquote>
        		<h3>"The reforestation project made me realize the importance of every tree we plant. It's a rewarding experience."</h3>
        		<cite>- John D., Conservationist</cite>
		</blockquote>
	</div>
</div> <!--inner-->

</div> <!--content-->

</div> <!--article-content-->
</div> <!--main-content-->

</div> <!--containerabout-->

<!-- Javascript -->
<!-- Handles search -->
<script src="article_search.js" ></script>

<script>
// Handles donation form submission 
document.getElementById('donationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch('handle_donation.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        const msg = document.getElementById('donationMessage');
        msg.innerHTML = data;
        msg.style.display = 'block';
        msg.style.opacity = '1'; // Ensure visible before fading

        // Improved timeout with fade effect
        setTimeout(() => {
            msg.style.opacity = '0';
            setTimeout(() => {
                msg.style.display = 'none';
            }, 500); // Wait for fade to complete
        }, 2500); // Start fade after 2.5 seconds (total 3s visibility)
        
        form.reset();
    })
    .catch(error => {
    });
});
</script>


<!-- Javascript -->
<!-- Handles search -->
<script src="article_search.js" ></script>

</body>
</html>
