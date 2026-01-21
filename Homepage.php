<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$profileLink = $isLoggedIn ? 'myprofile.php' : 'LoginPage.php?redirect=myprofile.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sustainable Development Goal 15 - Life on Land</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    body {
        background-image:linear-gradient(#daefcf,white);
        min-height: 300vh;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .navbar {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding-right: 20px;
    }

    .navbar a {
        color: black;
        font-family: monospace, "Lucida Console";
        font-size: 20px;
        text-decoration: none;
        transition: 0.3s;
    }

    .navbar a:hover {
        color: #76E036;
        transform: scale(1.05);
    }

    .profile-fa-icon i {
        font-size: 20px;
    }

    video {
        position: absolute;
        top: 0;
        left: 0;
        width: 40%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }

    .title {
        border: 10px solid #194e3b;
        width: 690px; 
        height: 400px;
        background-image: url("https://wallpaperaccess.com/full/1729077.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        padding-top: 10px;
        padding-left: 30px;
        position: absolute;
        right: 58px; top: 100px;
        font-size: 90px;
        font-style: italic;
        font-weight: bold;
    }

    .caption {
        position: absolute;
        right:80px; top:550px;
        font-size: 30px;
        font-style: italic;
        font-weight: bold;
        color: #194e3b;
        text-decoration: overline underline dashed;
        text-underline-offset:10px;
    }

    .doyouknow {
        display: flex;
        margin: 900px auto 0 auto;
        padding: 30px;
        border: 10px solid #194e3b;
        width: fit-content;
    }

    .doyouknow img {
        width: 300px; height: 300px;
    }

    .question {
        margin-left: 40px;
        font-size: 30px;
    }

    .overview1, .overview2 {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 50px;
    }

    .about, .initiatives, .resources, .getinvolved, .events, .contactus {
    position: relative;
    border: 2px solid #194e3b;
    border-radius: 30px;
    padding: 10px;
    text-align: center;
    margin: 20px;
    width: 220px;
    height: 300px;
    overflow: hidden;
    transition: all 0.3s ease;
    background-color: white;
}

.about:hover img, 
.initiatives:hover img, 
.resources:hover img, 
.getinvolved:hover img, 
.events:hover img, 
.contactus:hover img {
    opacity: 0;
}
    .about a, .initiatives a, .resources a, .getinvolved a, .events a, .contactus a {
        text-decoration: none;
        color: black;
        display: block;
    }

    
.about img, .initiatives img, .resources img, .getinvolved img, .events img, .contactus img {
    height: 200px; 
    width: 200px;
    border-radius: 30px;
    transition: 0.3s ease;
}

    .brief {
 opacity: 0;
 position: absolute;
 bottom: 160px;
 background-color: lightgreen;
 text-align: left;
 padding: 10px;
 height: 200px; width: 200px;
 border-radius: 30px;
}

.brief li {
    height: 35px;
    line-height: 35px;
}
.brief2 {
 opacity: 0;
 position: absolute;
 bottom: 160px;
 background-color: lightgreen;
 text-align: center;
 padding: 10px;
 height: 200px; width: 200px;
 border-radius: 30px;
}

.about:hover .brief,
.initiatives:hover .brief,
.resources:hover .brief,
.getinvolved:hover .brief,
.events:hover .brief,
.contactus:hover .brief2 {
    opacity: 1;
}

    .footer {
        margin-top: 80px;
        width: 100%;
        background-color: #194e3b;
        color: #daefcf;
        text-align: center;
        padding: 50px 0;
    }

    .footer img {
        height: 150px; width: 150px;
    }

    .footer h1 {
        font-size: 50px;
    }

    .footer p {
        font-size: 20px;
    }

.brief, .brief2 {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 200px;
    height: 200px;
    background-color: lightgreen;
    border-radius: 30px;
    opacity: 0;
    transition: 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 0; 
}

.brief ul, .brief2 {
    margin: 0;
    padding: 0;
}
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <a href="<?= $isLoggedIn ? 'about.php' : 'LoginPage.php' ?>">About</a>
    <a href="<?= $isLoggedIn ? 'initiatives.php' : 'LoginPage.php' ?>">Initiatives</a>
    <a href="<?= $isLoggedIn ? 'resources.php' : 'LoginPage.php' ?>">Resources</a>
    <a href="<?= $isLoggedIn ? 'getinvolved.php' : 'LoginPage.php' ?>">Get Involved</a>
    <a href="<?= $isLoggedIn ? 'events.php' : 'LoginPage.php' ?>">Events</a>
    <a href="<?= $isLoggedIn ? 'index.php' : 'LoginPage.php' ?>">Community</a>
    <a href="<?= $isLoggedIn ? 'contactus.php' : 'LoginPage.php' ?>">Contact Us</a>
    <a href="<?= $profileLink ?>" class="profile-fa-icon"><i class="fas fa-user"></i></a>
</div>

<video autoplay loop muted>
    <source src="videos/homepagevideo.mp4" type="video/mp4">
</video>

<div class="title">
    <p>LIFE<br>ON LAND</p>
</div>

<div class="caption">
    <p>"Protecting Life on Land for a Sustainable Future"</p>
</div>

<div class="doyouknow">
    <img src="https://cdn-images-1.medium.com/max/1600/1*7MDLuoSaJjS-q5tZ_vJbVA.png">
    <div class="question">
        <p>Have you heard about SDG 15?</p>
        <p>How familiar are you with SDG 15?</p>
        <p>Would you like to contribute to protecting the life on land?</p>
        <p>Let's explore about it!</p>
    </div>
</div>

<!-- Overview 1 -->
<div class="overview1">
    <div class="about">
        <a href="<?= $isLoggedIn ? 'about.php' : 'LoginPage.php' ?>">
            <img src="https://miro.medium.com/max/1280/1*bvbDYGJfB81P2xEMrxNRpA.jpeg">
            <div class="brief">
                <ul>
                    <li>Mission</li>
                    <li>Causes</li>
                    <li>Statistics</li>
                    <li>Impacts</li>
                </ul>
            </div>
            <p>About</p>
        </a>
    </div>

    <div class="initiatives">
        <a href="<?= $isLoggedIn ? 'initiatives.php' : 'LoginPage.php' ?>">
            <img src="https://www.agilityirl.com/wp-content/uploads/2019/10/Picture1-8-270x300.png">
            <div class="brief">
                <ul>
                    <li>Education</li>
                    <li>Replant</li>
                    <li>Laws</li>
                    <li>NGO</li>
                    <li>Advertising</li>
                    <li>FSC</li>
                </ul>
            </div>
            <p>Initiatives</p>
        </a>
    </div>

    <div class="resources">
        <a href="<?= $isLoggedIn ? 'resources.php' : 'LoginPage.php' ?>">
            <img src="https://www.marketing91.com/wp-content/uploads/2020/07/Types-of-Resources.jpg">
            <div class="brief">
                <ul>
                    <li>Report</li>
                    <li>Case Studies</li>
                    <li>Analysis</li>
                </ul>
            </div>
            <p>Resources</p>
        </a>
    </div>
</div>

<!-- Overview 2 -->
<div class="overview2">
    <div class="getinvolved">
        <a href="<?= $isLoggedIn ? 'getinvolved.php' : 'LoginPage.php' ?>">
            <img src="https://static.wixstatic.com/media/13f57d_6c10e17a02c944729a021b849d9ad49b~mv2.jpg/v1/fill/w_660,h_250,al_c/13f57d_6c10e17a02c944729a021b849d9ad49b~mv2.jpg">
            <div class="brief">
                <ul>
                    <li>Tree Planting Events</li>
                    <li>Spread Awareness</li>
                    <li>Donation</li>
                    <li>Volunteer</li>
                </ul>
            </div>
            <p>Get Involved</p>
        </a>
    </div>

    <div class="events">
        <a href="<?= $isLoggedIn ? 'events.php' : 'LoginPage.php' ?>">
            <img src="https://th.bing.com/th/id/OIP.F64-83D9hp-98NWKeNcWDgHaFj?rs=1&pid=ImgDetMain">
            <div class="brief">
                <ul>
                    <li>Trillion Tree Campaign</li>
                    <li>Spread Awareness</li>
                    <li>The Bonn Challenge</li>
                    <li>World Wildlife Day</li>
                </ul>
            </div>
            <p>Events</p>
        </a>
    </div>

    <div class="contactus">
        <a href="<?= $isLoggedIn ? 'contactus.php' : 'LoginPage.php' ?>">
            <img src="https://png.pngtree.com/png-vector/20220621/ourmid/pngtree-green-contact-us-icon-in-3d-vector-infographic-label-symbol-vector-png-image_47182812.jpg">
            <div class="brief2">
                Any questions or suggestions?<br>Feel free to contact us!
            </div>
            <p>Contact Us</p>
        </a>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <img src="https://cdn-images-1.medium.com/max/1600/1*7MDLuoSaJjS-q5tZ_vJbVA.png">
    <h1>LIFE ON LAND</h1>
    <p>SDG 15</p>
</div>

</body>
</html>
