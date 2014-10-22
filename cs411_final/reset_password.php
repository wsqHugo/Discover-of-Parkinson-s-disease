<?php
	//require_once "account_status.php";
	require_once "util/header.php";
	require_once('plugin/email/decoding.php');
	
	if($_SESSION['status']=="online")
	{
		header("location: index.php");
		exit;
	}

	$check = false;
	if(isset($_REQUEST['link']))
	{
		$string = decode_string($_REQUEST['link']);
		$array = explode(",",$string);
		
		if(sizeof($array)==5)
		{
			$username = $array[0];
			$email = $array[1];
			$password = $array[2];
			$time = $array[3];
			$update_time = $array[4];
			opendatabase();
			if(reset_password_link_check($username,$email,$password,$time,$update_time))
			{
?>
				<div id="reset_password">
					<h3 style="text-align:center">Please type in new password</h3>
					<br/>
					<?php if(isset($_REQUEST['message'])){ ?>
						<span style="color:red;">Password Error: 6-20character, accept 0~9,a~z,A~Z, or two passwords are not the same.</span><br/>
					<?php } ?>
								
					<form action="account.php" method="POST" id="reset_password_submit">
						<div>
							<div class="group">
								<label for="pass1" class="text">Password</label>
								<input name="password1" type="password" required class="text ui-widget-content ui-corner-all" />
							</div>
							</br>
							<div class="group">
								<label for="pass2" class="text">Confirm&nbsp</label>
								<input name="password2" type="password" required class="text ui-widget-content ui-corner-all"/><br/>
							</div>
						</div>
						<div id="jqueryButtonDiv">
							<input type="submit" value="submit" class="jqueryButton"/>
						</div>
						<input type="hidden" name="link" value="<?php print $_REQUEST['link']; ?>"/>
						<input type="hidden" name="mode" value="update_password"/>
					</form>
				</div>

<?php
				$check = true;
			}
			closedatabase();
		}
	}
	
	if(!$check)
	{
?>
		<div class="text ui-widget-content" style="width:500px; padding:10px; margin:auto; margin-top: 20px; margin-bottom: 20px;text-align:center">
	
		<?php if(isset($_REQUEST['success'])) {?>
			Password has been updated.<br/>
		<?php } else { ?>
			Link is invalid.<br/>
		<?php } ?>	
		
			<a href="index.php"> After 3 second, go to index page<a>
		</div>
		<script type="text/javascript">
		var t = setTimeout
		(
			function()
			{
				window.location.href="index.php";
			},
			3000
		);
		</script>
		
<?php
	}

	
?>


</body>
</html>
