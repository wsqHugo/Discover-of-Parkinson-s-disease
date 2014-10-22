<?php
	require_once('system/DatabaseUtility.php');

	if(isset($_COOKIE['PHPSESSID']))
	{
		session_id($_COOKIE['PHPSESSID']);
	}
		
	session_save_path("/Applications/XAMPP/xamppfiles/temp/");
	session_start();
	openDatabase();
	
	if (isset($_SESSION['name']) && $_SESSION['name']!='Guest' && checkUsername($_SESSION['name']))
	{
		$username = $_SESSION['name'];
		$_SESSION['output'] = "<a id='My_Account' href='userAccount.php'>$username</a><a id='Logout'>logout</a>";
		//add user logiin time update
		$_SESSION['status'] = "online";
		$result = getAccountActivity($_SESSION['name']);
		$_SESSION['level'] = $result['Level'];
	}
	else
	{
		setcookie("PHPSESSID",session_id());//need path
		$_SESSION['name']="Guest";
		$_SESSION['output'] = '<a id="Login">Login</a><a herf="#" id="Sign_up" >Sign up</a>';
		$_SESSION['status'] = "offline";
		$_SESSION['level'] = 0;
	}
	
	closeDatabase() ;

?>