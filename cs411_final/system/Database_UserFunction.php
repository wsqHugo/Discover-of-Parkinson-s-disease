<?php

function handleSingleQuotation($message)
{
	$newMessage=$message;
	$pos=0;
	for ($loop=0;$loop<strlen($message);$loop++)
	{
		if ($message[$loop]=='\'')
		{
			$newMessage[$pos]='\\';
			$pos++;
		}
		$newMessage[$pos]=$message[$loop];
		$pos++;
	}
	return $newMessage;
}

// sign in
	function checkUsername($username)
	{
		$query = "SELECT * FROM usr_UserAccount WHERE UserName='$username'";
		$result = runQuery($query);
		$num=0;
		foreach ($result as $row)
			$num++;
		if ($num>0)
			return true;
		else
			return false;
	}
	
	function checkEmail($email)
	{
		$query = "SELECT * FROM usr_UserAccount WHERE Email='$email'";
		$result = runQuery($query);
		$num=0;
		foreach ($result as $row)
			$num++;
		if ($num>0)
			return true;
		else
			return false;
	}
	
	function addUser($username, $pwd, $email, $type)
	{
		$currentTime=time();
		$query = "INSERT INTO usr_UserAccount (UserName, Password, Email, LastUpdateTime) VALUE ('$username', '$pwd', '$email',$currentTime)";
		runQueryNoReturn($query);
		$query = "INSERT INTO usr_UserDetails (UserName, LastUpdateTime) VALUE ('$username',$currentTime)";												
		runQueryNoReturn($query);	
		$query = "INSERT INTO usr_UserStatistics (UserName, RegisterTime, Level) VALUE ('$username', $currentTime, $type)";		
		runQueryNoReturn($query);
	}
	
//Login
	function getPassword($username)
	{
		$query = "SELECT Password FROM usr_UserAccount WHERE UserName='$username'";
		$result = runQuery($query);
		return $result[0]['Password'];
	}
	
		//Ge,Huangyi add
	
	function checkLogin($username)
	{
		$query = "SELECT UserName, Password FROM usr_UserAccount WHERE UserName='$username' OR Email='$username'";
		$result = runQuery($query);
		return $result;
	}
//Logout
	function updateLoginTime($username)
	{
		$loginTime=time();
		$query = "SELECT LastLogoutTime FROM usr_UserStatistics WHERE UserName='$username'";
		$result = runQuery($query);
		$duration=$loginTime-$result[0]['LastLogoutTime'];
		$query = "UPDATE usr_UserStatistics SET LastLoginTime=$loginTime, NoActivityDuration=$duration WHERE UserName='$username'";
		runQueryNoReturn($query);
	}
	
	function updateLogoutTime($username)
	{
		$query = "SELECT LastLoginTime FROM usr_UserStatistics WHERE UserName='$username'";
		$result = runQuery($query);
		$currentTime=time();
		$duration=$currentTime-$result[0]['LastLoginTime'];
		$query = "UPDATE usr_UserStatistics SET LastLogoutTime=$currentTime, LoginDuration=$duration WHERE UserName='$username'";
		runQueryNoReturn($query);
	}
	
	function getUsernameByEmail($email)
	{
		$query = "SELECT * FROM usr_UserAccount WHERE Email='$email'";
		$result = runQuery($query);
		return $result[0]['UserName'];
	}
//Ge,Huangyi add
//reset password
	//check information before sending email
	function reset_password_check($username,$email,&$message,&$string)
	{
		$query = "SELECT * FROM usr_UserAccount WHERE UserName='$username' AND Email='$email'"; 
		$result = runQuery($query);
		$num=0;
		
		foreach ($result as $row)
			$num++;
		
		if($num==0) 
			$message +=2;
		else
			$string = $username.",".$email.",".$result[0]['Password'].",".time().",".$result[0]['LastUpdateTime'];
			
		return ;
	}
	
	//check call back link
	function reset_password_link_check($username,$email,$password,$time,$update_time)
	{
		if(time()-$time>24*3600)
			return false;
			
		$query = "SELECT * FROM usr_UserAccount WHERE UserName='$username' AND Email='$email' AND Password='$password' AND LastUpdateTime='$update_time'"; //Username='$username' OR 
		$result = runQuery($query);
		$num=0;
		
		foreach ($result as $row)
			$num++;
		
		if($num==0) 
			return false;
		else
			return true;
	}
