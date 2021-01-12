<?php
require_once 'config/config.php';

$process = ( $input->get('option') == 'form' )  ? 'FORM' : 'LIST' ;
/*
*  Strats load data to edit
*/
$view = (int)$input->get('view');
if( isset( $_POST['btnCancelBooking'] ) ){


    echo $id = (int) $input->post('id');
    $cancellation_reason = $input->post('cancellation_reason');
    $form_validation->set_rules('cancellation_reason','Reason','required');
    $form_errors =  $form_validation->run();
    if( count($form_errors) >0 ){
        $view = $id;
    }else{
        $sql = "UPDATE `service_booking` SET `cancelled` ='1', `cancellation_reason` = '".$cancellation_reason."' WHERE id= ".$id;
        if ( $db->execute($sql) ){
            $session->set_flashdata('msg',alert('Data saved successfully','success'));
        }else{
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
        }
        redirect('my-service-bookings.php?view='.$id);
    }
}
if( isset( $_POST['btnUpdateBooking'] ) ){
    $form_validation->set_rules('name','Name','required');
    $form_validation->set_rules('address','Address','required');
    $form_validation->set_rules('phone','Address','required|valid_phone');
    $form_errors =  $form_validation->run();
    $id = (int) $input->post('id');
    $name = $input->post('name');
    $address = $input->post('address');
    $phone = $input->post('phone');
    if( count($form_errors) >0 ){
        $process = 'FORM';
        goto htmlView;
    }else{
        $sql = "UPDATE `service_booking` SET `name` ='".$name."', `address` = '".$address."', `phone` = '".$phone."' WHERE id= ".$id;
        if ( $db->execute($sql) ){
            $session->set_flashdata('msg',alert('Data saved successfully','success'));
        }else{
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
        }
        redirect('my-service-bookings.php?view='.$id);
    }
}
if( $view > 0 ){
    $q = "SELECT srvbk.*,c.`name` AS customer_name,srv.`name` AS service_name FROM service_booking srvbk 
    LEFT JOIN `services` srv ON srvbk.`service_id` = srv.`id`
    LEFT JOIN `customers` c ON srvbk.`customer_id` = c.`id`
    WHERE  srvbk.`id`='".$view."'";
    $booking = $db->execute_get_row( $q );
    if ( $booking ){
        $id = $booking->id;
        $process = 'FORM';
        
    }else{
        redirect('my-service-bookings.php');
    }
}
/*
*  ### End load data to edit
*/
htmlView:

