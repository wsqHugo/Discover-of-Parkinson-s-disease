

<?php



function send_email($to,$user,$subject = "",$body = "")
{
    //Author:Jiucool WebSite: http://www.jiucool.com 
    //$to: sender email Address, $subject: title, $body: email content
    //error_reporting(E_ALL);
    error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");//timezone
    require_once('class.phpmailer.php');
    include("class.smtp.php"); 
    $mail             = new PHPMailer(); //create a new PHPMailer object
    $body             = eregi_replace("[\]",'',$body); //fliter the email content
    $mail->CharSet ="UTF-8";//set CharSet UTF8, especially for international language
    $mail->IsSMTP(); // set to SMTP service
    $mail->SMTPDebug  = 1;                     // start SMTP debug funtion
                                           // 1 = errors and messages
                                           // 2 = messages only 
    $mail->SMTPAuth   = true;                  // start SMTP authorization checking
    $mail->SMTPSecure = "ssl";                 // secure protocol
    $mail->Host       = "smtp.gmail.com";      // SMTP server
    $mail->Port       = 465;                   // SMTP port
    $mail->Username   = "p3dbgroup@gmail.com";  // SMTP username
    $mail->Password   = "p3dbgrouppublic";            // SMTP password
    $mail->SetFrom('p3dbgroup@gmail.com', 'Admin');
    $mail->AddReplyTo("p3dbgroup@gmail.com","Admin");
    $mail->Subject    = $subject;
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer! - From www.jiucool.com"; // optional, comment out and test
    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, $user);
    //$mail->AddAttachment("images/phpmailer.gif");      // attachment 
    //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
    if(!$mail->Send()) 
	{
		return false;
        //echo "Mailer Error: " . $mail->ErrorInfo;
    } else 
	{
		return true;
        //echo "Message sent!";
    }
}
	

?>

