<?php
//database is on gene
//Global varible $mysqli
$mysqli;
$systemEmail = 'p3dbgroup@gmail.com';
$systemUser ='P3DB SYSTEM';

//stmp or sendmail by system
function mailConfig($mail)
{
	$mail->CharSet ="UTF-8";//set CharSet UTF8, especially for international language
	$mail->IsSendmail();
	/* for gamil account
    $mail->IsSMTP(); // set to SMTP service
    $mail->SMTPDebug  = 1;                     // start SMTP debug funtion
                                           // 1 = errors and messages
                                           // 2 = messages only 
    $mail->SMTPAuth   = true;                  // start SMTP authorization checking
    $mail->SMTPSecure = "ssl";                 // secure protocol
	$mail->SMTPKeepAlive = true;				// SMTP connection will not close after each email sent, reduces SMTP overhead
    $mail->Host       = "smtp.gmail.com";      // SMTP server
    $mail->Port       = 465;                   // SMTP port
    $mail->Username   = "p3dbgroup@gmail.com";  // SMTP username
    $mail->Password   = "p3dbgrouppublic";            // SMTP password
	*/
	//$mail->SetFrom($GLOBALS['systemEmail'],$GLOBALS['systemUser'],0);
	$mail->FromName = $GLOBALS['systemUser'];
	$mail->From = '';
}

session_save_path("/Applications/XAMPP/xamppfiles/temp/");
function getGaSql() {
	$gaSql['server']     = 'localhost';
	$gaSql['user']       = 'root';
	$gaSql['password']   = '';
	$gaSql['db']         = 'p3db_test';
	
	return $gaSql;
}
?>