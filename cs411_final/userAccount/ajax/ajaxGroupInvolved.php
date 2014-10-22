<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$memberName =$_SESSION['name'];
$groupID = isset($_POST['value']) ? check(decrypt($_POST['value'])) : array();

#check creator authority
$query = "SELECT * FROM usr_GroupMember WHERE groupID='".$groupID."' AND memberName ='".$memberName ."';";
$affectRow = runQueryNoReturn($query);
if($affectRow==0)
{
?>
	<table cellpadding="1" cellspacing="0" border="0" style="padding-left:30px;">
		<tr><td><div class='ui-state-error error'>ACCESS DENIED<div></td></tr>
	</table>
<?php
	closeDatabase();
	exit;
}

$query = "
	SELECT count(*) AS size FROM usr_GroupMember WHERE groupID='".$groupID."';
	SELECT * FROM usr_Group WHERE groupID='".$groupID."';
";
$result = runMultiQuery($query);
$groupSize = $result[0][0]['size'];
$creator = $result[1][0]['creater'];
$createTime = $result[1][0]['createTime'];
$groupName = $result[1][0]['groupName'];

$query = "SELECT * FROM usr_UserDetails WHERE UserName='".$creator."';";
$result = runQuery($query);

if($result!=NULL)
{
?>
	<table cellpadding="2" cellspacing="0" border="0" style="padding-left:30px;">
		<tr>
			<td>Group Name:</td>
			<td><?php print $groupName;?></td>
		</tr>
		<tr>
			<td>gGroup Create Time:</td>
			<td><?php print date("Y-m-d H:i:s", $createTime);?></td>
		</tr>
		<tr>
			<td>Group Size:</td>
			<td><?php print $groupSize+1;?></td>
		</tr>
		<tr>
			<td>Creator:</td>
			<td><?php print $creator;?></td>
		</tr>
		<tr>
			<td>Creator's Profile:</td>
			<td><?php ($result[0]['UserProfile']=='') ? print '--' : print $result[0]['UserProfile'];?></td>
		</tr>
		<tr>
			<td>Creator's Interest:</td>
			<td><?php ($result[0]['Interest']=='') ? print '--' : print $result[0]['Interest'];?></td>
		</tr>
		<tr>
			<td>Creator's Country:</td>
			<td><?php ($result[0]['Country']=='') ? print '--' : print $result[0]['Country'];?></td>
		</tr>
		<tr>
			<td>Creator's Job:</td>
			<td><?php ($result[0]['JobTitle']=='') ? print '--' : print $result[0]['JobTitle'];?></td>
		</tr>
		<tr>
			<td>Creator's Organiztion:</td>
			<td><?php ($result[0]['OrganizationName']=='') ? print '--' : print $result[0]['OrganizationName'];?></td>
		</tr>
	</table>
<?php
}
else
{
?>
	<table cellpadding="1" cellspacing="0" border="0" style="padding-left:30px;">
		<tr><td><div class='ui-state-error error'>ACCESS DENIED<div></td></tr>
	</table>
<?php
}

closeDatabase();
?>