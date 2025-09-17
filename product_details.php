<?php
session_start();
include_once('includes/config.php');

// Check if product ID is passed
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $product_qry = "SELECT * FROM products WHERE id='$product_id'";
    $product_result = mysqli_query($conn, $product_qry) or die("Product select fail: " . mysqli_error($conn));
    $product = mysqli_fetch_assoc($product_result);

    if (!$product) {
        header("Location: viewproduct.php");
        exit();
    }
} else {
    header("Location: viewproduct.php");
    exit();
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $pid = $_POST['product_id'];
    $qty = (int)$_POST['quantity'];
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
    header("Location: product_details.php?id=$pid");
    exit();
}

// Handle Review
if (isset($_POST['submit_review'])) {
    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }

    $pid = $_POST['product_id'];
    $user = $_SESSION['username'];
    $review = mysqli_real_escape_string($conn, $_POST['review_text']);
    $rating = (int)$_POST['rating'];

    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $pid, $user, $review, $rating);
    $stmt->execute();
    header("Location: product_details.php?id=$pid");
    exit();
}

// Fetch reviews
$reviews_result = mysqli_query($conn, "SELECT * FROM reviews WHERE product_id='$product_id' ORDER BY created_at DESC");

// Collect product images
$product_images = [];
if (!empty($product['image']))  $product_images[] = $product['image'];
if (!empty($product['image2'])) $product_images[] = $product['image2'];
if (!empty($product['image3'])) $product_images[] = $product['image3'];

// Fetch featured products
$fp_result = mysqli_query($conn, "SELECT * FROM products WHERE id != '$product_id' LIMIT 12"); // more products for slider
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['productname']; ?> | Product Details</title>
      

    <?php include_once('includes/style.php'); ?>


</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Shop Detail</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="shop.php">Shop</a></li>
                    <li class="breadcrumb-item active"><?php echo $product['productname']; ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Shop Detail -->
<div class="shop-detail-box-main">
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-xl-5 col-lg-5 col-md-6">
                <div id="carousel-example-1" class="single-product-slider carousel slide" data-ride="carousel" data-interval="false">
                    <div class="carousel-inner" role="listbox">
                       <?php
// inside your carousel loop, replace the img output with:
foreach ($product_images as $index => $image) {
    $active = $index === 0 ? 'active' : '';
    echo '<div class="carousel-item ' . $active . '">';
    echo '  <img class="d-block product-main-image" src="images/products/' . trim($image) . '" alt="Product Image ' . ($index + 1) . '">';
    echo '</div>';
}
?>

                    </div>
                    <?php if(count($product_images) > 1): ?>
                    <a class="carousel-control-prev" href="#carousel-example-1" role="button" data-slide="prev"> 
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                        <span class="sr-only">Previous</span> 
                    </a>
                    <a class="carousel-control-next" href="#carousel-example-1" role="button" data-slide="next"> 
                        <i class="fa fa-angle-right" aria-hidden="true"></i> 
                        <span class="sr-only">Next</span> 
                    </a>
                    <?php endif; ?>
                    <ol class="carousel-indicators">
                        <?php
                        foreach ($product_images as $index => $image) {
                            $active = $index === 0 ? 'active' : '';
                            echo '<li data-target="#carousel-example-1" data-slide-to="' . $index . '" class="' . $active . '">
                            <img class="d-block w-100 img-fluid" src="images/products/' . trim($image) . '" alt="" />
                        </li>';
                        }
                        ?>
                    </ol>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-xl-7 col-lg-7 col-md-6">
                <div class="single-product-details">
                    <h2><?php echo $product['productname']; ?></h2>
                    <h5>₹<?php echo number_format($product['productprice'], 2); ?></h5>
                    <p class="available-stock"><span>In Stock</span></p>
                    <h4>Description:</h4>
                    <p><?php echo nl2br(htmlspecialchars($product['productdescription'])); ?></p>
                    
                    <form method="post" action="">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <div class="form-group quantity-box">
                            <label>Quantity</label>
                            <input class="form-control" name="quantity" value="1" min="1" type="number" style="max-width: 100px;">
                        </div>
                        <div class="price-box-bar">
                            <button type="submit" name="add_to_cart" class="btn hvr-hover">Add to cart</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Featured Products Slider -->
        <div class="row my-5">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>Featured Products</h1>
                </div>
                <div class="owl-carousel owl-theme featured-slider">
                    <?php
                    if ($fp_result && mysqli_num_rows($fp_result) > 0) {
                        while ($fp = mysqli_fetch_assoc($fp_result)) {
                            $fp_img = !empty($fp['image']) ? $fp['image'] : (!empty($fp['image2']) ? $fp['image2'] : (!empty($fp['image3']) ? $fp['image3'] : 'default.jpg'));
                            echo '
                            <div class="item text-center">
                                <img src="images/products/' . trim($fp_img) . '" alt="' . $fp['productname'] . '">
                                <h4>' . $fp['productname'] . '</h4>
                                <h5>₹' . number_format($fp['productprice'], 2) . '</h5>
                                <a class="btn btn-outline-primary btn-sm" href="product_details.php?id=' . $fp['id'] . '">View Details</a>
                            </div>';
                        }
                    } else {
                        echo '<p class="text-center">No featured products available</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <div class="row">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>Customer Reviews</h1>
                </div>
                
                <div class="reviews-section">
                    <?php
                    if (mysqli_num_rows($reviews_result) > 0) {
                        while ($r = mysqli_fetch_assoc($reviews_result)) {
                            echo '<div class="review-box">
                                <div class="d-flex justify-content-between">
                                    <strong>' . htmlspecialchars($r['user_name']) . '</strong> 
                                    <span class="rating">';
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $r['rating'] ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                            }
                            echo '</span></div>
                                <p>' . htmlspecialchars($r['review_text']) . '</p>
                                <small class="text-muted">' . date('F j, Y', strtotime($r['created_at'])) . '</small>
                            </div>';
                        }
                    } else {
                        echo "<p class='text-center'>No reviews yet. Be the first to review this product!</p>";
                    }
                    ?>
                </div>
                
                <!-- Submit Review -->
                <div class="review-form mt-5">
                    <h4>Submit Your Review</h4>
                    <form method="post" action="">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <div class="form-group">
                            <label>Your Rating</label>
                            <select name="rating" class="form-control" required style="max-width: 200px;">
                                <option value="">Select Rating</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Your Review</label>
                            <textarea name="review_text" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="btn hvr-hover">
                            <?php echo isset($_SESSION['email']) ? 'Submit Review' : 'Login to Submit Review'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Shop Detail -->

<?php include_once('includes/footer.php'); ?>

<!-- jQuery + Bootstrap (already in your project probably) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
$(document).ready(function(){
    $('.featured-slider').owlCarousel({
        loop:true,
        margin:15,
        nav:true,
        dots:false,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        responsive:{
            0:{items:1},
            576:{items:2},
            768:{items:3},
            1200:{items:4}
        }
    });
});
</script>
</body>
</html>
