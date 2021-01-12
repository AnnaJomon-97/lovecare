<?php
require_once 'config/config.php';
$customer_id =  (int)$session->get_userdata('shop_customer_id');
if($customer_id <= 0 ){
    redirect('login.php');
}
$q = "SELECT c.*, itm.`name` AS item_name,itm.`image`,itm.`price`,itm.`disc_amount` FROM `cart` c 
    LEFT JOIN `items` itm ON c.`item_id` = itm.`id`
    WHERE  c.`customer_id`='".$customer_id."'";
$cart_items = $db->execute_get( $q );

$name = $address = $phone = "";
if( isset( $_POST['btnCheckout'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('name','Name','required');
    $form_validation->set_rules('address','Address','required');
    $form_validation->set_rules('phone','Phone','required|valid_phone');
    $form_errors =  $form_validation->run();
    /*
    * ### End form validation
    */
    $id = (int) $input->post('id');
    $date = date('Y-m-d');
    $name = $input->post('name');
    $address = $input->post('address');
    $phone = $input->post('phone');
    $payment_type = $input->post('payment_type');
    $payment_status = '0';

    if( count ($cart_items) == 0 ){
        $form_errors['items'] = 'No items found in cart !';
    }
    if( count($form_errors) >0 ){
        $process = 'FORM';
        goto htmlView;
    }else{
        //SELECT `id`, `customer_id`, `name`, `address`, `phone`, `date`, `status`, `cancelled`, `cancellation_reason`, `payment_type`, `payment_status` FROM `orders` WHERE 1
        $sql = "INSERT INTO `orders`( `customer_id`, `name`, `address`, `phone`, `date`, `status`,`cancelled`, `cancellation_reason`,`payment_type`,`payment_status`) VALUES ('".$customer_id."','".$name."','".$address."','".$phone."','".$date."','1','0','','".$payment_type."','".$payment_status."')";
        if ( !$db->execute($sql) ){
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
            goto htmlView;
        }
        $order_id =  $db->get_insert_id();   
       
        foreach ($cart_items as $key => $row) {
            $item_id = $row['item_id'] ;
            $quantity = $row['quantity'] ;
            $price  = $row['price'] -   $row['disc_amount'] ;
            $q = "INSERT INTO `order_items`( `order_id`, `item_id`, `quantity`, `price`) VALUES ('".$order_id."','".$item_id."','".$quantity."','".$price."')";
            $db->execute($q);  
        }
        $del_rec = $db->delete('cart',array('customer_id' => $customer_id));
        if ($payment_type == 'ONLINE_PAYMENT'){
            redirect('payment.php?order_id='.$order_id);
        }else{

        }
        redirect('order-completed.php');
    }
}
htmlView:
include_once 'header.php';

?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 40px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Checkout</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section" style="padding-top: 40px">
	<div class="container">     
        <div class="row">
        	<div class="col-md-6">
            	<div class="heading_s1">
            		<h4>Delivery Details</h4>
                </div>
                <form method="post" action="<?= base_url('checkout.php') ?>" id="frmCheckout">
                    <div class="form-group">
                        <input type="text" required class="form-control" name="name" placeholder="Name *" value="<?= $name ?>">
                        <?= form_error('name') ?>
                    </div>
                   
                    <div class="form-group">
                        <textarea class="form-control"  name="address" placeholder="Address"><?= $address ?></textarea>
                        <?= form_error('address') ?>
                    </div>
                    <div class="form-group">
                        <input class="form-control" required type="text" name="phone" placeholder="Phone *"  value="<?= $phone ?>">
                        <?= form_error('phone') ?>
                    </div>
                    <div class="form-group">
                        <label style="margin-right: 20px"><input type="radio" name="payment_type" value="CASH_ON_DELIVERY" style="width: 24px;height: 24px" > Cash on Delivery</label>
                        <label><input type="radio" name="payment_type" value="ONLINE_PAYMENT" style="width: 24px;height: 24px" > Pay Online</label>
                        <?= form_error('phone') ?>
                    </div>
                    <div class="form-group">
                        <input name="btnCheckout" value="Checkout" type="hidden">
                        <a href="#" id="btnCheckout" class="btn btn-fill-out btn-block" style="margin-left:0px">Place Order</a>
                    </div>  
                </form>
            </div>
            <div class="col-md-6">
                <div class="order_review">
                    <div class="heading_s1">
                        <h4>Your Orders</h4>
                    </div>
                    <?= form_error('items') ?>
                    <div class="table-responsive order_table">
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
                                    <th>Product</th>
                                    <th>Total</th>
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
                                    <td><?= $row['item_name'] ?><span class="product-qty">x <?= $row['quantity'] ?></span></td>
                                    <td><?= number_format($line_total,2) ?></td>
                                </tr>
                            <?php }?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <td class="product-subtotal">Rs. <?= number_format($cart_total,2) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    <?php } ?>
                    </div>
                   
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION SHOP -->
<?php
include_once 'footer.php';
?>
<script type="text/javascript">
    $("#btnCheckout").click(function(){
        $("#frmCheckout").submit();
    })
</script>