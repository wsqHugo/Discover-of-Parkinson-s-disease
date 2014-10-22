<?php
	set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
	
	require_once("DatabaseUtility.php");
	require_once("session.php");
	
	openDatabase();
	$username=$_SESSION['name'];
	$result = getUserDetails($username);
?>
	<form action="#" method="POST" id="userAccountForm">
		<label class="label_name">Username:</label> <label><?php echo $result['UserName']?></label><br/>
		<label>User Profile:</label><br/> <textarea rows="3" cols="80" name="profile" style="width:100%"><?php echo $result['UserProfile']?></textarea><br />
		<label>Interest:</label><br/> <textarea rows="3" cols="30" name="interest"  style="width:100%"><?php echo $result['Interest']?></textarea><br />
		<label class="label_name">Country:</label>  <input type="text" name="country" value="<?php echo $result['Country']?>"/><br />
		<label>Mail Address: </label><br/><textarea rows="3" cols="30" name="mailAddr"  style="width:100%"><?php echo $result['MailAddress']?></textarea><br />
		<label class="label_name">Job Title: </label><input type="text" name="job" value="<?php echo $result['JobTitle']?>"/><br />
		<label class="label_name">Organization Name: </label><input type="text" name="org" value="<?php echo $result['OrganizationName']?>"/><br />
		<label class="label_name">Last Update Time: </label> <label><?php echo date("F j, Y, g:i a", $result['LastUpdateTime'])?></label><br />
		<input type="hidden" name="mod" value="UserDetails"/>
		<input class="button" type="submit" value="Update" />
	</form>
<?php
	closeDatabase();
?>