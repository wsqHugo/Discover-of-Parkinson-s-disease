<?php
$mod = isset($_POST['mod']) ? $_POST['mod'] : NULL;

$error = json_encode(array("<div class='ui-state-error error'>ACCESS DENIED<div>"));

if(file_exists("userAccount/ajax/ajax".$mod.".php"))
{
	require_once("userAccount/ajax/ajax".$mod.".php");
}
else
{
	print $error;
}
?>