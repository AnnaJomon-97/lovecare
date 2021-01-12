<?php

require_once '../config/config.php';
/*
* Check user is logined
*/
$shop_staff_id = $session->get_userdata('shop_staff_id');
if( !$shop_staff_id && ! ( $shop_staff_id > 0) ){
    $session->set_flashdata('msg',alert('Please login to staff panel!','success'));
    redirect('staff/login.php');
} 
/*
* ### End login check
*/
$process = ( $input->get('option') == 'form' )  ? 'FORM' : 'LIST' ;
/*
* Save or Update data into database starts
*/
$id = $employee_id ="";
$assign_date = date('Y-m-d');
$remarks = "";
$orders_items =[];
$order = null;
$view = (int)$input->get('view');
if( isset( $_POST['btnSaveDelivery'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('delivery_id','Id','required','Please enter name');
    $form_validation->set_rules('delivery_date','Date','required');
    $form_errors =  $form_validation->run();
    /*
    * ### End form validation
    */
    $delivery_id = (int) $input->post('delivery_id');
    $delivery_date = $input->post('delivery_date');
    $status = '2'; 
    if( count($form_errors) >0 ){
        $process = 'FORM';
        $view = $delivery_id;
        goto htmlView;
    }else{

        $sql = "UPDATE `deliveries` SET `delivery_date` = '".$delivery_date."',`status` ='".$status."' WHERE id= ".$delivery_id;
        if ( $db->execute($sql) ){

            $order_id = $db->get_colum_value('deliveries','order_id',array('id' => $delivery_id ));

            $q1 = "SELECT oi.`item_id`,oi.`quantity` FROM order_items oi 
            WHERE oi.`order_id`='". (int) $order_id ."'";
            $order_items = $db->execute_get($q1);

            foreach ($order_items  as $key => $items) {
                $q = " UPDATE items SET stock =stock-".$items['quantity']." WHERE id=".$items['item_id'];
                $db->execute($q);
            }
            $sql = "UPDATE `orders` SET `status`= '3' WHERE id =".$order_id;
            $db->execute($sql);
            $session->set_flashdata('msg',alert('Data saved successfully','success'));
        }else{
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
        }
        redirect('staff/assignments.php?view='.$delivery_id);
    }
}
/*
*  ### END Save or Update data into database
*/

$undelivery = (int)$input->get('undelivery');
if( $undelivery  > 0 ){

    $sql = "UPDATE `deliveries` SET `delivery_date` = '',`status` ='1' WHERE id= ".$undelivery;

    if ( $db->execute($sql) ){
        $order_id = $db->get_colum_value('deliveries','order_id',array('id' =>$undelivery ));
        $q = "UPDATE orders SET status ='2' WHERE id=".(int)$order_id ;
        $db->execute( $q ) ;
        $session->set_flashdata('msg',alert('Marked as Undelivered','success'));
    }else{
        $session->set_flashdata('msg',alert('Couldn\'t update the data !','success'));
    }
    redirect('staff/assignments.php');
}
/*
*  Strats load data to edit
*/
if( $view > 0 ){
    echo $q = "SELECT  o.*,c.`name` AS customer_name,(SELECT SUM(quantity * price ) FROM order_items oitm WHERE oitm.`order_id` = o.`id` ) AS order_total FROM `deliveries` d
        LEFT JOIN `orders` o ON d.`order_id` = o.`id`
        LEFT JOIN `customers` c ON o.`customer_id` = c.`id`
        WHERE d.`id`='".$view."'";
        $order = $db->execute_get_row( $q );
        if ( $order ){
            $id = $order->id;
            if ( $id < 1 ){
              redirect('staff/assignments.php');
            }
            $process = 'FORM';
            $q = "SELECT oitm.*,itm.`name` AS item_name  FROM `order_items` oitm 
            LEFT JOIN items itm ON oitm.`item_id` = itm.`id`
            WHERE oitm.`order_id` = ".$id ;
            $orders_items = $db->execute_get($q);
        }else{
            redirect('staff/assignments.php');
        }
}
/*
*  ### End load data to edit
*/

$del_status = (int)$input->get('status');
$del_status = $del_status >  0 ? $del_status : 1 ;
htmlView:
if ($process != 'FORM'){

    $q = "SELECT d.`id` AS delivery_id,d.`assign_date`, d.`status` AS delivery_status, o.`id` AS order_id,o.`cancelled`, o.`date` AS order_date,o.`name`,o.`address`,o.`phone`,c.`name` AS customer_name,(SELECT SUM(quantity * price ) FROM order_items oitm WHERE oitm.`order_id` = o.`id` ) AS order_total FROM
     `deliveries` d 
    LEFT JOIN `orders` o ON d.`order_id` = o.`id`
    LEFT JOIN `customers` c ON o.`customer_id` = c.`id`
    WHERE d.`employee_id`=".(int)$shop_staff_id ." AND d.status = '".$del_status."' ORDER BY d.`id` DESC";
    $rec_list = $db->execute_get( $q );
}else{
    if($order == null){
        redirect('staff/assignments.php');
    }
    $employees =  $db->get('employees');
    $assignmet = $db->get_row('deliveries',array('id' =>  $view));
}

include_once 'header.php';
?>



<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 40px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
          <div class="col-md-6">
                <div class="page-title">
                <h1>Assignments</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('staff/index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item" ><a  href="<?= base_url('staff/assignments.php') ?>"> Assignments</a> </li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section" style="padding:40px 0px">
  <div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
             <?= $session->get_flashdata('msg') ?>
        </div>
    </div>

       
    <?php  if ($process == 'FORM') {?>
    <div class="row">
        <div class="col-sm-6">
            <h2>Order details</h2>
            <table class="table table-bordered" style="width: 100%">
            <tr>
                <td style="width: 30%">Order No : </td>
                <td style="width: 70%"><?= $order->id ?></td>
              </tr>
              <tr>
                <td style="width: 30%">Customer : </td>
                <td style="width: 70%"><?= $order->customer_name ?></td>
              </tr>
              <tr>
                <td style="width: 30%">Date : </td>
                <td style="width: 30%"><?= date('d-m-Y',strtotime( $order->date ))?></td>
              </tr>
              <tr>
                <td style="width: 30%">Address : </td>
                <td style="width: 30%"><?= $order->address ?></td>
              </tr>
              <tr>
                <td style="width: 30%">Phone : </td>
                <td style="width: 30%"><?= $order->phone ?></td>
              </tr>
            </table>
        </div>
         <div class="col-sm-6">
            <h2>Add delivery details</h2>
            <?php 
            $status = 0;
            if ( $assignmet ) {
               $id =  $assignmet->id;
               $employee_id =  $assignmet->employee_id;
               $assign_date =  $assignmet->assign_date;
               $delivery_date =  $assignmet->delivery_date;
               $remarks =  $assignmet->remarks;
               $status =  $assignmet->status;
            }
            ?> 
            <form class="form-horizontal" method="post" action="<?= base_url('staff/assignments.php') ?>"  >
                <input type="hidden" name="delivery_id" value="<?= $id ?>">
                <fieldset <?= $order->cancelled == '1' ?'disabled' :'' ?>>
                <table class="table table-bordered">
                    <tr>
                        <td style="width: 30%">Status</td>
                        <td style="width: 70%">
                          <?php 
                          
                          if ($order->cancelled == '1'){
                             echo '<div class="well" style="color:red">
                                  Cancelled
                              </div>';
                          }else if ($status == '1'){
                              echo '<div class="well" style="color:red">
                                  Delivery pending !
                              </div>';
                          }else if ($status == '2'){
                              echo '<div class="well" style="color:green">
                                  Delivered   
                              </div>';
                          } ?>
                        </td> 
                    </tr>
                    <tr>
                        <td style="width: 30%">Assigned to</td>
                        <td style="width: 70%"><?= date('d-m-Y',strtotime($assign_date )) ?></td> 
                    </tr>
                    <tr>
                        <td>Remarks</td>
                        <td><?= $remarks ?></td> 
                    </tr>
                    <tr >
                        <td>Delivery on</td>
                        <td><input type="date" name="delivery_date" value="<?= $delivery_date ?>" ></td> 
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" name="btnSaveDelivery" class="btn btn-success" value="Save Delivery" >
                        <?php if ($status == '2'){?>
                        <a href="<?= base_url('staff/assignments.php?undelivery='.$id) ?>" >Mark as Undelivered</a>
                        <?php } ?>
                        </td> 
                    </tr>
                </table>
                </fieldset>
            </form>
         </div>
    </div>
    <div class="row">
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
                <?php 
                  $grand_total = 0 ;
                if (count ($orders_items) == 0) {?>
                    <tr class="temp_row">
                        <td colspan="5">No rows added !</td> 
                    </tr>
                <?php }else{
                  
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
   
                    
    <!-- /.row -->
<?php }else{ ?>
    
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
             <?= $session->get_flashdata('msg') ?>
        </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            List of orders 
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
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
                            <th style="width: 10%">Assigned to</th>
                            <th style="width: 10%">Customer</th>
                            <th style="width: 28%">Delivery address</th>
                            <th style="width: 20%">Order total</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 8%"></th>
                           
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($rec_list as $key => $row) { ?>
                        <tr class="odd gradeX">
                          <td><?=  ($key+1) ?></td>
                          <td><?= date('d-m-Y',strtotime($row['order_date']))  ?></td>
                          <td><?= date('d-m-Y',strtotime($row['assign_date']))  ?></td>
                          <td><?= $row['customer_name']  ?></td>
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
                                }else if ( $row['delivery_status'] == '2' ){
                                    echo get_label('Delivered !','info');
                                    
                                }else  {
                                     echo get_label('Not delivered !','warning');
                                }
                            ?>
                          </td>
                          <td><a href="<?= base_url('staff/assignments.php?view='.$row['delivery_id'] ) ?>" class="btn btn-sm btn-default"> <i class="fa fa-list"></i> View </a></td>
                        </tr>
                      <?php } ?>
                        </tbody>
                    </table>
                <?php }?>         
              </div>
            </div>
            <!-- /.row (nested) -->
          </div>
          <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
      </div>
      <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
<?php } ?>
</div>
</div>
<!-- /#page-wrapper -->
<?php 
include_once 'footer.php';
?>

