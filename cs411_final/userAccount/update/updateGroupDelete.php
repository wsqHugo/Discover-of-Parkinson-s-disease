<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");

openDatabase();

$username=$_SESSION['name'];
$groupID=isset($_POST['id']) ? check(decrypt($_POST['id'])) : '';

$query = "DELETE FROM usr_Group WHERE groupID = '".$groupID."' AND creater='".$username."';";
$affectRow = runQueryNoReturn($query);

if($affectRow)
{
	$query = "DELETE FROM usr_GroupMember WHERE groupID = '".$groupID."';";
	$affectRow = runQueryNoReturn($query);
	print 1;
}
else
	print 0;


//$groupName=getGroupNameByID($groupID);
//delete_usr_Group($groupID);
closeDatabase();
//echo "<label>Group $groupName has been deleted.<lable>";
?>