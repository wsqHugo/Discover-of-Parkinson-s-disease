<?php
set_include_path('system'.PATH_SEPARATOR.'templates');
require_once('DatabaseUtility.php');
require_once("session.php");
openDatabase();

$stype = isset($_GET['stype']) ? $_GET['stype'] : '';
if($stype=='CommentManage')
{
	$sTable = "usr_Comment";
	$sJoinCondition = "UserName='".$_SESSION['name']."'";
	$aColumns = array('CommentID','ReplyTo','SendTime','LastReplyTime','TargetID');
	// Select distinct or not
	$distinct = false;
	$sWhere="WHERE UserName='".$_SESSION['name']."'";
	$dataHandleFunction = 'ptmUserCommentManageDataHandele';
}
else if($stype=='LicenseView')
{
	$sTable = "usr_LicenseRequest";
	$sJoinCondition = "UserName='".$_SESSION['name']."'";
	$aColumns = array('LicenseID','UserName','SendTime','Oprator','OpratorResult');
	// Select distinct or not
	$distinct = false;
	$sWhere="WHERE UserName='".$_SESSION['name']."'";
	$dataHandleFunction = 'ptmUserLicenseDataHandele';
}
else if($stype=='LicenseManage')
{
	$level = $_SESSION['level'];
	if($level<90)
	{
		error();
		exit;
	}
	$sTable = "usr_LicenseRequest";
	$sJoinCondition = "";
	$aColumns = array('LicenseID','UserName','SendTime','Oprator','OpratorResult');
	// Select distinct or not
	$distinct = false;
	$sWhere="";
	$dataHandleFunction = 'ptmUserLicenseManageDataHandele';
	
}
else if($stype=='GroupView')
{
	$sTable = "usr_Group";
	$sJoinCondition = "creater='".$_SESSION['name']."'";
	$aColumns = array('groupName','creater','createTime','creater');
	// Select distinct or not
	$distinct = false;
	$sWhere="WHERE creater='".$_SESSION['name']."'";
	$dataHandleFunction = 'ptmUserGroupViewDataHandele';
	
}
else if($stype=='GroupMemberView')
{
	$groupID = isset($_GET['GroupID']) ? check(decrypt($_GET['GroupID'])) : '';
	$query = "SELECT * FROM usr_Group WHERE groupID='".$groupID."' AND creater ='".$_SESSION['name']."';";
	$affectRow = runQueryNoReturn($query);
	if($affectRow)
	{
		$sTable = "usr_GroupMember";
		$sJoinCondition = "groupID='".$groupID."'";
		$aColumns = array('memberName','memberName','joinDate','memberName');
		// Select distinct or not
		$distinct = false;
		$sWhere="WHERE groupID='".$groupID."'";
		$dataHandleFunction = 'ptmUserGroupMemberViewDataHandele';
	}
	else
	{
		error();
		closeDataBase();
		exit;
	}
	
}
else if($stype=='GroupInvolved')
{
	$sTable = "usr_Group, usr_GroupMember";
	$sJoinCondition = "usr_Group.groupID = usr_GroupMember.groupID AND usr_GroupMember.memberName='".$_SESSION['name']."'";
	$aColumns = array('usr_Group.groupName','usr_Group.creater','usr_GroupMember.joinDate','usr_Group.groupName');
	// Select distinct or not
	$distinct = false;
	$sWhere="WHERE ".$sJoinCondition;
	$dataHandleFunction = 'ptmUserGroupInvolvedDataHandele';
}
else if($stype=='Comment')
{
	$idArray = isset($_GET['id']) ? json_decode(decrypt($_GET['id']),true) : array();
	if(count($idArray)!=2)
	{
		error();
		exit;
	}
	$_GET['iSortCol_0'] = 2;
	$_GET['iSortingCols'] = 1;
	$_GET['sSortDir_0'] = 'asc';
	$_GET['bSortable_2'] = true;
	$targetID = check($idArray["targetID"]);
	$targetDescript = check($idArray["targetDescript"]);
	$sTable = "
		usr_Comment INNER JOIN usr_UserAccount ON usr_Comment.UserName=usr_UserAccount.UserName 
		INNER JOIN usr_UserDetails ON usr_Comment.UserName=usr_UserDetails.UserName
		INNER JOIN usr_UserStatistics ON usr_Comment.UserName=usr_UserStatistics.UserName";
	$sJoinCondition = "TargetID='".$targetID."' AND TargetDescript='".$targetDescript."'";// ORDER BY usr_Comment.SendTime ASC";
	$aColumns = array('usr_UserAccount.UserName','usr_Comment.Content','usr_Comment.SendTime');
	// Select distinct or not
	$distinct = false;
	$sWhere="WHERE ".$sJoinCondition;
	$dataHandleFunction = 'ptmUserCommentDataHandele';
}
else
{
	error();
	closeDataBase();
	exit;
}

