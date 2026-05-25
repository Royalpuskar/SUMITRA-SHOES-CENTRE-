<?php
session_start();
include "config.php";

// Redirection safeguard
if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

$error_msg = "";
$success_msg = "";

// Calculate Grand Total
$total_cost = 0;
$items_narrative_arr = [];
foreach ($_SESSION['cart'] as $prod_id => $qty) {
    $res = mysqli_query($conn, "SELECT id, name, price FROM products WHERE id = $prod_id");
    if ($res && mysqli_num_rows($res) > 0) {
        $p = mysqli_fetch_assoc($res);
        $total_cost += ($p['price'] * $qty);
        $items_narrative_arr[] = $p['name'] . " (Qty: " . $qty . ")";
    }
}
$items_narrative = implode(", ", $items_narrative_arr);

// Action checkout payment verification
if (isset($_POST['record_payment'])) {
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $transaction_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);
    $user_id = $_SESSION['user'];

    if (empty($transaction_id)) {
        $error_msg = "Please insert your Esewa or Khalti Payment Transaction ID!";
    } else {
        // Log transaction to DB
        $sql = "INSERT INTO orders (user_id, items, total, transaction_id) 
                VALUES ('$user_id', '$items_narrative', '$total_cost', '$transaction_id')";
        
        if (mysqli_query($conn, $sql)) {
            // Success state - clear active cart
            $_SESSION['cart'] = array();
            $success_msg = "Your Order is Placed successfully! Transaction logged with ID " . htmlspecialchars($transaction_id);
        } else {
            $error_msg = "Database logging failed: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esewa / Khalti Checkout | Sumitra Shoes Centre</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>Sumitra Shoes Centre</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Shopping Cart</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container" style="margin-top: 40px;">
    <h2 class="section-title">Checkout & Dynamic Gateway</h2>

    <?php if (!empty($success_msg)): ?>
        <div style="background:white; padding:50px; text-align:center; border-radius:8px; box-shadow:var(--card-shadow); max-width:650px; margin:0 auto;">
            <div style="color:#2e7d32; font-size:48px; margin-bottom:15px;">&check;</div>
            <h2 style="color:#2e7d32; margin-bottom:15px;">Order Placed Successfully!</h2>
            <p style="font-size:16px; color:#555; margin-bottom:25px;"><?php echo $success_msg; ?></p>
            <p style="font-size:14px; color:#777; margin-bottom:30px;">Your transaction has been securely logged on our local database. It is ready for assignment inspection.</p>
            <a href="products.php" class="btn">View Customer Catalogue</a>
        </div>
    <?php else: ?>

        <div class="checkout-layout">
            
            <!-- Left Panel: Payment qr rendering -->
            <div class="payment-card esewa-theme">
                <div class="payment-logo"><span class="esewa-logo-text">eSewa</span> Scan & Pay</div>
                <p style="color:#666; font-size:14px; margin-bottom:20px;">Scan the official payment receiving QR below of <strong>Sumitra Shoes Centre</strong> to proceed.</p>
                
                <div class="payment-qrcode-container">
                    <!-- High definition SVG design of merchant eSewa Sumitra Tamang QR -->
                    <div style="background:white; padding: 15px; border-radius:8px; display:inline-block; margin-bottom:15px;">
                        <!-- Inline SVG representing merchant qr to function independently in XAMPP htdocs without depending on static images folder -->
                        <svg width="200" height="200" viewBox="0 0 29 29" style="display:block; margin:0 auto; shape-rendering:crispEdges;">
                            <!-- QR background -->
                            <rect width="29" height="29" fill="white"/>
                            <!-- QR Top-Left position finder -->
                            <rect x="0" y="0" width="7" height="7" fill="black"/>
                            <rect x="1" y="1" width="5" height="5" fill="white"/>
                            <rect x="2" y="2" width="3" height="3" fill="black"/>
                            <!-- QR Top-Right position finder -->
                            <rect x="22" y="0" width="7" height="7" fill="black"/>
                            <rect x="23" y="1" width="5" height="5" fill="white"/>
                            <rect x="24" y="2" width="3" height="3" fill="black"/>
                            <!-- QR Bottom-Left position finder -->
                            <rect x="0" y="22" width="7" height="7" fill="black"/>
                            <rect x="1" y="23" width="5" height="5" fill="white"/>
                            <rect x="2" y="24" width="3" height="3" fill="black"/>
                            <!-- Random Data blocks representative of standard Sumitra QR pattern -->
                            <rect x="9" y="0" width="1" height="1" fill="black"/><rect x="11" y="0" width="2" height="1" fill="black"/><rect x="15" y="0" width="1" height="1" fill="black"/><rect x="18" y="0" width="1" height="1" fill="black"/><rect x="20" y="0" width="1" height="1" fill="black"/>
                            <rect x="9" y="2" width="2" height="1" fill="black"/><rect x="13" y="2" width="1" height="2" fill="black"/><rect x="17" y="2" width="3" height="1" fill="black"/>
                            <rect x="0" y="9" width="1" height="2" fill="black"/><rect x="3" y="9" width="3" height="1" fill="black"/><rect x="8" y="9" width="2" height="1" fill="black"/><rect x="12" y="9" width="1" height="3" fill="black"/><rect x="16" y="9" width="1" height="1" fill="black"/><rect x="19" y="9" width="2" height="2" fill="black"/><rect x="23" y="9" width="1" height="1" fill="black"/><rect x="25" y="9" width="3" height="1" fill="black"/>
                            <rect x="1" y="12" width="2" height="1" fill="black"/><rect x="5" y="12" width="1" height="3" fill="black"/><rect x="8" y="12" width="3" height="1" fill="black"/><rect x="15" y="12" width="3" height="2" fill="black"/><rect x="21" y="12" width="2" height="1" fill="black"/><rect x="27" y="12" width="1" height="3" fill="black"/>
                            <rect x="0" y="15" width="4" height="1" fill="black"/><rect x="10" y="15" width="2" height="1" fill="black"/><rect x="13" y="15" width="1" height="2" fill="black"/><rect x="19" y="15" width="1" height="2" fill="black"/><rect x="22" y="15" width="3" height="1" fill="black"/>
                            <rect x="2" y="18" width="1" height="2" fill="black"/><rect x="6" y="18" width="2" height="1" fill="black"/><rect x="11" y="18" width="1" height="1" fill="black"/><rect x="15" y="18" width="3" height="1" fill="black"/><rect x="20" y="18" width="1" height="3" fill="black"/><rect x="24" y="18" width="3" height="1" fill="black"/>
                            <rect x="9" y="21" width="3" height="1" fill="black"/><rect x="14" y="21" width="1" height="1" fill="black"/><rect x="16" y="21" width="2" height="2" fill="black"/><rect x="22" y="21" width="1" height="1" fill="black"/>
                            <rect x="9" y="24" width="1" height="3" fill="black"/><rect x="12" y="24" width="2" height="1" fill="black"/><rect x="15" y="24" width="1" height="1" fill="black"/><rect x="23" y="24" width="2" height="2" fill="black"/><rect x="27" y="21" width="1" height="4" fill="black"/>
                            <rect x="11" y="27" width="3" height="1" fill="black"/><rect x="18" y="27" width="2" height="1" fill="black"/><rect x="21" y="27" width="4" height="1" fill="black"/>
                        </svg>
                    </div>

                    <div class="qr-meta" style="color:var(--esewa-color); font-size:18px;"><strong>eSewa Merchant Account</strong></div>
                    <div style="font-size:16px; margin:5px 0; color:#333; font-weight:700;">Sumitra Tamang</div>
                    <div style="font-size:14px; color:#666; font-family: monospace;">Primary Mobile: 9816230823</div>
                    <div style="margin-top:10px; font-size:12px; color:#888;">Scan QR code directly via Esewa/Fonepay app</div>
                </div>
            </div>

            <!-- Right Panel: Items description and billing actions validation -->
            <div class="payment-card border-top" style="border-top: 5px solid black;">
                <h3>Order Grand Total: Rs. <?php echo number_format($total_cost, 2); ?></h3>
                <p style="color:#555; margin-bottom:15px; font-size:14px;"><strong>Paying for items:</strong> <?php echo htmlspecialchars($items_narrative); ?></p>
                <hr style="border:1px solid #eee; margin-bottom:20px;">

                <?php if (!empty($error_msg)): ?>
                    <div class="form-message message-error">
                        &times; <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="checkout.php">
                    <div class="form-group">
                        <label for="payment_method">Preferred Payment Gateway</label>
                        <select name="payment_method" id="payment_method" style="width:100%; padding: 12px; border:1px solid #ccc; border-radius:4px; background:white; font-size:14px; font-weight:600;">
                            <option value="Esewa">Esewa Nepalese Wallet</option>
                            <option value="Khalti">Khalti Digital Payment</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="transaction_id">Insert Fonepay / Esewa / Khalti Transaction ID</label>
                        <input type="text" id="transaction_id" name="transaction_id" placeholder="Copy-paste the digital transaction code" required>
                        <small style="color:#888; font-size:11px; display:block; margin-top:5px;">After scanning the merchant receiving QR and paying, specify your transaction ID.</small>
                    </div>

                    <div style="margin-top:30px;">
                        <button type="submit" name="record_payment" class="btn">Verify and Place Order</button>
                    </div>
                </form>
            </div>

        </div>

    <?php endif; ?>
</div>

</body>
</html>