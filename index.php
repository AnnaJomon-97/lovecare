<?php
require_once 'config/config.php';
include_once 'header.php';
$categories = $db->get('item_categories');
$q = "SELECT * FROM items WHERE 1 ORDER BY id DESC LIMIT 10";
$new_arrivals = $db->execute_get( $q );
$brands = $db->get('brands');
?>
<!-- START SECTION BANNER -->
<div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
    <div id="carouselExampleControls" class="carousel slide carousel-fade light_arrow" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active background_bg" data-img-src="<?= base_url('assets/web/images/banner1.jpg') ?>">
            </div>
            <div class="carousel-item background_bg" data-img-src="<?= base_url('assets/web/images/banner4.jpg') ?>">
            </div>
            
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"><i class="ion-chevron-left"></i></a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"><i class="ion-chevron-right"></i></a>
    </div>
</div>
<!-- END SECTION BANNER -->

<!-- END MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section small_pt" style="padding-bottom: 30px;background-color: #f1f1f1">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
            	<div class="heading_s1 text-center">
                	<h2>Product Categories</h2>
                </div>
            </div>
		</div>
        <div class="row">
        	<div class="col-12">
            	<div class="row shop_container">
                    <?php foreach ($categories as $key => $row) { ?>
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="<?= base_url('subcategories.php?cid='.$row['id']) ?>">
                            <div class="product">
                                <div class="product_img">
                                    
                                        <img src="<?= base_url( $row['image']) ?>" alt="product_img1">
                                    
                                    
                                </div>
                                <div class="product_info">
                                    <h6><?= $row['name'] ?></h6>
                                </div>
                            </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                
            </div>
        </div> 
    </div>
</div>
<!-- END SECTION SHOP -->
<!-- START SECTION SERVICES  -->
<?php 
$services = $db->get('services'); 
if ( count ( $services ) > 0   )?>
<div class="section small_pt" style="padding-bottom: 30px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="heading_s1 text-center">
                    <h2>Services</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row shop_container">
                    <?php foreach ($services as $key => $row) { ?>
                    <div class="col-lg-3 col-md-4 col-6">
                        
                            <div class="product">
                                <div class="product_img">
                                    <img src="<?= base_url( $row['image']) ?>" alt="product_img1">
                                </div>
                                <div class="product_info">
                                    <h6><?= $row['name'] ?></h6>
                                    <p>Rs.<?= number_format($row['fee'],2)."/". $row['unit'] ?> </p>
                                    <a href="<?= base_url('service-booking.php?sid='.$row['id']) ?>" class="btn btn-warning" >Book Now</a>
                                </div>
                            </div>
                          
                        </div>
                    <?php } ?>
                </div>
                
            </div>
        </div> 
    </div>
</div>
<!-- END SECTION SERVICES -->
<!-- START SECTION INSTAGRAM IMAGE -->
<div class="section small_pt small_pb">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-12">
                <div class="client_logo carousel_slider owl-carousel owl-theme" data-dots="false" data-margin="0" data-loop="true" data-autoplay="true" data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "4"}, "991":{"items": "6"}}'>
                    <?php foreach ( $new_arrivals as $row ) {?>
                    <div class="item">
                        <div class="instafeed_box">
                            <a href="#">
                                <?php if ($row['image'] != ""){?>
                                    <img src="<?= base_url( $row['image']) ?>" alt="product_img1">
                                <?php }else{ ?>
                                    <img src="<?= base_url('uploads/items/dummy_540X600.jpg') ?>" alt="product_img1"> 
                                <?php } ?>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION INSTAGRAM IMAGE --> 


<!-- START SECTION CLIENT LOGO -->
<div class="section small_pt">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="client_logo carousel_slider owl-carousel owl-theme" data-dots="false" data-margin="30" data-loop="true" data-autoplay="true" data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "4"}, "991":{"items": "5"}}'>
                <?php foreach ( $brands as $row ) {?>
                    <div class="item">
                        <div class="cl_logo">
                            <img src="<?= $row['logo'] ?>"/>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION CLIENT LOGO -->
</div>
<!-- END MAIN CONTENT -->
<?php
include_once 'footer.php'
?>