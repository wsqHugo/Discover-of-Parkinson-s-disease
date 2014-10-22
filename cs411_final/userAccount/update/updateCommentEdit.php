<?php
#ERROR CODE FOR COMMENT
#0 ACCESS DENIED
#1 ADD SUCCESS
#2 EDIT SUCCESS
#3 SEVERICE ERROR
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDataBase();
$level=$_SESSION['level'];

if($level<1)
{
	print 0;
	closeDataBase();
	exit;
}

$idArray = isset($_POST['id']) ? json_decode(decrypt($_POST['id']),true) : array();
if(count($idArray)!=2)
{
	print 0;
	closeDataBase();
	exit;
}

$username = check($_SESSION['name']);
$content = isset($_POST['commentContent']) ? check($_POST['commentContent']) : '';
if(strlen($content>500))
	$content = substr($content, 0,500);
$targetID = check($idArray["targetID"]);
$targetDescript = check($idArray["targetDescript"]);
$commentID = isset($_POST['commentID']) ? check(decrypt($_POST['commentID'])) : '';
$editTime = time();


$query = 
	"UPDATE usr_Comment SET Content='".$content."', EditTime='".$editTime."' 
	WHERE  CommentID = '".$commentID."' AND UserName='".$_SESSION['name']."'AND TargetID = '".$targetID."' AND TargetDescript='".$targetDescript."';"; 
$affectedRow= runQueryNoReturn($query);
if($affectedRow)
{
	print 2;
}
else
{
	print 3;
}
?>