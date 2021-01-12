<?php
require_once 'config/config.php';


include_once 'header.php';
$cid = (int) $input->get('cid');
$subcategories = $db->get('item_subcategories', array('category_id' => $cid));
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="page-title">
                    <h1>Subcategories</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Subcategories</a></li>
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
                    <?php foreach ($subcategories as $key => $row) { ?>
                    <div class="col-lg-3 col-md-4 col-6">
                        <a href="<?= base_url('items.php?sbc='.$row['id']) ?>">
                            <div class="product">
                                <div class="product_img">
                                <?php if ($row['image'] != ""){?>
                                    <img src="<?= base_url( $row['image']) ?>" alt="product_img1">
                                    
                                <?php }else{ ?>
                                    <img src="<?= base_url('uploads/subcategories/dummy_300X200.jpg') ?>" alt="product_img1"> 
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