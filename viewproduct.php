<!DOCTYPE html>
<html lang="en">

<?php
include_once('includes/header.php');
include_once('includes/style.php');
?>

<body>
<?php
include_once('includes/config.php');

// Site settings
$settingqry = "SELECT * FROM sitesettings";
$settingresult = mysqli_query($conn, $settingqry) or exit("Settings select fail: " . mysqli_error($conn));
$settingrow = mysqli_fetch_array($settingresult);

// Get category, subcategory, search
$catid = isset($_REQUEST['catid']) ? intval($_REQUEST['catid']) : 0;
$subcatid = isset($_REQUEST['subcatid']) ? intval($_REQUEST['subcatid']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// Subcategory/category details
$subcatname = "Products";
$subcatdescription = "Browse our products";

if ($subcatid > 0) {
    $subcatqry = "SELECT * FROM subcategories WHERE id='" . $subcatid . "'";
    $subcatresult = mysqli_query($conn, $subcatqry);
    if ($subcatresult && mysqli_num_rows($subcatresult) > 0) {
        $subcatrow = mysqli_fetch_array($subcatresult);
        $subcatname = $subcatrow['subcatname'];
        $subcatdescription = $subcatrow['subcatdescription'];
    }
} elseif ($catid > 0) {
    $catqry = "SELECT * FROM categories WHERE id='" . $catid . "'";
    $catresult = mysqli_query($conn, $catqry);
    if ($catresult && mysqli_num_rows($catresult) > 0) {
        $catrow = mysqli_fetch_array($catresult);
        $subcatname = $catrow['catname'];
        $subcatdescription = $catrow['catdescription'];
    }
}
?>

<!-- Search Bar -->
<div class="top-search">
    <div class="container">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search">
            <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
        </div>
    </div>
</div>

<!-- Title -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2><?php echo htmlspecialchars($subcatname); ?></h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($subcatname); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Shop Page -->
<div class="shop-box-inner">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-xl-3 col-lg-3 col-sm-12 col-xs-12 sidebar-shop-left">
                <div class="product-categori">

                    <!-- Search -->
                    <div class="search-product">
                        <form action="shop.php" method="GET">
                            <input class="form-control" name="search" placeholder="Search here..." type="text">
                            <button type="submit"> <i class="fa fa-search"></i> </button>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="filter-sidebar-left">
                        <div class="title-left">
                            <h3>Categories</h3>
                        </div>
                        <div class="list-group list-group-collapse list-group-sm list-group-tree" id="list-group-men" data-children=".sub-men">
                            <?php
                            $cat_query = "SELECT * FROM categories ORDER BY catname";
                            $cat_result = mysqli_query($conn, $cat_query);

                            if ($cat_result && mysqli_num_rows($cat_result) > 0) {
                                while ($category = mysqli_fetch_assoc($cat_result)) {
                                    echo '<div class="list-group-collapse sub-men">';
                                    echo '<a class="list-group-item list-group-item-action" href="#sub-men' . $category['id'] . '" data-toggle="collapse" aria-expanded="false" aria-controls="sub-men' . $category['id'] . '">';
                                    echo htmlspecialchars($category['catname']);
                                    echo '</a>';

                                    $subcat_query = "SELECT * FROM subcategories WHERE catid = " . $category['id'] . " ORDER BY subcatname";
                                    $subcat_result = mysqli_query($conn, $subcat_query);

                                    if ($subcat_result && mysqli_num_rows($subcat_result) > 0) {
                                        echo '<div class="collapse" id="sub-men' . $category['id'] . '" data-parent="#list-group-men">';
                                        echo '<div class="list-group">';

                                        while ($subcategory = mysqli_fetch_assoc($subcat_result)) {
                                            $product_count_query = "SELECT COUNT(*) as count FROM products WHERE subcatid = " . $subcategory['id'];
                                            $product_count_result = mysqli_query($conn, $product_count_query);
                                            $product_count = mysqli_fetch_assoc($product_count_result)['count'];

                                           echo '<a href="viewproduct.php?catid=' . $category['id'] . '&subcatid=' . $subcategory['id'] . '" class="list-group-item list-group-item-action">';
echo htmlspecialchars($subcategory['subcatname']) . ' <small class="text-muted">(' . $product_count . ')</small>';
echo '</a>';

                                        }
                                        echo '</div></div>';
                                    }
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="col-xl-9 col-lg-9 col-sm-12 col-xs-12 shop-content-right">
                <div class="right-product-box">
                    <div class="product-item-filter row">
                        <div class="col-12 col-sm-8 text-center text-sm-left">
                            <p>
                                <?php
                                $count_query = "SELECT COUNT(*) as total FROM products WHERE 1";
                                if ($catid > 0) $count_query .= " AND catid = $catid";
                                if ($subcatid > 0) $count_query .= " AND subcatid = $subcatid";
                                if (!empty($search)) $count_query .= " AND productname LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";

                                $count_result = mysqli_query($conn, $count_query);
                                $count_row = mysqli_fetch_assoc($count_result);
                                echo "Showing " . $count_row['total'] . " results";
                                ?>
                            </p>
                        </div>
                    </div>

                    <div class="row product-categorie-box">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade show active" id="grid-view">
                                <div class="row">
                                    <?php
                                    $productqry = "SELECT * FROM products WHERE 1";
                                    if ($catid > 0) $productqry .= " AND catid = '$catid'";
                                    if ($subcatid > 0) $productqry .= " AND subcatid = '$subcatid'";
                                    if (!empty($search)) $productqry .= " AND productname LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
                                    $productqry .= " ORDER BY id DESC";

                                    $productresult = mysqli_query($conn, $productqry);

                                    if (mysqli_num_rows($productresult) > 0) {
                                        while ($productrow = mysqli_fetch_array($productresult)) {
                                    ?>
                                    <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
                                        <div class="products-single fix">
                                            <div class="box-img-hover">
                                                <div class="type-lb"><p class="sale">Sale</p></div>
                                                <img src="images/products/<?php echo $productrow['image']; ?>" class="img-fluid" style="height:250px;width:100%;object-fit:cover;" alt="<?php echo htmlspecialchars($productrow['productname']); ?>">
                                                <div class="mask-icon">
                                                    <ul>
                                                        <li><a href="product_details.php?id=<?php echo $productrow['id']; ?>" data-toggle="tooltip" data-placement="right" title="View"><i class="fas fa-eye"></i></a></li>
                                                        <li><a href="#" data-toggle="tooltip" data-placement="right" title="Compare"><i class="fas fa-sync-alt"></i></a></li>
                                                        <li><a href="#" data-toggle="tooltip" data-placement="right" title="Add to Wishlist"><i class="far fa-heart"></i></a></li>
                                                    </ul>
                                                    <a class="cart" href="add_to_cart.php?product_id=<?php echo $productrow['id']; ?>">Add to Cart</a>
                                                </div>
                                            </div>
                                            <div class="why-text">
                                                <h4><?php echo htmlspecialchars($productrow['productname']); ?></h4>
                                                <h5>Rs. <?php echo number_format($productrow['productprice'], 2); ?></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    } else {
                                        echo '<div class="col-12"><div class="alert alert-info">No products found.</div></div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include_once('includes/footer.php'); ?>

<div class="footer-copyright">
    <p class="footer-company">All Rights Reserved. &copy; <?php echo date('Y'); ?> <a href="#">ThewayShop</a></p>
</div>

<a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

<!-- Scripts -->
<?php include_once('includes/script.php'); ?>
</body>
</html>
