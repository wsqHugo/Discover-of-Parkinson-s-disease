<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
$username=$_SESSION['name'];
$newPwd=isset($_POST["newPwd"]) ? md5($_POST["newPwd"]) : '';
$currentPwd=isset($_POST["currentPwd"]) ? md5($_POST["currentPwd"]) : '';
$newPwd1=isset($_POST["newPwd1"]) ? md5($_POST["newPwd1"]) : '';
openDatabase();
if ($currentPwd!=getPassword($username))
{
	echo "<div class='ui-state-error error'>";
	echo "Please re-enter your password<br />";
	echo "The password you entered is incorrect. Please try again (make sure your caps lock is off).<br />";
	echo "Forgot your password? Request a new one.<br />";
	echo "</div>";
}
else
{
	if ($newPwd!=$newPwd1)
	{
		echo "<div class='ui-state-error error'>";
		echo "New passwords don't match.";
		echo "</div>";
	}
	else
	{
		resetPwd($username, $newPwd);
		echo "<div  class='ui-state-highlight'>Your password has been changed.</div>";
	}
}
closeDatabase();
?>