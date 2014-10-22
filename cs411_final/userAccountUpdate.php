<?php
$mod = isset($_POST['mod']) ? $_POST['mod'] : NULL;
//for degug
//print_r($_POST);
if(file_exists("userAccount/update/update".$mod.".php"))
{
	require_once("userAccount/update/update".$mod.".php");
}
else
{
	print "<div class='ui-state-error error'>ACCESS DENIED<div>";
}
?>