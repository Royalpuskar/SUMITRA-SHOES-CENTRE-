<?php
session_start();
include "config.php";

// Standard security redirection
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Action Handlers
if (isset($_GET['action'])) {
    $prod_id = intval($_GET['id']);
    
    if ($_GET['action'] == 'increase') {
        $_SESSION['cart'][$prod_id]++;
    }
    else if ($_GET['action'] == 'decrease') {
        if ($_SESSION['cart'][$prod_id] > 1) {
            $_SESSION['cart'][$prod_id]--;
        } else {
            unset($_SESSION['cart'][$prod_id]);
        }
    }
    else if ($_GET['action'] == 'remove') {
        unset($_SESSION['cart'][$prod_id]);
    }
    
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart | Sumitra Shoes Centre</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>Sumitra Shoes Centre</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Shopping Cart (<?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>)</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container" style="margin-top: 40px;">
    <h2 class="section-title">Your Selected Items</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <div style="background:white; padding:50px; text-align:center; border-radius:8px; box-shadow:var(--card-shadow);">
            <p style="font-size:18px; color:#666; margin-bottom:25px;">Your cart is empty. Fill it with premium shoe collections!</p>
            <a href="products.php" class="btn" style="max-width:250px; display:inline-block;">Browse Shoes Now</a>
        </div>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Shoe Details</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $subtotal = 0;
                foreach ($_SESSION['cart'] as $prod_id => $qty) {
                    $res = mysqli_query($conn, "SELECT * FROM products WHERE id = $prod_id");
                    if ($res && mysqli_num_rows($res) > 0) {
                        $p = mysqli_fetch_assoc($res);
                        $line_total = $p['price'] * $qty;
                        $subtotal += $line_total;
                        ?>
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:15px;">
                                    <strong><?php echo htmlspecialchars($p['name']); ?></strong>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($p['category']); ?></td>
                            <td>Rs. <?php echo number_format($p['price'], 2); ?></td>
                            <td>
                                <div class="cart-quantity">
                                    <a href="cart.php?action=decrease&id=<?php echo $prod_id; ?>">-</a>
                                    <span style="font-weight:600; font-size:16px;"><?php echo $qty; ?></span>
                                    <a href="cart.php?action=increase&id=<?php echo $prod_id; ?>">+</a>
                                </div>
                            </td>
                            <td>Rs. <?php echo number_format($line_total, 2); ?></td>
                            <td>
                                <a href="cart.php?action=remove&id=<?php echo $prod_id; ?>" class="cart-remove-btn">Remove</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="cart-total-section">
            <h3>Cart Net Total: Rs. <?php echo number_format($subtotal, 2); ?></h3>
            <p style="color:#666; margin-bottom:20px;">Shipping charges are complementary within Nepal.</p>
            <div style="display:flex; gap:15px; width:100%; max-width:400px;">
                <a href="products.php" class="btn btn-secondary" style="margin:0;">Continue Shopping</a>
                <a href="checkout.php" class="btn" style="margin:0;">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>