<?php
session_start();
include_once('includes/config.php');

// Handle cart updates
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle product removal
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

// Fetch cart items from the database
$cart_items = [];
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = mysqli_query($conn, $query) or exit("Error fetching cart items: " . mysqli_error($conn));

    while ($row = mysqli_fetch_assoc($result)) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $row['total'] = $row['quantity'] * $row['productprice'];
        $total_price += $row['total'];
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <?php include_once('includes/style.php'); ?>
</head>
<body>
    <?php include_once('includes/header.php'); ?>
<div class="container mt-5">
    <h2 class="mb-4">Your Cart</h2>

    <?php if (empty($cart_items)) : ?>
        <p>Your cart is empty. <a href="viewproduct.php">Continue shopping</a>.</p>
    <?php else : ?>
        <form method="post" action="cart.php">
            <div class="row">
                <!-- Cart Table -->
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item) : ?>
                                <tr>
                                    <td>
                                        <img src="images/products/<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" width="80" height="80">
                                    </td>
                                    <td><?php echo htmlspecialchars($item['productname']); ?></td>
                                    <td>Rs. <?php echo number_format($item['productprice'], 2); ?></td>
                                    <td>
                                        <input type="number" name="quantity[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width: 80px;">
                                    </td>
                                    <td>Rs. <?php echo number_format($item['total'], 2); ?></td>
                                    <td>
                                        <a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm">×</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total</th>
                                <th colspan="2">Rs. <?php echo number_format($total_price, 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Order Summary -->
                <div class="col-md-4">
                    <div class="card border-dark">
                        <div class="card-header bg-dark text-white">
                            Order Summary
                        </div>
                        <div class="card-body">
                            <?php
                            $discount = 40;
                            $coupon = 10;
                            $tax = 2;
                            $shipping = 0;
                            $grand_total = $total_price - $discount - $coupon + $tax + $shipping;
                            ?>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Sub Total</span>
                                    <strong>Rs. <?php echo number_format($total_price, 2); ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Discount</span>
                                    <strong>-Rs. <?php echo number_format($discount, 2); ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Coupon Discount</span>
                                    <strong>-Rs. <?php echo number_format($coupon, 2); ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Tax</span>
                                    <strong>Rs. <?php echo number_format($tax, 2); ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Shipping</span>
                                    <strong>Free</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between font-weight-bold">
                                    <span>Grand Total</span>
                                    <strong>Rs. <?php echo number_format($grand_total, 2); ?></strong>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                           
                            <a href="checkout.php" class="btn btn-danger btn-block">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>


    <?php include_once('includes/footer.php'); ?>
</body>
</html>
