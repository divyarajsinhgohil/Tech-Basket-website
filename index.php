<!DOCTYPE html>
<html lang="en">
    

<?php
include_once('includes/header.php');
?>
<?php
include_once('includes/style.php');
?>

<body>
    
    <?php
    include_once('includes/config.php');
    $settingqry ="select * from sitesettings";
    $settingresult=mysqli_query($conn,$settingqry)or exit("settings select fail".mysqli_error($conn));
    $settingrow=mysqli_fetch_array($settingresult);
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

    <!-- Start Slider -->
    <div id="slides-shop" class="cover-slides">
        <ul class="slides-container">

        <?php
       
    $sliderqry ="select * from slider";
    $sliderresult=mysqli_query($conn,$sliderqry)or exit("slider select fail".mysqli_error($conn));
  while( $sliderrow=mysqli_fetch_array($sliderresult)){
    ?>


            <li class="<?php echo $sliderrow['alignment']?>">
                <img src="images/slider/<?php echo $sliderrow['image']?>" alt="">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="m-b-20"><?php echo $sliderrow['name'];?></h1>
                            <p class="m-b-40"><?php echo $sliderrow['description'];?></p>
                            <p><a class="btn hvr-hover" href="<?php echo $sliderrow['button_link']?>">Shop New</a></p>
                        </div>
                    </div>
                </div>
            </li>
            <?php
  }
            ?>
        </ul>
        <div class="slides-navigation">
            <a href="#" class="next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            <a href="#" class="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
        </div>
    </div>
    <!-- End Slider -->

   <!-- Start Categories -->
<div class="categories-shop">
    <div class="container">
        <div class="row justify-content-center">
            <?php
                $homecatqry = "SELECT * FROM categories ORDER BY id DESC";
                $homecatresult = mysqli_query($conn, $homecatqry) or exit("Category select fail: " . mysqli_error($conn));

                while ($homecatrow = mysqli_fetch_array($homecatresult)) {
            ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <a href="viewsubcategories.php?id=<?php echo $homecatrow['id']; ?>" class="shop-cat-link">
                        <div class="shop-cat-box">
                            <img src="images/categories/<?php echo $homecatrow['image']; ?>" alt="<?php echo $homecatrow['catname']; ?>">
                            <span class="category-name"><?php echo $homecatrow['catname']; ?></span>
                        </div>
                    </a>
                </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>

<!-- End Categories -->




   <!-- Start Products  -->
<div class="products-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>Featured Products</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet lacus enim.</p>
                </div>
            </div>
        </div>

        <!-- Subcategory Buttons (optional for filtering) -->
      <div class="subcategory-slider owl-carousel owl-theme">
    <?php
    $subcatqry = "SELECT * FROM subcategories ORDER BY subcatname ASC";
    $subcatresult = mysqli_query($conn, $subcatqry);
    while($subcatrow = mysqli_fetch_assoc($subcatresult)){
        ?>
        <div class="item">
            <a href="viewproduct.php?subcatid=<?php echo $subcatrow['id']; ?>" class="subcategory-btn">
                <?php echo $subcatrow['subcatname']; ?>
            </a>
        </div>
    <?php } ?>
</div>
<style>
    .subcategory-btn {
    display: inline-block;
    background: #d33b33;
    color: #fff;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 4px;
    text-decoration: none;
    text-align: center;
    transition: background 0.3s;
}

.subcategory-btn:hover,
.subcategory-btn.active {
    background: #010101;
}
.subcategory-btn {
    display: inline-block;
    background: #d33b33;
    color: #fff;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 4px;
    text-decoration: none;
    text-align: center;
    transition: background 0.3s;
    margin: 0;           /* remove margin */
    float: left;         /* align tightly next to each other */
}

.subcategory-btn:hover,
.subcategory-btn.active {
    background: #010101;
}

/* Clearfix for parent container */
.subcategory-buttons::after {
    content: "";
    display: table;
    clear: both;
}


    </style>

        <!-- Products Slider -->
        <div class="owl-carousel owl-theme products-slider">
            <?php
            $productqry = "SELECT * FROM products ORDER BY id DESC";
            $productresult = mysqli_query($conn, $productqry);
            while($productrow = mysqli_fetch_assoc($productresult)){
                ?>
                <div class="item">
                    <div class="products-single fix">
                        <div class="box-img-hover">
                            <img src="images/products/<?php echo $productrow['image']; ?>" class="img-fluid" alt="<?php echo $productrow['productname']; ?>">
                            <div class="mask-icon">
                                <ul>
                                    <li><a href="#" title="View"><i class="fas fa-eye"></i></a></li>
                                    <li><a href="#" title="Compare"><i class="fas fa-sync-alt"></i></a></li>
                                    <li><a href="#" title="Add to Wishlist"><i class="far fa-heart"></i></a></li>
                                </ul>
                                <a class="cart" href="#">Add to Cart</a>
                            </div>
                        </div>
                        <div class="why-text">
                            <h4><?php echo $productrow['productname']; ?></h4>
                            <h5>$<?php echo $productrow['productprice']; ?></h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

<!-- End Products -->

    



    <!-- Start Instagram Feed  -->
    <div class="instagram-box">
        <div class="main-instagram owl-carousel owl-theme">
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/photo.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-02.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-03.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-04.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-05.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-06.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-07.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-08.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-09.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-05.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Instagram Feed  -->


    <!-- Start Footer  -->
  <?php
 include_once('includes/footer.php');
  ?>
    <!-- End Footer  -->

    <!-- Start copyright  -->
    <div class="footer-copyright">
        <p class="footer-company">All Rights Reserved. &copy; 2018 <a href="#">ThewayShop</a> Design By :
            <a href="https://html.design/">html design</a></p>
    </div>
    <!-- End copyright  -->

    <a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

    <!-- ALL JS FILES -->
  <?php
 include_once('includes/script.php');
  ?>
</body>
<script>
    $('.products-slider').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: false,
    navText: ["<i class='fas fa-chevron-left'></i>","<i class='fas fa-chevron-right'></i>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:4
        }
    }
});

</script>
<script>
$(document).ready(function(){
    $(".subcategory-slider").owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        dots: false,
        responsive:{
            0:{ items:2 },
            576:{ items:3 },
            768:{ items:5 },
            992:{ items:6 },
            1200:{ items:8 }
        }
    });
});
</script>


</html>