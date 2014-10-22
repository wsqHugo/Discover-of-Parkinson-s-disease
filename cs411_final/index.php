<?php 
  //this file shows the status of the user
  //every page will contain session.php and this page
  require_once "session.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>WhiteCoat</title>

    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">
    <link href="css/account.css" rel="stylesheet">  
    <link type="text/css" href="css/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jumbotron.css" rel="stylesheet">

    <script src="js/jquery-1.8.2.js"></script>
    <script src="js/jquery-ui-1.9.1.custom.min.js"></script>
    <script src="js/account.js"></script>
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
            <?php
            if( isset($_SESSION['level']) && $_SESSION['level'] == 1)
            {
              echo '<li><a href="test_step1.php">Start a New Test</a></li>';
            }
            ?>
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

   

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron" style="background-image: url(img/bg.jpg); background-size: cover; background-repeat: no-repeat;">
      <div class="container">
        <h1>Do you have Parkinson Disease?</h1>
        <p>Parkinson Disease is not horrible. Have a scientific pre-screening in JUST 3 minites, and look for advice from medical professionals.</p>
        <?php
        if( isset($_SESSION['level']) && $_SESSION['level'] == 1)
        {
          echo '<p><a class="btn btn-primary btn-danger" role="button" href="test_step1.php">Test Now &raquo;</a></p>';
        }
        ?>
      </div>
    </div>

    <div class="container">
    </div> 
  </body>
</html>
