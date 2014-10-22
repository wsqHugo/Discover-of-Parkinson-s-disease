<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();
$username=$_SESSION['name'];
$message=check(isset($_POST["message"])?$_POST["message"]:'');
$sendTime=time();  
$query = "INSERT INTO usr_LicenseRequest (UserName, SendTime, RequestMessage,OpratorResult) VALUE ('$username', $sendTime, '$message','4')";
runQueryNoReturn($query);
//requestLicense($username, $message);
echo "<div class='ui-state-highlight'>The request message has been submitted.</div>";
closeDatabase();
?>