
1. IMG
2. SELECT
3. AJAX_RESPONSE
4. AJAX

/*
* IMG
*/
<?php if (  $row['image'] != "") {  ?>
	<img src ="<?= base_url( $row['image'] )   ?>" style="width: 70px">
<?php } ?>

<div class="col-sm-2">
  <?php if (  $image != "") {  ?>
    <img src ="<?= base_url( $image )   ?>" style="width: 70px">
  <?php } ?>
</div>
============================================
/*
* SELECT
*/

<div class="form-group">
  <label class="control-label col-sm-3">Category : </label>
  <div class="col-sm-5">
    <select class="form-control" name="category_id" id="category_id">
      <option value="">Select</option>
      <?php foreach($categories as $row){ 
      	$selected =$fields['category_id'] == $row['id'] ?'selected="seletced"':''; ?>
      <option value="<?=$row['id'] ?>" <?= $selected ?>><?= $row['name'] ?></option>
      <?php } ?>
    </select>
    <?= form_error('category_id'); ?>
  </div>
</div>	

==================================

/*
* AJAX_RESPONSE
*/

header('Content-Type: application/json');
$return = array(
  'status'=>$status,
  'itemDetails' => $itemDetails,
  'taxRates' => $taxRates,
);
echo json_encode($return);      
==========================================================
/*
* AJAX
*/

$('#category_id').change(function(){
    var categoryId =    $('#category_id').val();
    alert(categoryId)
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


Visa
4012001037141112
Exp 05/20
CVV :123
OTP :123456


Master

5123456789012346