
<?php 
	//this file shows the status of the user
	//every page will contain session.php and this page
	require_once "session.php";
	
?>
<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>p3db</title>
	<link href="css/account.css" rel="stylesheet">	
	<link href="css/custom-theme/jquery-ui-1.9.1.custom.min.css" rel="stylesheet">
	<script src="js/jquery-1.8.2.js"></script>
	<script src="js/jquery-ui-1.9.1.custom.min.js"></script>
	<script src="js/account.js"></script>

	</head>
	<body>
		<div id="form_container" style="display:none">
			<div class='loading_dialog'>
				<img src='img/loader-earth.gif' alt='Loading'/>
				<span> Loading</span>
			</div>
		</div>
		<div id="load_container" style="display:none" >
			<div class="loading_dialog">
				<img src='img/loader-earth.gif' alt='Processing'/>
				<span> Processing</span>
			</div>
		</div>
		<div id="account_status" class="group ui-widget-content ui-corner-all"><?php print $_SESSION['output']; ?></div>
		
		