//Ge,Huangyi add
	
//User Account Center
	function getDuration($time)
	{
		$milliSecondPerDay=1000*24*60*60;
		$day=$time/$milliSecondPerDay;
		$day=(int)$day;
		if ($time>$day*$milliSecondPerDay)
			return $day+1;
		else
			return $day;
	}
	
	function getUserInfo($username)
	{
		$query = "SELECT * FROM usr_UserAccount WHERE UserName='$username'";
		$result = runQuery($query);
		foreach ($result as $row)
			return $row;
	}
	
	function getUserDetails($username)
	{
		$query = "SELECT * FROM usr_UserDetails WHERE UserName='$username'";
		$result = runQuery($query);
		foreach ($result as $row)
			return $row;
	}
	
	function getAccountActivity($username)
	{
		$query = "SELECT * FROM usr_UserStatistics WHERE UserName='$username'";
		$result = runQuery($query);
		foreach ($result as $row)
			return $row;
	}
	
	function getLevelByUserName($username)
	{
		$query = "SELECT * FROM usr_UserStatistics WHERE UserName='$username'";
		$result = runQuery($query);
		foreach ($result as $row)
			return $row['Level'];
	}
	//License Begin
	function licenseLevel($level)
	{
		if ($level<=89)
			return "User";
		else if ($level<=94)
			return "Data Manager";
		else if ($level<=98)
			return "Developer Manager";
		else
			return "Root";
	}
	
	function getLicenseRequest($username)
	{
		$query = "SELECT * FROM usr_LicenseRequest WHERE UserName='$username'";
		$result = runQuery($query);
		return $result;
	}
	
	
	function getLicenseRequestByID($id)
	{
		$query = "SELECT * FROM usr_LicenseRequest WHERE LicenseID='$id'";
		$result = runQuery($query);
		foreach ($result as $row)
			return $row;
	}
	
	function getPendingLicense($userid, $level)
	{
		if ($userid==NULL)
		{
			$query = "SELECT * FROM usr_LicenseRequest WHERE OpratorResult is NULL and UserName in (SELECT UserName FROM usr_UserStatistics WHERE Level<=$level)";
			$result = runQuery($query);
		}
		else
		{
			$query = "SELECT * FROM usr_LicenseRequest WHERE OpratorResult is NULL and UserName='$userid' and UserName in (SELECT UserName FROM usr_UserStatistics WHERE Level<=$level)";
			$result = runQuery($query);
		}
		return $result;
	}
	
	function setLicense($username,$level)
	{
		$query = "UPDATE usr_UserStatistics SET Level=$level WHERE UserName='$username'";
		RunQueryNoReturn($query); 
	}
	
	function requestLicense($username, $message)
	{
		$message=handleSingleQuotation($message);
		$sendTime=time();  
		$query = "INSERT INTO usr_LicenseRequest (LicenseID, UserName, SendTime, RequestMessage) VALUE (0, '$username', $sendTime, '$message')";
		RunQueryNoReturn($query);
	}
	
	function operateLicense($id, $operator, $level, $comment)
	{
		$query = "SELECT UserName FROM usr_LicenseRequest WHERE LicenseID=$id";
		$result= runQuery($query);
		$currentLevel=getLevelByUsername($result[0]['UserName']);
		if ($level!=0)
			setLicense($result[0]['UserName'],$level);
		$comment=handleSingleQuotation($comment);
		if ($level>0)
			if ($level<=$currentLevel)
				$level=1;
			else
				$level=2;
		$query = "UPDATE usr_LicenseRequest SET Oprator='$operator', OpratorResult=$level, OpratorComment='$comment' WHERE LicenseID=$id";
		RunQueryNoReturn($query);
	}

	function operateResult($result)
	{
		if ($result==0)
			return "Decline";
		else if ($result==1)
			return "Downgraded";
		else if ($result==2)
			return "Upgraded";
		else if($result==3)
			return "No changing";
		else if($result==4)
			return "Pending";
	}
	//License End
	
	function updateUserAccount($username, $fName, $lName, $Gender, $BirthdayMonth, $BirthdayDay, $BirthdayYear)
	{
		$currentTime=time();
		/*
		if ($BirthdayMonth=="")
			$BirthdayMonth='NULL';
		if ($BirthdayDay=="")
			$BirthdayMonth='NULL';
		if ($BirthdayYear=="")
			$BirthdayMonth='NULL';
		*/
		$query="UPDATE usr_UserAccount SET FirstName='$fName', LastName='$lName',Gender='$Gender', LastUpdateTime='$currentTime',BirthdayMonth=".(($BirthdayMonth=='') ? "NULL" : "'$BirthdayMonth'").",BirthdayDay=".(($BirthdayDay=='') ? "NULL" : "'$BirthdayDay'").",BirthdayYear=".(($BirthdayYear=='') ? "NULL" : "'$BirthdayYear'")." WHERE UserName='$username'";
		runQueryNoReturn($query);
	}
	
	function updateUserDetails($username, $userProfile, $interest, $country, $mailAddress, $jobTitle, $organizationName)
	{
		$currentTime=time();
		$query="UPDATE usr_UserDetails SET UserProfile='$userProfile',Interest='$interest',Country='$country', MailAddress='$mailAddress',JobTitle='$jobTitle',OrganizationName='$organizationName',LastUpdateTime='$currentTime' WHERE UserName='$username'";
		runQueryNoReturn($query);
	}
	
	function updateLastUpdateTime($username)
	{
		$currentTime=time();
		$query="UPDATE usr_UserAccount SET LastUpdateTime=$currentTime WHERE UserName='$username'";
		runQueryNoReturn($query);
	}
	
	function resetPwd($username, $pwd)
	{
		updateLastUpdateTime($username);
		$query="UPDATE usr_UserAccount SET Password='$pwd' WHERE UserName='$username'";
		runQueryNoReturn($query);
	}
	
	function resetEmail($username, $email)
	{
		updateLastUpdateTime($username);
		$query="UPDATE usr_UserAccount SET Email='$email' WHERE UserName='$username'";
		if(checkEmail($email))
		{
			//Duplicate email
			return false;
		}
		else
		{
			runQueryNoReturn($query);
			return true;
		}
	}

	
	
	
	
	
	
	
	
