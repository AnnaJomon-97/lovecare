<?php
### TOP
//SELECT `id`, `supplier_id`, `date` FROM `purchase` WHERE 1
//SELECT `id`, `purchase_id`, `item_id`, `purchase_rate`, `quantity` FROM `purchase_items` WHERE 1
//SELECT `id`, `name`, `address`, `email`, `phone` FROM `suppliers` WHERE 1

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
$id = $supplier_id ="";
$date = date('Y-m-d');
$purchase_items =[];
$disc_type = "PERCENATGE";
if( isset( $_POST['btnSave'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('date','Date','required','Please enter name');
    $form_errors =  $form_validation->run();
    /*
    * ### End form validation
    */
    $id = (int) $input->post('id');
    $supplier_id = $input->post('supplier_id');
    $date = $input->post('date');
    $ar_items = isset($_POST['item_id']) ? $_POST['item_id'] : [];

    if( count ($ar_items) == 0 ){
        $form_errors['items'] = 'Please add purchase items !';
    }
    if( count($form_errors) >0 ){
       
        $process = 'FORM';
        goto htmlView;
    }else{
        
        if ( $id > 0 ){
            $sql = "UPDATE `purchase` SET `supplier_id`= '".$supplier_id."',`date`= '".$date."' WHERE id =".$id;
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Data updated successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t updated the data !','danger'));
            }

            $db->delete('purchase_items',array('purchase_id' => $id ));

        }else{
            $sql = "INSERT INTO `purchase`(  `supplier_id`,`date`) VALUES ('".$supplier_id."','".$date."')";
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Data saved successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
            }
            $id =  $db->get_insert_id();   

        }
        $i =0 ;
        foreach ($ar_items as $key => $item_id) {
            $purchase_rate = isset($_POST['purchase_rate'][$i]) ? $_POST['purchase_rate'][$i] : 0;
            $quantity = isset($_POST['quantity'][$i]) ? $_POST['quantity'][$i] : 0;
            $q = "INSERT INTO `purchase_items`( `purchase_id`, `item_id`, `purchase_rate`, `quantity`) VALUES ('".$id."','".$item_id."','".$purchase_rate."','".$quantity."')";
            $db->execute($q);  
            $i++;
        }

        redirect('admin/purchase.php');
    }
}
/*
*  ### END Save or Update data into database
*/

/*
*  Strats load data to edit
*/
$edit = (int)$input->get('edit');
if( $edit > 0 ){
    $edit_rec = $db->get_row('purchase',array('id' => $edit));
    if ( $edit_rec ){
        $id = $edit_rec->id;
        $supplier_id = $edit_rec->supplier_id;
        $date = $edit_rec->date;
        $process = 'FORM';
        $q = "SELECT pitm.*,itm.`name` AS item_name  FROM `purchase_items` pitm 
        LEFT JOIN items itm ON pitm.`item_id` = itm.`id`
        WHERE pitm.`purchase_id`=". $edit ;
        $purchase_items = $db->execute_get($q);
    }
}
/*
*  ### End load data to edit
*/

/*
*  Strats record delete
*/
$del = (int)$input->get('del');
if ( $del > 0 ) {
    

    $del_rec = $db->delete('purchase',array('id' => $del));
    if ( $del_rec ){
        $db->delete('purchase_items',array('purchase_id' => $del));
        $session->set_flashdata('msg',alert('Data deleted successfully','success'));
    }else{
        $session->set_flashdata('msg',alert('Could n\'t delete the data !','danger'));
    }  
    redirect('admin/purchase.php');
}
/*
*  ### End record delete
*/
htmlView:
if ($process != 'FORM'){
    $q = "SELECT p.*,s.`name` AS supplier_name,(SELECT SUM(quantity * purchase_rate ) FROM purchase_items pitm WHERE pitm.`purchase_id` = p.`id` ) AS purchase_total FROM `purchase` p 
    LEFT JOIN `suppliers` s ON p.`supplier_id` = s.`id`
    WHERE 1 ORDER BY p.`id` DESC";
    $rec_list = $db->execute_get( $q );
}else{
    $items =  $db->get('items');
    $suppliers =  $db->get('suppliers');
}

