<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include('Net/SFTP.php');

class file_upload {

    var $the_file;
	var $the_temp_file;
    var $upload_dir;
	var $replace;
	var $do_filename_check;
	var $max_length_filename = 100;
    var $extensions;
	var $ext_string;
	var $http_error;
	var $rename_file; // if this var is true the file copy get a new name
	var $file_copy; // the new name
	var $message = array();
	var $create_directory = true;
	var $server;
	var $username;
	var $password;
	var $conn_id;
	var $sftp_conn;
	var $link;
	var $address;
	var $content;
	var $rowNum;
		
	function file_upload() 
	{
		$this->rename_file = false;
		$this->ext_string = '';
	}
	/*connect to ftp*/
	function connection() 
	{
		$this->sftp_conn = new Net_SFTP( $this->server );
		$this->sftp_conn->login( $this->username, $this->password );
	}
	
	function show_error_string($br = '<br />') 
	{
		$msg_string = '';
		foreach ($this->message as $value) {
			$msg_string .= $value.$br;
		}
		return $msg_string;
	}
	
	function set_file_name($new_name = '') // this 'conversion' is used for unique/new filenames
	{  
		if ($this->rename_file) {
			if ($this->the_file == '') return;
			$name = ($new_name == '') ? uniqid() : $new_name;
			sleep(3);
			$name = $name.$this->get_extension($this->the_file);
		} else {
			$name = str_replace(' ', '_', $this->the_file); // space will result in problems on linux systems
		}
		return $name;
	}
	
	function upload($to_name = '') 
	{
		$new_name = $this->set_file_name($to_name);
		
		if ($this->check_file_name($new_name)) 
		{
			if ($this->validateExtension()) 
			{
				if (is_uploaded_file($this->the_temp_file)) 
				{
					$this->file_copy = $new_name;
					
					if ($this->move_upload($this->the_temp_file, $this->file_copy)) 
					{
						$this->message[] = $this->error_text($this->http_error);
						if ($this->rename_file) $this->message[] = $this->error_text(16);
						return true;
					}
				} 
				else 
				{
					$this->message[] = $this->error_text($this->http_error);
					return false;
				}
			}
			else 
			{
				$this->show_extensions();
				$this->message[] = $this->error_text(11);
				return false;
			}
		} 
		else 
		{
			return false;
		}
	}

	function check_file_name($the_name) 
	{
		if ($the_name != '') {
			if (strlen($the_name) > $this->max_length_filename) {
				$this->message[] = $this->error_text(13);
				return false;
			} else {
				if ($this->do_filename_check == 'y') {
					if (preg_match('/^[a-z0-9_\.]*\.(.){1,5}$/i', $the_name)) {
						return true;
					} else {
						$this->message[] = $this->error_text(12);
						return false;
					}
				} else {
					return true;
				}
			}
		} else {
			$this->message[] = $this->error_text(10);
			return false;
		}
	}
	
	function get_extension($from_file) {
		$extend = explode( "." , $from_file );  
		$va = count( $extend ) - 1; 
		$ext = "." . strtolower( $extend[$va] );
		return $ext;
	}
	
	function validateExtension() 
	{
		$extension = $this->get_extension($this->the_file);
		$ext_array = $this->extensions;
		if (in_array($extension, $ext_array)) { 
			// check mime type hier too against allowed/restricted mime types (boolean check mimetype)
			return true;
		} 
		else 
		{
			return false;
		}
	}
	// this method is only used for detailed error reporting
	function show_extensions() 
	{
		$this->ext_string = implode(' ', $this->extensions);
	}
	
	function move_upload($tmp_file, $new_file) 
	{
		$this->sftp_conn->chdir($this->upload_dir);
		$this->sftp_conn->put( $new_file, $tmp_file, NET_SFTP_LOCAL_FILE);
		$this->sftp_conn->chmod(0777, $new_file);
		
		return true;
	}
	
