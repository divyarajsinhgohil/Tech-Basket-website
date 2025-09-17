<?php
session_start();
include_once('includes/config.php');

// Add product to cart
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $quantity = 1;

    // Initialize cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Update cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Fetch all products in cart from database
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

    // Display all products in cart
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add to Cart</title>
        <?php include_once('includes/style.php'); ?>
    </head>
    <body>
         <?php include_once('includes/style.php'); ?>
        <?php include_once('includes/header.php'); ?>

        <div class="container mt-5">
            <h2>Add to Cart</h2>
            <p>Product added to cart successfully.</p>

            <?php if (!empty($cart_items)) : ?>
                <div class="row">
                    <?php foreach ($cart_items as $item) : ?>
                        <div class="col-lg-3">
                            <img src="images/products/<?php echo $item['image']; ?>" class="img-fluid" alt="Image">
                        </div>
                        <div class="col-lg-9">
                            <h4><?php echo $item['productname']; ?></h4>
                            <h5><?php echo "Rs. " . $item['productprice']; ?></h5>
                            <p><?php echo $item['productdescription']; ?></p>
                            <a href="cart.php" class="btn btn-primary">Add to Cart</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="text-center">
                <a href="cart.php" class="btn btn-primary">View Cart</a>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </body>
    </html>
    <?php
} else {
    header("Location: viewproduct.php");
    exit();
}
?>