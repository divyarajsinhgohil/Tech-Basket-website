<?php
session_start();
include_once('includes/config.php');

// Check if the form is submitted
if (isset($_POST['checkout'])) {
    // Retrieve the customer details from the form
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];
    $customer_address = $_POST['customer_address'];
    $payment_method = $_POST['payment_method'];

    // Calculate the total amount of the cart
    $order_total = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $query = "SELECT * FROM products WHERE id = '$product_id'";
        $result = mysqli_query($conn, $query) or exit("Error fetching product details: " . mysqli_error($conn));
        $product = mysqli_fetch_assoc($result);
        $order_total += $product['productprice'] * $quantity;
    }

    // Insert the order into the orders table
    $query = "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, order_total, payment_method) 
              VALUES ('$customer_name', '$customer_email', '$customer_phone', '$customer_address', '$order_total', '$payment_method')";
    if (!mysqli_query($conn, $query)) {
        echo "Error inserting order: " . mysqli_error($conn);
        exit;
    }

    // Get the last inserted order ID
    $order_id = mysqli_insert_id($conn);

    // Insert the individual items into the order_items table
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$order_id', '$product_id', '$quantity')";
        if (!mysqli_query($conn, $query)) {
            echo "Error inserting order item: " . mysqli_error($conn);
            exit;
        }
    }

    // Clear the cart after the order is placed
    unset($_SESSION['cart']);

    // Redirect to a success page or display a success message
    header("Location: success_page.php");
    exit;
} else {
    echo "An error occurred while processing your order.";
}
?>
