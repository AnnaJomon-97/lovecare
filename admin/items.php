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
$id = $category_id = $subcategory_id = $brand_id = $name = $description = $image = $price =  $disc_percentage = $disc_amount = $stock ="";
$disc_type = "PERCENATGE";
if( isset( $_POST['btnSave'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('subcategory_id','Subcategory','required','Please enter name');
    $form_validation->set_rules('name','Name','required','Please enter name');
    $form_validation->set_rules('price','Price','required','Please enter name');
    $form_errors =  $form_validation->run();
    /*
    * ### End form validation
    */
    $id = (int) $input->post('id');
    $category_id = $input->post('category_id');
    $subcategory_id = $input->post('subcategory_id');
    $brand_id = $input->post('brand_id');
    $name = $input->post('name');
    $description = $input->post('description');
    $image = $input->post('image');
    $price = $input->post('price');
    $disc_type = $input->post('disc_type');
    if( count($form_errors) >0 ){
        $disc_percentage = $input->post('disc_percentage');
        $disc_amount = $input->post('disc_amount');
        $process = 'FORM';
        goto htmlView;
    }else{
        if ( $disc_type == 'PERCENTAGE' ){
            $disc_percentage = (float)$input->post('disc_percentage');
            $disc_amount = $price *  $disc_percentage /100 ;
        }else if ($disc_type == 'AMOUNT') {
            $disc_amount = (float)$input->post('disc_amount');
            $disc_percentage = 0;
            if ( $price > 0 ){
              $disc_percentage = ($disc_amount * 100) / $price ;
            } 
        }
        $stock = $input->post('stock');
        if( $_FILES['fl_image']['name'] != "" ) {
            $upload->config['max_size'] = 3000000;
            $upload->config['allowed_types'] = 'jpg|png|jpeg|gif';
            $upload->config['upload_path'] = 'uploads/items/';
            if ( $upload->do_upload('fl_image') ){
                if(file_exists(base_path( $image ) )){
                    unlink(base_path( $image ));
                }
                $image = $upload->upload_info['file_name'];
            }else{
                $form_errors['image'] = $upload->upload_info['error']; 
                $process = 'FORM';
                goto htmlView;
            }
        }
        if ( $id > 0 ){
            $sql = "UPDATE `items` SET `subcategory_id`= '".$subcategory_id."',`brand_id`= '".$brand_id."',`name`= '".$name."',`description`='".$description."',`image`='".$image."',`price`='".$price."',`disc_type`='".$disc_type."',`disc_percentage`='".$disc_percentage."',`disc_amount`='".$disc_amount."',`stock`='".$stock."' WHERE id =".$id;
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Data updated successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t updated the data !','danger'));
            }
        }else{

            $sql = "INSERT INTO `items`(  `subcategory_id`,`brand_id`, `name`, `description`, `image`,`price`,`disc_type`,`disc_percentage`,`disc_amount`,`stock` ) VALUES ('".$subcategory_id."','".$brand_id."','".$name."','".$description."','".$image."','".$price."','".$disc_type."','".$disc_percentage."','".$disc_amount."','".$stock."')";
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Data saved successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
            }   
        }
        redirect('admin/items.php');
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
    $edit_rec = $db->get_row('items',array('id' => $edit));
    if ( $edit_rec ){
        $id = $edit_rec->id;
        $category_id = $db->get_colum_value('item_subcategories','category_id',array('id' => $id ));
        $subcategory_id = $edit_rec->subcategory_id;
        $brand_id = $edit_rec->brand_id;
        $name = $edit_rec->name;
        $description = $edit_rec->description;
        $image = $edit_rec->image;
        $price = $edit_rec->price;
        $disc_type = $edit_rec->disc_type;
        $disc_percentage = $edit_rec->disc_percentage;
        $disc_amount = $edit_rec->disc_amount;
        $stock = $edit_rec->stock;
        $process = 'FORM';
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
    $del_row = $db->get_row('items',array('id' => $del));
    if ($del_row){
        $image = $del_row->image;
        if (file_exists(  base_path($image) )){
            unlink( base_path($image) );
        }
    }

    $del_rec = $db->delete('items',array('id' => $del));
    if ( $del_rec ){
        $session->set_flashdata('msg',alert('Data deleted successfully','success'));
    }else{
        $session->set_flashdata('msg',alert('Could n\'t delete the data !','danger'));
    }  
    redirect('admin/items.php');
}
/*
*  ### End record delete
*/
htmlView:
if ($process != 'FORM'){
    $q = "SELECT itm.*,sbc.`name` AS subcategory_name,c.`name` AS category_name,b.name AS brand_name FROM `items` itm 
    LEFT JOIN `item_subcategories` sbc ON itm.`subcategory_id` = sbc.`id`
    LEFT JOIN `item_categories` c ON sbc.`category_id` = c.`id`
    LEFT JOIN `brands` b ON itm.`brand_id` = b.`id`
    WHERE 1 ORDER BY sbc.`category_id` ASC, itm.`subcategory_id` ASC, itm.`name` ASC ";
    $rec_list = $db->execute_get( $q );
}else{
    $categories =  $db->get('item_categories');
    $brands =  $db->get('brands');
    $subcategories =  $db->get('item_subcategories',array('category_id' => $category_id ));
}
/*
* Save or Update data into database ends
*/


include_once 'header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Items </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <?php  if ($process == 'FORM') {?>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <p><?= $id >0 ? 'Edit ' : 'Add ' ?> Items</p>
          </div>
          <div class="panel-body">
            <div class="row">
             
              <div class="col-lg-8 col-lg-offset-1">
                <form class="form-horizontal" method="post" action="<?= base_url('admin/items.php') ?>" enctype="multipart/form-data" >
                  <input type="hidden" name="id" value="<?= $id ?>">
                 
                  <div class="form-group">
                    <label class="control-label col-sm-3">Category : </label>
                    <div class="col-sm-5">
                      <select class="form-control" name="category_id" id="category_id">
                        <option value="">Select</option>
                        <?php foreach( $categories as $row ){ 
                        $selected = $category_id == $row['id'] ?'selected="seletced"':''; ?>
                        <option value="<?=$row['id'] ?>" <?= $selected ?> ><?= $row['name'] ?></option>
                        <?php } ?>
                      </select>
                     <?= form_error('category_id'); ?>
                     </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-sm-3">Subcategory : </label>
                    <div class="col-sm-5">

                      <select class="form-control" name="subcategory_id" id="subcategory_id">
                        <option value="">Select</option>
                        <?php foreach( $subcategories as $row ){ 
                        $selected = $subcategory_id == $row['id'] ?'selected="seletced"':''; ?>
                        <option value="<?=$row['id'] ?>" <?= $selected ?>><?= $row['name'] ?></option>
                        <?php } ?>
                      </select>
                     <?= form_error('subcategory_id'); ?>
                     </div>
                  </div> 
                  <div class="form-group">
                    <label class="control-label col-sm-3">Brand : </label>
                    <div class="col-sm-5">
                      <select class="form-control" name="brand_id" id="brand_id">
                        <option value="">Select</option>
                        <?php foreach( $brands as $row ){ 
                        $selected = $brand_id == $row['id'] ?'selected="seletced"':''; ?>
                        <option value="<?=$row['id'] ?>" <?= $selected ?>><?= $row['name'] ?></option>
                        <?php } ?>
                      </select>
                     <?= form_error('brand_id'); ?>
                     </div>
                  </div>  
                  <div class="form-group">
                    <label class="control-label col-sm-3" >Name:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="name" value="<?= $name ?>">
                      <?= form_error('name') ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-3" >Description:</label>
                    <div class="col-sm-9">
                      <textarea  class="form-control" name="description"><?= $description ?></textarea>
                      <?= form_error('description') ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-3" >Image:</label>
                    <div class="col-sm-5">
                      <input type="hidden" name="image" value="<?= $image ?>">
                      <input type="file"  name="fl_image" >
                      <label>540X600 px</label>
                      <?= form_error('image') ?>
                    </div>
                    <div class="col-sm-3">
                      <?php if (  $image != "") {  ?>
                        <img src ="<?= base_url( $image )   ?>" style="width: 70px">
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-3" >Price:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="price" value="<?= $price ?>">
                      <?= form_error('price') ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-3">Discount. Type : </label>
                    <div class="col-sm-5">
                      <select class="form-control" name="disc_type" id="disc_type">
                        <option value="PERCENTAGE" <?= $disc_type == 'PERCENTAGE' ? 'selected ="selected"' :'' ?>>%</option>
                        <option value="AMOUNT" <?= $disc_type == 'AMOUNT' ? 'selected ="selected"' :'' ?>>Rs.</option> 
                      </select>
                     <?= form_error('disc_type'); ?>
                     </div>
                  </div>  
                  <div class="form-group" <?= $disc_type == 'AMOUNT' ? 'style="display:none"' :'' ?> id ="disc_percentage">
                    <label class="control-label col-sm-3" >Disc. Percentage:</label>
                    <div class="col-sm-9">
                      <input type="disc_percentage" class="form-control" name="disc_percentage" value="<?= $disc_percentage ?>">
                      <?= form_error('disc_percentage') ?>
                    </div>
                  </div> 
                  <div class="form-group" <?= $disc_type == 'PERCENTAGE' ? 'style="display:none"' :'' ?>  id ="disc_amount">
                    <label class="control-label col-sm-3" >Disc. Amount:</label>
                    <div class="col-sm-9">
                      <input type="disc_amount" class="form-control" name="disc_amount" value="<?= $disc_amount ?>">
                      <?= form_error('disc_amount') ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-3" >Stock:</label>
                    <div class="col-sm-9">
                      <input type="stock" class="form-control" name="stock" value="<?= $stock ?>">
                      <?= form_error('stock') ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                      <button type="submit" name="btnSave" class="btn btn-success">Submit</button>
                      <a class="btn btn-default" href="<?= base_url('admin/items.php') ?>">Cancel</a>
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
        <a href="<?= base_url('admin/items.php?option=form') ?>" class =" btn btn-info pull-right"><i class="fa fa-plus "></i> Add Items </a>
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
            List of Items 
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
                            <th style="width: 10%">Category</th>
                            <th style="width: 10%">Brand</th>
                            <th style="width: 30%">Name</th>
                            <th style="width: 10%">Image</th>
                            <th style="width: 5%">Price</th>
                            <th style="width: 5%">Discount</th>
                            <th style="width: 10%">Stock</th>
                            <th style="width: 8%"></th>
                            <th style="width: 8%"></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($rec_list as $key => $row) { ?>
                        <tr class="odd gradeX">
                          <td><?=  ($key+1) ?></td>
                          <td><?= $row['category_name'] ."/".$row['subcategory_name']  ?></td>
                          <td><?= $row['brand_name']  ?></td>
                          <td><?= $row['name']  ?></td>
                          <td>
                            <?php if (  $row['image'] != "") {  ?>
                             <img src ="<?= base_url($row['image'])   ?>" style="width: 70px">
                            <?php } ?>
                          </td>
                          <td><?= number_format($row['price'],2)?></td>
                          <td><?= number_format($row['disc_percentage'],2)?>%</td>
                          <td><?= $row['stock']?></td>
                          <td><a href="<?= base_url('admin/items.php?edit='.$row['id'] ) ?>" class="btn btn-sm btn-default"> <i class="fa fa-edit"></i> Edit </a></td>
                          <td><a onclick="if (confirm('Delete record ?')) { window.location.href='<?= base_url('admin/items.php?del='.$row['id'] ) ?>';}" class="btn btn-sm btn-default"> <i class="fa fa-trash"></i> Delete </a></td>
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
    $('#disc_type').change(function(){
        if ($(this).val() == 'PERCENTAGE'){
            $("#disc_amount").hide();
            $("#disc_percentage").show();
        }else if ($(this).val() == 'AMOUNT'){
            $("#disc_amount").show();
            $("#disc_percentage").hide();
        }

    });
    $('#category_id').change(function(){
        var categoryId =    $('#category_id').val();
        $.ajax({
            type: "POST",
            url: "<?php echo  base_url('ajax.php');?>",
            data: {
                category_id : categoryId,
                process : 'SUBCATS_BY_CAT',
            },
            beforeSend: function(){
                $("#divPreLoader").show();
            },
            complete:function(){
                $("#divPreLoader").hide();
            }
        })
        .done(function( response ) {
            if ( response.subcategories != null ){
                var html = '<option value="">Select</option>';
                $.each(response.subcategories, function(i, row) {
                    html += '<option value="'+row.id+'">'+row.name+'</option>';
                });
                $("#subcategory_id").html(html);
            }
        });
    });
</script>
