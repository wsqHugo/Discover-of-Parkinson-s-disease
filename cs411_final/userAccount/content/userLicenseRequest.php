<?php
	set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
	
	require_once("DatabaseUtility.php");
	require_once('session.php');
?>
<div>
<div class="message" id="username" >Username: <?php echo $_SESSION['name'];?></div>
<div class="message" id="level" >License: <?php echo licenseLevel($_SESSION['level']);?></div>
<form action="#" method="POST" id="userAccountForm">
	<label>Request Message:</label><br />  
    <textarea name="message" cols="40" rows="4" placeholder="Please type in the Authority you want here." style="width:100%" required></textarea><br />
	<input type="hidden" name="mod" value="UserRequest"/>
	<input class="button" type="submit" value="submit" />
</form>
</div>