include_once 'header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Purchase </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <?php  if ($process == 'FORM') {?>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <p><?= $id >0 ? 'Edit ' : 'Add ' ?> Purchase</p>
          </div>
          <div class="panel-body">
            <div class="row">
             
              <div class="col-lg-12">
                <form class="form-horizontal" method="post" action="<?= base_url('admin/purchase.php') ?>" enctype="multipart/form-data" >
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <div class="form-group">
                    <label class="control-label col-sm-3">Supplier : </label>
                    <div class="col-sm-4">
                      <select class="form-control" name="supplier_id" id="supplier_id">
                        <option value="">Select</option>
                        <?php foreach( $suppliers as $row ){ 
                        $selected = $supplier_id == $row['id'] ?'selected="seletced"':''; ?>
                        <option value="<?=$row['id'] ?>" <?= $selected ?> ><?= $row['name'] ?></option>
                        <?php } ?>
                      </select>
                     <?= form_error('supplier_id'); ?>
                    </div>
                    <label class="control-label col-sm-2" >Date:</label>
                    <div class="col-sm-3">
                      <input type="date" class="form-control" name="date" value="<?= $date ?>">
                      <?= form_error('date') ?>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label class="control-label col-sm-2" >Item:</label>
                    <div class="col-sm-4">
                    <select class="form-control" id="item_id">
                        <option value="">Select</option>
                        <?php foreach( $items as $row ){ ?>
                        <option value="<?=$row['id'] ?>" <?= $selected ?>><?= $row['name'] ?></option>
                        <?php } ?>
                    </select>
                    <?= form_error('items'); ?>
                    </div>
                    <div class="col-sm-2">
                       <input type="text" class="form-control" placeholder="Quantiy" id="quantity">
                       <?= form_error('quantity'); ?>
                    </div>  
                    <div class="col-sm-2">
                      <input type="text" class="form-control" placeholder="Purchase rate" id="purchase_rate" >
                     <?= form_error('purchase_rate'); ?>
                     </div>
                     <div class="col-sm-2">
                        <a href="#" class="btn btn-success " id="addPurchRow" > Add </a>
                     </div>
                  </div> 
                  <div class="form-group">
                    <div class="col-sm-12">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="tblPurchase" style="margin-bottom: 5px;">
                      <thead> 
                        <tr>
                            <th style="width: 4%">Sl.No</th>
                            <th style="width: 20%">Item</th>
                            <th style="width: 40%">Quantity</th>
                            <th style="width: 20%">Purchase  rate</th>
                            <th style="width: 8%"></th>
                        </tr>
                      </thead>
                      <tbody id="purchaseTbody">
                        <?php if (count ($purchase_items) == 0) {?>
                            <tr class="temp_row">
                                <td colspan="5">No rows added !</td> 
                            </tr>
                        <?php }else{
                            foreach ($purchase_items as $key => $row) { ?>
                                <tr>
                                    <td><span class="slno"><?= $key+1 ?></span></td>
                                    <td>
                                    <input type="hidden" name="item_id[]" value="<?= $row['item_id'] ?>">
                                    <input type="hidden" name="quantity[]" value="<?= $row['quantity'] ?>">
                                    <input type="hidden" name="purchase_rate[]" value="<?= $row['purchase_rate'] ?>">
                                    <?= $row['item_name'] ?>
                                    </td>
                                    <td><?= $row['quantity'] ?></td>
                                    <td><?= $row['purchase_rate'] ?></td>
                                    <td><a class="btn btn-default btn-sm remove_row" data-id ="<?= $row['id'] ?>" >Delete</a></td>
                                </tr>
                            <?php }
                        } ?>
                      </tbody>
                    </table>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-2 col-sm-offset-10">
                        <input type="submit" name="btnSave"  class="btn btn-success" value="Save Entry">
                    </div>
                  </div>
                  </div> 
                </form>
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
<?php }else{ ?>
    <div  class="row" >
      <div class="col-lg-12 " style="margin-bottom: 10px">
        <a href="<?= base_url('admin/purchase.php?option=form') ?>" class =" btn btn-info pull-right"><i class="fa fa-plus "></i> Add Purchase </a>
      </div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
             <?= $session->get_flashdata('msg') ?>
        </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            List of Purchase 
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
                            <th style="width: 20%">Date</th>
                            <th style="width: 40%">Supplier</th>
                            <th style="width: 20%">Purchase.total</th>
                            
                            <th style="width: 8%"></th>
                            <th style="width: 8%"></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($rec_list as $key => $row) { ?>
                        <tr class="odd gradeX">
                          <td><?=  ($key+1) ?></td>
                          <td><?= date('d-m-Y',strtotime($row['date']))  ?></td>
                          <td><?= $row['supplier_name']  ?></td>
                          <td><?= number_format($row['purchase_total'],2)  ?></td>
                          <td><a href="<?= base_url('admin/purchase.php?edit='.$row['id'] ) ?>" class="btn btn-sm btn-default"> <i class="fa fa-edit"></i> Edit </a></td>
                          <td><a onclick="if (confirm('Delete record ?')) { window.location.href='<?= base_url('admin/purchase.php?del='.$row['id'] ) ?>';}" class="btn btn-sm btn-default"> <i class="fa fa-trash"></i> Delete </a></td>
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
        var purchaseRate =    $('#purchase_rate').val();
        if(itemId == ""){
            alert("Enter item ");
            return false;
        }
        if(quantity == ""){
            alert("Enter quantity ");
            return false;
        }
        if(purchaseRate == ""){
            alert("Enter purchase rate ");
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
                '<input type="hidden" name="purchase_rate[]" value="'+purchaseRate+'">'+
                itemName+'</td>'+
                '<td>'+quantity+'</td>'+
                '<td>'+purchaseRate+'</td>'+
                '<td><a class="btn btn-default btn-sm remove_row" data-id="0" >Delete</a></td>'+
                '</tr>'
                ;
                $("#purchaseTbody").append(html);
                $('#item_id').val('');
                $('#quantity').val('');
                $('#purchase_rate').val('');
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
            if ( $("#purchaseTbody tr").length == 0 ){
                $("#purchaseTbody").html('<tr class="temp_row">'+
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
