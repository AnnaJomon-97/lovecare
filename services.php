<?php
require_once 'config/config.php';

include_once 'header.php';
$services = $db->get('services');
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="page-title">
                    <h1>Services</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Services</a></li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section small_pt" style="padding-bottom: 30px">
    <div class="container">
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
<!-- END SECTION SHOP -->
</div>
<?php
include_once 'footer.php'
?>