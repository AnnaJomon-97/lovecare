<!-- view-source:https://bestwebcreator.com/shopwise/demo/index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="Anil z" name="author">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- SITE TITLE -->
<title><?= get_appname() ?></title>
<!-- Favicon Icon -->
<link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/web/images/favicon.png' )?>">  
<!-- Animation CSS -->
<link rel="stylesheet" href="<?= base_url('assets/web/css/animate.css') ?>">	
<!-- Latest Bootstrap min CSS -->
<link rel="stylesheet" href="<?= base_url('assets/web/bootstrap/css/bootstrap.min.css') ?>">
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap" rel="stylesheet"> 
<!-- Icon Font CSS -->
<link rel="stylesheet" href="<?= base_url('assets/web/css/all.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/web/ionicons-2.0.1/css/ionicons.min.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/web/css/themify-icons.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/web/css/linearicons.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/web/css/flaticon.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/web/css/simple-line-icons.css')?>">
<!--- owl carousel CSS-->
<link rel="stylesheet" href="<?= base_url('assets/web/owlcarousel/css/owl.carousel.min.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/web/owlcarousel/css/owl.theme.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/web/owlcarousel/css/owl.theme.default.min.css')?>">
<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="<?= base_url('assets/web/css/magnific-popup.css')?>">
<!-- Slick CSS -->
<link rel="stylesheet" href="<?= base_url('assets/web/css/slick.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/web/css/slick-theme.css')?>">
<!-- Style CSS -->
<link rel="stylesheet" href="<?= base_url('assets/web/css/style.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/web/css/responsive.css')?>">
<style type="text/css">
    ul.subcat-list li.active a{
        color: red !important;
    }
    span.error {
        color: red;
    }
</style>
</head>

<body>



<!-- START HEADER -->
<header class="header_wrap fixed-top header_with_topbar">
    <div class="bottom_header dark_skin main_menu_uppercase">
    	<div class="container">
            <nav class="navbar navbar-expand-lg"> 
                <a class="navbar-brand" href="index.html">
                    <img class="logo_light" src="<?= base_url('assets/web/images/logo_light.png') ?>" alt="logo" />
                    <img class="logo_dark" src="<?= base_url('assets/web/images/logo_dark.png') ?>" alt="logo" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-expanded="false"> 
                    <span class="ion-android-menu"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                       
                        <li >
                            <a class="nav-link " href="<?= base_url('staff/index.php') ?>">Home</a>
                        </li> 
                        <li >
                            <a class="nav-link " href="<?= base_url('staff/assignments.php') ?>">Assigments</a>
                        </li> 
                        <li >
                            <a class="nav-link " href="<?= base_url('staff/assignments.php?status=2') ?>">Deliveries</a>
                        </li>
                        <?php
                        $shop_staff_id = $session->get_userdata('shop_staff_id');
                        $staff_name = $db->get_colum_value('employees','name',array('id' =>   $shop_staff_id ));
                        if ( $shop_staff_id > 0 ){ ?>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="nav-link dropdown-toggle " href="#"><?= $staff_name != '' ? $staff_name : 'User' ?></a>
                            <div class="dropdown-menu">
                                <ul> 
                                    <li><a class="dropdown-item nav-link nav_item " href="<?= base_url('staff/change-password.php') ?>">Change-password</a></li> 
                                    <li><a class="dropdown-item nav-link nav_item " href="<?= base_url('staff/login.php?logout=1') ?>">Logout</a></li> 
                                </ul>
                            </div>   
                        </li>
                        <?php } ?> 
                    </ul>
                </div>
               
            </nav>
        </div>
    </div>
</header>
<!-- END HEADER -->