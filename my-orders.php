<?php
require_once 'config/config.php';

$process = ( $input->get('option') == 'form' )  ? 'FORM' : 'LIST' ;
/*
*  Strats load data to edit
*/
$view = (int)$input->get('view');
if( isset( $_POST['btnCancelOrder'] ) ){
    echo $id = (int) $input->post('id');
    $cancellation_reason = $input->post('cancellation_reason');
    $form_validation->set_rules('cancellation_reason','Reason','required');
    $form_errors =  $form_validation->run();
    if( count($form_errors) >0 ){
        $view = $id;
    }else{
        $sql = "UPDATE `orders` SET `cancelled` ='1', `cancellation_reason` = '".$cancellation_reason."' WHERE id= ".$id;
        if ( $db->execute($sql) ){
            $session->set_flashdata('msg',alert('Data saved successfully','success'));
        }else{
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
        }
        redirect('my-orders.php?view='.$id);
    }
}
if( isset( $_POST['btnUpdateOrder'] ) ){
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
        $sql = "UPDATE `orders` SET `name` ='".$name."', `address` = '".$address."', `phone` = '".$phone."' WHERE id= ".$id;
        if ( $db->execute($sql) ){
            $session->set_flashdata('msg',alert('Data saved successfully','success'));
        }else{
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
        }
        redirect('my-orders.php?view='.$id);
    }
}
if( $view > 0 ){
    $q = "SELECT o.*,c.`name` AS customer_name,(SELECT SUM(quantity * price ) FROM order_items oitm WHERE oitm.`order_id` = o.`id` ) AS order_total FROM `orders` o 
        LEFT JOIN `customers` c ON o.`customer_id` = c.`id`
        WHERE o.`id`='".$view."'";
        $order = $db->execute_get_row( $q );
        if ( $order ){
            $id = $order->id;
            $process = 'FORM';
            $q = "SELECT oitm.*,itm.`name` AS item_name  FROM `order_items` oitm 
            LEFT JOIN items itm ON oitm.`item_id` = itm.`id`
            WHERE oitm.`order_id`=".$view;
            $orders_items = $db->execute_get($q);
        }else{
            redirect('my-orders.php');
        }
}
/*
*  ### End load data to edit
*/
htmlView:

