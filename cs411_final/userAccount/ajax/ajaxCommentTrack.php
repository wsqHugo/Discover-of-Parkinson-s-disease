<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$idArray = isset($_POST['id']) ? json_decode(decrypt($_POST['id']),true) : array();
if(count($idArray)!=2)
{
	print 0;
	closeDataBase();
	exit;
}
$targetID = check($idArray["targetID"]);
$targetDescript = check($idArray["targetDescript"]);
$CommentID = isset($_POST['commentID']) ? check(decrypt($_POST['commentID'])) : '0';

$query = 
		"SELECT COUNT(*) AS count FROM usr_Comment 
		WHERE  CommentID < '".$CommentID."' AND TargetID = '".$targetID."' AND TargetDescript='".$targetDescript."';";
$result = runQuery($query);
print $result[0]['count'];


closeDatabase();
?>