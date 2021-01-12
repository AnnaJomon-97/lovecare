<?php
require_once 'config/config.php';
$customer_id =  (int)$session->get_userdata('shop_customer_id');
if($customer_id <= 0 ){
    redirect('login.php');
}
/*
*  Strats record delete
*/
$del = (int)$input->get('del');
if ( $del > 0 ) {
    $del_rec = $db->delete('cart',array('id' => $del));
    if ( $del_rec ){
        $session->set_flashdata('msg',alert('Data deleted successfully','success'));
    }else{
        $session->set_flashdata('msg',alert('Could n\'t delete the data !','danger'));
    }  
    redirect('cart.php');
}
$q = "SELECT c.*, itm.`name` AS item_name,itm.`image`,itm.`price`,itm.`disc_amount` FROM `cart` c 
    LEFT JOIN `items` itm ON c.`item_id` = itm.`id`
    WHERE  c.`customer_id`='".$customer_id."'";
    $cart_items = $db->execute_get( $q );

include_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 40px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Cart</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Shopping Cart</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section" style="padding-top: 30px">
	<div class="container">
        <div class="row">
            <div class="col-12">
                <?= $session->get_flashdata("msg") ?>
                <div class="table-responsive shop_cart_table">
                    <?php 
                    $cart_total = 0 ;
                    if( count( $cart_items ) == 0){
                        echo '<div class="jumbotron" style="padding:20px">
                          <h3 class="text-center" >No items in cart !</h3>
                        </div>';
                    }else{ ?>

                	<table class="table">
                    	<thead>
                        	<tr>
                            	<th class="product-thumbnail">&nbsp;</th>
                                <th class="product-name">Product</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-subtotal">Total</th>
                                <th class="product-remove">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            foreach ($cart_items as $row) {
                            $price  = $row['price'] -   $row['disc_amount'] ;
                            $line_total = $price * $row['quantity'] ;
                            $cart_total += $line_total;
                             ?>
                        	<tr>
                            	<td class="product-thumbnail"><a href="#"><img src="<?= base_url($row['image']) ?>" alt="product1"></a></td>
                                <td class="product-name" data-title="Product"><a href="#"><?= $row['item_name'] ?></a></td>
                                <td class="product-price" data-title="Price">Rs.<?= number_format($price)  ?></td>
                                <td class="product-quantity" data-title="Quantity"><div class="quantity">
                                <input type="button" value="-" class="minus qty_minus" data-id="<?= $row['id'] ?>">
                                <input type="text" name="quantity" value="<?= $row['quantity'] ?>" title="Qty" class="qty" size="4" id="cartqty_<?= $row['id'] ?>"  data-id="<?= $row['id'] ?>">
                                <input type="button" value="+" class="plus qty_plus" data-id="<?= $row['id'] ?>">
                              </div></td>
                              	<td class="product-subtotal" data-title="Total">Rs. <?= number_format($line_total,2) ?></td>
                                <td class="product-remove" data-title="Remove"><a onclick="if(confirm('Delete ?')){ window.location.href='<?= base_url('cart.php?del='.$row['id']) ?>' }" href="#"><i class="ti-close"></i></a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
            	
            	<div class="divider center_icon"><i class="ti-shopping-cart-full"></i></div>
            	
            </div>
        </div>
        <?php if($cart_total  > 0){ ?>
        <div class="row">
        	<div class="col-md-6">
            </div>
            <div class="col-md-6">
            	<div class="border p-3 p-md-4">
                    <div class="heading_s1 mb-3">
                        <h6>Cart Total  : Rs. <?= number_format($cart_total,2) ?></h6>
                    </div>
                    
                    <a href="<?= base_url('checkout.php') ?>" class="btn btn-fill-out">Proceed To CheckOut</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<!-- END SECTION SHOP -->
<?php
include_once 'footer.php'
?>


<script type="text/javascript">
    $(document).on("blur",".qty",function(){
        update_cart($(this));
    });
    $('.qty_plus').on('click', function() {
        update_cart( $(this) );
    });
    $('.qty_minus').on('click', function() {
        update_cart( $(this) );
    });

    function update_cart( cart_btutton ){  
        var cartId  =  cart_btutton.data('id');
        var cartQty  = $("#cartqty_"+cartId).val();
        if( cartId == null || cartQty ==  null  ) return false;
        $.ajax({
            method: "POST",
            url: "<?= base_url('ajax.php') ?>",
            data: {
                cart_id:cartId,  
                cart_qty:cartQty, 
                process : 'UPDATE_CART',
            },
            beforeSend: function(){
                $("#divPreLoader").show();
            },
            complete:function(data){
                $("#divPreLoader").hide();
            }
        })
        .done(function( response ) {
            if( response.status == '1' ){
                window.location.href="<?= base_url('cart.php') ?>";
            }else{
                alert("Cart couldn\'t update the cart !");
            }
        });
    }

    

</script>