	function check_dir($directory) 
	{
		if (!is_dir($directory)) {
			if ($this->create_directory) {
				umask(0);
				mkdir($directory, $this->dirperm);
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	function existing_file($file_name) 
	{
		if ($this->replace == 'y') {
			return true;
		} else {
			if (file_exists($this->upload_dir.$file_name)) {
				return false;
			} else {
				return true;
			}
		}
	}

	function get_uploaded_file_info($name) 
	{
		$str = 'File name: '.basename($name).PHP_EOL;
		$str .= 'File size: '.filesize($name).' bytes'.PHP_EOL;
		if (function_exists('mime_content_type')) {
			$str .= 'Mime type: '.mime_content_type($name).PHP_EOL;
		}
		if ($img_dim = getimagesize($name)) {
			$str .= 'Image dimensions: x = '.$img_dim[0].'px, y = '.$img_dim[1].'px'.PHP_EOL;
		}
		return $str;
	}
	// this method was first located inside the foto_upload extension
	function del_temp_file($file) 
	{
		$delete = @unlink($file); 
		clearstatcache();
		if (@file_exists($file)) { 
			$filesys = eregi_replace('/','\\',$file); 
			$delete = @system('del $filesys');
			clearstatcache();
			if (@file_exists($file)) { 
				$delete = @chmod ($file, 0644); 
				$delete = @unlink($file); 
				$delete = @system('del $filesys');
			}
		}
	}
	
	function create_file_field($element, $label = '', $length = 25, $show_replace = true, $replace_label = 'Replace old file?', $file_path = '', $file_name = '', $show_alternate = false, $alt_length = 30, $alt_btn_label = 'Delete image') 
	{
		$field = '';
		if ($label != '') $field = '
			<label>'.$label.'</label>';
		$field = '
			<input type="file" name="'.$element.'" size="'.$length.'" />';
		if ($show_replace) $field .= '
			<span>'.$replace_label.'</span>
			<input type="checkbox" name="replace" value="y" />';
		if ($file_name != '' && $show_alternate) {
			$field .= '
			<input type="text" name="'.$element.'" size="'.$alt_length.'" value="'.$file_name.'" readonly="readonly"';
			$field .= (!@file_exists($file_path.$file_name)) ? ' title="'.sprintf($this->error_text(17), $file_name).'" />' : ' />';
			$field .= '
			<input type="checkbox" name="del_img" value="y" />
			<span>'.$alt_btn_label.'</span>';
		} 
		return $field;
	}
	
	function check_file()
	{
		$this->address = 'ftp://' . $this->link . '/' . $this->upload_dir . $this->file_copy;
		$this->content = file( $this->address );
		
		if( $this->get_extension($this->the_file) == ".xlsx" || $this->get_extension($this->the_file) == ".xls" )
		{
			return $this->check_excel();
		}
	}
	
	private function check_excel()
	{
		if( count( $this->content ) == 0 || count( $this->content ) == 1 )
		{
			return false;
		}
		
		$this->content[0] = preg_split( '/[\t]/', $this->content[0] );
		$numCol = count( $this->content[0] );
		
		for( $j=1; $j<count( $this->content ); $j++ ) 
		{
			$this->content[$j] = preg_split( '/[\t]/', $this->content[$j] );

			if( $numCol != count( $this->content[$j] ) ) 
			{
				return false;
			}
		}
		
		for( $i=0; $i<count($this->content); $i++ ) 
		{
			for( $j=0; $j<count($this->content[$i]); $j++ ) 
			{
				if( $this->content[$i][$j] == "" ) 
				{
					return false;
				}
			}
		}
		
		return true;
	}
	
	function error_text($err_num) 
	{
		// start http errors
		$error[0] = 'File: <b>'.$this->the_file.'</b> successfully uploaded!';
		$error[1] = 'The uploaded file exceeds the max. upload filesize directive in the server configuration.';
		$error[2] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.';
		$error[3] = 'The uploaded file was only partially uploaded';
		$error[4] = 'No file was uploaded';
		$error[6] = 'Missing a temporary folder. ';
		$error[7] = 'Failed to write file to disk. ';
		$error[8] = 'A PHP extension stopped the file upload. ';
		
		// end  http errors
		$error[10] = 'Please select a file for upload.';
		$error[11] = 'Only files with the following extensions are allowed: <b>'.$this->ext_string.'</b>';
		$error[12] = 'Sorry, the filename contains invalid characters. Use only alphanumerical chars and separate parts of the name (if needed) with an underscore. <br>A valid filename ends with one dot followed by the extension.';
		$error[13] = 'The filename exceeds the maximum length of '.$this->max_length_filename.' characters.';
		$error[14] = 'Sorry, the upload directory does not exist!';
		$error[15] = 'Uploading <b>'.$this->the_file.'...Error!</b> Sorry, a file with this name already exitst.';
		$error[16] = 'The uploaded file is renamed to <b>'.$this->file_copy.'</b>.';
		$error[17] = 'The file %s does not exist.';
		
		return $error[$err_num];
	}
}
?>