$customer_id =  (int)$session->get_userdata('shop_customer_id');
$q = "SELECT srvbk.*,c.`name` AS customer_name,srv.`name` AS service_name FROM service_booking srvbk 
LEFT JOIN `services` srv ON srvbk.`service_id` = srv.`id`
LEFT JOIN `customers` c ON srvbk.`customer_id` = c.`id`
WHERE srvbk.`customer_id`='".$customer_id."'  ORDER BY srvbk.`id` DESC";
$rec_list = $db->execute_get( $q );
require_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding-top: 40px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>My Service Bookings</h1>
                </div>
                <div style="padding: 10px 0px">
                    <a href="<?= base_url('my-service-bookings.php') ?>" class="btn btn-info btn-sm pull-right" > <i class="ti-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('my-account.php') ?>">Account</a></li>
                    <li class="breadcrumb-item"><a  href="#"> My SErvice Bookings </a></li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section" style="padding:40px 0px" >
	<div class="container">
        <div class="row">
            <div class="col-md-12">
                <?= $session->get_flashdata('msg') ?>
            </div>
        </div>
         <div class="row">
            <div class="col-md-12">

            </div>
        </div>

        
        <div class="row">
            <div class="col-md-12">
        <?php  if ($process == 'FORM') {?>
                
                     <h3>  Booking details</h3>
                
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?= base_url('my-service-bookings.php') ?>" >
                    <div class="form-group">
                    <div class="col-sm-12"> 
                        
                        <fieldset <?= $booking->status == 2 || $booking->cancelled == '1' ? 'disabled="disabled"':''?>>
                              <input type="hidden" name="id" value="<?= $booking->id ?>">
                              <table>
                                <tr>
                                    <td>Booking No :</td>
                                    <td>
                                        <?= $booking->id  ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Booked on :</td>
                                    <td>
                                        <?= date('d-m-Y',strtotime( $booking->created_at ) )  ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date From/To :</td>
                                    <td>
                                    <?php if ( $booking->date_from != "0000-00-00" &&  $booking->date_to != "0000-00-00"  &&  $booking->date_from != $booking->date_to ){
                                       echo date('d-m-Y',strtotime( $booking->date_from) ). " To " .  date('d-m-Y',strtotime( $booking->date_to ) );
                                    }elseif  (  $booking->date_from != "0000-00-00"  ){
                                        echo date('d-m-Y',strtotime($booking->date_from ));
                                    }elseif  (  $booking->date_to != "0000-00-00"  ){
                                        echo date('d-m-Y',strtotime( $booking->date_to ));
                                    } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Booking Status :</td>
                                    <td>
                                        <?php 
                                        if ( $booking->status =='1' ){
                                            echo get_label('Pending','warning');
                                        }elseif ( $booking->status =='2' ) {
                                            echo  get_label('Accepted','success');
                                        }elseif ( $booking->status =='3' ) {
                                            echo get_label('Rejected','danger');
                                        } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Name :</td>
                                    <td>
                                        <input type="text" name="name" value="<?= $booking->name ?>">
                                        <?= form_error('name') ?>
                                        </td>
                                </tr>
                                <tr>
                                    <td>Addresss :</td>
                                    <td>
                                        <textarea name="address" style="width: 300px"><?= $booking->address ?></textarea>
                                        <?= form_error('address') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Phone :</td>
                                    <td>
                                        <input type="text" name="phone" value="<?= $booking->phone ?>">
                                        <?= form_error('phone') ?>
                                    </td>
                                </tr>
                                <?php if ($booking->status != 2 ){?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="submit" name="btnUpdateBooking" value="Update Address" class="btn btn-success">
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-6">
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?= base_url('my-service-bookings.php') ?>" >
                <div class="form-group">
                    <?php 
                    if ( $booking->cancelled == '1'){
                        echo '<div class="well " ><h3 style="color:red;">Order cancelled!</h3></div>';
                    }else{?>
                    <div class="col-sm-12">
                        <fieldset <?= $booking->status == 2 ? 'disabled="disabled"':''?>>
                              <input type="hidden" name="id" value="<?= $booking->id ?>">
                              <table>
                                 <tr>
                                    <td>Reason for cancellation:</td>
                                    <td>
                                        <textarea name="cancellation_reason" style="width: 300px"></textarea>
                                        <?= form_error('cancellation_reason') ?>
                                    </td>
                                </tr>
                               
                                <?php if ($booking->status != 2 ){?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="submit" name="btnCancelBooking" value="Cancel order" class="btn btn-warning">
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                            </fieldset>
                        </div>
                        <?php } ?>
                    </div>
                  </form>
            </div>
        </div>

            </div>
        </div>
       
          
          <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
      </div>
      <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
<?php }else{ ?>
    <div class="card">
        <div class="card-header">
            <h3>Service Bookings</h3>
        </div>
        <div class="card-body">
            <?php 
            if(count( $rec_list) == 0){
                echo '<div class="jumbotron" style="padding:20px">
                  <h3 class="text-center" >No records found !</h3>
                </div>';
            }else{ ?>
              <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                  <thead>
                        <tr>
                            <th style="width: 4%">Sl.No</th>
                            <th style="width: 10%">Booking date</th>
                            <th style="width: 15%">Customer</th>
                            <th style="width: 28%">Address</th>
                            <th style="width: 28%">Service</th>
                            <th style="width: 20%">Date From/To</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 8%"></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($rec_list as $key => $row) { ?>
                        <tr class="odd gradeX">
                          <td><?=  ($key+1) ?></td>
                          <td><?= date('d-m-Y',strtotime($row['created_at']))  ?></td>
                          <td><?= $row['customer_name']  ?></td>
                          <td>
                            <?= $row['name']."<br>".
                            $row['address']."<br>".
                            $row['phone']."<br>";
                            ?>
                          </td>
                          <td><?= $row['service_name']  ?></td>
                          <td><?php 
                          if ( $row['date_from'] != "0000-00-00" &&  $row['date_to'] != "0000-00-00"  &&  $row['date_from'] != $row['date_to'] ){
                               echo date('d-m-Y',strtotime($row['date_from']) ). " To " .  date('d-m-Y',strtotime($row['date_to']) );
                          }elseif  (  $row['date_from'] != "0000-00-00"  ){
                                echo date('d-m-Y',strtotime($row['date_from']));
                          }elseif  (  $row['date_to'] != "0000-00-00"  ){
                                echo date('d-m-Y',strtotime($row['date_to']));
                          }  ?></td>

                          <td>
                            <?php
                                if($row['cancelled'] == '1'){
                                    echo 'Cancelled';
                                }else if ( $row['status'] == '1' ){
                                    echo get_label('Pending !','warning');
                                }else if( $row['status'] == '2' ) {
                                    echo get_label('Accepted !','info');
                                }else if( $row['status'] == '3' ) {
                                    echo get_label('Reject !','success');
                                }
                            ?>
                          </td>
                          <td>
                           <a href="<?= base_url('my-service-bookings.php?view='.$row['id'] ) ?>" class="btn btn-sm btn-default"> <i class="fa fa-list"></i> View </a>
                          </td>
                        </tr>
                      <?php } ?>
                        </tbody>
                    </table>
            <?php }?>  
        </div>
    </div>
<?php } ?>
			</div>
		</div>
	</div>
</div>
<!-- END SECTION SHOP -->
<?php
require_once 'footer.php';
?>