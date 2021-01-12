<?php
require_once 'config/config.php';
$process = ( $input->get('option') == 'form' )  ? 'FORM' : 'LIST' ;
/*
*  Strats load data to edit
*/
$edit = (int)$input->get('edit');
$id = $item_id = $review ="";
$customer_id =  (int)$session->get_userdata('shop_customer_id');
if( isset( $_POST['btnSaveReview'] ) ){
    $form_validation->set_rules('item_id','Item','required');
    $form_validation->set_rules('review','Review','required');
    $form_errors =  $form_validation->run();
    $id = (int) $input->post('id');
    $item_id = $input->post('item_id');
    $review = $input->post('review');
    if( count($form_errors) >0 ){
        $process = 'FORM';
        goto htmlView;
    }else{
        if ($id >0 ){
            $sql = "UPDATE `reviews` SET `customer_id` ='".$customer_id."', `item_id` = '".$item_id."', `review` = '".$review."' WHERE id= ".$id;
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Review updated successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
            }
        }else{ 
            $sql = "INSERT INTO `reviews`( `customer_id`, `item_id`, `review`) VALUES ('".$customer_id."','".$item_id."','".$review."')";
            if ( $db->execute($sql) ){
                $session->set_flashdata('msg',alert('Review added successfully','success'));
            }else{
                $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
            }
        }
        redirect('my-reviews.php');
    }
}
/*
*  Strats record delete
*/
$del = (int)$input->get('del');
if ( $del > 0 ) {
    $del_rec = $db->delete('reviews',array('id' => $del));
    if ( $del_rec ){
        $session->set_flashdata('msg',alert('Data deleted successfully','success'));
    }else{
        $session->set_flashdata('msg',alert('Could n\'t delete the data !','danger'));
    }  
    redirect('my-reviews.php');
}
if( $edit > 0 ){
    $review_row =  $db->get_row('reviews',array('id' => $edit));
    if ( $review_row ) {
        $id = $review_row->id;
        $item_id = $review_row->item_id;
        $review = $review_row->review;
    }
    $process = 'FORM';
}
/*
*  ### End load data to edit
*/
htmlView:

$items = [];
if ( $process == 'FORM' ){
    /*SELECT `id`, `subcategory_id`, `brand_id`, `name`, `description`, `image`, `price`, `disc_type`, `disc_percentage`, `disc_amount`, `stock` FROM `items` WHERE 1
    SELECT `id`, `customer_id`, `name`, `address`, `phone`, `date`, `status` FROM `orders` WHERE 1
    SELECT `id`, `order_id`, `item_id`, `quantity`, `price` FROM `order_items` WHERE 1*/
    $q = "SELECT itm.* FROM  order_items oi 
    LEFT JOIN orders o ON oi.order_id = o.id
    LEFT JOIN items itm ON oi.item_id = itm.id
    WHERE o.`customer_id`='".$customer_id."'
    GROUP BY itm.id ";
    $items = $db->execute_get($q );

}else{
    $q = "SELECT r.*,itm. `name` AS item_name ,itm.`image`  FROM `reviews` r 
    LEFT JOIN `items` itm ON r.`item_id` = itm.`id`
    WHERE r.`customer_id`='".$customer_id."' ORDER BY r.`id` DESC";
    $rec_list = $db->execute_get( $q );
}
require_once 'header.php';
?>

<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding-top: 40px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>My Reviews</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('my-account.php') ?>">Account</a></li>
                    <li class="breadcrumb-item"><a  href="<?= base_url('my-reviews.php') ?>"> Reviews </a></li>
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
                <h3>Add/Edit review</h3>
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?= base_url('my-reviews.php') ?>" >
                <input type="hidden" name="id" value="<?= $id ?>">
                <div class="form-group">
                    <div class="col-sm-12">
                        <fieldset >
                             
                              <table>
                                 <tr>
                                    <td>Item :</td>
                                    <td>
                                        <select name="item_id" id="category_id" style="width: 300px;padding: 5px">
                                          <option value="">Select</option>
                                          <?php foreach($items as $row){ 
                                            $selected =$item_id == $row['id'] ?'selected="seletced"':''; ?>
                                          <option value="<?=$row['id'] ?>" <?= $selected ?>><?= $row['name'] ?></option>
                                          <?php } ?>
                                        </select>
                                        <?= form_error('item_id'); ?>
                                        <div style="padding-top: 10px"></div>
                                    </td>

                                </tr>
                                <tr >
                                    <td >Review :</td>
                                    <td>
                                        <textarea name="review" style="width: 300px;height: 150px"><?= $review ?></textarea>
                                        <?= form_error('review'); ?>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="submit" name="btnSaveReview" value="Save Review" class="btn btn-success">
                                    </td>
                                </tr>
                              
                            </table>
                            </fieldset>
                        </div>
                    </div>
                  </form>
                
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
            <h3>Reviewss</h3>
        </div>
        <div class="card-body">
            <a href="<?= base_url('my-reviews.php?option=form') ?>" class="btn btn-success"><i class="fa fa-plus">Add Review</i> </a>
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
                       
                        <th style="width: 36%" colspan="2">Item</th>
                        <th style="width: 60%">Review</th>
                        <th style="width: 10%"></th>
                        <th style="width: 10%"></th>
                        
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($rec_list as $key => $row) { ?>
                    <tr class="odd gradeX">
                      <td><?=  ($key+1) ?></td>
                      <td style="width: 10%"><img src="<?= base_url($row['image'] ) ?>"></td>
                      <td><?= $row['item_name']  ?></td>
                      <td><?= $row['review']  ?></td>
                      <td><a href="<?= base_url('my-reviews.php?edit='.$row['id'] ) ?>" class="btn btn-sm btn-default"> <i class="fa fa-edit"></i> Edit </a></td>
                          <td><a onclick="if (confirm('Delete record ?')) { window.location.href='<?= base_url('my-reviews.php?del='.$row['id'] ) ?>';}" class="btn btn-sm btn-default"> <i class="fa fa-trash"></i> Delete </a></td>
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