<?php
	set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
	
	require_once("DatabaseUtility.php");
	require_once("session.php");
	openDatabase();
	$username=$_SESSION['name'];
	$result=getUserInfo($username);
	closeDatabase();
?>
<form action="#" method="POST" id="userAccountForm">
	<label>E-mail:</label> <label><?php echo $result["Email"];?></label><br />
	<label>New E-mail:</label> <input type="email" name="email" required/><br />
	<label>Password</label> <input type="password" name="pwd" required/><br />
	<input type="hidden" name="mod" value="UserEmail"/>
	<input class="button" type="submit" value="Reset" />
</form>