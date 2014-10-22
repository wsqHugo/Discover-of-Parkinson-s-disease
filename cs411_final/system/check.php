<?php
	function check($query)
	{
		$res=$GLOBALS['mysqli']->real_escape_string($query);
		//$res=stripslashes($res);
		return $res;
	}
?>
