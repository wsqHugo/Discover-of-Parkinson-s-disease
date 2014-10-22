<form action="#" method="POST" id="userAccountForm">
	<label>Current:</label><input type="password" name="currentPwd" required/><br />
	<label>New:</label>		<input type="password" name="newPwd" required/><br />
	<label>Re-type new: </label><input type="password" name="newPwd1" required/><br />
	<input type="hidden" name="mod" value="UserPwd"/>
	<input class="button" type="submit" value="Reset" />
</form>