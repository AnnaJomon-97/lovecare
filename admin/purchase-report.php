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

/*
*  Strats load data to edit
*/

htmlView:
$month = date('m');
$year = date('Y');
//SELECT `id`, `order_id`, `employee_id`, `assign_date`, `delivery_date`, `status`, `remarks` FROM `deliveries` WHERE 1
//SELECT `id`, `customer_id`, `name`, `address`, `phone`, `date`, `status`, `cancelled`, `cancellation_reason` FROM `orders` WHERE 1
//SELECT `id`, `order_id`, `item_id`, `quantity`, `price` FROM `order_items` WHERE 1
//SELECT `id`, `subcategory_id`, `brand_id`, `name`, `description`, `image`, `price`, `disc_type`, `disc_percentage`, `disc_amount`, `stock` FROM `items` WHERE 1
$rec_list = [];
if (isset ($_POST['btnReport'])){
  $month = (int)$input->post('month');
  $year = (int)$input->post('year');
  $year = date('Y');
  $q = "SELECT p.`id` AS purchase_id,p.`date` AS purchase_date, itm.`name` AS item_name,pitm.`purchase_rate`,pitm.`quantity`  FROM purchase_items pitm 
  LEFT JOIN purchase p ON pitm.`purchase_id` = p.`id`
  LEFT JOIN items itm ON pitm.`item_id` = itm.`id`
  WHERE   YEAR( p.`date` ) ='". $year ."' AND MONTH( p.`date` ) ='".$month. "'";
  $rec_list =  $db->execute_get($q); 
}

include_once 'header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Purchase report </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
             <?= $session->get_flashdata('msg') ?>
        </div>
    </div>
    <div class="row" style="padding: 20px 0px">
      
        <form class="form-horizontal" action="<?= base_url('admin/purchase-report.php') ?> " method="post">
        <?php 
          
          $ar_months = ['Jan','feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
          ?>
          <div class="col-sm-2">
             <select name="month" class="form-control">
               <?php foreach ($ar_months as $key => $month_name) {
                $selected = ($key+1) == (int)$month ? 'selected="selected"':'' ;
                ?>
                 <option <?= $selected ?> value="<?= ($key+1) ?>"><?= $month_name ?></option>
               <?php } ?>
               
             </select>
            <?=form_error('month'); ?>
          </div>

          <?php 
          $q = "SELECT YEAR(MIN(date)) AS start_year FROM purchase WHERE 1 ";
          $year_row = $db->execute_get_row($q);
          $sart_year =date('Y');
          if( $year_row && $year_row->start_year > 0 ){
             $sart_year =  $year_row->start_year;
          }
          ?>
          <div class="col-sm-2" style="padding: 0px">
             <select name="year" class="form-control">
               <?php  for ($y= $sart_year  ; $y<= date('Y') ; $y++) {
                $selected = $y== (int)$year ? 'selected="selected"':'' ;
                ?>
                 <option <?= $selected ?> value="<?= $y ?>"><?= $y ?></option>
               <?php } ?>  
             </select>
            <?=form_error('date'); ?>
          </div>
          <div class="col-sm-2">
            <input type="submit" value="Show Report" class="btn btn-success" name="btnReport">
          </div> 
      </form>
     
    </div>
    <div class="row">
      <div class="col-lg-12">
        <?php 
        if (isset($_POST['btnReport'])){ ?>
        <div id="div1" >
        <?php $month_name = isset($ar_months[$month -1]) ? $ar_months[$month-1] :''  ?>
        <h2>Purchase report <?=  $month_name."-".$year ?></h2>
        <?php 
        if(count( $rec_list) == 0){
            echo '<div class="jumbotron" style="padding:20px">
              <h3 class="text-center" >No records found !</h3>
            </div>';
        }else{ ?>
          <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
            <tr>
                <th style="width: 4%">Sl.No</th>
                <th style="width: 10%">Purchase No</th>
                <th style="width: 10%">Order Date</th>
                <th style="width: 30%">Item</th>
                <th style="width: 10%">Rate</th>
                <th style="width: 10%">Quantity</th>
                <th style="width: 10%">Line Total</th>
            </tr>
            <?php foreach ($rec_list as $key => $row) { ?>
                <tr class="odd gradeX">
                  <td><?=  ($key+1) ?></td>
                  <td><?= $row['purchase_id']  ?></td>
                  <td><?= date('d-m-Y',strtotime($row['purchase_date']))  ?></td>
                  <td><?= $row['item_name']  ?></td>
                  <td><?=  number_format($row['purchase_rate'],2)  ?></td>
                  <td><?= $row['quantity']  ?></td>
                  <td><?=  number_format($row['purchase_rate'] *  $row['quantity'] ,2)  ?></td>
                </tr>
              <?php } ?>
            </table>
        <?php }?>  
        </div>
        <?php } ?>       
      </div>
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php 
include_once 'footer.php';


if (isset($_POST['btnReport'])){
?>

<script type="text/javascript">
    headerContent = '<style>'+
        'table,tr,td,th {'+
        'border:1px solid #3333;'+
        'border-collapse:collapse;'+
        '}'+
    '</style>';
 var divToPrint = document.getElementById('div1');
  var newWin=window.open('','Print-Window');
  newWin.document.open();
  newWin.document.write('<html>'+headerContent+'<body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
  newWin.document.close();
</script>
<?php } ?>