<?php
	set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
	require_once("DatabaseUtility.php");
	require_once("session.php");
	openDatabase();
	$level=$_SESSION['level'];
	$username=$_SESSION['name'];
	echo "<div class='message'>";
	echo $username."\t".licenseLevel($level);
	echo "</div>";	
	closeDatabase();
?>
<div>
<table cellpadding="0" cellspacing="0" border="0" class="display data" id="userAccountTableLicenseView">
	<thead>
		<tr>
			<th></th>
			<th>Username</th>
			<th>Send Time</th>
			<th>Operator</th>
			<th>Result</th>
		</tr>
	</thead>
	<tbody>
		<tr><td colspan="4" class="dataTables_empty">Loading data from server</td></tr>
	</tbody>
</table>
</div>