$sLimit = dataTableQueryConditionLimit();
$sOrder = dataTableQueryConditionOrdering($aColumns);
$sWhere = dataTableQueryConditionFiltering($sWhere,$aColumns);
$rResult = dataTableRunQuery($distinct,$sTable,$sWhere,$sOrder,$sLimit);

$iFilteredTotal = dataTableQueryFoundRows();
$iTotal = dataTableQueryTotalRows($sTable,$sJoinCondition);
dataTableQueryResultOutput($iTotal,$iFilteredTotal,$rResult,$dataHandleFunction);

closeDataBase();

function ptmUserCommentManageDataHandele($row)
{
	return 	array(
				"<img src='img/details_open.png' alt='view' mod='Comment' title='view' value='".encrypt($row['CommentID'])."' style='cursor:pointer'/>",
				($row["ReplyTo"]==0) ? '--' : getCommentUsernameByID($row["ReplyTo"]),
				date("Y-m-d H:i:s", $row['SendTime']),
				($row['LastReplyTime']!=NULL) ? date("Y-m-d H:i:s", $row['LastReplyTime']) : '--',
				"<a title='link' class='icon-link icon-large icon-button' target='_blank' href='".$row['TargetDescript']."?id=".$row['TargetID']."&ref=&commentID=".encrypt($row['CommentID'])."#commentTable'></a>&nbsp&nbsp
				<a style='cursor:pointer'><i class='icon-edit icon-large' title='edit' onclick='editCommentAlert(\"".encrypt(json_encode(array("targetID"=>$row['TargetID'],"targetDescript"=>$row['TargetDescript'])))."\",\"".encrypt($row['CommentID'])."\")'></i></a>&nbsp&nbsp
				<a style='cursor:pointer'><i class='icon-trash icon-large icon-button' title='delete' onclick='deleteCommentAlertById(\"".encrypt($row['CommentID'])."\")'></i></a>"
			);
}

function ptmUserLicenseDataHandele($row)
{
	$result = $row['OpratorResult'];
	if($result==4)
		$result = "<i style='color:blue'><i class='icon-spinner icon-large icon-spin' title='Pending'></i> Pending</i>";
	else if($result==0)
		$result = "<i class='icon-ban-circle icon-large' style='color:red' title='Declined'  href='#'> Declined</i>";
	else if($result==1)
		$result = "<i class='icon-arrow-down icon-large' style='color:brown' title='Downgraded'> Downgraded</i>";
	else if($result==2)
		$result = "<i class='icon-arrow-up icon-large' style='color:green' title='Upgraded'  href='#'> Upgraded</i>";
	else if($result==3)
		$result = "<i class='icon-minus icon-large' title='No Changing'> No Changing</i>";
		
	return 	array(
				"<img src='img/details_open.png' alt='view' mod='LicenseRequest' title='view' value='".encrypt($row['LicenseID'])."' style='cursor:pointer'/>",
				$row['UserName'],
				date("Y-m-d H:i:s", $row['SendTime']),
				(($row['Oprator']=='')?'--':$row['Oprator']),
				$result
				
			);
}

function ptmUserLicenseManageDataHandele($row)
{
	$result = $row['OpratorResult'];
	if($result==4)
		$result = "<i style='color:blue'><i class='icon-spinner icon-large icon-spin' title='Pending'></i> Pending</i>";
	else if($result==0)
		$result = "<i class='icon-ban-circle icon-large' style='color:red' title='Declined'  href='#'> Declined</i>";
	else if($result==1)
		$result = "<i class='icon-arrow-down icon-large' style='color:brown' title='Downgraded'> Downgraded</i>";
	else if($result==2)
		$result = "<i class='icon-arrow-up icon-large' style='color:green' title='Upgraded'  href='#'> Upgraded</i>";
	else if($result==3)
		$result = "<i class='icon-minus icon-large' title='No Changing'> No Changing</i>";
		
	return 	array(
				"<img src='img/details_open.png' alt='view' mod='LicenseManage' title='view' value='".encrypt($row['LicenseID'])."' style='cursor:pointer'/>",
				$row['UserName'],
				date("Y-m-d H:i:s", $row['SendTime']),
				(($row['Oprator']=='')?'--':$row['Oprator']),
				$result,
			);
}

function ptmUserGroupViewDataHandele($row)
{		
	return 	array(
				$row['groupName'],
				$row['creater'],
				date("Y-m-d H:i:s", $row['createTime']),
				"<a style='cursor:pointer'><i title='delete group' onclick='deleteGroupAlert(\"".encrypt($row['groupID'])."\",\"".$row['groupName']."\")' class='icon-trash icon-large icon-button'></i></a>&nbsp
				<a style='cursor:pointer'><i title='rename group' onclick='renameGroupAlert(\"".encrypt($row['groupID'])."\",\"".$row['groupName']."\")' class='icon-edit icon-large icon-button'></i></a>&nbsp
				<a style='cursor:pointer'><i title='view group' onclick='openGroup(\"".encrypt($row['groupID'])."\",\"".$row['groupName']."\")' class='icon-signin icon-large icon-button'></i></a>"
			);
}

