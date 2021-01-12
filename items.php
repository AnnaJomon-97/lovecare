<?php
require_once 'config/config.php';
include_once 'header.php';
$categories = $db->get('item_categories');
$q = "SELECT * FROM items WHERE 1 ORDER BY id DESC LIMIT 10";
$new_arrivals = $db->execute_get( $q );
$brands = $db->get('brands');
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding:40px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Items</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Items</a></li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section">
	<div class="container">
    	<div class="row">
			<div class="col-lg-9">
                <div class="row shop_container">
                    <?php 
                        $subcategory_id = (int)$input->get('sbc');
                        $q = "SELECT itm.*,sbc.`name` AS subcategory_name,c.`name` AS category_name,b.name AS brand_name FROM `items` itm 
    LEFT JOIN `item_subcategories` sbc ON itm.`subcategory_id` = sbc.`id`
    LEFT JOIN `item_categories` c ON sbc.`category_id` = c.`id`
    LEFT JOIN `brands` b ON itm.`brand_id` = b.`id`
    WHERE 1 ";
    if ($subcategory_id > 0) {
        $q .= "  AND itm.subcategory_id='".$subcategory_id."'";
    }
    $q .= " ORDER BY sbc.`category_id` ASC, itm.`subcategory_id` ASC, itm.`name` ASC ";
    $items = $db->execute_get( $q );
                       
                        foreach ($items as $row) {?>
                            
                    <div class="col-md-4 col-6">
                        <a href="<?= base_url('item-details.php?item_id='.$row['id'])?>">
                        <div class="product">
                            <div class="product_img">
                                <?php if ($row['image'] != ""){?>
                                    <img src="<?= base_url( $row['image']) ?>" alt="product_img1">
                                <?php }else{ ?>
                                    <img src="<?= base_url('uploads/items/dummy_540X600.jpg') ?>" alt="product_img1"> 
                                <?php } ?>
                               
                            </div>
                            <div class="product_info">
                                <h6 class="product_title"><?= $row['name'] ?></h6>
                                <!--  SELECT `id`, `subcategory_id`, `brand_id`, `name`, `description`, `image`, `price`, `disc_type`, `disc_percentage`, `disc_amount`, `stock` FROM `items` WHERE 1 -->
                                <div class="product_price">
                                    
                                    <?php if ($row['disc_amount'] > 0 ){?>
                                    <span class="price">Rs.<?= number_format( $row['price']-$row['disc_amount'],2 )?></span>
                                    <del>Rs.<?=  number_format($row['price'],2) ?></del>

                                    <?php }else{?>
                                    <span class="price"><?= number_format($row['price'],2) ?></span>
                                    <?php  }?>
                                    <?php if ($row['disc_percentage'] > 0 ){?>
                                    <div class="on_sale">
                                        <span><?= number_format($row['disc_percentage'] )?>% Off</span>
                                    </div>
                                    <?php }?>
                                    <div><span>Brand : <?= $row['brand_name'] ?></span></div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                    
                    <?php } ?>
                </div>
        		<!-- <div class="row">
                    <div class="col-12">
                        <ul class="pagination mt-3 justify-content-center pagination_style1">
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="linearicons-arrow-right"></i></a></li>
                        </ul>
                    </div>
                </div> -->
        	</div>
            <div class="col-lg-3 order-lg-first mt-4 pt-2 mt-lg-0 pt-lg-0">
            	<div class="sidebar">
                	<div class="widget">
                        <h5 class="widget_title">Categories</h5>
                        <ul class="widget_categories">
                            <?php 
                            $mnu_cats = $db->get('item_categories');
                            $mun_subcats = $db->get('item_subcategories');
                            $ar_munsubcats = [];
                            $subcatgeory_id = (int) $input->get('sbc') ;
                            $catgeory_id = (int) $db->get_colum_value('item_subcategories','category_id',array('id'=> $subcatgeory_id)) ;
                            foreach ($mun_subcats as $mnu_sbcrow){
                                $cat_id = $mnu_sbcrow['category_id'];
                                $ar_munsubcats[$cat_id][] = $mnu_sbcrow;
                            }
                            foreach ( $mnu_cats as $mnu_catrow){
                                $cat_id = $mnu_catrow['id'];
                                $mnusubcat2 = isset($ar_munsubcats[$cat_id]) ? $ar_munsubcats[$cat_id] :[];
                            ?>
                            <li><a  data-toggle="collapse" href="#collapseExample<?= $mnu_catrow['id'] ?>"><span class="categories_name"><strong><?= $mnu_catrow['name']  ?></strong></span></a></li>

                            <div  class="collapse <?=  $catgeory_id == $mnu_catrow['id'] ?'show' :'' ?>" id="collapseExample<?= $mnu_catrow['id'] ?>">
                                <ul class="subcat-list">
                                    <?php foreach ($mnusubcat2 as $subcat_row){?>
                                    <li  <?=  $subcatgeory_id == $subcat_row['id'] ?'class="active"' :'' ?> ><a href="<?= base_url('items.php/?sbc='.$subcat_row['id']) ?>">  <?= $subcat_row['name'] ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION SHOP -->
<?php
include_once 'footer.php'
?>