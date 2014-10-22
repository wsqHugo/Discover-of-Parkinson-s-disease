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
$commentID = isset($_GET['commentID']) ? check(decrypt($_GET['commentID'])) : '';

$query = 
	"SELECT * FROM usr_Comment 
	WHERE  CommentID = '".$commentID."' AND UserName='".$_SESSION['name']."' AND TargetID = '".$targetID."' AND TargetDescript='".$targetDescript."';";
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
	<form id="commentFormEdit" method="POST">
		<input type='hidden' name='mod' value='CommentEdit' />
		<input type='hidden' name='id' value='<?php print $_GET['id'];?>' />
		<input type='hidden' name='commentID' value='<?php print encrypt($result[0]['CommentID']);?>' />
		<table>
			<tbody>
				<tr>
					<th style="text-align:left"><?php print $_SESSION['name'] ?> (500 Characters, Shortcuts: Ctrl+Enter)</th>
					<th style="text-align:right">
						<input type='submit' class='button' value="Edit">
					</th>
				</tr>
				<tr>
					<td colspan="2">
						<div class='editTime'>Last Edit Time: <?php print date("Y-m-d H:i:s", $result[0]['EditTime']) ?></div>
					</td>
				</tr>
<?php
	if($result[0]['Quote']!='')
	{
		$quote = json_decode($result[0]['Quote'],true);
?>
				<tr>
					<td colspan="2">
						<table class='quote group'>
							<tr>
								<td  class="quote-left"><div><i class='icon-quote-left icon-2x'></i></div></td>
								<td>
									<div><?php print $quote['quoteAuthor'];?> Publish at <?php print date("Y-m-d H:i:s", $quote['quoteSendTime']);?></div><br/>
									<div class='quote-content'><?php print $quote['quoteContent'];?></div>
								</td>
								<td  class="quote-right"><div><i class='icon-quote-right icon-2x'></i></div></td>
							</tr>	
						</table>
					</td>
				</tr>
<?php
	}
?>
				<tr>
					<td colspan="2">
						<textarea name="commentContent" cols="60" rows="8" placeholder="(500 Characters, Shortcuts: Ctrl+Enter)" onkeydown="Comment(event,'commentFormEdit');" maxlength="500" required><?php print $result[0]['Content'];?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>