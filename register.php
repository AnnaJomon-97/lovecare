<?php

require_once 'config/config.php';
/*
* Save or Update data into database starts
*/
$id = $name = $email = $password ="";
if( isset( $_POST['btnRegister'] ) ){
    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('name','Name','required','Please enter name');
    $form_validation->set_rules('email','Email','required|valid_email');
    $form_validation->set_rules('password','Password','required');

    $form_errors =  $form_validation->run();
    if ($input->post('password') != $input->post('c_password')  ){
        $form_errors['c_password'] ='<span class=""> Mismatch in confirm password !</span>';
    }
    /*
    * ### End form validation
    */
    //SELECT `id`, `name`, `email`, `password`, `status`, `reg_date` FROM `customers` WHERE 1
    $id = (int) $input->post('id');
    $name = $input->post('name');
    $email = $input->post('email');
    $password = $input->post('password');
    $status ='1';
    $reg_date = date('Y-m-d');
    if( count($form_errors) >0 ){
        $process = 'FORM';
        goto htmlView;

    }else{
        $sql = "INSERT INTO `customers`( `name`, `email`, `password`, `status`, `reg_date`) VALUES ('".$name."','".$email."','".$password."','".$status."','".$reg_date."')"; 
        if ( $db->execute($sql) ){
            $customer_id =  $db->get_insert_id();
            $session->set_userdata('shop_customer_id',$customer_id);
            redirect('index.php');
           
        }else{
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
        }   
    }
}
/*
*  ### END Save or Update data into database
*/

//SELECT `id`, `name`, `email`, `password`, `status`, `reg_date` FROM `customers` WHERE 1
htmlView:
include_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 20px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
        	<div class="col-md-6">
                <div class="page-title">
            		<h1>Register</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Register</li>
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
                        <form method="post" action="<?= base_url('register.php') ?>">
                            <div class="form-group">
                                <input type="text"  class="form-control" name="name" placeholder="Enter Your Name" value="<?= $name ?>">
                                <?= form_error('name') ?>
                            </div>
                            <div class="form-group">
                                <input type="text"  class="form-control" name="email" placeholder="Enter Your Email" value="<?= $email ?>">
                                <?= form_error('email') ?>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" name="password" placeholder="Password">
                                <?= form_error('password') ?>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" name="c_password" placeholder="Confirm Password">
                                <?= form_error('c_password') ?>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-fill-out btn-block" name="btnRegister">Register</button>
                            </div>
                        </form>
                        <div class="form-note text-center">Already have an account? <a href="<?= base_url('login.php') ?>">Log in</a></div>
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
