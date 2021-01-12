<?php
require_once '../config/config.php';
if (isset ($_POST['btnLogin'])){
    $username = $input->post('username');
    $password = $input->post('password');
    $where =  array(
        'username' => $username , 
        'password' => $password , 
    );
    $admin = $db->get_row('admin_users',$where );
    if (  $admin  ){
        $session->set_userdata('admin_userid',$admin->id);
        $session->set_userdata('admin_name',$admin->name);
        redirect('admin/');
    }else{
        $session->set_flashdata('msg',alert('Invalid username or password !','danger'));

    }
}

$logout = $input->get('logout');
if ( $logout == '1' ){
    $session->unset_userdata('admin_userid');
    $session->unset_userdata('admin_name');
    redirect('admin/login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= get_appname() ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url('assets/admin/vendor/bootstrap/css/bootstrap.min.css') ?> " rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?= base_url('assets/admin/vendor/metisMenu/metisMenu.min.css')?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/admin/dist/css/sb-admin-2.css')?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= base_url('assets/admin/vendor/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
       
        <div class="row">
            <div class="col-md-4 col-md-offset-4">

                
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        
                        <form role="form" action="<?= base_url('admin/login.php') ?>" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                </div> 
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!-- <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div> -->
                                <!-- Change this to a button or input when using this as a form -->
                                <input type="submit" class="btn btn-lg btn-success btn-block" name="btnLogin" value="Login">
                               
                            </fieldset>
                        </form>
                        <div style="padding: 10px 0px">
                            <?= $session->get_flashdata('msg')?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= base_url('assets/admin/vendor/jquery/jquery.min.js')?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?= base_url('assets/admin/vendor/bootstrap/js/bootstrap.min.js')?>"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?= base_url('assets/admin/vendor/metisMenu/metisMenu.min.js')?>"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?= base_url('assets/admin/dist/js/sb-admin-2.js')?>"></script>

</body>

</html>
