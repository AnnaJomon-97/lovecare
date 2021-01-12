<?php
require_once 'config/config.php';
require_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding-top: 40px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>My Account</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">My Account</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section" >
	<div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <?php include_once 'account-menu.php' ?>
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Dashboard</h3>
                    </div>
                    <div class="card-body">
                        <p>From your account dashboard. you can easily check &amp; view your <a href="">recent orders</a>, manage your <a href="">shipping  addresses</a> and <a href="">edit your password and account details.</a></p>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- END SECTION SHOP -->
<?php
require_once 'footer.php';
?>