<?php
/**
 * Class for session functions
 */
class Session 
{
	function __construct()
	{
		# code...
	}
	/**
	 * Function to store messages
	*/
	function set_flashdata( $index,$message ){
		$_SESSION[$index] = $message ;
	}
	/**
	 * Function to get stored messages
	*/
	function get_flashdata( $index ){
		$output = null;
		if( isset ($_SESSION[$index]) ){
			$output = $_SESSION[$index];

			unset($_SESSION[$index]);

		}
		return $output;
	}


	/**
	 * Function to store user data 
	*/
	function set_userdata( $index,$message ){
		$_SESSION[$index] = $message ;
	}
	/**
	 * Function to get stored messages
	*/

	/**
	 * Function to get stored messages
	*/
	function get_userdata( $index ){
		return isset ( $_SESSION[$index] ) ?  $_SESSION[$index] : null;
	}

	/**
	 * Function to get stored messages
	*/
	function unset_userdata( $index ){
		if ( isset ( $_SESSION[$index] )){
			unset($_SESSION[$index]);
		} 
	}
}
$session = new Session;