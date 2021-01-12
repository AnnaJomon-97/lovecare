<?php

require_once '../config/config.php';
/*
* Check user is logined
*/
$admin_userid = $session->get_userdata('admin_userid');
if( !$admin_userid && ! ( $admin_userid > 0) ){
    $session->set_flashdata('msg','Please login to admin panel!');
    redirect('admin/login.php');
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
if( isset( $_POST['btnAssignDelivery'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('employee_id','Employee','required','Please enter name');
    $form_validation->set_rules('assign_date','Date','required');
    $form_errors =  $form_validation->run();

    /*
    * ### End form validation
    */
    $id = (int) $input->post('id');
    $order_id = (int) $input->post('order_id');
    $employee_id = $input->post('employee_id');
    $remarks = $input->post('remarks');
    $status = '1'; 
    $assign_date = $input->post('assign_date');
    if( count($form_errors) >0 ){
        $process = 'FORM';
        goto htmlView;
    }else{
        if ( $id > 0 ){
            $sql = "INSERT INTO `deliveries`(`order_id`, `employee_id`, `assign_date`, `status`, `remarks`) VALUES ('".$order_id."','".$employee_id."','".$assign_date."','".$status."','".$remarks."')";
            if ( $db->execute($sql) ){
                $sql = "UPDATE `orders` SET `status`= '2' WHERE id =".$order_id;
                $db->execute($sql);
                $session->set_flashdata('msg',alert('Data saved successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
            }
        }else{
            $sql = "UPDATE `deliveries` SET `employee_id` ='".$employee_id."', `assign_date` = '".$assign_date."', `remarks` = '".$remarks."' WHERE id= ".$id;
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Data saved successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
            }

        }


        
        redirect('admin/orders.php?view='.$order_id);
    }
}
/*
*  ### END Save or Update data into database
*/

/*
*  Strats load data to edit
*/
$view = (int)$input->get('view');

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
            WHERE oitm.`order_id`= ".$view;
            $orders_items = $db->execute_get($q);
        }else{
            redirect('admin/orders.php');
        }
}
/*
*  ### End load data to edit
*/


htmlView:
if ($process != 'FORM'){

    $q = "SELECT o.*,c.`name` AS customer_name,(SELECT SUM(quantity * price ) FROM order_items oitm WHERE oitm.`order_id` = o.`id` ) AS order_total FROM `orders` o 
    LEFT JOIN `customers` c ON o.`customer_id` = c.`id`
    WHERE 1 ORDER BY o.`id` DESC";
    $rec_list = $db->execute_get( $q );
}else{
    if($order == null){
        redirect('admin/orders.php');
    }
    $employees =  $db->get('employees');
    $assignmet = $db->get_row('deliveries',array('order_id' =>  $view));
}

include_once 'header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Orders </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <?php  if ($process == 'FORM') {?>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
             <?= $session->get_flashdata('msg') ?>
        </div>
      <div class="col-lg-12">

        <div class="panel panel-default">
          <div class="panel-heading">
            <p>Order details</p>
          </div>

          <div class="panel-body">
            <div class="row">
             
              <div class="col-lg-12">
                <form class="form-horizontal" method="post" enctype="multipart/form-data" >
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <div class="form-group">
                    <label class="control-label col-sm-3">Customer : </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control"  value="<?= $order->customer_name ?>" readonly >
                       
                    </div>
                    <label class="control-label col-sm-2" >Date:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control"  value="<?= date('d-m-Y',strtotime( $order->date ))?>" readonly >
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-3">Address : </label>
                    <div class="col-sm-4">
                        <textarea class="form-control" readonly="" ><?= $order->address ?></textarea>

                    </div>
                    <label class="control-label col-sm-2" >Phone:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control"  value="<?=  $order->phone  ?>" readonly >
                    </div>
                  </div>
                  <hr>
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
                </form>
              </div>
             </div>
            </div>
          </div>
         </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <p>Delivery Assigment <span class="pull-right"><?= $assignmet ? get_label('Assigned','success') :  get_label('Not assigned','warning')  ?></span> </p>
                  <div class="pull-right"></div>
                  <?php 
                  $status = 0;
                  $delivery_date = "";
                  if ( $assignmet ) {
                     $id =  $assignmet->id;
                     $employee_id =  $assignmet->employee_id;
                     $assign_date =  $assignmet->assign_date;
                     $remarks =  $assignmet->remarks;
                     $status =  $assignmet->status;
                     $delivery_date =  $assignmet->delivery_date;
                     
                  }
                  ?>
              </div>
              <div class="panel-body">
                <form class="form-horizontal" method="post" action="<?= base_url('admin/orders.php') ?>"  >
                <input type="hidden" name="order_id" value="<?= $order->id ?>">
                <input type="hidden" name="id" value="<?= $order->id ?>">
                  <fieldset <?=  $status == '2' || $order->cancelled=='1' ? 'disabled' : '' ?>>
                <div class="form-group">
                  <label class="control-label col-sm-3" >Staff:</label>
                  <div class="col-lg-9">
                      <select class="form-control" name="employee_id" id="employee_id" >
                      <option value="">Select</option>
                       <?php foreach($employees as $row){ 
                        $selected = $employee_id == $row['id'] ?'selected="seletced"':''; ?>
                       <option value="<?=$row['id'] ?>" <?= $selected ?>><?= $row['name'] ?></option>
                       <?php } ?>
                       </select>
                       <?= form_error('employee_id'); ?>
                   </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-sm-3" >Date:</label>
                      <div class="col-lg-9">
                          <input type="date" name="assign_date" class="form-control" value="<?= $assign_date ?>">
                           <?= form_error('assign_date'); ?>
                       </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-sm-3" >Remarks:</label>
                      <div class="col-lg-9">
                        <textarea class="form-control" name="remarks"><?= $remarks ?></textarea>
                           <?= form_error('remarks'); ?>
                       </div>
                  </div>
                  <div class="form-group">
                     <div class="col-md-9 col-md-offset-3">
                         <input type="submit" class="btn btn-success" name="btnAssignDelivery" value="Save Assigment">
                    </div>
                  </div>
                </fieldset>
                  </form>
                </div>
                    
              </div>
          </div>
          <?php if( $assignmet ){ ?>
          <div class="col-lg-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                <p>Order status</p>
              </div>
              <div class="panel-body">
                <div class="col-sm-12">
                    <?php
                    if ($order->cancelled == '1'){
                        echo '<div class="well" style="color:red">
                            Order cancelled !
                        </div>'; 
                    }else if ($status == '1'){
                        echo '<div class="well">
                            Delivery pending !
                        </div>';
                    }else if ($status == '2'){
                        echo '<div class="well">
                            Delivered on ' .  ($delivery_date != "" ?date('d-m-Y',strtotime($delivery_date)): "") .
                        '</div>';
                    } ?>
                </div>
               

              </div>
            </div>
          </div>
          <?php } ?>
           
        </div>
          
          <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
      </div>
      <!-- /.col-lg-12 -->
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
                            <th style="width: 20%">Customer</th>
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
                          <td><?= date('d-m-Y',strtotime($row['date']))  ?></td>
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
                                }else if ( $row['status'] == '1' ){
                                    echo get_label('Not confirmed !','warning');
                                }else if( $row['status'] == '2' ) {
                                     echo get_label('Confirmed !','info');
                                }else if( $row['status'] == '3' ) {
                                     echo get_label('Deliverd !','success');
                                }
                            ?>
                          </td>
                          <td><a href="<?= base_url('admin/orders.php?view='.$row['id'] ) ?>" class="btn btn-sm btn-default"> <i class="fa fa-list"></i> View </a></td>
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
<!-- /#page-wrapper -->
<?php 
include_once 'footer.php';
?>

<script type="text/javascript">
    $('#addPurchRow').click(function(){
        var itemId =    $('#item_id').val();
        var quantity =    $('#quantity').val();
        var ordersRate =    $('#orders_rate').val();
        if(itemId == ""){
            alert("Enter item ");
            return false;
        }
        if(quantity == ""){
            alert("Enter quantity ");
            return false;
        }
        if(ordersRate == ""){
            alert("Enter orders rate ");
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo  base_url('ajax.php');?>",
            data: {
                item_id : itemId,
                process : 'GET_ITEM_ROW',
            },
            beforeSend: function(){
                $("#divPreLoader").show();
            },
            complete:function(){
                $("#divPreLoader").hide();
            }
        })
        .done(function( response ) {
            if ( response.status != null && response.status == '1' ){
                var itemName = response.item.name ;
                if ( $(".temp_row").length > 0 ){
                    $(".temp_row").remove();
                }
                var slno = $('span.slno').length + 1;
                var html = '<tr>'+
                '<td><span class="slno">'+slno+'</span></td>'+
                '<td>'+
                '<input type="hidden" name="item_id[]" value="'+itemId+'">'+
                '<input type="hidden" name="quantity[]" value="'+quantity+'">'+
                '<input type="hidden" name="orders_rate[]" value="'+ordersRate+'">'+
                itemName+'</td>'+
                '<td>'+quantity+'</td>'+
                '<td>'+ordersRate+'</td>'+
                '<td><a class="btn btn-default btn-sm remove_row" data-id="0" >Delete</a></td>'+
                '</tr>'
                ;
                $("#ordersTbody").append(html);
                $('#item_id').val('');
                $('#quantity').val('');
                $('#orders_rate').val('');
            }
        });
    });
    $(document).on ("click","a.remove_row",function(e){
        e.preventDefault();
        if (confirm('Delete row ?') ){
            if ( $(this).data('id')  != null &&  $(this).data('id')  != 0 ){
                var purchItemId = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "<?php echo  base_url('ajax.php');?>",
                    data: {
                        purch_item_id : purchItemId,
                        process : 'DELETE_PURCH_ITEM',
                    },
                    beforeSend: function(){
                        $("#divPreLoader").show();
                    },
                    complete:function(){
                        $("#divPreLoader").hide();
                    }
                })
                .done(function( response ) {
                    if (response.status != '1' ){
                        alert('Error. couldn\'t delete the record !');
                        return false;
                    }
                    
                });
            }
            $(this).parents('tr').remove();
            if ( $("#ordersTbody tr").length == 0 ){
                $("#ordersTbody").html('<tr class="temp_row">'+
                            '<td colspan="5">No rows added !</td>'+
                            '</tr>'); 
            }
            fill_slno();
        }
    });
    function fill_slno(){
        $.each($("span.slno"),function(i,e){
            $(this).text(i+1);
        });
    }
</script>
