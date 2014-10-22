<?php
# ERROE CODE
# 0 SUCCESS
# 1 ACCESS DENIED
# 2 INVALID LICENCE LEVEL
# 3 NO SUCH OPERATION
# 4 ERROR REQUEST LICENSE ID OR HAS BEEN OPERATORED BY OTHER USER
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");

$operator=$_SESSION['name'];
$level = $_SESSION['level'];
if($level<90)
{
	print "1";
	exit;
}

openDatabase();
$newLevel	= isset($_POST["level"])		?	intval(check($_POST["level"]))	:	intval('1');
$comment	= isset($_POST["comment"])		?	check($_POST["comment"])		:	'';
$licenseID	= isset($_POST["licenseID"])	?	check(decrypt($_POST["licenseID"]))	: NULL;
$option		= isset($_POST["option"])		?	check(decrypt($_POST["option"])): NULL;
if($licenseID==NULL || $option==NULL)
{
	print "3";
}
else
{
	if($option==1){
		if($newLevel<1||$newLevel>89)
		{
			print "2";
		}else 
		{
			$query="SELECT UserName FROM usr_LicenseRequest WHERE LicenseID='".$licenseID."';";
			$result = runQuery($query);
			$username = $result[0]['UserName'];
			
			$query="SELECT Level FROM usr_UserStatistics WHERE UserName='".$username."';";
			$result = runQuery($query);
			$currentLevel = $result[0]['Level'];
			if($currentLevel>$newLevel)
				$operationResult = 1;
			else if($currentLevel<$newLevel)
				$operationResult = 2;
			else 
				$operationResult = 3;
				
			if($currentLevel!=$newLevel)
			{
				$query="UPDATE usr_UserStatistics SET Level = '".$newLevel."' WHERE UserName='".$username."';";
				$affectRow = runQueryNoReturn($query);
				if(!$affectRow)
				{
					print '1';
					closeDatabase();
					EXIT;
				}
			}
				
			$query="UPDATE usr_LicenseRequest SET Oprator='".$operator."', OpratorResult='".$operationResult."', OpratorComment='".$comment."' WHERE LicenseID='".$licenseID."' AND OpratorResult=4;";
			$affectRow = runQueryNoReturn($query);
			if($affectRow)
				print '0';
			else
				print '4';
		}
			
	} else if($option==0){
		$operationResult = 0;
		$query="UPDATE usr_LicenseRequest SET Oprator='".$operator."', OpratorResult='".$operationResult."', OpratorComment='".$comment."' WHERE LicenseID='".$licenseID."' AND OpratorResult=4;";
		$affectRow = runQueryNoReturn($query);
		if($affectRow)
			print '0';
		else
			print '4';
			
	} else {
		print "3";
	}
}
closeDatabase();
?>