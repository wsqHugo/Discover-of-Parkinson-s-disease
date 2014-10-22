<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
require_once('plugin/email/send_email.php');
openDataBase();
$email = isset($_POST['email']) ? check($_POST['email']) : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
if($email=='')
{
	print "<div class='ui-state-error error'>Email cannot be empty</div>";
	closeDataBase();
	exit;
}

$query = "SELECT COUNT(*) AS count FROM usr_UserAccount WHERE Email = '".$email."'";
$result = runQuery($query);
if($result[0]['count'])
{
	print "<div class='ui-state-error error'>Email has been registered</div>";
	closeDataBase();
	exit;
}
else
{
	$username = check($_SESSION['name']);
	$query = "SELECT Email FROM usr_UserAccount WHERE UserName = '".$username."'";
	$result = runQuery($query);
	if($result==NULL)
	{
		print "<div class='ui-state-error error'>ACCESS DENIED</div>";
		closeDataBase();
		exit;
	}
	
	$sender = $result[0]['Email'];
	$subject = "P3DB Invitation";
	$body = '
		<p><span style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">Dear Researcher,</span></p>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;&nbsp; &nbsp;Your friend&nbsp;
		<address style="display:inline"><a href="mailto:'.$sender.'">'.$sender.'</a></address>
		would like to invite you to join the&nbsp;<a href="http://www.p3db.org/" title="http://www.p3db.org
		">P3DB (Plant Protein Phosphorylation DataBase) community</a>.</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;&nbsp; &nbsp;The Message from you friend is:</div>

		<div style="font-family: Tahoma; font-size: 13px; line-height: normal; "><font color="#000000">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;</font>

		<pre class="default prettyprint prettyprinted" style="margin-left:50px; margin-top: 0px; margin-bottom: 10px; padding: 5px; border: 0px; font-size: 14px; vertical-align: baseline; background-color: rgb(238, 238, 238); font-family: Consolas, Menlo, Monaco, '."'Lucida Console', 'Liberation Mono', 'DejaVu Sans Mono', 'Bitstream Vera Sans Mono', 'Courier New'".', monospace, serif; overflow: auto; max-width: 600px; height:auto; line-height: 18px; ">'.(($message=='')?'<i>NO MESSAGE</i>':$message).'
		&nbsp;</pre>
		</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;&nbsp; &nbsp;You are welcome to come and sign up. Link:&nbsp;<a href="http://www.p3db.org./" target="_blank">www.p3db.org</a></div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">&nbsp;</div>

		<div style="color: rgb(0, 0, 0); font-family: Tahoma; font-size: 13px; line-height: normal; ">'.$GLOBALS['systemUser'].'</div>
		';
	
	if(send_email($email,'Researcher',$subject,$body))
	{
		print "<div class='ui-state-highlight'>Your invitation has been sent.</div>";
	}
}
?>