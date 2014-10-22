<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
$level=$_SESSION['level'];
if($level<90)
{
	print "<div class='ui-state-error error'>ACCESS DENIED<div>";
	exit;
}
?>
<div>
<table cellpadding="0" cellspacing="0" border="0" class="display data" id="userAccountTableLicenseManage">
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
		<tr><td colspan="5" class="dataTables_empty">Loading data from server</td></tr>
	</tbody>
</table>
</div>
