<?php
session_start(); 
include 'db_connect.php';


$email_value = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
$password_value = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';




if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password_input = $_POST['password'] ?? '';

    if (empty($email) || empty($password_input)) {
        $error = "Please fill in both fields!";
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password_input,$user['password'])) {

                $_SESSION["username"] = $user["username"];
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["username"];
                $_SESSION["role"] = $user["role"];

               if (isset($_POST['remember'])) {
                    setcookie('email', $email, time() + (86400 * 7), "/");
                    setcookie('password', $password_input, time() + (86400 * 7), "/");
                } else {
                    setcookie('email', '', time() - 3600, "/");
                    setcookie('password', '', time() - 3600, "/");
                }

                header("Location: HomePage.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Email not found. Please sign up first!";
        }
    }
}

mysqli_close($conn);
?>



<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Log In Page</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
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

        .login-container {
            width: 360px;
            margin: 150px auto;
            padding: 30px;
            background-color:#2b961d;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 1);
            position: relative; 
            z-index: 1;
            opacity:100%;
        }

        body {
            background: linear-gradient(#A4CE8D, white);
            background-attachment: fixed;
            font-family: "Gill Sans", Arial, sans-serif;
            min-height: 100%; 
   
.login-title {
    color: white;
    padding-left: 80px;
    padding-bottom: 10px;
    margin: 0;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    font-family: Verdana, Tahoma, sans-serif;
    font-size: 30px;
}


        .form-group {
            margin-bottom: 20px;
        }


        .form-label {
            display: block;
            color: white;
            font-size: 20px;
            margin-bottom: 8px;
        }

 
        .form-input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #E8F5E9;
            font-size: 18px;
            box-sizing: border-box;
        }

        
        .login-button {
            width: 100%;
            padding: 12px;
            background-color: #31483f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #76E036;
        }


        .action-links {
            margin-top: 30px;
            text-align: center;
            font-size: 18px;
        }

        .link {
            color: #E8F5E9;
            text-decoration: none;
            transition: color 0.3s;
        }

        .link-emphasis {
            color: #76E036;
            font-weight: bold;
        }

   
        .link:hover {
            color: #B2FF59;
            text-decoration: underline;
        }

        .separator {
            color: #E8F5E9;
            margin: 0 5px;
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



    </style>
</head>
<body>
     <video autoplay muted loop playsinline class="video-bg">
        <source src="1448735-uhd_4096_2160_24fps.mp4" type="video/mp4">
    </video>

    <div class="login-container">
       <div class="header-row">
    <a href="HomePage.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>
    <h1 class="login-title">Log In</h1>
</div>


        <?php if (!empty($error)) { ?>
        <div class="message" style="color: red;"><?php echo $error; ?></div>
        <?php } ?>

        <form action="" method="POST">
  
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input" required placeholder="Enter your email here" value="<?php echo htmlspecialchars($email_value); ?>">
            </div>
            
        
            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-input" required placeholder="Enter your password here" value="<?php echo htmlspecialchars($password_value); ?>">

            </div>

            <div class="form-group">
                <label class="form-label">
                <input type="checkbox" name="remember" <?php if (isset($_COOKIE['email'])) echo "checked"; ?>> Remember me
                </label>
            </div>
            
          
            <button type="submit" class="login-button">Log In</button>
          
            <div class="action-links">
                <a href="forgetPassword.php" class="link">Forget password?</a>
                <span class="separator">|</span>
                <a href="SignUp.php" class="link-emphasis">Sign up here</a>
            </div>
        </form>
    </div>
</body>
</html>