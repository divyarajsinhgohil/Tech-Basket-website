<?php
session_start();

include_once('includes/style.php');
include_once('includes/header.php');

// Ensure cart is not empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    echo "<script>alert('Your cart is empty!'); window.location.href='index.php';</script>";
    exit;
}

$order_placed = false;
$cart_items = $_SESSION['cart'] ?? [];

// Handle checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $customer_name = htmlspecialchars($_POST['customer_name']);
    $customer_email = filter_var($_POST['customer_email'], FILTER_SANITIZE_EMAIL);
    $customer_phone = htmlspecialchars($_POST['customer_phone']);
    $customer_address = htmlspecialchars($_POST['customer_address']);
    $payment_method = htmlspecialchars($_POST['payment_method']);
    $shipping_method = htmlspecialchars($_POST['shipping_method']);

    // Determine shipping cost
    $shipping_cost = 0;
    switch ($shipping_method) {
        case 'Express Delivery':
            $shipping_cost = 10;
            break;
        case 'Next Business day':
            $shipping_cost = 20;
            break;
        default:
            $shipping_method = 'Standard Delivery';
            $shipping_cost = 0;
    }

    // Calculate totals
    $sub_total = 0;
    foreach ($cart_items as $product_id => $quantity) {
        $product_id = (int)$product_id;
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $sub_total += $product['productprice'] * $quantity;
    }

    $discount = 40.00;
    $coupon_discount = 10.00;
    $tax = 2.00;
    $order_total = $sub_total - $discount - $coupon_discount + $tax + $shipping_cost;

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders 
        (customer_name, customer_email, customer_phone, customer_address, order_total, sub_total, discount, tax, shipping_cost, shipping_method, payment_method) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdddddss", $customer_name, $customer_email, $customer_phone, $customer_address, $order_total, $sub_total, $discount, $tax, $shipping_cost, $shipping_method, $payment_method);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert each cart item
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    foreach ($cart_items as $product_id => $quantity) {
        $product_id = (int)$product_id;
        $quantity = (int)$quantity;
        $stmt_item->bind_param("iii", $order_id, $product_id, $quantity);
        $stmt_item->execute();
    }

    // Clear cart and update flag
    unset($_SESSION['cart']);
    $order_placed = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
   
<body>
    <?php
    include_once('includes/config.php');
    ?>

<div class="container mt-5">
    <h1 class="mb-4">Checkout</h1>
    <div class="row">
        <div class="col-md-8">
            <form action="checkout.php" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Billing Address</h4>
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="customer_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="customer_address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" name="customer_phone" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4>Payment</h4>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="cash_on_delivery">Cash on Delivery</option>
                            </select>
                        </div>

                        <h4>Shipping Method</h4>
                        <div class="form-check">
                            <input type="radio" name="shipping_method" value="Standard Delivery" class="form-check-input" checked>
                            <label class="form-check-label">Standard Delivery (FREE)</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="shipping_method" value="Express Delivery" class="form-check-input">
                            <label class="form-check-label">Express Delivery ($10.00)</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="shipping_method" value="Next Business day" class="form-check-input">
                            <label class="form-check-label">Next Business Day ($20.00)</label>
                        </div>
                    </div>
                </div>

                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input" name="sameadr" checked>
                    <label class="form-check-label">Shipping address same as billing</label>
                </div>

                <button type="submit" name="checkout" class="btn btn-success mt-3">Place Order</button>

                <!-- ✅ Success Message -->
                <?php if ($order_placed): ?>
                    <div class="alert alert-success mt-3">
                        ✅ Your order has been placed successfully!
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <?php if (!$order_placed): ?>
        <div class="col-md-4">
            <h4>Cart Summary</h4>
            <?php
            $sub_total = 0;
            foreach ($cart_items as $product_id => $quantity) {
                $product_id = (int)$product_id;
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();
                $total = $product['productprice'] * $quantity;
                $sub_total += $total;
                echo "<p>{$product['productname']} (x{$quantity}) <span class='float-right'>\$" . number_format($total, 2) . "</span></p>";
            }

            $discount = 40.00;
            $coupon_discount = 10.00;
            $tax = 2.00;
            $shipping_cost = 0.00;
            $grand_total = $sub_total - $discount - $coupon_discount + $tax + $shipping_cost;
            ?>
            <hr>
            <p>Sub Total <span class="float-right">$<?php echo number_format($sub_total, 2); ?></span></p>
            <p>Discount <span class="float-right">-$<?php echo number_format($discount, 2); ?></span></p>
            <p>Coupon Discount <span class="float-right">-$<?php echo number_format($coupon_discount, 2); ?></span></p>
            <p>Tax <span class="float-right">$<?php echo number_format($tax, 2); ?></span></p>
            <p>Shipping <span class="float-right">$<?php echo number_format($shipping_cost, 2); ?>*</span></p>
            <hr>
            <h5>Grand Total <span class="float-right">$<?php echo number_format($grand_total, 2); ?>*</span></h5>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
<?php include_once('includes/script.php'); ?>
</body>
</html>
