<?php
	set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
	
	require_once("DatabaseUtility.php");
	require_once('session.php');

	openDatabase();
	$result=getUserInfo($_SESSION['name']);
?>
	<form action="#" method="POST" id="userAccountForm">
		<label class="label_name">Username:</label> <label><?php echo $result['UserName']?></label><br />
		<label class="label_name">First Name:</label> <input type="text" name="fname" value="<?php echo $result['FirstName']?>"/><br />
		<label class="label_name"> Last Name:</label> <input type="text" name="lname" value="<?php echo $result['LastName']?>"/><br />
		<label class="label_name">Gender:</label> 
		<input id="male" name="gender" type="radio" value=0 <?php if ($result['Gender']==0) echo "checked=true"?>/><label>Male</label>
		<input id="female" name="gender" type="radio" value=1 <?php if ($result['Gender']==1) echo "checked=true"?>/><label>Female</label><br />
		<label class="label_name">LastUpdateTime: </label><label><?php echo date("F j, Y, g:i a",$result['LastUpdateTime'])?></label><br />
		<label class="label_name">BirthdayMonth: </label><input type="text" name="bmonth" value="<?php echo $result['BirthdayMonth']?>"/><br />
		<label class="label_name">BirthdayDay: </label><input type="text" name="bday" value="<?php echo $result['BirthdayDay']?>"/><br />
		<label class="label_name">BirthdayYear: </label><input type="text" name="byear" value="<?php echo $result['BirthdayYear']?>"/><br />
		<label class="label_name">E-mail: </label><label><?php echo $result['Email']?></label><br />
<?php
	$result=getAccountActivity($_SESSION['name']);
?>
		<label class="label_name">Type</label> <label><?php if($result['Level'] == 1) echo "Potential Patient"; else echo "Physician";?></label><br />
		<label class="label_name">Register Time:</label> <label><?php echo date("F j, Y, g:i a", $result['RegisterTime'])?></label><br />
		<label class="label_name">Account Status:</label> <label><?php echo  "Active"//$result['AccountStatus'])?></label><br />
		<label class="label_name">Last Login Time:</label> <label><?php echo date("F j, Y, g:i a", $result['LastLoginTime'])?></label><br />
		<label class="label_name">Last Logout Time:</label> <label><?php echo date("F j, Y, g:i a", $result['LastLogoutTime'])?></label><br />
		<label class="label_name">Login Duration:</label> <label><?php echo getDuration($result['LoginDuration'])."day(s)";?></label><br />
		<label class="label_name">No Activity Duration:</label> <label><?php echo getDuration($result['NoActivityDuration'])."day(s)";?></label><br />
		<input type="hidden" name="mod" value="UserGeneral"/>
		<input class="button" type="submit" value="Update" />
	</form>

<?php
	closeDatabase();
?>