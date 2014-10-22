<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$creator=$_SESSION['name'];
$MemberArrayEncrypt = isset($_POST['id']) ? $_POST['id'] : array();

$memberList = "";

foreach($MemberArrayEncrypt AS $key)
{
	$data = json_decode(decrypt($key),true);
	$groupIDtmp = isset($data['groupID']) ? check($data['groupID']) : '' ;
	$memberName = isset($data['memberName']) ? check($data['memberName']) : '' ;
	if((isset($groupID))&&($groupID!=$groupIDtmp))
	{
		closeDatabase();
		exit;
	}
	$groupID = $groupIDtmp;
	$memberList .= "'".$memberName."',";
}
if(!isset($groupID ))
{
	closeDatabase();
	exit;
}

#check creator authority
$query = "SELECT * FROM usr_Group WHERE groupID='".$groupID."' AND creater='".$creator."';";
$affectRow = runQueryNoReturn($query);
if($affectRow==0)
{
	closeDatabase();
	exit;
}

if($memberList!="")
{
	$memberList = substr_replace( $memberList, "", -1 );
	$query = "DELETE FROM usr_GroupMember WHERE groupID='".$groupID."' AND memberName IN(".$memberList.");";
	runQueryNoReturn($query);
}

closeDatabase();
?>