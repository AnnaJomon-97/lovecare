<?php
require_once 'config/config.php';

$process = ( $input->get('option') == 'form' )  ? 'FORM' : 'LIST' ;
/*
* Save or Update data into database starts
*/

$id = $name = $address = $email = $phone = $subject = $message = "";
if( isset( $_POST['btnSend'] ) ){

    /*
    * Form validatin stars 
    */
    $form_validation->set_rules('name','Name','required','Please enter name');
    $form_validation->set_rules('email','Email','required|valid_email');
    $form_validation->set_rules('phone','Phone','required|valid_phone');
    $form_validation->set_rules('subject','Subject','required');
    $form_validation->set_rules('message','Message','required');
    $form_errors =  $form_validation->run();
    /*
    * ### End form validation
    */
    $name = $input->post('name');
    $address = $input->post('address');
    $email = $input->post('email');
    $phone = $input->post('phone');
    $subject = $input->post('subject');
    $message = $input->post('message');
    $date = date('Y-m-d');
    if( count($form_errors) >0 ){
        $process = 'FORM';
        goto htmlView;
    }else{
        
        $sql = "INSERT INTO `messages`( `name`, `email`, `phone`, `subject`, `message`,`date`) VALUES ('".$name."','".$email."','".$phone."','".$subject."','".$message."','".$date."')";
        if ( $db->execute($sql) ){
            $session->set_flashdata('msg',alert('Message send successfully','success'));
        }else{
            $session->set_flashdata('msg',alert('Could n\'t save the data !','danger'));
        }   
        redirect('contact.php');
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
            		<h1>Contact</h1>
                </div>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= base_url('index.php') ?>">Home</a></li>
                    <li class="breadcrumb-item active">Contact</li>
                </ol>
            </div>
        </div>
    </div><!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION CONTACT -->
<div class="section pt-0">
	<div class="container">
    	<div class="row">
        	<div class="col-lg-6">
                <p class="leads">Keep in touch with us</p>
                <div class="field_form">
                    <?= $session->get_flashdata("msg") ?>
                    <form method="post" name="enq" action="<?= base_url('contact.php') ?>">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input required placeholder="Enter Name *" id="first-name" class="form-control" name="name" type="text" value="<?= $name ?>">
                                <?= form_error('name') ?>
                             </div>
                            <div class="form-group col-md-6">
                                <input required placeholder="Enter Email *" id="email" class="form-control" name="email" type="email" value="<?= $email ?>">
                                <?= form_error('email') ?>
                            </div>
                            <div class="form-group col-md-6">
                                <input required placeholder="Enter Phone No. *" id="phone" class="form-control" name="phone" value="<?= $phone ?>">
                                <?= form_error('phone') ?>
                            </div>
                            <div class="form-group col-md-6">
                                <input placeholder="Enter Subject" id="subject" class="form-control" name="subject" value="<?= $subject ?>">
                                <?= form_error('subject') ?>
                            </div>
                            <div class="form-group col-md-12">
                                <textarea required placeholder="Message *" id="description" class="form-control" name="message" rows="4"><?= $message ?></textarea>
                                <?= form_error('message') ?>
                            </div>
                            <div class="col-md-12">
                                <input type="submit" name="btnSend" class="btn btn-fill-out"  value="Send Message" style="color: red" >
                            </div>
                            <div class="col-md-12">
                                <div id="alert-msg" class="alert-msg text-center"></div>
                            </div>
                        </div>
                    </form>		
                </div>
            </div>
            
        </div>
    </div>
</div>
<!-- END SECTION CONTACT -->
<?php 
include_once 'footer.php';
?>