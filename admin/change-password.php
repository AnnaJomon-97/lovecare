<?php
require_once '../config/config.php';
/*
* Check user is logined
*/
$admin_userid = $session->get_userdata('admin_userid');
if( !$admin_userid && ! ( $admin_userid > 0) ){
    $session->set_flashdata('msg','Please login to admin panel!');
    redirect('admin/login.php');
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
        $q = "UPDATE admin_users SET  password ='".$password."' WHERE id='".(int)$admin_userid."'";
        $res = $db->execute($q);
        if (  $res  ){
             $session->set_flashdata('msg',alert('Password changed successfully!','success'));
            redirect('admin/change-password.php');
        }else{
            $session->set_flashdata('msg',alert('Password not changed successfully!!','danger'));

        }
    }
}
htmlView:
include_once 'header.php';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Change password </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-sm-offset-3 col-lg-6">    
        <div style="padding: 10px 0px">
            <?= $session->get_flashdata('msg')?>
        </div> 
            <div class=" panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Change password</h3>
                </div>
                <div class="panel-body">
                        
                    <form role="form" action="<?= base_url('admin/change-password.php') ?>" method="post">
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

<?php include_once 'footer.php'; ?>