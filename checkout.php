<?php
declare(strict_types=1);
session_start();
require "config.php";

if (!isset($_SESSION['user']) || empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    header("Location: products.php");
    exit;
}
$_SESSION['checkout_csrf'] ??= bin2hex(random_bytes(24));
$error_msg = "";
$success_msg = "";
$total_cost = 0.0;
$items = [];
$lookup = mysqli_prepare($conn, "SELECT name, price FROM products WHERE id = ?");
if (!$lookup) {
    http_response_code(500);
    exit("Catalog temporarily unavailable.");
}
foreach ($_SESSION['cart'] as $rawId => $rawQty) {
    $id = filter_var($rawId, FILTER_VALIDATE_INT);
    $qty = filter_var($rawQty, FILTER_VALIDATE_INT);
    if ($id === false || $qty === false || $id < 1 || $qty < 1 || $qty > 30) {
        $error_msg = "Invalid cart quantity. Return to the catalog.";
        break;
    }
    mysqli_stmt_bind_param($lookup, "i", $id);
    mysqli_stmt_execute($lookup);
    mysqli_stmt_bind_result($lookup, $name, $price);
    if (!mysqli_stmt_fetch($lookup)) {
        $error_msg = "A cart product is no longer available.";
        break;
    }
    mysqli_stmt_free_result($lookup);
    $total_cost += (float)$price * $qty;
    $items[] = $name . " (Qty: " . $qty . ")";
}
mysqli_stmt_close($lookup);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!hash_equals($_SESSION['checkout_csrf'], (string)($_POST['csrf'] ?? ""))) {
        $error_msg = "Your session expired. Refresh checkout and try again.";
    } elseif ($error_msg === "" && $total_cost > 0 && count($items) > 0) {
        // DEMO ONLY: this is an unpaid order request; no gateway or bank confirmation exists.
        $userId = (int)$_SESSION['user'];
        $description = implode(", ", $items);
        $reference = "UNPAID-DEMO-" . strtoupper(bin2hex(random_bytes(8)));
        $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, items, total, transaction_id) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            $error_msg = "Unable to submit demo order.";
        } else {
            mysqli_stmt_bind_param($stmt, "isds", $userId, $description, $total_cost, $reference);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['cart'] = [];
                $_SESSION['checkout_csrf'] = bin2hex(random_bytes(24));
                $success_msg = "Unpaid demo order recorded as " . $reference . ". No payment was initiated, received, or verified.";
            } else {
                $error_msg = "Unable to submit demo order.";
            }
            mysqli_stmt_close($stmt);
        }
    } elseif ($error_msg === "") {
        $error_msg = "The cart cannot be processed.";
    }
}
?>
<!doctype html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Demo Checkout | Sumitra Shoes Centre</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<header><h1>Sumitra Shoes Centre</h1><nav aria-label="Main"><a href="index.php">Home</a><a href="products.php">Products</a><a href="cart.php">Cart</a><a href="logout.php">Logout</a></nav></header>
<main class="container" style="margin-top:36px">
  <h2 class="section-title">Checkout · Demonstration only</h2>
  <div class="form-message message-error"><strong>Payments are disabled.</strong> This project does not process eSewa, Khalti, Fonepay, cards, or bank transfers. Do not send money or enter a real transaction reference. This is a local order-recording demo.</div>
  <?php if ($success_msg !== ""): ?>
    <section class="payment-card" style="max-width:750px;margin:22px auto">
      <h3>Demo order submitted — payment pending</h3>
      <p><?= htmlspecialchars($success_msg, ENT_QUOTES, 'UTF-8') ?></p>
      <a class="btn" href="products.php">Back to products</a>
    </section>
  <?php else: ?>
    <?php if ($error_msg !== ""): ?><div class="form-message message-error"><?= htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <div class="checkout-layout">
      <section class="payment-card">
        <h3>Order summary</h3>
        <?php foreach ($items as $line): ?><p><?= htmlspecialchars($line, ENT_QUOTES, 'UTF-8') ?></p><?php endforeach; ?>
        <h3>Estimated total: Rs. <?= number_format($total_cost, 2) ?></h3>
        <p style="color:#646a74">Prices are read from the local database. No shipping, tax, inventory reservation or merchant payment verification is implemented.</p>
      </section>
      <section class="payment-card">
        <h3>Submit unpaid demo order</h3>
        <p>This creates a record in the local orders table for development and testing. It does not complete a purchase.</p>
        <form method="post" action="checkout.php">
          <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['checkout_csrf'], ENT_QUOTES, 'UTF-8') ?>">
          <button type="submit" class="btn" <?= $error_msg !== "" || $total_cost <= 0 ? 'disabled' : '' ?>>Place unpaid demo order</button>
        </form>
      </section>
    </div>
  <?php endif; ?>
</main>
</body></html>
