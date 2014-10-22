<?php
set_include_path('system'.PATH_SEPARATOR.'templates'.'../../');
require_once("DatabaseUtility.php");
require_once("session.php");
openDatabase();

$username=$_SESSION['name'];
$CommentID = isset($_POST['value']) ? check(decrypt($_POST['value'])) : '';
$query = "SELECT * FROM usr_Comment WHERE CommentID='".$CommentID."' AND UserName='".$username."'";
$result = runQuery($query);
/*if($result!=NULL)
{
	$query = "SELECT annotation,latin,commonName FROM protein,organism WHERE id=".$result[0]['TargetID']." AND protein.organism=organism.label;";
	$protein = runQuery($query);
}*/
if($result==NULL)
{
	print 	$error;
}
else
{
	if($result[0]['Quote']!='')
	{
		$quote = json_decode($result[0]['Quote'],true);
		$quote =	"<div>
							<table class='quote group'>
								<tr>
									<td  class='quote-left'><div><i class='icon-quote-left icon-2x'></i></div></td>
									<td>
										<div>".$quote['quoteAuthor']." Publish at ".date("Y-m-d H:i:s", $quote['quoteSendTime'])."</div><br/>
										<div class='quote-content'>".$quote['quoteContent']."</div>
									</td>
									<td  class='quote-right'><div><i class='icon-quote-right icon-2x'></i></div></td>
								</tr>	
							</table>
						</div>";
	}
	else
		$quote = '--';
	print 	json_encode(
				array(
					//($protein==NULL) ? '--' : $protein[0]['annotation'],
					//($protein==NULL) ? '--' : "<i>".$protein[0]['latin']."</i>(".$protein[0]['commonName'].")",
					date("Y-m-d H:i:s",$result[0]['EditTime']),
					$quote,
					($result[0]['Content']==NULL) ? '--' : $result[0]['Content']
				)
			);
}
closeDatabase();
?>