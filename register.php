<?php
session_start();
include "config.php";

$message = "";
$message_type = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_raw = $_POST['password'];

    // Input Validation
    if (empty($username) || empty($email) || empty($password_raw)) {
        $message = "Please fill in all details!";
        $message_type = "error";
    } else {
        // Check if user already exists
        $user_check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($user_check) > 0) {
            $message = "Email is already registered! Please Login.";
            $message_type = "error";
        } else {
            // Apply secure password hashing
            $hashed_password = password_hash($password_raw, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                $message = "Registration Successful! You can login now.";
                $message_type = "success";
            } else {
                $message = "Database writing failed. Try again.";
                $message_type = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Register | Sumitra Shoes Centre</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>Sumitra Shoes Centre</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<div class="container">
    <div class="form-container">
        <h2>Customer Register</h2>
        
        <?php if (!empty($message)): ?>
            <div class="form-message message-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Full Name</label>
                <input type="text" id="username" name="username" placeholder="Enter Full Name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="example@mail.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Choose Password</label>
                <input type="password" id="password" name="password" placeholder="Create Password" required>
            </div>
            
            <button type="submit" name="register" class="btn">Register</button>
        </form>
        
        <p style="text-align:center; margin-top:20px; font-size:14px; color:#666;">
            Already have an account? <a href="login.php" style="color:black; font-weight:600;">Login Here</a>
        </p>
    </div>
</div>

</body>
</html>