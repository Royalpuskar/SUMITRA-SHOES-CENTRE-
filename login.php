<?php
session_start();
include "config.php";

$message = "";
$message_type = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Please provide both Email and Password!";
        $message_type = "error";
    } else {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            
            // Verify BCRYPT hashed password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("Location: products.php");
                exit();
            } else {
                $message = "Incorrect password! Please try again.";
                $message_type = "error";
            }
        } else {
            $message = "Email is not registered. Please register.";
            $message_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login | Sumitra Shoes Centre</title>
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
        <h2>Customer Login</h2>
        
        <?php if (!empty($message)): ?>
            <div class="form-message message-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="example@mail.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" required>
            </div>
            
            <button type="submit" name="login" class="btn">Login</button>
        </form>
        
        <p style="text-align:center; margin-top:20px; font-size:14px; color:#666;">
            New to Sumitra Shoes? <a href="register.php" style="color:black; font-weight:600;">Register Here</a>
        </p>
    </div>
</div>

</body>
</html>