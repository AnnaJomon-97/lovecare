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
require_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 40px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Dashboard</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('staff/index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section">
	<div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="dashboard_menu">
                    <ul class="nav nav-tabs flex-column" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="dashboard-tab" data-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="false"><i class="ti-layout-grid2"></i>Dashboard</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('staff/assignments.php') ?>"><i class="ti-shopping-cart-full"></i>Assignments</a>
                      </li> 
                      <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('staff/assignments.php?status=2') ?>" ><i class="ti-location-pin"></i>Deliveries</a>
                      </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Dashboard</h3>
                    </div>
                    <?php
                    $pending = $db->get_count('deliveries',array('status' =>'1','employee_id' =>(int)$shop_staff_id  ));
                    $completed = $db->get_count('deliveries',array('status' =>'2','employee_id' =>(int)$shop_staff_id  ));
                    ?>
                    <div class="card-body">
                       <div class="box">
                        <a href="<?= base_url('staff/assignments.php?status=1') ?>">Pending assignets [<?= $pending  ?>]</a>
                       </div>
                       <br>
                       <div class="box">
                        <a href="<?= base_url('staff/assignments.php?status=2') ?>">Completed assignments [<?= $completed  ?>]</a>
                       </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- END SECTION SHOP -->
<?php require_once ('footer.php') ?>