$customer_id =  (int)$session->get_userdata('shop_customer_id');
$q = "SELECT o.*,d.`status` AS delivery_status,d.`assign_date`,d.`delivery_date`,(SELECT SUM(quantity * price ) FROM order_items oitm WHERE oitm.`order_id` = o.`id` ) AS order_total FROM `orders` o 
LEFT JOIN `deliveries` d ON d.`order_id` = o.`id`
WHERE o.`customer_id`='".$customer_id."' ORDER BY o.`id` DESC";
$rec_list = $db->execute_get( $q );
require_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding-top: 40px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>My Orders</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('my-account.php') ?>">Account</a></li>
                    <li class="breadcrumb-item"><a  href="<?= base_url('my-orders.php') ?>"> My Orders </a></li>
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
                <?php  if ($process == 'FORM') {?>
                <h3>Order details</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?= base_url('my-orders.php') ?>" >
                    <div class="form-group">
                    <div class="col-sm-12">
                        <fieldset <?= $order->status == 2 || $order->cancelled == '1' ? 'disabled="disabled"':''?>>
                              <input type="hidden" name="id" value="<?= $order->id ?>">
                              <table>
                                <tr>
                                    <td>Order No :</td>
                                    <td>
                                        <?= $order->id  ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date :</td>
                                    <td>

                                        <?= date('d-m-Y',strtotime( $order->date ))?></td>
                                </tr>
                                <tr>
                                    <td>Order Status :</td>
                                    <td>
                                        <?= $order->status =='2' ? 'Delivered' :'Not delivered';  ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Name :</td>
                                    <td>
                                        <input type="text" name="name" value="<?= $order->name ?>">
                                        <?= form_error('name') ?>
                                        </td>
                                </tr>
                                <tr>
                                    <td>Addresss :</td>
                                    <td>
                                        <textarea name="address" style="width: 300px"><?= $order->address ?></textarea>
                                        <?= form_error('address') ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Phone :</td>
                                    <td>
                                        <input type="text" name="phone" value="<?= $order->phone ?>">
                                        <?= form_error('phone') ?>
                                    </td>
                                </tr>
                                <?php if ($order->status != 2 ){?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="submit" name="btnUpdateOrder" value="Update Address" class="btn btn-success">
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
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?= base_url('my-orders.php') ?>" >
                <div class="form-group">
                    <?php 
                    if ( $order->cancelled == '1'){
                        echo '<div class="well " ><h3 style="color:red;">Order cancelled!</h3></div>';
                    }else{?>
                    <div class="col-sm-12">
                        <fieldset <?= $order->status == 2 ? 'disabled="disabled"':''?>>
                              <input type="hidden" name="id" value="<?= $order->id ?>">
                              <table>
                                 <tr>
                                    <td>Reason for cancellation:</td>
                                    <td>
                                        <textarea name="cancellation_reason" style="width: 300px"></textarea>
                                        <?= form_error('cancellation_reason') ?>
                                    </td>
                                </tr>
                               
                                <?php if ($order->status != 2 ){?>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="submit" name="btnCancelOrder" value="Cancel order" class="btn btn-warning">
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


                  <div class="form-group">
                    <div class="col-sm-12">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="tblorders" style="margin-bottom: 5px;">
                      <thead> 
                        <tr>
                            <th style="width: 4%">Sl.No</th>
                            <th style="width: 50%">Item</th>
                            <th style="width: 10%">Quantity</th>
                            <th style="width: 10%">Price</th>
                            <th style="width: 16%">Total</th>  
                        </tr>
                      </thead>
                      <tbody id="ordersTbody">
                        <?php if (count ($orders_items) == 0) {?>
                            <tr class="temp_row">
                                <td colspan="5">No rows added !</td> 
                            </tr>
                        <?php }else{
                            $grand_total = 0 ;
                            foreach ($orders_items as $key => $row) {
                            $grand_total += ($row['price'] * $row['quantity']);
                             ?>
                                <tr>
                                    <td><span class="slno"><?= $key+1 ?></span></td>
                                    <td>
                                    <?= $row['item_name'] ?>
                                    </td>
                                    <td><?= $row['quantity'] ?></td>
                                    <td><?= number_format($row['price'],2)  ?></td>
                                    <td><?= number_format( ($row['price'] * $row['quantity']) ,2) ?></td>
                                </tr>
                            <?php }
                        } ?>
                            <tr>
                                <td colspan="4"></td>
                                <td><?= number_format( $grand_total ,2) ?></td>
                            </tr>
                      </tbody>
                    </table>
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
            <h3>Orders</h3>
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
                        <th style="width: 10%">Order date</th>
                        <th style="width: 28%">Delivery address</th>
                        <th style="width: 20%">Order total</th>
                        <th style="width: 10%">Order status</th>
                        <th style="width: 8%"></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($rec_list as $key => $row) { ?>
                    <tr class="odd gradeX">
                      <td><?=  ($key+1) ?></td>
                      <td><?= date('d-m-Y',strtotime($row['date']))  ?></td>
                      <td>
                        <?= $row['name']."<br>".
                        $row['address']."<br>".
                        $row['phone']."<br>";
                        ?>
                      </td>
                      <td><?= number_format($row['order_total'],2)  ?></td>
                      <td>
                        <?php 
                            if($row['cancelled'] == '1'){
                                echo 'Cancelled';
                            }else if( $row['status'] == '3' ) {
                                echo get_label('Deliverd !','success');
                            }else{
                                echo get_label('Not deliverd !','success');
                            }
                        ?>
                      </td>
                      <td><a href="<?= base_url('my-orders.php?view='.$row['id'] ) ?>" class="btn btn-sm btn-default"> <i class="fa fa-list"></i> View </a></td>
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