<?php
require_once 'config/config.php';

$item_id = (int)$input->get('item_id');
$q = "SELECT itm.*,sbc.`name` AS subcategory_name,c.`name` AS category_name,b.name AS brand_name FROM `items` itm 
    LEFT JOIN `item_subcategories` sbc ON itm.`subcategory_id` = sbc.`id`
    LEFT JOIN `item_categories` c ON sbc.`category_id` = c.`id`
    LEFT JOIN `brands` b ON itm.`brand_id` = b.`id`
    WHERE  itm.`id`='".$item_id."'";
    $item = $db->execute_get_row( $q );

if (!$item ) {

    redirect('categories.php');
}
include_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 40px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Product Detail</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Product Detail</li>
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
            <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
              <div class="product-image">
                    <div class="product_img_box">
                        <?php if ($item->image  != ""){?>
                                    <img id="product_img" src="<?= base_url( $item->image  ) ?>" alt="product_img1" data-zoom-image="<?= base_url( $item->image )  ?>">
                                <?php }else{ ?>
                                    <img id="product_img" src="<?= base_url('uploads/items/dummy_540X600.jpg') ?>" alt="product_img1" data-zoom-image="<?= base_url( 'uploads/items/dummy_540X600.jpg')  ?>"> 
                                <?php } ?>
                       
                        <a href="#" class="product_img_zoom" title="Zoom">
                            <span class="linearicons-zoom-in"></span>
                        </a>
                    </div>
                  
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6">
                <div class="pr_detail">
                    <div class="product_description">
                        <h4 class="product_title"><a href="#"><?= $item->name ?> </a></h4>
                        <div class="product_price">
                            <?php if ( $item->disc_amount  > 0 ){?>
                            <span class="price">Rs.<?= number_format( ($item->price - $item->disc_amount),2 )?> </span>
                            <del><?= number_format( $item->price,2 )?></del>
                            <?php }else{ ?>
                            <span class="price">Rs.<?= number_format( $item->price,2 )?></span>
                            <?php }  ?>
                            <?php if ( $item->disc_percentage  > 0 ){?>
                            <div class="on_sale">
                                <span><?= number_format($item->disc_percentage) ?>% Off</span>
                            </div>
                            <?php }  ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="pr_desc">
                            <p><?= $item->description ?></p>
                        </div>
                    </div>
                    <hr />
                    <div class="cart_extra">
                        <input type="hidden" id="itemId" value="<?= $item->id ?>">
                        <div class="cart-product-quantity">
                            <div class="quantity">
                                <input type="button" value="-" class="minus">
                                <input type="text" name="quantity" value="1" title="Qty" class="qty" id="cartQty" size="4">
                                <input type="button" value="+" class="plus">
                            </div>
                        </div>  
                        <div class="cart_btn">
                            <button class="btn btn-fill-out btn-addtocart" id="btnAddToCart" type="button"><i class="icon-basket-loaded"></i> Add to cart</button>
                        </div>
                    </div>
                    <hr />
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-12">
            	<div class="large_divider clearfix"></div>
            </div>
        </div>
        <div class="row">
        	<div class="col-12">
                <?php 
                $q ="SELECT r.*,c.`name` AS customer_name  FROM `reviews` r
                LEFT JOIN `customers` c ON r.`customer_id` = c.`id`
                WHERE r.`item_id`='".(int)$item->id."'" ;
                $review_list = $db->execute_get($q);
                ?>
            	<div class="tab-style3">
					<ul class="nav nav-tabs" role="tablist">
                      	<li class="nav-item">
                        	<a class="nav-link active" id="Reviews-tab" data-toggle="tab" href="#Reviews" role="tab" aria-controls="Reviews" aria-selected="false">Reviews (<?= count( $review_list ) ?>)</a>
                      	</li>
                    </ul>
                	<div class="tab-content shop_info_tab">
                      	<div class="tab-pane fade show active" id="Reviews" role="tabpanel" aria-labelledby="Reviews-tab">
                        	<div class="comments">
                            	<h5 class="product_tab_title"><?= count( $review_list ) ?> Review For <span>
                                    <?= $item->name ?>
                                    </span></h5>
                                <ul class="list_none comment_list mt-4">
                                    <?php foreach ($review_list as $key => $review_row) { ?>
                                        
                                    <li>
                                        
                                        <div class="comment_block" style="padding-left: 0px">
                                          
                                            <p class="customer_meta">
                                                <span class="review_author"><?= $review_row['customer_name'] ?></span>
                                               
                                            </p>
                                            <div class="description">
                                                <p><?= $review_row['review'] ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <?php }  ?>
                                    
                                </ul>
                        	</div>
                            
                      	</div>
                	</div>
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-12">
            	<div class="small_divider"></div>
            	<div class="divider"></div>
                <div class="medium_divider"></div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION SHOP -->

</div>
<!-- END MAIN CONTENT -->
<?php
include_once 'footer.php'
?>

<script type="text/javascript">
    $('#btnAddToCart').click(function(){
    var itemId =    $('#itemId').val();
    var cartQty =    $('#cartQty').val();
    $.ajax({
        type: "POST",
        url: "<?php echo  base_url('ajax.php');?>",
        data: {
            item_id : itemId,
            quantity : cartQty,
            process : 'ADD_TO_CART',
        }, 
        beforeSend: function(){
            $("#divPreLoader").show();
        },
        complete:function(){
            $("#divPreLoader").hide();
        }
    })
    .done(function( response ) {
        if (response.login_status == '0' ){
            alert ('Please login to continue!');
            window.location.href ="<?= base_url('login.php') ?>"
        }else if (response.alert != ""){
            alert (response.alert);
        }
    });
});
</script>