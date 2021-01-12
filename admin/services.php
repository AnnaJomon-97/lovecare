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
$id = $name = $description = $image = $fee = $unit = "";
if( isset( $_POST['btnSave'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('name','Name','required','Please enter name');
    $form_errors =  $form_validation->run();
    /*
    * ### End form validation
    */
    $id = (int) $input->post('id');
    $name = $input->post('name');
    $description = $input->post('description');
    $image = $input->post('image');
    $fee = $input->post('fee');
    $unit = $input->post('unit');
    if( count($form_errors) >0 ){ 
        $process = 'FORM';
        goto htmlView;
    }else{
        if( $_FILES['fl_image']['name'] != "" ) {
            $upload->config['max_size'] = 3000000;
            $upload->config['allowed_types'] = 'jpg|png|jpeg|gif';
            $upload->config['upload_path'] = 'uploads/services/';
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
            $sql = "UPDATE `services` SET `name`= '".$name."',`description`='".$description."',`image`='".$image."',`fee`='".$fee."',`unit`='".$unit."' WHERE id =".$id;
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Data updated successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t updated the data !','danger'));
            }
        }else{
            $sql = "INSERT INTO `services`( `name`,`description`, `image`,`fee`, `unit`) VALUES ('".$name."','".$description."','".$image."','".$fee."','".$unit."')";
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Data saved successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
            }   
        }
        redirect('admin/services.php');
    }
}
/*
*  ### END Save or Update data into database
*/

/*
*  Strats load data to edit
*/
$edit = (int)$input->get('edit');
$edit_rec = $db->get_row('services',array('id' => $edit));
if ( $edit_rec ){
    $id = $edit_rec->id;
    $name = $edit_rec->name;
    $description = $edit_rec->description;
    $image = $edit_rec->image;
    $fee = $edit_rec->fee;
    $unit = $edit_rec->unit;
    $process = 'FORM';
}
/*
*  ### End load data to edit
*/

/*
*  Strats record delete
*/
$del = (int)$input->get('del');
if ( $del > 0 ) {
    $del_row = $db->get_row('services',array('id' => $del));
    if ($del_row){
        $image = $del_row->image;
        if (file_exists(  base_path($image) )){
            unlink( base_path($image) );
        }
    }
    $del_rec = $db->delete('services',array('id' => $del));
    if ( $del_rec ){
        $session->set_flashdata('msg',alert('Data deleted successfully','success'));
    }else{
        $session->set_flashdata('msg',alert('Could n\'t delete the data !','danger'));
    }  
    redirect('admin/services.php');
}
/*
*  ### End record delete
*/
if ($process != 'FORM'){
    $whrere = [];
    $order_by = array(
        'id' => 'ASC' , 
    );
    $rec_list = $db->get('services', $whrere, $order_by);
}

/*
* Save or Update data into database ends
*/
htmlView:
include_once 'header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Services </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <?php  if ($process == 'FORM') {?>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <p><?= $id >0 ? 'Edit ' : 'Add ' ?> Service</p>
          </div>
          <div class="panel-body">
            <div class="row">
             
              <div class="col-lg-6 col-lg-offset-2">
                <form class="form-horizontal" method="post" action="<?= base_url('admin/services.php') ?>" enctype="multipart/form-data" >
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <div class="form-group">
                    <label class="control-label col-sm-2" >Name:</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="name" value="<?= $name ?>">
                      <?= form_error('name') ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-2" >Description:</label>
                    <div class="col-sm-10">
                      <textarea  class="form-control" name="description"><?= $description ?></textarea>
                      <?= form_error('description') ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-2" >Image:</label>
                    <div class="col-sm-5">
                      <input type="hidden" name="image" value="<?= $image ?>">
                      <input type="file"  name="fl_image" >
                      <label>300X200 px</label>
                      <?= form_error('image') ?>
                    </div>
                    <div class="col-sm-2">
                      <?php if (  $image != "") {  ?>
                        <img src ="<?= base_url( $image )   ?>" style="width: 70px">
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-sm-2" >Fee:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="fee" value="<?= $fee ?>">
                        <?= form_error('fee') ?>
                    </div>
                     <label class="control-label col-sm-1" >Per:</label>
                    <div class="col-sm-3">
                        <select name="unit" class="form-control">
                            <option value="Hour">Hour</option>
                            <option value="Day">Day</option>
                            <option value="Week">Week</option>
                            <option value="Month">Month</option>
                        </select>
                      <?= form_error('unit') ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" name="btnSave" class="btn btn-success">Submit</button>
                      <a class="btn btn-default" href="<?= base_url('admin/services.php') ?>">Cancel</a>
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
        <a href="<?= base_url('admin/services.php?option=form') ?>" class =" btn btn-info pull-right"><i class="fa fa-plus "></i> Add Service </a>
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
            List of Services 
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
                            <th style="width: 30%">Name</th>
                            <th style="width: 30%">Description</th>
                            <th style="width: 10%">Image</th>
                            <th style="width: 10%">Fee</th>
                            <th style="width: 8%"></th>
                            <th style="width: 8%"></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($rec_list as $key => $row) { ?>
                        <tr class="odd gradeX">
                          <td><?=  ($key+1) ?></td>
                          <td><?= $row['name']  ?></td>
                          <td><?=  short_text( $row['description'],50 ) ?></td>
                          <td>
                            <?php if (  $row['image'] != "") {  ?>
                             <img src ="<?= base_url($row['image'])   ?>" style="width: 70px">
                            <?php } ?>
                          </td>
                          <td><?=  number_format( $row['fee'],2 )."/".$row['unit']  ?></td>
                          <td><a href="<?= base_url('admin/services.php?edit='.$row['id'] ) ?>" class="btn btn-sm btn-default"> <i class="fa fa-edit"></i> Edit </a></td>
                          <td><a onclick="if (confirm('Delete record ?')) { window.location.href='<?= base_url('admin/services.php?del='.$row['id'] ) ?>';}" class="btn btn-sm btn-default"> <i class="fa fa-trash"></i> Delete </a></td>
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
