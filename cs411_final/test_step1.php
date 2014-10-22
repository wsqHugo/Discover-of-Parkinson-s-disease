<?php
require_once('session.php');

if( $_SESSION['status'] == "offline" || $_SESSION['level'] == 2)
{
	header('Location: index.php');
}

if( isset($_POST['submit']) )
{	
	$_SESSION["q1"] = isset($_POST['q1']) ? $_POST['q1'] : 0;
	$_SESSION["q2"] = isset($_POST['q2']) ? $_POST['q2'] : 0;
	$_SESSION["q3"] = isset($_POST['q3']) ? $_POST['q3'] : 0;
	$_SESSION["q4"] = isset($_POST['q4']) ? $_POST['q4'] : 0;
	$_SESSION["q5"] = isset($_POST['q5']) ? $_POST['q5'] : 0;
	$_SESSION["q6"] = isset($_POST['q6']) ? $_POST['q6'] : 0;

	//echo "<br><br><br><br><br><br><br><br><br><br><br><br>";

	//echo $_SESSION["q1"];
	//echo $_SESSION["q2"];
	//echo $_SESSION["q3"];
	//echo $_SESSION["q4"];
	//echo $_SESSION["q5"];
	//echo $_SESSION["q6"];

	if( isset($_POST['q1']) && isset($_POST['q2']) && isset($_POST['q3']) && isset($_POST['q4']) && isset($_POST['q5']) && isset($_POST['q6']) )
	{
		header('Location: test_step2.php');
	}
	else
	{
		echo "<script type='text/javascript'>alert('Please reply all questions!')</script>";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>WhiteCoat</title>

	<link rel="shortcut icon" href="../../assets/ico/favicon.ico">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/account.css" rel="stylesheet">  
    <link type="text/css" href="css/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
    <link type="text/css" href="css/site.css" rel="stylesheet" />
    <link type="text/css" href="css/table.css" rel="stylesheet" />
    <link type="text/css" href="css/userAccount.css" rel="stylesheet" />
    <link type="text/css" href="css/font-awesome.css" rel="stylesheet" />
    <link type="text/css" href="css/contextmenu.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/stylesheets/jquery.treetable.css" />
    <link rel="stylesheet" href="css/stylesheets/jquery.treetable.theme.default.css" />
    <link href="css/jumbotron.css" rel="stylesheet">
	<link href="css/reset.css" rel="stylesheet" type="text/css" />
	<link type="text/css" href="css/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
	<link href="css/fileUploader.css" rel="stylesheet" type="text/css" />
	<link href="css/steps.css" rel="stylesheet" type="text/css">

	<script src="http://a.tbcdn.cn/s/kissy/1.1.6/kissy-min.js"  type="text/javascript"></script>
	<script src="js/steps.js"></script>
	<script src="js/jquery-1.8.2.js"></script>
	<script src="js/jquery-ui-1.9.1.custom.min.js"></script>
	<script src="js/account.js"></script>
	<script src="js/jquery.fileUploader.js" type="text/javascript"></script>

</head>

<body>

	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">James Parkinsons</a>
        </div>

        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="test_review.php">Test Review</a></li>
            <li><a href="test_step1.php">Start a New Test</a></li>
          </ul>
          <form class="navbar-form navbar-right" role="form">

             <div id="account">
              <div id="form_container" style="display:none">
                <div class='loading_dialog'>
                  <img src='img/loader-earth.gif' alt='Loading'/>
                  <span> Loading</span>
                </div>
              </div>
              <div id="load_container" style="display:none" >
                <div class="loading_dialog">
                  <img src='img/loader-earth.gif' alt='Processing'/>
                  <span> Processing</span>
                </div>
              </div>
              <div id="account_status" class="group ui-corner-all"><?php print $_SESSION['output']; ?></div>
            </div>
          
          </form>
        </div><!--/.navbar-collapse -->

      </div>
    </div>

	<div id="container">
		<div id="steps">
			<ol id="test-steps-2">
				<li>1. FILL GENERAL INFORMATION</li>
				<li>2. UPLOAD VIDEO</li>
				<li>3. PROCESSING VIDEO</li>
			</ol>
			<script type="text/javascript">
				var  S = KISSY, DOM = S.DOM, Event = S.Event, step1;
				
				step1 = new S.Steps('#test-steps-2');
				//run
				step1.render();
				//activate first step
				step1.set( 'act', 1 );
				S.log( step1.get('act') );
				//step1.set('color','red');
				//step1.set('width',200);
			</script>
		</div>

		<br/>

		<table class="box">
			<thead>
				<tr>
					<th class='ui-state-default'><h3>General Questions</h3></th>
				</tr>
			</thead>
			<tbody>
				<form action="test_step1.php" method="POST">
					<tr>
						<td>
							<h3>Q1. Have you recently been having any anxiety, caffeine overuse, and liver testing?</h3>
							<input type="radio" name="q1" value="1">Yes
							<input type="radio" name="q1" value="0">No
						</td>
					</tr>
					<tr>
						<td>
							<br/>
							<h3>Q2. Have you been taking drugs: from the following list?</h3>
							<h6>1. Cancer medicines such as thalidomide and cytarabine.</h6>
							<h6>2. Seizure medicines such as valproic acid (Depakote), and sodium valproate (Depakene)</h6>
							<h6>3. Asthma medicines such as theophylline and albuterol</h6>
							<h6>4. Immunosuppressants such as cyclosporine</h6>
							<h6>5. Mood stabilizers such as lithium carbonate</h6>
							<h6>6. Stimulants such as caffeine and amphetamines</h6>
							<h6>7. Selective serotonin reuptake inhibitors</h6>
							<h6>8. Tricyclic antidepressants</h6>
							<h6>9. Heart medicines such as amiodarone, procainamide, and others</h6>
							<h6>10. Certain antibiotics</h6>
							<h6>11. Certain antivirals such as acyclovir and vidarabine</h6>
							<h6>12. Alcohol</h6>
							<h6>13. Nicotine</h6>
							<h6>14. Certain high blood pressure drugs</h6>
							<h6>15. Epinephrine and norepinephrine</h6>
							<h6>16. Weight loss medication (tiratricol)</h6>
							<h6>17. Too much thryoid medication (levothyroxine)</h6>
							<h6>18. Tetrabenazine, a medicine to treat excessive movement disorder</h6>
							<input type="radio" name="q2" value="1">Yes
							<input type="radio" name="q2" value="0">No
						</td>
					</tr>
					<tr>
						<td>
							<br/>
							<h3>Q3: Do you feel tremor occur more when you are resting (no motion) or during action or specific posture.</h3>
							<input type="radio" name="q3" value="1">Resting
							<input type="radio" name="q3" value="0">During Action
						</td>
					</tr>
					<tr>
						<td>
							<br/>
							<h3>Q4: Have you been constantly under any rigidity and postural instability?</h3>
							<input type="radio" name="q4" value="1">Yes
							<input type="radio" name="q4" value="0">No
						</td>
					</tr>
					<tr>
						<td>
							<h3>Q5: Is your age under 40?</h3>
							<input type="radio" name="q5" value="1">Yes
							<input type="radio" name="q5" value="0">No
						</td>
					</tr>
					<tr>
						<td>
							<br/>
							<h3>Q6: Does your tremor symptom occur only during motion?</h3>
							<input type="radio" name="q6" value="1">Yes
							<input type="radio" name="q6" value="0">No
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="submit" value="Submit">
						</td>
					</tr>
				</form>
			</tbody>
		</table>
	</div>

</body>
</html>
