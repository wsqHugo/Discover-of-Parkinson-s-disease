<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");

openDatabase();
updateUserDetails(
	$_SESSION['name'],
	check(isset($_POST['profile'])? $_POST['profile']:''), 
	check(isset($_POST['interest'])? $_POST['interest']:''), 
	check(isset($_POST['country'])? $_POST['country']:''), 
	check(isset($_POST['mailAddr'])? $_POST['mailAddr']:''), 
	check(isset($_POST['job'])? $_POST['job']:''), 
	check(isset($_POST['org'])? $_POST['org']:'')
);
echo "<div class='ui-state-highlight'>Updated</div>";
closeDatabase();
?>