<?php
require_once 'config/config.php';

include_once 'header.php';
$categories = $db->get('item_categories');
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="page-title">
                    <h1>Categories</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Categories</a></li>
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
                    <?php foreach ($categories as $key => $row) { ?>
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="<?= base_url('subcategories.php?cid='.$row['id']) ?>">
                            <div class="product">
                                <div class="product_img">
                                <?php if ($row['image'] != ""){?>
                                    <img src="<?= base_url( $row['image']) ?>" alt="product_img1">
                                <?php }else{ ?>
                                    <img src="<?= base_url('uploads/categories/dummy_300X200.jpg') ?>" alt="product_img1"> 
                                <?php } ?>
                                </div>
                                <div class="product_info">
                                    <h6 class=""><?= $row['name'] ?></h6>
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
</div>
<?php
include_once 'footer.php'
?>