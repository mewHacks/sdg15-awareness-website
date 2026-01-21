<?php
session_start();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_param = $search ? "%$search%" : "%"; 

$profileLink = isset($_SESSION['user_id']) ? 'myprofile.php' : 'LoginPage.php?redirect=myprofile.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Initiative 3: Law Enforcement</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  
 body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(#DAEFCF, white);
    min-height: 100vh;
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
    flex: 1;
    background: linear-gradient(#DAEFCF, white);
    padding-top: 10px;
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

    padding: 10px;
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
    justify-content: right;  /* This pushes search bar to the right */
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

  .image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
  }

  .image-grid img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    border: 3px solid #7a9e06;
    box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
    transition: transform 0.3s;
  }
    h1 {
    background-color: #2E7D32;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #ffffff;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    text-align: center;
    padding: 15px;
    border-radius: 10px;
    width: 60%;
    margin: 30px auto 30px auto;
    font-size: 2.2rem;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
  }
  
  h1::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #76E036, #2E7D32);
  }

  h2 {
    color: #2E7D32;
    font-size: 1.8rem;
    margin: 30px 0 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #A4CE8D;
    text-align: left;
    width: 100%;
  }
.text-content ul {
    margin: 25px 0;
    padding: 0;
  }

  .text-content li {
    background: linear-gradient(90deg, #E8F5E9, #C8E6C9);
    padding: 15px 20px;
    margin-bottom: 12px;
    border-radius: 8px;
    border-left: 6px solid #2E7D32;
    font-size: 1.1rem;
    color: #1B5E20;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    position: relative;
    padding-left: 50px;
    transition: transform 0.2s, box-shadow 0.2s;
    text-align: left;
  }

  .text-content li:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  .text-content li::before {
    content: "\f058";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    position: absolute;
    left: 18px;
    color: #4CAF50;
    font-size: 1.2rem;
  }

  .content {
    padding: 30px;
    max-width: 1200px;
    margin: 0 auto;
  }

  .text-content {
    background-color: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-top: 30px;
  }
  .image-grid img {
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .image-grid img:hover {
    transform: scale(1.03);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    z-index: 1;
  }

</style>
</head>

<body>
<<div class="containerabout">

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
    <div class="content">
      <h1>Law Enforcement</h1>
      
      <div class="image-grid">
        <img src="https://kanoongurus.com/blog/wp-content/uploads/2021/08/85ed2867bf78e8096c3471644b62e06f.jpg" alt="Law enforcement 1">
        <img src="https://th.bing.com/th/id/OIP.ZsD8CwIWCkFcyN3FO-cqCwHaEJ" alt="Law enforcement 2">
        <img src="https://as1.ftcdn.net/v2/jpg/05/67/59/92/1000_F_567599295_aZFpDglppRnOgPedgynxv30kYJjdrSi8.jpg" alt="Law enforcement 3">
        <img src="https://img.freepik.com/premium-photo/understanding-intersection-global-environmental-laws-ethics-justice-regulations-sustainable-corporate-practices-concept-environmental-laws-ethics-justice-regulations_864588-62771.jpg" alt="Law enforcement 4">
        <img src="https://titc.io/wp-content/uploads/2024/01/DALL%C2%B7E-2024-01-24-11.12.06-Image-of-an-ethical-balance-scale-with-one-side-having-gold-coins-and-the-other-side-having-a-green-leafy-tree-representing-the-balance-between-prof.png" alt="Law enforcement 5">
        <img src="https://www.realestatelawcorp.com/wp-content/uploads/2023/08/AdobeStock_246973839-2048x1365.jpg" alt="Law enforcement 6">
      </div>

      <div class="text-content">
        <h2><u>Forest Conservation Laws/Land Use Zoning</u></h2>
        <ul>
          <li>Establish legal protections for endangered forests, making them off-limits for logging, agriculture, or development.</li>
          <li>Preventing illegal deforestation by outside parties.</li>
          <li>Ensuring that violations are swiftly addressed and penalized.</li>
          <li>Legally designate more land as forest reserves or national parks, ensuring their long-term protection from deforestation and development.</li>
          <li>Control of Agricultural Expansion: Pass laws that regulate agricultural expansion into forested areas, such as restricting large-scale cattle ranching or palm oil plantations that lead to deforestation.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Javascript -->
<!-- Handles search -->
<script src="article_search.js" ></script>
</body>
</html>