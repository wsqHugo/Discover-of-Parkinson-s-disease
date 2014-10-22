<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$username=$_SESSION['name'];
$CommentIdArrayEncrypt = isset($_POST['id']) ? $_POST['id'] : array();

$idList = "";

foreach($CommentIdArrayEncrypt AS $key)
{
	$idList .= "'".check(decrypt($key))."',";
}

if($idList!="")
{
	$idList = substr_replace( $idList, "", -1 );
	$query = "
				DELETE FROM usr_Comment WHERE CommentID IN(".$idList.") AND UserName='".$username."';
				UPDATE usr_Comment SET ReplyTo=0 , Quote='' WHERE ReplyTo IN(".$idList.");
				";
	runMultiQueryNoReturn($query);
}

closeDatabase();
?>