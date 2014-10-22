<?php
require_once('session.php');

if( $_SESSION['status'] == "offline" )
{
  header('Location: index.php');
}

include("classes/upload_class.php"); //classes is the map where the class file is stored


//for sftp
/*
$upload = new file_upload();

$upload->upload_dir = '/Applications/XAMPP/xamppfiles/htdocs/shangquan/uploader/uploads';
$upload->extensions = array('.xlsx', '.xls'); // specify the allowed extensions here
$upload->rename_file = false;

$upload->server = 'localhost';
$upload->username = 'root';
$upload->password = '';

if(!empty($_FILES)) 
{
	$upload->the_temp_file = $_FILES['userfile']['tmp_name'];
	$upload->the_file = $_FILES['userfile']['name'];
	$upload->http_error = $_FILES['userfile']['error'];
	$upload->do_filename_check = 'y'; // use this boolean to check for a valid filename
	
	//if( $_SESSION['status'] == 'online' )
	//{
		$upload->connection();
		
		if( $upload->upload() )
		{
			$_SESSION['uploadfile'] = $upload->file_copy;
		
			//if( $upload->check_file() )
			//{
				echo '<div id="status">success</div>';
				echo '<div id="message">'. $upload->file_copy .' Successfully Uploaded</div>';
				//return the upload file
				echo '<div id="uploadedfile">'. $upload->file_copy .'</div>';
			//}
			//else
			//{
			//	echo '<div id="status">success</div>';
			//	echo '<div id="message">'. $upload->file_copy .' does not meet requirment</div>';
			//	echo '<div id="uploadedfile">'. $upload->file_copy .'</div>';
			//}
		}
		else 
		{	
			echo '<div id="status">failed</div>';
			echo '<div id="message">'. $upload->show_error_string() .'</div>';	
		}
	//}
}*/

//for localhost
$target = "/Applications/XAMPP/xamppfiles/htdocs/shangquan/cs411_final/uploads/";

if( !empty($_FILES) )
{
 	if( move_uploaded_file($_FILES['userfile']['tmp_name'], $target.$_FILES['userfile']['name']) ) 
 	{
 		$_SESSION['uploadfile'] = $_FILES['userfile']['name'];

 		echo '<div id="status">success</div>';
		echo '<div id="message">'. $_FILES['userfile']['name'] .' Successfully Uploaded</div>';
		echo '<div id="uploadedfile">'. $_FILES['userfile']['name'] .'</div>';
 	} 
 	else 
 	{
 		echo '<div id="status">failed</div>';
		echo '<div id="message">'. $_FILES['userfile']['name'] .'</div>';
 	}
}
else
{
	echo '<div id="status">failed</div>';
	echo '<div id="message">Failed to upload file.</div>';
}
?>