function ptmUserGroupMemberViewDataHandele($row)
{		
	return 	array(
				"<img src='img/details_open.png' alt='view' mod='GroupMemberView' title='view' value='".encrypt(json_encode(array("groupID"=>$row['groupID'],"memberName"=>$row['memberName'])))."' style='cursor:pointer'/>",
				$row['memberName'],
				date("Y-m-d H:i:s", $row['joinDate']),
				"<span title='delete member' style='cursor:pointer' onclick='deleteGroupMemberAlertById(\"".encrypt(json_encode(array("groupID"=>$row['groupID'],"memberName"=>$row['memberName'])))."\",\"".$row['memberName']."\")'><i class='icon-trash icon-large'> </i></span>"
			);
}

function ptmUserGroupInvolvedDataHandele($row)
{		
	return 	array(
				"<img src='img/details_open.png' alt='view' mod='GroupInvolved' title='view' value='".encrypt($row['groupID'])."' style='cursor:pointer'/> ".$row['groupName'],
				$row['creater'],
				date("Y-m-d H:i:s", $row['joinDate']),
				"<span title='Quit Group' style='cursor:pointer' onclick='quitGroupAlertById(\"".encrypt($row['groupID'])."\",\"".$row['groupName']."\")'><i class='icon-trash icon-large'></i> Quit</span>"
			);
}

function ptmUserCommentDataHandele($row)
{	
	if ($row['Level']<=89)
		$color = 'green';
	else if ($row['Level']<=94)
		$color = 'blue';
	else if ($row['Level']<=98)
		$color = 'yellow';
	else
		$color = 'red';
	$left = "
		<div>
			<div class='head group'><span class='left'><i class='icon-user icon-large' style='color:".$color."' title='".licenseLevel($row['Level'])."'></i> ".$row['UserName']."</span></div>
			<table style='font-size:10pt'>
				<tr><td>Country</td><td>".$row['Country']."</td></tr>
				<tr><td>Job</td><td>".$row['JobTitle']."</td></tr>
				<tr><td>Organzation</td><td>".$row['OrganizationName']."</td></tr>
			</table>
		</div>
		";
	$right = "
		<div>
			<div class='head group'>
				<span class='left'>Publish Time: ".date("Y-m-d H:i:s", $row['SendTime'])." | <a onclick='commentFilterByUser(\"".$row['UserName']."\")'>View This User</a> | <a onclick='commentFilterByUser(\"\")'>View ALL</a></span>
				<span class='right'>";
	$right .=		($_SESSION['level']>=1) ? "<i class='icon-comment-alt icon-large' title='reply' onclick='addCommentAlert(\"".$_GET['id']."\",\"".encrypt($row['CommentID'])."\")'> reply</i> " : "";
	$right .=		($row['UserName']==$_SESSION['name']) ? 
						"<i class='icon-edit icon-large' title='edit' onclick='editCommentAlert(\"".$_GET['id']."\",\"".encrypt($row['CommentID'])."\")'> edit</i> 
						<i class='icon-trash icon-large' title='delete' onclick='deleteCommentAlertById(\"".encrypt($row['CommentID'])."\")'> delete</i>" 
						: "";
	$right .=		"</span>
			</div>";
	if($row['SendTime']!=$row['EditTime'])
		$right .="<div class='editTime'>Last Edit Time: ".date("Y-m-d H:i:s",$row['EditTime'])."</div>";
	if($row['Quote']!='')
	{
		$quote = json_decode($row['Quote'],true);
		$right .=	"<div>
						<table class='quote group'>
							<tr>
								<td  class='quote-left'><div><i class='icon-quote-left icon-2x'></i></div></td>
								<td>
									<div>".$quote['quoteAuthor']." Publish at ".date("Y-m-d H:i:s", $quote['quoteSendTime'])." &nbsp&nbsp<a title='view' onclick='commentPage(\"".encrypt($row['ReplyTo'])."\")'><i class='icon-reply icon-large'></i></a></div><br/>
									<div class='quote-content'>".$quote['quoteContent']."</div>
								</td>
								<td  class='quote-right'><div><i class='icon-quote-right icon-2x'></i></div></td>
							</tr>	
						</table>
					</div>";
	}
	$right .=	"<div class='content'>".str_replace("\n","<br/>\n",$row['Content'])."</div>
		</div>
		";
	return 	array(
				$left,
				$right,
				""
			);
}

function error()
{
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => 0,
		"iTotalDisplayRecords" => 0,
		"aaData" => array(array("<div class='ui-state-error error'>ACCESS DENIED<div>"))
	);
	print json_encode( $output );
}
?>