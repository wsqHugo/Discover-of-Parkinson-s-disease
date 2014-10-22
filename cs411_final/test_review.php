<?php
require_once('session.php');

if( $_SESSION['status'] == "offline" )
{
  header('Location: index.php');
}

set_include_path('system'.PATH_SEPARATOR.'templates');

require_once('Template.php');
require_once('RedirectBrowserException.php');
require_once('DatabaseUtility.php');
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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/account.css" rel="stylesheet">  
    <link type="text/css" href="css/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
    <link type="text/css" href="css/site.css" rel="stylesheet" />
    <link type="text/css" href="css/table.css" rel="stylesheet" />
    <link type="text/css" href="css/userAccount.css" rel="stylesheet" />
    <link type="text/css" href="css/font-awesome.css" rel="stylesheet" />
    <link type="text/css" href="css/contextmenu.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/stylesheets/jquery.treetable.css" />
    <link rel="stylesheet" href="css/stylesheets/jquery.treetable.theme.default.css" />
    <link href="css/jumbotron.css" rel="stylesheet">

    <script src="js/jquery-1.8.2.js"></script>
    <script src="js/jquery-ui-1.9.1.custom.min.js"></script>
    <script src="js/jquery.contextmenu.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.plugin.js"></script>
    <script type="text/javascript" src="js/FixedColumns.js"></script>
    <script type="text/javascript" src="js/jquery.treetable.js"></script>
    <script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/jquery.selectBox.min.js"></script>
    <script type="text/javascript" src="js/extentions.js"></script>
    <script type="text/javascript" src="js/Jmol.js"></script>
    <script type="text/javascript" src="js/site.js"></script>
    <script type="text/javascript" src="js/jquery.lavalamp.js"></script>
    <script src="js/account.js"></script>
    <script type="text/javascript" src="js/userAccount.js"></script>

    <script type="text/javascript" charset="utf-8">
      var username = "<?php print $_SESSION['name'];?>";
      var level = "<?php print $_SESSION['level'];?>";

      $(document).ready(function() {
        var oTable;
        oTable = $('#alert').dataTable( {
          "sScrollX": "100%",
          "bScrollCollapse": true,
          "bProcessing": true,
          "bServerSide": true,
          "bAutoWidth": false,
          "bFilter": true,
          "bInfo": true,
          "bJQueryUI": true,
          "bLengthChange": true,
          "bPaginate": true,
          "bSort": true,
          "bSortClasses": true,
          "sPaginationType": "full_numbers",
          "sAjaxSource": 'scripts/test_server_processing.php?UserName='+username+'&Level='+level
        } );
        setInterval(function() { oTable.fnDraw(); }, 5000);
      } );
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
      <div class="col-md-3">
        <table class="box">
          <thead>
            <tr>
              <th class='ui-state-default'><h3>General Information</h3></th>
            </tr>
          </thead>
          <tbody>
  <?php
    openDatabase();
    $result=getUserInfo($_SESSION['name']);
  ?>
            <tr>
              <td>
                <label class="label_name">Username: </label> <label><?php echo $result['UserName']?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">First Name: </label> <label><?php echo $result['FirstName']?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name"> Last Name: </label> <label><?php echo $result['LastName']?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">Gender: </label> 
                <label><?php if ($result['Gender']==0) echo "Male";?></label>
                <label><?php if ($result['Gender']==1) echo "Female";?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">LastUpdateTime: </label><label><?php echo date("F j, Y, g:i a",$result['LastUpdateTime'])?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">Birthday: </label><?php echo $result['BirthdayMonth'].".".$result['BirthdayDay'].".".$result['BirthdayYear']?><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">E-mail: </label><label><?php echo $result['Email']?></label><br />
              </td>
            </tr>
            <tr>
              <td>
  <?php
    $result=getAccountActivity($_SESSION['name']);
  ?> 
                <label class="label_name">Type: </label> <label><?php if($result['Level'] == 1) echo "Potential Patient"; else echo "Physician";?></label><br />
              </td>
            </tr>
            <tr>
              <td>   
                <label class="label_name">Register Time: </label> <label><?php echo date("F j, Y, g:i a", $result['RegisterTime'])?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">Account Status: </label> <label><?php echo  "Active"//$result['AccountStatus'])?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">Last Login Time: </label> <label><?php echo date("F j, Y, g:i a", $result['LastLoginTime'])?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">Last Logout Time: </label> <label><?php echo date("F j, Y, g:i a", $result['LastLogoutTime'])?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">Login Duration: </label> <label><?php echo getDuration($result['LoginDuration'])."day(s)";?></label><br />
              </td>
            </tr>
            <tr>
              <td>
                <label class="label_name">No Activity Duration: </label> <label><?php echo getDuration($result['NoActivityDuration'])."day(s)";?></label><br />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  <?php
    closeDatabase();
  ?>

      <div id="container" class="col-md-10">
          <span class="txt_bold txt_align_top"><h3>Test Review:</h3></span>      
          <div id="test-list">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="alert">
              <thead>
                <tr>
                  <th>Detail</th>
                  <th>Creator</th>
                  <th>Create Time</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="5" class="dataTables_empty">Loading data from server</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>
    </div>

    </div> 
  </body>
</html>