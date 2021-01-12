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
$process ="";
if ( isset($_GET['id']) &&  isset( $_GET['status'] )){
    $id = (int)$_GET['id'];
    $status = (int)$_GET['status'];
    $q = "UPDATE service_booking SET status ='".$status."' WHERE id= '".$id."'";
    if ( $db->execute( $q ) ){        
        $session->set_flashdata('msg',alert('Status changed successfuly !','success'));
    }else{
        $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
    }
    redirect('admin/service-bookings.php');
}

htmlView:
$q = "SELECT srvbk.*,c.`name` AS customer_name,srv.`name` AS service_name FROM service_booking srvbk 
LEFT JOIN `services` srv ON srvbk.`service_id` = srv.`id`
LEFT JOIN `customers` c ON srvbk.`customer_id` = c.`id`
WHERE 1 ORDER BY srvbk.`id` DESC";
$rec_list = $db->execute_get( $q );
include_once 'header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Service Bookings </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
  
    
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
             <?= $session->get_flashdata('msg') ?>
        </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            List of Service Bookings 
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
                            <?php
                            if($row['cancelled'] != '1'){ ?>
                            <div class="dropdown">
                              <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action
                              <span class="caret"></span></button>
                              <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="<?= base_url('admin/service-bookings.php?id='.$row['id'].'&status=1' ) ?>">Pending</a></li>
                                <li><a href="<?= base_url('admin/service-bookings.php?id='.$row['id'].'&status=2' ) ?>">Accept</a></li>
                                <li><a href="<?= base_url('admin/service-bookings.php?id='.$row['id'].'&status=3' ) ?>">Reject</a></li>
                              </ul>
                            </div>
                            <?php } ?>
                          </td>
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
