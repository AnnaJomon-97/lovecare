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
                            <a  class="nav-link" href="<?= base_url('index.php') ?>">Home</a>
                        </li>
                        <?php 
                        $mnu_cats = $db->get('item_categories');
                        $mun_subcats = $db->get('item_subcategories');
                        $ar_munsubcats = [];
                        foreach ($mun_subcats as $mnu_sbcrow){
                            $cat_id = $mnu_sbcrow['category_id'];
                            $ar_munsubcats[$cat_id][] = $mnu_sbcrow;
                        }
                        foreach ( $mnu_cats as $mnu_catrow){
                            $cat_id = $mnu_catrow['id'];
                            $mnusubcat2 = isset($ar_munsubcats[$cat_id]) ? $ar_munsubcats[$cat_id] :[];
                        ?>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="nav-link dropdown-toggle " href="#"><?= $mnu_catrow['name'] ?></a>
                            <div class="dropdown-menu">
                                <ul> 
                                    <?php foreach ($mnusubcat2 as $mnu_sbc2){?>
                                    <li><a class="dropdown-item nav-link nav_item " href="items.php?sbc=<?= $mnu_sbc2['id'] ?>"><?= $mnu_sbc2['name'] ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>   
                        </li>
                        <?php } ?>
                        <li >
                            <a  class="nav-link" href="<?= base_url('services.php') ?>">Services</a>
                        </li>
                        <li><a class="nav-link nav_item" href="<?= base_url('contact.php') ?>">Contact Us</a></li>
                        <?php
                        $user_id = $session->get_userdata('shop_customer_id');
                        if ( $user_id > 0 ){ ?>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="nav-link dropdown-toggle " href="#">User</a>
                            <div class="dropdown-menu">
                                <ul> 
                                    <li><a class="dropdown-item nav-link nav_item " href="<?= base_url('my-account.php') ?>">Account</a></li> 
                                    <li><a class="dropdown-item nav-link nav_item " href="login.php?logout=1">Logout</a></li> 
                                </ul>
                            </div>   
                        </li>

                        <?php }else{?>
                            <li class="dropdown">
                            <a data-toggle="dropdown" class="nav-link dropdown-toggle " href="#">Login</a>
                            <div class="dropdown-menu">
                                <ul> 
                                    <li><a class="dropdown-item nav-link nav_item " href="<?= base_url('login.php') ?>">Login</a></li> 
                                    <li><a class="dropdown-item nav-link nav_item " href="<?= base_url('register.php') ?>">Register</a></li> 
                                </ul>
                            </div>   
                        </li>
                        <?php } ?>

                        
                    </ul>
                </div>
                <ul class="navbar-nav attr-nav align-items-center">
                   
                    <?php 

                    $cart_count = (int)$db->get_count('cart',array('customer_id' => $user_id ));

                    ?>
                    <li><a class="nav-link cart_trigger" href="<?= base_url('cart.php') ?>" ><i class="linearicons-cart"></i><span class="cart_count"><?=  $cart_count ?></span></a>
                        
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
<!-- END HEADER -->