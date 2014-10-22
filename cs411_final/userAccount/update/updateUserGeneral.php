<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");

openDatabase();
updateUserAccount(
	$_SESSION['name'],
	check(isset($_POST['fname'])? $_POST['fname']:''), 
	check(isset($_POST['lname']) ? $_POST['lname']:''), 
	check(isset($_POST['gender']) ? $_POST['gender']:''),
	check(isset($_POST['bmonth']) ? $_POST['bmonth']:''), 
	check(isset($_POST['bday']) ? $_POST['bday']:''), 
	check(isset($_POST['byear']) ? $_POST['byear']:''),
	check(isset($_POST['byear']) ? $_POST['byear']:'')
);
echo "<div class='ui-state-highlight'>Updated</div>";
closeDatabase();
?>