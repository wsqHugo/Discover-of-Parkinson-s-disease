<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$creator =$_SESSION['name'];
$data = isset($_POST['value']) ? json_decode(decrypt($_POST['value']),true) : array();
$groupID = isset($data['groupID']) ? check($data['groupID']) : '' ;
$memberName = isset($data['memberName']) ? check($data['memberName']) : '' ;

#check creator authority
$query = "SELECT * FROM usr_Group WHERE groupID='".$groupID."' AND creater='".$creator."';";
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

$query = "SELECT * FROM usr_GroupMember WHERE groupID='".$groupID."' AND memberName='".$memberName."';";
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

$query = "SELECT * FROM usr_UserDetails WHERE UserName='".$memberName."';";
$result = runQuery($query);
if($result!=NULL)
{
?>
	<table cellpadding="2" cellspacing="0" border="0" style="padding-left:30px;">
		<tr>
			<td>Profile:</td>
			<td><?php ($result[0]['UserProfile']=='') ? print '--' : print $result[0]['UserProfile'];?></td>
		</tr>
		<tr>
			<td>Interest:</td>
			<td><?php ($result[0]['Interest']=='') ? print '--' : print $result[0]['Interest'];?></td>
		</tr>
		<tr>
			<td>Country:</td>
			<td><?php ($result[0]['Country']=='') ? print '--' : print $result[0]['Country'];?></td>
		</tr>
		<tr>
			<td>Job:</td>
			<td><?php ($result[0]['JobTitle']=='') ? print '--' : print $result[0]['JobTitle'];?></td>
		</tr>
		<tr>
			<td>Organiztion:</td>
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