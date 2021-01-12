<?php
require_once 'config/config.php';
$customer_id =   (int)$session->get_userdata('shop_customer_id');
//SELECT `id`, `customer_id`, `service_id`, `date_from`, `date_to`, `name`, `address`, `phone`, `status`, `created_at` FROM `service_booking` WHERE 1
$sid = (int)$input->get('sid');
$date_from =   $date_to = date('Y-m-d');
$service_id =    $name =   $address = $phone = "";
if( isset( $_POST['btnSrvBooking'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('name','Name','required','Please enter name');
    $form_validation->set_rules('address','Address','required','Please enter name');
    $form_validation->set_rules('phone','Phone','required','Please enter name');
    $form_errors =  $form_validation->run();
    /*
    * ### End form validation
    */
    $service_id  = $sid = (int) $input->post('service_id');
    $date_from =  $input->post('date_from');
    $date_to =  $input->post('date_to');
    $name = $input->post('name');
    $address = $input->post('address');
    $phone = $input->post('phone');
    $status = $input->post('status');
    if( count($form_errors) >0 ){ 
        $process = 'FORM';
        goto htmlView;
    }else{
        $sql = "INSERT INTO `service_booking`( `customer_id`,`service_id`, `date_from`,`date_to`, `name`, `address`, `phone`, `status`,`cancelled` ) VALUES ('".$customer_id."','".$service_id."','".$date_from."','".$date_to."','".$name."','".$address."','".$phone."','1','0')";
        if ( $db->execute($sql) ){
            $session->set_flashdata('msg',alert('Service booked successfully','success'));
        }else{
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
        }   
        redirect('service-booking.php?sid='.$service_id);
    }
}
htmlView:

$service = $db->get_row( 'services',array( 'id' => $sid ) );
if (!$service ) {
    redirect('services.php');
}

include_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 40px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Service Booking</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Service Booking</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="col-sm-12">
    <?= $session->get_flashdata('msg') ?>
</div>
<div class="section">
	<div class="container">
		<div class="row">
            <div class="col-lg-3 col-md-3 mb-4 mb-md-0">
              <div class="product-image">
                    <div class="product_img_box">
                        <?php if ($service->image  != ""){?>
                            <img id="product_img" src="<?= base_url( $service->image  ) ?>" alt="product_img1" data-zoom-image="<?= base_url( $service->image )  ?>">
                        <?php }else{ ?>
                            <img id="product_img" src="<?= base_url('uploads/items/dummy_540X600.jpg') ?>" alt="product_img1" data-zoom-image="<?= base_url( 'uploads/items/dummy_540X600.jpg')  ?>"> 
                        <?php } ?>
                        <a href="#" class="product_img_zoom" title="Zoom">
                            <span class="linearicons-zoom-in"></span>
                        </a>
                    </div>
                  
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="pr_detail">
                    <div class="product_description">
                        <h4 class="product_title"><a href="#"><?= $service->name ?> </a></h4>
                        <div class="product_price">
                            <span class="price">Rs.<?= number_format( $service->fee,2 )."/".$service->unit ?></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="pr_desc">
                            <p><?= $service->description ?></p>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="heading_s1">
                    <h4>Booking Information</h4>
                </div> 
                <form method="post" action="<?= base_url('service-booking.php') ?>" id="frmServiceBooking" class="form-horizontal" >
                    <input type="hidden" name="service_id" value="<?= $service->id ?>">
                    <div class="form-group row">
                        <div class="col-sm-6">
                          <div class="input-group mb-3 ">
                             <div class="input-group-prepend">
                               <span class="input-group-text" style="font-size: 85%">From</span>
                            </div>
                            <input type="date" class="form-control" name="date_from" value ="<?= $date_from ?>">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="input-group mb-3 ">
                             <div class="input-group-prepend">
                               <span class="input-group-text" style="font-size: 85%">To</span>
                            </div>
                            <input type="date" class="form-control" name="date_to" value ="<?= $date_to ?>">
                          </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" required class="form-control" name="name" placeholder="Name *" value ="<?= $name ?>">
                        <?= form_error('name') ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <textarea class="form-control"  name="address" placeholder="Address"><?= $address ?></textarea>
                        <?= form_error('address') ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                            <input class="form-control" required type="text" name="phone" placeholder="Phone *"  value="<?= $phone ?>">
                            <?= form_error('phone') ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-10">
                            <input name="btnSrvBooking" value="Booking" type="hidden">
                            <a href="#" id="btnSrvBooking" class="btn btn-fill-out" style="margin-left:0px">Confirm Booking</a>
                        </div>
                    </div>  
                    <hr />
                </form>
            </div>
        </div>
        <div class="row">
        	<div class="col-12">
            	<div class="large_divider clearfix"></div>
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
    $('#btnSrvBooking').click(function(e){
        e.preventDefault();
        var customerId = '<?= $customer_id ?>';
        if ( parseInt(customerId) == 0){
            alert("Please login to continue !");
            window.location.href= "<?= base_url('login.php') ?>";
            return false;
        }else{
            $("#frmServiceBooking").submit();
        }
        
    });
</script>