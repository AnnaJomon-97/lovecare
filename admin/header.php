<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?= get_appname() ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/web/images/favicon.png' )?>">  

    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url('assets/admin/vendor/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?= base_url('assets/admin/vendor/metisMenu/metisMenu.min.css')?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/admin/dist/css/sb-admin-2.css')?>" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?= base_url('assets/admin/vendor/morrisjs/morris.css')?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= base_url('assets/admin/vendor/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .page-header {
            margin: 20px 0 20px !important; 
        }
        .error{
            color: red;
            font-weight: 500;
        }
        .preloader {
          align-items: center;
          background: rgb(23, 22, 22,0.3);
          display: flex;
          height: 100vh;
          justify-content: center;
          left: 0;
          position: fixed;
          top: 0;
          transition: opacity 0.3s linear;
          width: 100%;
          z-index: 9999;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><?= get_appname() ?></a>
            </div>
            <!-- /.navbar-header -->
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                       Admin <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i> 
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        
                        <li><a href="<?= base_url('admin/change-password.php') ?>"><i class="fa fa-gear fa-fw"></i> Change Password</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?= base_url('admin/login.php?logout=1') ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="<?= base_url('admin/') ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/employee.php') ?>"><i class="fa fa-edit"></i> Employee</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/brands.php') ?>"><i class="fa fa-edit"></i> Brands</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/items.php') ?>"><i class="fa fa-edit"></i> Items</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/customers.php') ?>"><i class="fa fa-edit"></i> Customers</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/services.php') ?>"><i class="fa fa-edit"></i> Services</a>
                        </li>

                        
                       
                        <li>
                            <a href="#"><i class="fa fa-edit"></i> Categories <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                     <a href="<?= base_url('admin/item-categories.php') ?>"> Item-Categories</a>
                                </li>
                                <li>
                                    <a href="<?= base_url('admin/item-subcategories.php') ?>">Item-Subcategories</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="<?= base_url('admin/suppliers.php') ?>"><i class="fa fa-edit"></i> Suppliers</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/purchase.php') ?>"><i class="fa fa-edit"></i> Purchase</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/orders.php') ?>"><i class="fa fa-list"></i> Orders</a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/service-bookings.php') ?>"><i class="fa fa-list"></i> Service-Bookings</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-edit"></i> Reports <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                     <a href="<?= base_url('admin/sales-report.php') ?>"> Sales</a>
                                </li>
                                <li>
                                    <a href="<?= base_url('admin/purchase-report.php') ?>">Purchase</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="<?= base_url('admin/messages.php') ?>"><i class="fa fa-list"></i> Messages</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <div class="preloader" style="display: none" id="divPreLoader">
          <img src="<?= base_url('assets/admin/dist/img/ajax_loader.gif') ?>" alt="spinner">
        </div>
