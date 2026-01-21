<?php
$error = "";
$success = "";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_system";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
)";
mysqli_query($conn, $table_sql);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';



    if (empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Use prepared statement to prevent SQL injection
        $check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "This email is already registered. Please log in.";
        } else {
            // Prevent users from signing up with @admin.com
            if (str_ends_with($email, '@admin.com')) {
                $error = "Sorry, @admin.com addresses are reserved – please use another email.";
            } else {
                $role = 'user'; // Force all users to have 'user' role
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Use prepared statement to prevent SQL injection
                $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $insert_stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

                if ($insert_stmt->execute()) {
                    header("Location: Homepage.php");
                    exit();
                } else {
                    $error = "Error: " . $conn->error;
                }
            }
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        
        body {
            background: linear-gradient(#A4CE8D, white);
            background-attachment: fixed;
            font-family: "Gill Sans", Arial, sans-serif;
            min-height: 100%;
            align-items: center;
        }

        .video-bg {
            position: fixed;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            opacity: 0.8;
        }

        .signup-container {
    width: 300px;
    margin: 80px auto;
    padding: 20px;
    background-color: #2b961d;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 1);
    z-index: 1;
}
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: white;
            font-size: 16px;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 6px;
            border: none;
            border-radius: 5px;
            background-color: #E8F5E9;
            font-size: 16px;
            box-sizing: border-box;
        }

        .signup-btn {
            width: 100%;
            padding: 8px;
            background-color: #31483f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .signup-btn:hover {
            background-color: #76E036;
        }

        .login-prompt {
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
            color: #E8F5E9;
        }

        .login-link {
            color: #76E036;
            font-weight: bold;
            text-decoration: none;
        }

        .login-link:hover {
            color: #B2FF59;
            text-decoration: underline;
        }

        .message {
            text-align: center;
            color: yellow;
            margin-bottom: 10px;
        }

        .header-row {
    display: flex;
    align-items: center;  
    margin-bottom: 20px;
}

.back-arrow {
    color: white;
    text-decoration: none;
    font-size: 35px;  
    margin-right: 15px;  
    transition: color 0.3s, transform 0.3s;
}

.back-arrow:hover {
    color: #B2FF59;
    transform: scale(1.1);
}
.signup-title {
    color: white;
    padding-left: 50px;
    padding-bottom: 10px;
    margin: 0;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    font-family: Verdana, Tahoma, sans-serif;
    font-size: 24px;
}


    </style>
</head>
<body>

    <video autoplay muted loop playsinline class="video-bg">
        <source src="21723-320725678.mp4" type="video/mp4">
        </video>

    <div class="signup-container">
    <div class="header-row">
        <a href="Homepage.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
        <h1 class="signup-title">Sign Up</h1>
    </div>


    <?php if (!empty($error)) { ?>
        <div class="message" style="color: red;"><?php echo $error; ?></div>
    <?php } ?>

    <?php if (!empty($success)) { ?>
        <div class="message" style="color: lightgreen;"><?php echo $success; ?></div>
    <?php } ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-input"  placeholder="Choose a username">
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input"  placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-input"  placeholder="Create a password">
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-input"  placeholder="Re-enter your password">
            </div>
            
            <button a href="LoginPage.php" type="submit" class="signup-btn">Sign Up</button>
            
            <div class="login-prompt">
                Already have an account? <a href="LoginPage.php" class="login-link">Log in here</a>
            </div>
        </form>
    </div>
</body>
</html>