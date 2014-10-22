<?php
#ERROR CODE
#-1 UNEXPECTED ERROR
#0 ACCESS DENIED
#1 SUCEESS
#2 GROUP NAME LENGTH > 20
#3 EMAIL NOT EXISTED
#4 MEMBER HAS JOINED THE GROUP
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");

openDatabase();

$creator=check($_SESSION['name']);
$groupName=isset($_POST["groupName"]) ? check($_POST["groupName"]) : '';
if(strlen($groupName)>20)
{
	print 2;
	closeDatabase();
	exit;
}

$groupID=isset($_POST["groupID"]) ? check(decrypt($_POST["groupID"])) : '';

$query = "SELECT groupName FROM usr_Group WHERE groupID='".$groupID."' AND creater='".$creator."';";
$result = runQuery($query);
if($result!=NULL)
{
	if($result[0]['groupName']==$groupName)
	{
		print 1;
		exit;
	}
}
else
{
	print 0;
	closeDatabase();
	exit;
}

$query = "UPDATE usr_Group SET groupName='".$groupName."' WHERE groupID='".$groupID."' AND creater='".$creator."';";
$affectRow = runQueryNoReturn($query);

if($affectRow)
{
	print 1;
}
else
{
	print 0;
}
closeDatabase();
?>