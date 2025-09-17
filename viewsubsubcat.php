<!DOCTYPE html>
<html lang="en">

<?php include_once('includes/header.php'); ?>
<?php include_once('includes/style.php'); ?>

<body>
<?php
include_once('includes/config.php');

// Get subcategory ID
$subcatid = $_REQUEST['id'];

// Fetch site settings
$settingqry = "SELECT * FROM sitesettings";
$settingresult = mysqli_query($conn, $settingqry) or exit("Settings select fail: " . mysqli_error($conn));
$settingrow = mysqli_fetch_array($settingresult);
?>

<!-- Start Top Search -->
<div class="top-search">
    <div class="container">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search">
            <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
        </div>
    </div>
</div>
<!-- End Top Search -->

<!-- Start Sub-Subcategories -->
<div class="categories-shop">
    <div class="container">
        <div class="row">
            <?php
            $subsubqry = "SELECT * FROM subsubcategories WHERE subcatid = '$subcatid' ORDER BY id DESC";
            $subsubresult = mysqli_query($conn, $subsubqry) or exit("Sub-subcategory select fail: " . mysqli_error($conn));

            while ($subsubrow = mysqli_fetch_array($subsubresult)) {
            ?>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="shop-cat-box">
                        <img class="img-fluid" src="images/subsubcategories/<?php echo $subsubrow['image']; ?>" alt="" />
                        <a class="btn hvr-hover" href="viewproduct.php?subsubcatid=<?php echo $subsubrow['id']; ?>">
                            <?php echo $subsubrow['subsubcatname']; ?>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- End Sub-Subcategories -->

<!-- Start Footer -->
<?php include_once('includes/footer.php'); ?>
<!-- End Footer -->

<!-- Start Copyright -->
<div class="footer-copyright">
    <p class="footer-company">All Rights Reserved. &copy; <?php echo date("Y"); ?> <a href="#">YourSite</a> Design By:
        <a href="https://html.design/">html design</a></p>
</div>
<!-- End Copyright -->

<?php include_once('includes/script.php'); ?>
</body>
</html>
