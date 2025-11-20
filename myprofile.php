<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: LogInPage.php?redirect=myprofile.php");
    exit();


}

$username = $_SESSION['username'];
$role = $_SESSION['role']; // admin or user
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username); ?>'s Profile | Land Life Community</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #2e7d32;
            --secondary-green: #4caf50;
            --light-green: #e8f5e9;
            --dark-green: #1b5e20;
            --text-dark: #333;
            --text-light: #666;
        }
        
        body {
            padding: 20px;
            margin: 0;
            line-height: 1.6;
            font-family: 'Montserrat', sans-serif;
            background-color: #2e7d32;   
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--secondary-green);
            text-decoration: none;
            font-weight: bold;
        }
        
        .back-link i {
            margin-right: 5px;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light-green);
        }
        
        .profile-title {
            color: var(--dark-green);
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .username {
            color: var(--primary-green);
            font-weight: bold;
        }
        
        .option-cards {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 30px;
        }
        
        .profile-option {
            flex: 1;
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 2px solid var(--light-green);
            text-decoration: none;
            color: var(--text-dark);
        }
        
        .profile-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-color: var(--secondary-green);
        }
        
        .option-icon {
            font-size: 40px;
            color: var(--secondary-green);
            margin-bottom: 15px;
        }
        
        .option-title {
            font-size: 20px;
            color: var(--dark-green);
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .option-description {
            color: var(--text-light);
            font-size: 14px;
        }

        .logout-btn {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 40px;
        }

        .logout-btn a {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--secondary-green);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .logout-btn a:hover {
            background-color: var(--dark-green);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="#" onclick="if (history.length > 1) { window.history.back(); } else { window.location.href='Homepage.php'; } return false;" class="back-link">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <div class="profile-header">
            <h1 class="profile-title">My Profile</h1>
            <h3>Welcome back, <i><span class="username"><?php echo htmlspecialchars($username); ?></span></i> !</h3>
            <h5><a href="forgetPassword.php">Forgot Password?</a></h5>
        </div>
        
        <div class="option-cards">
            <a href="my_posts.php" class="profile-option">
                <div class="option-icon">
                    <i class="fas fa-pen-alt"></i>
                </div>
                <div class="option-title">My Posts</div>
                <div class="option-description">View and manage all posts you've created</div>
            </a>
            
            <a href="liked_posts.php" class="profile-option">
                <div class="option-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="option-title">Liked Posts</div>
                <div class="option-description">See all posts you've liked</div>
            </a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role']=== 'admin'): ?>
                <a href="banned_posts.php" class="profile-option">
                    <div class="option-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="option-title">Banned Posts</div>
                    <div class="option-description">View and manage posts you've banned</div>
                </a>
            <?php endif; ?>
        </div>

        <div class="logout-btn">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </div>
</body>
</html>