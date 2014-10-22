<?php
#ERROR CODE
#-1 UNEXPECTED ERROR
#0 ACCESS DENIED
#1 SUCEESS
#2 GROUP NAME LENGTH > 20
#3 EMAIL NOT EXISTED
#4 MEMBER HAS JOINED THE GROUP
#5 CREATOR CANNOT JOIN THE GROUP
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");

openDatabase();

$creator=check($_SESSION['name']);
$email=isset($_POST["email"]) ? check($_POST["email"]) : '';
$groupID=isset($_POST["groupID"]) ? check(decrypt($_POST["groupID"])) : '';

#check creator authority
$query = "SELECT * FROM usr_Group WHERE groupID='".$groupID."' AND creater='".$creator."';";
$affectRow = runQueryNoReturn($query);
if($affectRow==0)
{
	print 0;
	closeDatabase();
	exit;
}

$query = "SELECT UserName FROM usr_UserAccount WHERE Email='".$email."';";
$result = runQuery($query);
if($result==NULL)
{
	print 3;
	closeDatabase();
	exit;
}
else
{
	$memberName = $result[0]['UserName'];
	if($memberName==$creator)
	{
		print 5;
		closeDatabase();
		exit;
	}
}

$query = "SELECT memberName FROM usr_GroupMember WHERE groupID='".$groupID."' AND memberName='".$memberName."';";
$affectRow = runQueryNoReturn($query);
if($affectRow)
{
	print 4;
	closeDatabase();
	exit;
}


$query = "INSERT INTO usr_GroupMember (groupID,memberName,joinDate)VALUES ('".$groupID."','".$memberName."','".time()."');";
$affectRow = runQueryNoReturn($query);

if($affectRow)
{
	print 1;
}
else
{
	print -1;
}
closeDatabase();
?>