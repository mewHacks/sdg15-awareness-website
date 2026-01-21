<?php
session_start();
include 'db_connect.php';

$message = "";

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgetPassword.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($newPassword === $confirmPassword && !empty($newPassword) && !empty($email)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            $message = "Password reset successful! Redirecting to login...";
            
            header("Refresh:2; url=LoginPage.php");
        } else {
            $message = "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Passwords do not match, are empty, or email is missing.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            background-color: #e8f5e9;
            font-family: Arial, sans-serif;
            padding: 50px;
            position: relative;
        }
        .form-container {
            max-width: 700px;
            height: 400px;
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
        input[type=password] {
            width: 94%;
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
            padding: 20px;
            margin-top: 20px;
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
    <a href="forgetPassword.php" class="back-top">← Back</a>

    <h2>Reset Password</h2>
    
    <form method="POST" action="">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['reset_email']); ?>">

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <button type="submit" class="btn">Confirm</button>
    </form>

    <?php if (!empty($message)) { ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php } ?>
</div>

</body>
</html>
