<?php
session_start();
include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sumitra Shoes Centre | Premium Footwear</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>Sumitra Shoes Centre</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <?php if(isset($_SESSION['user'])): ?>
            <a href="cart.php">Shopping Cart</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
        <a href="admin/login.php" class="admin-badge">Admin Corner</a>
    </nav>
</header>

<section class="hero">
    <h2>Best Shoes Collection</h2>
    <p>Comfort &bull; Style &bull; Unmatched Quality</p>
</section>

<div class="container">
    <h2 class="section-title">Shop Footwear By Category</h2>
    
    <div class="category-filters">
        <a href="products.php?category=Sports">Athletic & Sports</a>
        <a href="products.php?category=Casual">Urban Casual</a>
        <a href="products.php?category=Formal">Premium Formal</a>
        <a href="products.php?category=Boots">Rugged Boots</a>
        <a href="products.php?category=School">Uniform School Shoes</a>
    </div>

    <div style="text-align:center; margin-top:40px;">
        <p style="font-size:16px; color:#666; margin-bottom:20px;">Welcome to Sumitra Shoes Centre. Discover extreme comfort and top durability.</p>
        <a href="products.php" class="btn" style="max-width:250px; display:inline-block;">Browse Full Catalog</a>
    </div>
</div>

<footer style="background:#111; color:#888; text-align:center; padding:30px; margin-top:60px; font-size:14px;">
    <p>&copy; <?php echo date("Y"); ?> Sumitra Shoes Centre. All Rights Reserved.</p>
</footer>

</body>
</html>