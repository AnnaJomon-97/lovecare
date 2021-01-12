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
if (isset ($_POST['btnChangePassword'])){
    $password = $input->post('password');
    $c_password = $input->post('c_password');
    $form_validation->set_rules('password','Password','required');
    $form_validation->set_rules('c_password','Confirm password','required');
    $form_errors =  $form_validation->run();
    if ( $password  != $c_password ){
        $form_errors['c_password'] = 'Mismatch in confirm password !';
    }
    if( count($form_errors) >0 ){
        $process = 'FORM';
        goto htmlView;
    }else{
        $q = "UPDATE employees SET  password ='".$password."' WHERE id='".$shop_staff_id."'";
        $res = $db->execute($q);
        if (  $res  ){
             $session->set_flashdata('msg',alert('Password changed successfully!','success'));
            redirect('staff/change-password.php');
        }else{
            $session->set_flashdata('msg',alert('Password not changed successfully!!','danger'));

        }
    }
}

htmlView:
include_once 'header.php';
?>
<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini" style="padding: 40px 0px">
    <div class="container"><!-- STRART CONTAINER -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="page-title">
                    <h1>Change password</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Change password</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section" style="padding-top: 30px">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div style="width: 400px;margin-left: auto;margin-right: auto;">      
                <?= $session->get_flashdata('msg')?>
                    <form role="form" action="<?= base_url('staff/change-password.php') ?>" method="post">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" autofocus>
                                <?= form_error('password') ?>
                            </div> 
                            <div class="form-group">
                                <input class="form-control" placeholder="Confirm Password" name="c_password" type="password" autofocus>
                                <?= form_error('c_password') ?>
                            </div>
                          
                            <input type="submit" class="btn btn-lg btn-success btn-block" name="btnChangePassword" value="Change Password">
                           
                        </fieldset>
                    </form>
                  
            </div>
        </div>
        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>