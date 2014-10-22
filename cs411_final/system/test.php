<?php
require_once('DatabaseUtility.php');
	openDatabase();
	$protein=getUserFussy("a");
	foreach ($protein as $row)
		echo $row['UserName'];
	closeDatabase();
?>