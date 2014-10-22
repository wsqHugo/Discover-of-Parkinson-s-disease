<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$username=$_SESSION['name'];
$pwd=isset($_POST["pwd"]) ? md5($_POST["pwd"]) : '';
$email=isset($_POST["email"]) ? check($_POST["email"]) : '';

if ($pwd!=getPassword($username))
{
	echo "<div class='ui-state-error error'>";
	echo "Please re-enter your password<br />";
	echo "The password you entered is incorrect. Please try again (make sure your caps lock is off).<br />";
	echo "Forgot your password? Request a new one.<br />";
	echo "</div>";
}
else
{
	if(resetEmail($username, $email))
	{
		echo "<div class='ui-state-highlight'>Your email has been changed</div>";
	}
	else
	{
		echo "<div class='ui-state-error error'>Email has been registered</div>";
	}
}
closeDatabase();
?>