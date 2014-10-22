<?php
require_once('session.php');

if( $_SESSION['status'] == "offline" || $_SESSION['level'] == 2)
{
  header('Location: index.php');
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

	<script type="text/javascript">  

	$(document).ready(function(){
		 $.get('process_video.php',
		 function(output) {
		 	$('#waiting').hide("slow");
		 	$('#dataDiv').html(output).fadeIn(250);
		 });
	});

	</script>

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

    <div id="advanced_container">
		<div id="container">
			<div id="steps">
				<ol id="test-steps-2">
					<li>1. FILL GENERAL INFORMATION</li>
					<li>2. UPLOAD VIDEO</li>
					<li>3. PROCESSING VIDEO</li>
				</ol>
				<script type="text/javascript">
					var  S = KISSY, DOM = S.DOM, Event = S.Event, step2;
					
					step2 = new S.Steps('#test-steps-2');
					//run
					step2.render();
					//activate first step
					step2.set( 'act', 3 );
					S.log( step2.get('act') );
					//step1.set('color','red');
					//step1.set('width',200);
				</script>
			</div>

			<br/>

			<table class="box">
		        <thead>
		          <tr>
		            <th class='ui-state-default'><h3>WAITING</h3></th>
		          </tr>
		        </thead>
		        <tbody>
		        	<tr>
		            	<td>
							<div id="dataDiv"><h5> Please wait while processing video... </h5></div>
							<img id="waiting" src="img/waiting.png" alt="waiting" width="700">
						</td>
		          	</tr>
		        </tbody>
	      	</table>
		</div>
	</div>

</body>
</html>


