<?php
require_once 'config/config.php';


if ( !empty ( $_POST )){
    $order_id = isset($_POST['udf1']) ? isset($_POST['udf1']) : 0;
    $status= isset($_POST['status']) ? isset($_POST['status']) : 0;
    if (    $status == 'success'  ){
        $q = "UPDATE orders SET `payment_status`='1' WHERE id='".$order_id."'";
        $session->set_flashdata('msg',alert('Payment was successfull!','success'));
        $db->execute( $q );
    }else{
        $session->set_flashdata('msg',alert('Payment was not successfull,Retry again!','danger'));
        redirect('payment.php?order_id='.$order_id);
    }
    
}
include_once 'header.php';

?>

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section">
	<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center order_complete">
                	<i class="fas fa-check-circle"></i>
                    <div class="heading_s1">
                  	<h3>Your order is completed!</h3>
                    </div>
                  	<p>Thank you for your order! Your order is being processed and will be completed within 3-6 hours. You will receive an email confirmation when your order is completed.</p>
                    <a href="<?= base_url('index.php') ?>" class="btn btn-fill-out">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END SECTION SHOP -->
<?php
include_once 'footer.php';
?>