<?php
/**
 * Class for get post or get values
 */
class Input 
{
	
	function __construct()
	{
		# code...
	}
	function  post( $field ,$html_decode = TRUE  ){
		$post_val  = isset( $_POST[$field] ) ? $_POST[$field] :  null ;
		if ($html_decode){
			return  htmlentities($post_val, ENT_QUOTES); 
		}else{
			return $post_val;
		}  
	}
	function  get( $field ){
		return isset( $_GET[$field] ) ? $_GET[$field] :  null ;
	}
}
$input = new Input;