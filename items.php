<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_2025";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Add item to the cart
        $sql = "INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE quantity = quantity + ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $user_id, $item_id, $quantity, $quantity);
        $stmt->execute();
    } else {
        // Handle guest cart logic (optional)
        // You can store items in a session or a cookie for guests
        $_SESSION['guest_cart'][$item_id] = isset($_SESSION['guest_cart'][$item_id]) ? $_SESSION['guest_cart'][$item_id] + $quantity : $quantity;
    }
}

// Fetch items from the database
$sql = "SELECT * FROM items"; // Adjust this query based on your items table structure
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Items</title>
</head>
<body>
    <h1>Select Items to Add to Cart</h1>
    <form method="POST" action="">
        <?php while ($item = $result->fetch_assoc()): ?>
            <div>
                <h3><?php echo $item['name']; ?></h3>
                <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" value="1" min="1" required>
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </div>
        <?php endwhile; ?>
    </form>
    <a href="addtocart.php">View Cart</a>
</body>
</html>

<?php
$conn->close();
?>