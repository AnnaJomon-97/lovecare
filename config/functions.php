<?php 
if( !function_exists('get_message'))
{
  function get_message($message = '',$msg_type="info")
  {
    return '<div class="alert alert-'.$msg_type.' alert-dismissible  fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$message.'
           </div>';
  }
}
if(!function_exists('base_url'))
{
  	function base_url($url_path ="")
  	{
    	return BASE_URL.$url_path;
  	}	
}
if(!function_exists('base_path'))
{
    function base_path($file_path ="")
    {
      return BASE_PATH."/".$file_path;
    } 
}
if(!function_exists('get_appname'))
{
  	function get_appname()
  	{
    	return APP_NAME;
  	}	
}
if(!function_exists('form_error'))
{
  	function form_error($filed)
  	{
    	if ( isset ( $GLOBALS['form_errors'][$filed] ) ){
    		return '<span class="error">'. $GLOBALS['form_errors'][$filed].'</span>';
    	}
  	}	
}
if(!function_exists('alert'))
{
  	function alert( $message="", $type ='success' )
  	{
    	return '<div class="alert alert-'.$type.' alert-dismissible">
		  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		  <strong>'.$message.'</strong>
		</div>';
  	}	
}
if(!function_exists('get_label'))
{
    function get_label( $message="", $type ='success' )
    {
      return '<label class="label label-'.$type.'">'.$message.'</label>';
    } 
}
if(!function_exists('redirect'))
{
  	function redirect( $urlpath= "")
  	{
    	header('location:'.base_url($urlpath));
    	exit;
  	}	
}

if(!function_exists('short_text'))
{
    function short_text($text = "",$length = 100) {
        $output = strlen( strip_tags($text))>100? substr(strip_tags($text), 0,100)." ..." : strip_tags($text);
        return $output;
    }
}
?>
