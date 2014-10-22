<?php
#ERROR CODE FOR COMMENT
#0 ACCESS DENIED
#1 ADD SUCCESS
#2 EDIT SUCCESS
#3 SEVERICE ERROR
#4 MINIUM INTERVAL 15 SECONDS
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
$replyTo = isset($_POST['replyTo']) ? check(decrypt($_POST['replyTo'])) : '0';
$sendTime = time();
$editTime = $sendTime;

$query = "SELECT SendTime FROM usr_Comment WHERE UserName ='".$username."' ORDER BY SendTime DESC LIMIT 0,1";
$result = runQuery($query);
//PRINT $sendTime."\n";
//print $sendTime."\n";
//print $result[0]['SendTime']."\n";
if($result!=NULL && ($sendTime-intval($result[0]['SendTime']))<15)
{
	print 4;
	closeDataBase();
	exit;
}

if($replyTo!='0')
{
	$query = 
		"SELECT UserName, SendTime, Content FROM usr_Comment 
		WHERE  CommentID = '".$replyTo."' AND TargetID = '".$targetID."' AND TargetDescript='".$targetDescript."';";
	$result = runQuery($query);
	if($result==NULL)
	{
		print 0;
		closeDataBase();
		exit;
	}
	$quoteArray = 
		array(
			"quoteAuthor"=>$result[0]['UserName'],
			"quoteSendTime"=>$result[0]['SendTime'],
			"quoteContent"=>text_Abstract($result[0]['Content'],120)
		);
	$quote = json_encode($quoteArray);
}
else
{
	$quote = '';
}

$query = 
	"INSERT INTO usr_Comment (UserName, Content, SendTime, EditTime, TargetID, TargetDescript, ReplyTo, Quote) 
	VALUE ('".$username."', '".$content."', '".$sendTime."', '".$editTime."', '".$targetID."', '".$targetDescript."', '".$replyTo."', '".$quote."')";
$affectedRow= runQueryNoReturn($query);
if($affectedRow)
{
	print 1;
}
else
{
	print 3;
}
?>