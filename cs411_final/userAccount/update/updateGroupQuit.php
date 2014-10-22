<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$memberName=$_SESSION['name'];
$groupIdArrayEncrypt = isset($_POST['id']) ? $_POST['id'] : array();

$groupList = "";

foreach($groupIdArrayEncrypt AS $key)
{
	$groupList .= "'".check(decrypt($key))."',";
}

if($groupList!="")
{
	$groupList = substr_replace( $groupList, "", -1 );
	$query = "DELETE FROM usr_GroupMember WHERE groupID IN(".$groupList.") AND memberName='".$memberName."';";
	runQueryNoReturn($query);
}

closeDatabase();
?>