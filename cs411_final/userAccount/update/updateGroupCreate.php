<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");

openDatabase();

$creator=check($_SESSION['name']);
$level = $_SESSION['level'];
if($level<1)
{
	echo "<div class='ui-state-error error'>Access Denied</div>";
	closeDatabase();
	exit;
}

$groupName=isset($_POST["groupName"]) ? check($_POST["groupName"]) : '';
$currentTime=time();

$query = "INSERT INTO usr_Group (groupName, createTime,creater) VALUE ('".$groupName."','".$currentTime."','".$creator."')";
$affectRow = runQueryNoReturn($query);

if($affectRow)
{
	echo "<div class='ui-state-highlight'>Group has been created</div>";
}
else
{
	echo "<div class='ui-state-error error'>Access Denied</div>";
}

closeDatabase();

?>