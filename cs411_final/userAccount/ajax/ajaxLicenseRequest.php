<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$username=$_SESSION['name'];
$LicenseID = isset($_POST['value']) ? check(decrypt($_POST['value'])) : '';
$query = "SELECT * FROM usr_LicenseRequest WHERE LicenseID='$LicenseID' AND UserName='$username'";
$result = runQuery($query);
($result==NULL) ? 
	print 	$error : 
	print 	json_encode(
				array(
					($result[0]['RequestMessage']==NULL) ? '--' : $result[0]['RequestMessage'],
					($result[0]['OpratorComment']==NULL) ? '--' : $result[0]['OpratorComment']
				)
			);

closeDatabase();
?>