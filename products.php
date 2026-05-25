<?php
session_start();
include "config.php";

// Initialize shopping cart session if absent
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Check for Add to Cart request
if (isset($_GET['add_to_cart'])) {
    $product_id = intval($_GET['add_to_cart']);
    
    // Check if the product really exists
    $prod_res = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
    if (mysqli_num_rows($prod_res) > 0) {
        // Increment quantity if exists, else assign quantity 1
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        header("Location: products.php?status=added_to_cart");
        exit();
    }
}

// Category filter
$category_filter = "";
$active_category = "All";
if (isset($_GET['category'])) {
    $active_category = mysqli_real_escape_string($conn, $_GET['category']);
    $category_filter = " WHERE category = '$active_category'";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Catalogue | Sumitra Shoes Centre</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>Sumitra Shoes Centre</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <?php if(isset($_SESSION['user'])): ?>
            <a href="cart.php">Shopping Cart (<?php echo array_sum($_SESSION['cart']); ?>)</a>
            <a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<div class="container" style="margin-top: 40px;">
    <h2 class="section-title">Explore Premium Footwear</h2>

    <!-- Dynamic Category Filter Links -->
    <div class="category-filters">
        <a href="products.php" class="<?php echo $active_category == 'All' ? 'active' : ''; ?>">All Brands</a>
        <a href="products.php?category=Sports" class="<?php echo $active_category == 'Sports' ? 'active' : ''; ?>">Sports Wear</a>
        <a href="products.php?category=Casual" class="<?php echo $active_category == 'Casual' ? 'active' : ''; ?>">Urban Casual</a>
        <a href="products.php?category=Formal" class="<?php echo $active_category == 'Formal' ? 'active' : ''; ?>">Premium Formal</a>
        <a href="products.php?category=Boots" class="<?php echo $active_category == 'Boots' ? 'active' : ''; ?>">Hiking Boots</a>
        <a href="products.php?category=School" class="<?php echo $active_category == 'School' ? 'active' : ''; ?>">School Shoes</a>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'added_to_cart'): ?>
        <div class="form-message message-success" style="text-align:center; max-width: 600px; margin: 0 auto 30px auto;">
            Product added to your shopping cart bundle! <a href="cart.php" style="font-weight: 700; color: #2e7d32;">View Cart here.</a>
        </div>
    <?php endif; ?>

    <div class="products-grid">
        <?php
        $query = "SELECT * FROM products" . $category_filter;
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="product">
                    <!-- Elegant placeholder handles if file not physically found in XAMPP directory -->
                    <img src="<?php echo (strpos($row['image'], 'http') === 0) ? $row['image'] : 'images/'.$row['image']; ?>" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600';" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="product-detail">
                        <span class="product-category"><?php echo htmlspecialchars($row['category']); ?></span>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <div class="product-price">Rs. <?php echo number_format($row['price'], 2); ?></div>
                        
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="products.php?add_to_cart=<?php echo $row['id']; ?>" class="btn">Add to Cart</a>
                        <?php else: ?>
                            <a href="login.php?redirect=products.php" class="btn btn-secondary">Login to Buy</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p style='grid-column: 1/-1; text-align:center; padding: 40px; color:#666;'>No products found in this category.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>