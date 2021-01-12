<?php
/**
 * Class  for upload files
 */
class Upload 
{
	public $config ;
	public $upload_info ;
	private $file_field ;
	function __construct()
	{
		$this->config =  array(
			'allowed_types' => '',
			'max_size' => '0',
			'upload_path' => 'uploads/',
		);

		$this->upload_info =  array(
			'uploaded' => '0',
			'file_name' => '',
			'error' => 'Error in file upload !',
		);
		$this->upload_error = null ;
	}
	function create_filename(){
		if ( !isset( $_FILES[$this->file_field]['name'] ) )  return null ;
		$upload_folder = $this->config['upload_path'];
		$upload_filename = basename($_FILES[$this->file_field]['name']);
		$original_filename = pathinfo($upload_filename, PATHINFO_FILENAME);
		$extension =  pathinfo($upload_filename, PATHINFO_EXTENSION);
		$FileCounter = 0;
		while (file_exists(BASE_PATH."/".$upload_folder."/".$upload_filename)) {
		     $FileCounter++;
		     $upload_filename = $original_filename . '_' . $FileCounter . '.' . $extension;
		}
		return $upload_folder."/".$upload_filename;
	}
	function do_upload( $file_field ){
		$this->file_field = $file_field;
		if( isset ($_FILES[$file_field]['name'] ) && $_FILES[$file_field]['name'] != '' ){
			$this->config['upload_path'] = rtrim($this->config['upload_path'] , '/');
			$upload_path = $this->config['upload_path'];
			if( !is_dir( BASE_PATH."/".$upload_path  ) ){
				mkdir( BASE_PATH."/".$upload_path );
			}
			$file_name = $this->create_filename();
			$target_file =  BASE_PATH."/".$file_name;
			if ($this->validate_file($file_field) === TRUE &&  move_uploaded_file( $_FILES[$file_field]["tmp_name"], $target_file )) {
				$this->upload_info =  array(
					'uploaded' => '1',
					'file_name' => $file_name,
					'error' => '',
				);
				return TRUE;
			}else{
				$this->upload_info =  array(
					'uploaded' => '0',
					'file_name' => '',
					'error' => $this->upload_error,
				);
				return FALSE;	
			}
			print_r($this->upload_info);
			exit;
		}else{
			 return FALSE;
		}
	}
	function validate_file($file_field){
		if ( $this->check_extension($file_field) === FALSE ){
			return FALSE;
		}else if (  $this->check_size($file_field) === FALSE ){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	function  check_extension( $file_field ){
		if ( $this->config['allowed_types']  != '' ){
			$extensions = explode("|",$this->config['allowed_types']);
			$ext = strtolower( pathinfo( basename( $_FILES[$file_field]['name'] ),PATHINFO_EXTENSION) );
			if ( in_array($ext, $extensions)){
				return TRUE;
			}else{
				$this->upload_error ='The file type not allowed !';
				return FALSE;
				
			}
		}else{
			return TRUE;
		}
	}
	function  check_size( $file_field ){
		if ( $this->config['max_size']  > 0  && $_FILES[$file_field]["size"] > $this->config['max_size']){
			$this->upload_error ='The file size is too larage ! Max.'.($this->config['max_size' ]/1000).'KB';
			return FALSE;
		}else{
			return TRUE;
		}
	}

}
$upload = new Upload;

?>