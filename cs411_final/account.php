<?php
require_once('system/DatabaseUtility.php');
require_once('session.php');
openDatabase();

Switch($_REQUEST['mode'])
{
case "checkusername": 	

	if(strlen($_REQUEST['username'])>20 || strlen($_REQUEST['username'])<6)
		print "invalid";
	else
	{
		if(checkUsername($_REQUEST['username']))
			print "exist";
		else
			print "notexist";
	}
	
	break;
						
						
case "checkemail": 		

	if(strlen($_REQUEST['email'])>50||strlen($_REQUEST['email'])<10)
		print "invalid";
	else
	{
		if(checkEmail($_REQUEST['email']))
			print "exist";
		else
			print "notexist";
	}
	
	break;

case "checkcaptcha":	
	
	if($_REQUEST['captcha']==$_SESSION['captcha'])	
		print "correct";
	else
		print "incorrect";
		
	break;

case "login":
	$result = checkLogin($_REQUEST['username']);
	
	if ($result)
		{
			if($result[0]['Password']!=md5($_REQUEST['password']))
			{
				
				print 2;
			}
			else
			{
				$username = $result[0]['UserName'];
				$_SESSION['name'] = $username;
				updateLoginTime($username);
				
				if($_REQUEST['cookie']=="true")
					setcookie("PHPSESSID",session_id(),time()+30*24*3600);
				else
					setcookie("PHPSESSID",session_id());
				
			}
		}
		else
			print 1;
	break;
	
case "signup":
	$message = 0;
	
	function string_check($string,$length)
	{
		for($i=0;$i<$length;$i++)
		{
			if(($string[$i]>='a'&&$string[$i]<='z')||($string[$i]>='A'&&$string[$i]<='Z')||$string[$i]>='0'&&$string[$i]<='9')
				continue;
			else
				return false;
		}
		return true;
	}
	
	if(strlen($_REQUEST['username'])<=20 && strlen($_REQUEST['username'])>=6&&checkUsername($_REQUEST['username'])==true) $message++;
	if(strlen($_REQUEST['email'])<=50&&strlen($_REQUEST['email'])>=10&&checkEmail($_REQUEST['email'])==true) $message +=2;
	if($_SESSION['captcha']!=$_REQUEST['captcha']) $message +=4;
	if($_REQUEST['password1']!=$_REQUEST['password2']||
		strlen($_REQUEST['password1'])>20||
		strlen($_REQUEST['password1'])<6||
		(!string_check($_REQUEST['password1'],strlen($_REQUEST['password1'])))
	)
		$message +=8;
	
	if($message == 0) 
	{
		$_SESSION['name']=$_REQUEST['username'];
		setcookie("PHPSESSID",session_id());
		addUser($_REQUEST['username'], md5($_REQUEST['password1']), $_REQUEST['email'], $_REQUEST['type']);

		if($_REQUEST['type'] == 1)
		{
			/*
			$query_phy = "SELECT UserName FROM usr_UserStatistics WHERE level = 2";

	        if( $result_phy = $mysqli->prepare( $query_phy ) )
	        {
	          $result_phy->execute();
	          $result_phy->store_result();
	          $result_phy->bind_result( $physician );
	        }

	        $updateGroup = array();

	        while( $result_phy->fetch() )
	        {
	        	$updateGroup[] = $physician;
	        }

	        $updateGroup_string = implode("|", $updateGroup);
			*/
	        $updateGroup_string = "|";

	        $query_usr_test = "INSERT INTO usr_authorization(userName, groupName) VALUE (?, ?)";

			if( $result_usr_test = $mysqli->prepare($query_usr_test) )
			{
				//echo $groupName; 
				$result_usr_test->bind_param( 'ss', $_REQUEST['username'], $updateGroup_string );
				$result_usr_test->execute();
				$result_usr_test->store_result();
			}
		}

		print $message;
	}
	else
	{
		print $message;
	}
	
	break;

case "logout":
	updateLogoutTime($_SESSION['name']);
	foreach($_SESSION as $key=>$value )
	{
		if($key=="captcha")
			continue;
		else
			unset($_SESSION[$key]);
	}

	foreach($_SESSION as $key => $value){
		print "$key = $value<br>\n" ;
	}

	setcookie("PHPSESSID",session_id(),time()-1);

	break;
	
case "reset_password":
	$message = 0;
	$string = "";
	reset_password_check($_REQUEST['username'],$_REQUEST['email'],$message,$string);

	if($_SESSION['captcha'] !=$_REQUEST['captcha'])
	{
		$message +=1;
	}

	if($message>0)
	{
		print $message;
	} 
	else
	{
		require_once('plugin/email/send_email.php');
		require_once('plugin/email/encoding.php');
		
		$string = encode_string($string);
		
		//test link
		//$string = "http://dev.p3db.org/Huangyi/p3db_account_ver4/reset_password.php?link=".$string;
		$string = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],'/'))."/reset_password.php?link=".$string;
		
		$subject = "Reset Password";
		$body = "Hi, ".$_REQUEST['username']."\n<br/><br/>".
				"&nbsp&nbsp&nbsp Here is the link of reseting password.\n<br/>".
				"&nbsp&nbsp&nbsp <a href=\"$string\">$string</a><br/><br/>".
				"Admin";
		
		if(send_email($_REQUEST['email'],$_REQUEST['username'],$subject,$body))
		{
			print $message;
		}
	}
	break;
	
case "update_password":
	require_once('plugin/email/decoding.php');
	
	function check_pass($pass1,$pass2)
	{
		if($pass1!=$pass2)
			return false;
			
		$length = strlen($pass1);
		
		if($length>20||$length<6)
			return false;
			
		for($i=0;$i<$length;$i++)
		{
			if(($pass1[$i]>='a'&&$pass1[$i]<='z')||($pass1[$i]>='A'&&$pass1[$i]<='Z')||$pass1[$i]>='0'&&$pass1[$i]<='9')
					continue;
				else
					return false;
		}
		
		return true;
	}
	
	$check = false;
	
	if(isset($_REQUEST['link'])&&check_pass($_REQUEST['password1'],$_REQUEST['password2']))
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
			if(reset_password_link_check($username,$email,$password,$time,$update_time))
			{
				resetPwd($username, md5($_REQUEST['password1']));
				
				require_once('plugin/email/send_email.php');
				$subject = "Password Updated";
				$body = "Hi, $username\n<br/><br/>".
						"&nbsp&nbsp&nbsp Your password has been updated!\n<br/>".
						"&nbsp&nbsp&nbsp Username: $username<br/>".
						"&nbsp&nbsp&nbsp Password: ********<br/><br/>".
						"Admin";
				if(send_email($email,$username,$subject,$body))
					$check = true;
			}
		}
	}
	
	if(!$check)
	{
		if(isset($_REQUEST['link'])) 
			header("location: reset_password.php?link=".$_REQUEST['link']."&message=1");
		else 
			header("location: reset_password.php?message=1");
	}
	else
	{
		header("location: reset_password.php?success=1");
	}
	break;
}

closeDatabase();

?>