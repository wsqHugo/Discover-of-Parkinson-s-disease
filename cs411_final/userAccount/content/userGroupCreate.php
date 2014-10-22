<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');

require_once("DatabaseUtility.php");
require_once("session.php");

openDatabase();
$username=$_SESSION['name'];
?>
<form action="#" method="POST" id="userAccountForm">
	<label>Creator:</label> <label><?php echo $result['UserName']?></label><br />
	<label>Group Name </label> <input type="text" name="groupName"/><br />
	<input type="hidden" name="mod" value="GroupCreate" required/>
	<input class="button" type="submit" value="Create" />
</form>
<?php
closeDatabase();
?>