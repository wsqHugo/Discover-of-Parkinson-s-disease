<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDataBase();
$level=$_SESSION['level'];
if($level<1)
{
	print "<div class='ui-state-error error'>SESSION EXPIRED<div>";
	closeDataBase();
	exit;
}

$idArray = isset($_GET['id']) ? json_decode(decrypt($_GET['id']),true) : array();
if(count($idArray)!=2)
{
	print "<div class='ui-state-error error'>ACCESS DENIED<div>";
	closeDataBase();
	exit;
}

$targetID = check($idArray["targetID"]);
$targetDescript = check($idArray["targetDescript"]);
$replyTo = isset($_GET['replyTo']) ? check(decrypt($_GET['replyTo'])) : '';

$query = 
	"SELECT UserName, SendTime, Content FROM usr_Comment 
	WHERE  CommentID = '".$replyTo."' AND TargetID = '".$targetID."' AND TargetDescript='".$targetDescript."';";
$result = runQuery($query);
if($result==NULL)
{
	print "<div class='ui-state-error error'>ACCESS DENIED<div>";
	closeDataBase();
	exit;
}
//print_r($result);
?>
<div>
	<form id="commentFormReply" method="POST">
		<input type='hidden' name='mod' value='CommentAdd' />
		<input type='hidden' name='id' value='<?php print $_GET['id'];?>' />
		<input type='hidden' name='replyTo' value='<?php print encrypt($replyTo);?>' />
		<table>
			<tbody>
				<tr>
					<th style="text-align:left"><?php print$_SESSION['name'] ?> (500 Characters, Shortcuts: Ctrl+Enter)</th>
					<th style="text-align:right">
						<input type='submit' class='button' value="Comment">
					</th>
				</tr>
				<tr>
					<td colspan="2">
						<table class='quote group'>
							<tr>
								<td  class="quote-left"><div><i class='icon-quote-left icon-2x'></i></div></td>
								<td>
									<div><?php print $result[0]['UserName'];?> Publish at <?php print date("Y-m-d H:i:s", $result[0]['SendTime']);?></div><br/>
									<div class='quote-content'><?php print text_Abstract($result[0]['Content'],120);?></div>
								</td>
								<td  class="quote-right"><div><i class='icon-quote-right icon-2x'></i></div></td>
							</tr>	
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea name="commentContent" cols="60" rows="8" placeholder="(500 Characters, Shortcuts: Ctrl+Enter)" onkeydown="Comment(event,'commentFormReply');" maxlength="500" required></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>