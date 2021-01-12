<?php

require_once '../config/config.php';
$logout = $input->get('logout');
if ( $logout == '1' ){
    $session->unset_userdata('shop_staff_id');
    redirect('staff/login.php');
}
$email = "";
if( isset( $_POST['btnLogin'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('email','Email','required|valid_email');
    $form_validation->set_rules('password','Password','required');
    $form_errors =  $form_validation->run();
    /*
    * ### End form validation
    */
    //SELECT `id`, `name`, `email`, `password`, `status`, `reg_date` FROM `customers` 
    $email = $input->post('email');
    $password = $input->post('password');
    if( count($form_errors) >0 ){
        $process = 'FORM';
        goto htmlView;

    }else{
        $where = array(
            'email' => $email,
            'password' => $password,
        );
        $res = $db->get_row('employees',$where);
        if( $res ){
            $session->set_userdata('shop_staff_id', $res->id);
            redirect('staff/index.php');
        }else{
            $session->set_flashdata('msg',alert('Incorrect username or password !','danger'));
        } 
    }
}
/*
*  ### END Save or Update data into database
*/

htmlView:
include_once 'header.php';

?>

<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 20px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Login</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Login</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START LOGIN SECTION -->
<div class="login_register_wrap section" style="padding-top: 20px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-10">
                <?= $session->get_flashdata('msg') ?>
                <div class="login_wrap">
            		<div class="padding_eight_all bg-white">
                       
                        <form method="post" <?= base_url('staff/login.php') ?>>
                            <div class="form-group">
                                <input type="text" required="" class="form-control" name="email" placeholder="Your Email" value="<?= $email  ?>">
                                <?= form_error('email') ?> 
                            </div>
                            <div class="form-group">
                                <input class="form-control" required="" type="password" name="password" placeholder="Password">
                                <?= form_error('password') ?>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-fill-out btn-block" name="btnLogin">Log in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END LOGIN SECTION -->

<?php
include_once 'footer.php'
?>
