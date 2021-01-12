<?php
	/**
	 * Class for form validation
	 */
	class Validation 
	{
		public $errors;
		public $rules;
		function __construct()
		{
			$this->rules = null;
		}
		function set_rules( $field,$label,$rules,$message=""){
			$this->rules[] = array(
	            'field' => $field,
	            'label' => $label,
	            'rules' => $rules,
	            'message' => $message,
	        );
		}
		function run( ){
			$this->field = null ;
			$this->errors=[];
			if ( $this->rules != null && is_array($this->rules ) ){
				foreach ( $this->rules as $row) {
					$field = isset( $row['field'] )? $row['field'] :'';
					$label = isset( $row['label'] )? $row['label'] :'';
					$rules = isset( $row['rules'] )? $row['rules'] :'';
		    		$ar_rules = explode("|", $rules);
		    		foreach ($ar_rules as  $rule) {
		    			if ( $rule == "required" ){
		    				if ( !$this->validate_required( $field,$label) ) break;
		    			}else if ( $rule == "valid_email" ){
		    				if ( !$this->validate_email( $field,$label ) ) break;
		    			}else if ( $rule == "valid_phone" ){
		    				if ( !$this->validate_phone( $field,$label ) ) break;
		    			}
		    		}
		    	}
		    }
	    	return $this->errors;
		}
		function validate_required( $field,$label ) {
			if (  empty($_POST[ $field ])  ){
				$this->errors[$field] =  $label . " field is required ";
				return false;
			}else{
				return true;
			}	
		}
		function validate_email( $field,$label ) {
			if ( !empty( $_POST[ $field ] )  ){
				if ( !preg_match( 
"^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $_POST[ $field ])) {
					$this->errors[$field] =  $label . " field is not  a valid email address ";
					return false;
				}else{
					return true;
				}	
			}
		}
		function validate_phone( $field,$label ) {
			if ( !empty( $_POST[$field] )  ){
				$mobileregex = "/^[6-9][0-9]{9}$/";  
				if ( !preg_match($mobileregex, $_POST[ $field ])){
					$this->errors[$field] =  $label . " field is not a valid phone number ";
					return false;
				}else{
					return true;
				}
				
			}
		}
	}
	$form_validation = new Validation;

?>