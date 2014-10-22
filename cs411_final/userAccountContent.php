<?php
$mod = isset($_GET['mod']) ? $_GET['mod'] : NULL;
if(file_exists("userAccount/content/user".$mod.".php"))
{
	require_once("userAccount/content/user".$mod.".php");
}
else
{
	print "<div class='ui-state-error error'>ACCESS DENIED or UNDER DEVELOPMENT<div>";
}
?>