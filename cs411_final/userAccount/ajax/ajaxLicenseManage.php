<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$username=$_SESSION['name'];
$level = $_SESSION['level'];
if($level<90)
{
	print "<div class='ui-state-error error'>ACCESS DENIED<div>";
	closeDatabase();
	exit;
}
$LicenseID = isset($_POST['value']) ? check(decrypt($_POST['value'])) : '';
$query = "SELECT * FROM usr_LicenseRequest WHERE LicenseID='$LicenseID'";
$result = runQuery($query);

if($result[0]!=NULL)
{
	if($result[0]['OpratorResult']==4)
	{
		$query = "SELECT Level FROM usr_UserStatistics WHERE UserName='".$result[0]['UserName']."';";
		$currentLevel = runQuery($query);
?>	
	<form id="<?php print $_POST['value'];?>" method="POST">
		<table cellpadding="2" cellspacing="0" border="0" style="padding-left:30px;">
			<tr><td>User Type:</td><td><?php print licenseLevel($currentLevel[0]['Level']);?></td></tr>
			<tr><td>Current Level:</td><td><?php print $currentLevel[0]['Level'];?></td></tr>
			<tr><td>Request Message:</td><td><?php print $result[0]['RequestMessage'];?></td></tr>
			<tr><td>Operator:</td><td><?php print $username;?></td></tr>
			<tr><td>Assign Level:</td><td><input type="number" name="level" value="<?php print $currentLevel[0]['Level'];?>" required/></td></tr>
			<tr><td>Comment:</td><td><textarea name="comment" rows="4" cols="60" style="width:100%" required></textarea> </td></tr>
			<tr>
				<td colspan="2">
					<input type="button" value="submit" class="button" onclick="licenseManage(<?php print "'".$_POST['value']."','".encrypt(1)."'";?>);"/>
					<input type="button" value="decline" class="button" onclick="licenseManage(<?php print "'".$_POST['value']."','".encrypt(0)."'";?>);"/>
				</td>
			</tr>
		</table>
	</form>
<?php		
	}
	else
	{
?>
	<table cellpadding="2" cellspacing="0" border="0" style="padding-left:30px;">
		<tr><td>Request Message:</td><td><?php print $result[0]['RequestMessage'];?></td></tr>
		<tr><td>Comment:</td><td><?php print $result[0]['OpratorComment'];?></td></tr>
	</table>
<?php
	}
}
else
{
	print "<div class='ui-state-error error'>ACCESS DENIED<div>";
}
closeDatabase();
?>