//Comments & Replies
	function addComment($username,$content,$targetID, $targetDescript, $replyTo)
	{
		$currentTime=time();
		$content=handleSingleQuotation($content);
		$query = "INSERT INTO usr_Comment (CommentID, UserName, Content, SendTime, TargetID, TargetDescript, ReplyTo) VALUE (0, '$username', '$content', $currentTime, '$targetID', '$targetDescript', $replyTo)";
		runQueryNoReturn($query);
	}
	
	function getCommentUsernameByID($id)
	{
		$query = "SELECT UserName FROM usr_Comment WHERE CommentID='$id'";
		$result = runQuery($query);	
		return $result[0]['UserName'];
	}
	function getComment($targetID, $targetDescript)
	{
		$query = "SELECT * FROM usr_Comment WHERE TargetID='$targetID' AND TargetDescript='$targetDescript' AND ReplyTo=0";
		$result = runQuery($query);
		return $result;
	}
	
	function getCommentByUsername($username)
	{
		$query = "SELECT * FROM usr_Comment WHERE UserName='$username'";
		$result = runQuery($query);
		return $result;
	}
	
	function getReply($replyTo, $targetID, $targetDescript)
	{
		$query = "SELECT * FROM usr_Comment WHERE TargetID=$targetID AND TargetDescript='$targetDescript' AND ReplyTo=$replyTo";
		$result = runQuery($query);
		return $result;
	}
	
	function deleteCommentByID($id)
	{	
		$query = "DELETE FROM usr_Comment WHERE ReplyTo='$id'";
		runQueryNoReturn($query);
		$query = "DELETE FROM usr_Comment WHERE CommentID='$id'";
		runQueryNoReturn($query);
	}
	
	//Chao Fang: Group
		
	function create_ptm_usr_Group($groupName, $creater)
	{
		$currentTime=time();
		$query = "INSERT INTO usr_Group (groupID, groupName, createTime,creater) VALUE (0, '$groupName',$currentTime,'$creater')";
		RunQueryNoReturn($query);	
	}
	
	function delete_ptm_usr_Group($groupId)
	{
		$query = "DELETE FROM usr_GroupMember WHERE groupID = $groupId";
		RunQueryNoReturn($query);
		$query = "DELETE FROM usr_Group WHERE groupID = $groupId";
		RunQueryNoReturn($query);
	}
	
	function leave_usr_Group($groupId, $username)
	{
		$joinDate=time();
		$query = "DELETE FROM usr_GroupMember where groupId = '$groupId' and memberName='$username'";
		RunQueryNoReturn($query);
	}
	
	function getMyGroupInfo($username)
	{
		$query="SELECT * FROM usr_Group WHERE creater='$username'";
		$result=runQuery($query);
		return $result;
	}
	
	function getInvolvedGroupInfo($username)
	{
		$query="SELECT G.groupID, groupName, creater, createTime, joinDate FROM usr_Group G, usr_GroupMember GM WHERE G.groupID=GM.groupID and GM.memberName='$username'";
		$result=runQuery($query);
		return $result;
	}	
	
	function getGroupNameByID($groupID)
	{
		$query = "SELECT * FROM usr_Group where groupId = '$groupID'";
		$result= runQuery($query);
		return $result[0]['groupName'];
	}
	
	function viewGroupMembers($groupID)
	{
		$query = "SELECT * FROM usr_GroupMember where groupId = '$groupID'";
		$result = runQuery($query);
		return $result;
	}
	
	function getJoinTime($groupID, $username)
	{
		$query="SELECT joinDate FROM usr_GroupMember WHERE groupID='$groupID' and memberName='$username'";
		$result=runQuery($query);
		return $result;
	}
	
	function invite_usr_Group($groupId, $username)
	{
		$joinDate=time();
		$query = "INSERT INTO usr_GroupMember (groupID, memberName, joinDate) VALUE ($groupId,'$username', $joinDate)";
		RunQueryNoReturn($query);
	}
	
	function getUserFussy($username)
	{
		$query = "SELECT UserName FROM usr_UserAccount WHERE UserName like CONCAT('%', CONCAT('$username','%'))";
		$result = runQuery($query);
		return $result;
	}
	/*
	function deleteCommentByName($username)
	{
		$query = "SELECT CommentID FROM ptm_usr_Comment WHERE UserName='$username'";
		$result = runQuery($query);
		$query = "DELETE FROM ptm_usr_Comment WHERE UserName='$username'";
		runQueryNoReturn($query);
		foreach ($result as $row)
			deleteReplyByID($row['CommentID']);
	}
	function deleteCommentByPage($targetID, $targetDescript)
	{
		$query = "DELETE FROM ptm_usr_Comment WHERE TargetID='$targetID' AND TargetDescript='$targetDescript'";
		runQueryNoReturn($query);
		deleteReplyByPage($targetID, $targetDescript);
	}

	function deleteReplyByName($username)
	{
		$query = "DELETE FROM ptm_usr_Reply WHERE UserName='$username'";
		runQueryNoReturn($query);
	}
	function deleteReplyByPage ($targetID, $targetDescript)
	{
		$query = "DELETE FROM ptm_usr_Reply WHERE UserName='$username' TargetID='$targetID' AND TargetDescript='$targetDescript'";
		runQueryNoReturn($query);
	}
	*/
?>
