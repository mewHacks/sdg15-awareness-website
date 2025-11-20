<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';

    if (!empty($email)) {
        $_SESSION['reset_email'] = $email; 
        header("Location: ResetPassword.php");
        exit();
    } else {
        $message = "Please enter your email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
    body {
        background-color: #e8f5e9;
        font-family: Arial, sans-serif;
        padding: 50px;
        position: relative;
    }
    .form-container {
    max-width: 700px;
    height: 250px;
    margin: auto;
    background-color: #ffffff;
    padding: 70px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    position: relative;
    }

    h2 {
        text-align: center;
        color: #2e7d32;
        font-size: 30px;
        margin-bottom: 30px;
    }
    label {
        font-size: 18px;
    }
    input[type=email] {
        width: 100%;
        padding: 15px;
        margin-top: 15px;
        margin-bottom: 25px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 18px;
    }
    .btn {
        background-color: #4caf50;
        color: white;
        padding: 15px;
        width: 100%;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 18px;
        cursor: pointer;
    }
 .back-top {
    position: absolute;
    top: 20px;
    left: 20px;
    text-decoration: none;
    color: #2e7d32;
    font-weight: bold;
    font-size: 20px;
    display: flex;
    align-items: center;
    padding: 8px 12px; 
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.back-top:hover {
    background-color: #c8e6c9; 
}



    .message {
        text-align: center;
        color: #2e7d32;
        margin-top: 20px;
        font-size: 16px;
    }
</style>
</head>
<body>

    
<div class="form-container">
    <a href="LogInPage.php" class="back-top">← Back</a>

    <h2>Forgot Password</h2>
    
    <form method="POST" action="">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required placeholder="e.g. you@email.com">
        <button type="submit" class="btn">Send Reset Link</button>
    </form>

    <?php if (!empty($message)) { ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php } ?>
</div>

</html>
