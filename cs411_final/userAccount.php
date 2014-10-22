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

if( isset($_POST['phyUpdate']) )
{
  openDatabase();

  $query_phy = "SELECT COUNT(UserName) FROM usr_UserStatistics WHERE level = 2";

  if( $result_phy = $mysqli->prepare( $query_phy ) )
  {
    $result_phy->execute();
    $result_phy->store_result();
    $result_phy->bind_result( $num );
    $result_phy->fetch();
  }
  
  $updateGroup = array();

  for($name = 0; $name < $num; $name++ ) 
  {
    $updateName = "phy".$name;
    //echo $updateName;
    
    if( isset($_POST["$updateName"]) )
    {
      $updateGroup[] = $_POST[$updateName];

      //echo $_POST["$updateName"];
    }
  }

  $updateGroup_string = implode("|", $updateGroup);
  
  //echo $updateGroup_string;
  
  $query_updateCheck = "UPDATE usr_authorization SET groupName = ? WHERE userName = ?";

  if( $result_updateCheck = $mysqli->prepare( $query_updateCheck ) )
  {
    $result_updateCheck->bind_param( 'ss', $updateGroup_string, $_SESSION["name"] );
    $result_updateCheck->execute();
    $result_updateCheck->store_result();
  }

  $query_updateTest = "UPDATE usr_Test SET groupName = ? WHERE userName = ?";

  if( $result_updateTest = $mysqli->prepare( $query_updateTest ) )
  {
    $result_updateTest->bind_param( 'ss', $updateGroup_string, $_SESSION["name"] );
    $result_updateTest->execute();
    $result_updateTest->store_result();
  }

  closeDatabase();
}
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
    <link href="css/all.css" rel="stylesheet">

    <script src="js/jquery-1.8.2.js"></script>
    <script src="js/jquery-ui-1.9.1.custom.min.js"></script>
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
    <script type="text/javascript" src="js/jquery.icheck.js"></script>
    <script src="js/account.js"></script>
    <script type="text/javascript" src="js/userAccount.js"></script>


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
        </div>

      </div>
    </div>

  	<br/><br/><br/>

    <div id="advanced_container">
    <div class="col-md-3">
    	<div id="userAccount" align="center">
    		<div id="workspace" align="left"></div>
    	<div id="workSpaceAlert"></div>

    		<script type="text/javascript">
    			$(document).ready(function() {

    				loadUserInfo('AccountInfo');

    				function getURLParameter(name) {
    					return decodeURI(
    						(RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    					);
    				}
    			});
    		</script>
    	</div>
    </div>

    <div id="container_special" class="col-md-11 holder">
      <table class="box">
      <thead>
        <tr>
          <th class='ui-state-default'><h3>Physician Authorization</h3></th>
        </tr>
      </thead>
      <tbody>
          <form action="userAccount.php" method="POST">
                <?php
                if( $_SESSION["level"] == 1 )
                {
                  openDatabase();

                  $query_phy = "SELECT UserName FROM usr_UserStatistics WHERE level = 2";

                  if( $result_phy = $mysqli->prepare( $query_phy ) )
                  {
                    $result_phy->execute();
                    $result_phy->store_result();
                    $result_phy->bind_result( $physician );
                  }

                  $name = 0;

                  while( $result_phy->fetch() )
                  {
                    $checkPhy = "%".$physician."%";
                    $newname = "phy".$name;

                    $query_phy_detail = "SELECT FirstName, LastName, Email FROM usr_UserAccount WHERE UserName = ?";

                    if( $result_phy_detail = $mysqli->prepare( $query_phy_detail ) )
                    {
                      $result_phy_detail->bind_param( 's', $physician );
                      $result_phy_detail->execute();
                      $result_phy_detail->store_result();
                      $result_phy_detail->bind_result( $firstname, $lastname, $email );
                      $result_phy_detail->fetch();
                    }

                    $query_phy_detail2 = "SELECT JobTitle, OrganizationName FROM usr_UserDetails WHERE UserName = ?";

                    if( $result_phy_detail2 = $mysqli->prepare( $query_phy_detail2 ) )
                    {
                      $result_phy_detail2->bind_param( 's', $physician );
                      $result_phy_detail2->execute();
                      $result_phy_detail2->store_result();
                      $result_phy_detail2->bind_result( $job, $org );
                      $result_phy_detail2->fetch();
                    }

                    $query_check = "SELECT * FROM usr_authorization WHERE groupName LIKE ? AND userName = ?";

                    if( $result_check = $mysqli->prepare( $query_check ) )
                    {
                      $result_check->bind_param( 'ss', $checkPhy, $_SESSION["name"] );
                      $result_check->execute();
                      $result_check->store_result();
                    }

                    if( $result_check->fetch() )
                    {
                      echo "<tr>";
                      echo  "<td>";
                      echo      "<input type='checkbox' name='".$newname."' value='".$physician."' checked>";
                      echo      "<span></span>";
                      echo      "<label><h4>".$physician."</h4></label>";
                      echo "<table>";
                      echo "<tr>";
                      echo "<td>Name: ".$firstname." ".$lastname."</td>";
                      echo "<td>Email: ".$email."</td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td>Job: ".$job."</td>";
                      echo "<td>Organization: ".$org."</td>";
                      echo "</tr>";
                      echo "</table>";
                      echo  "</td>";
                      echo "</tr>";
                    }
                    else
                    {
                      echo "<tr>";
                      echo  "<td>";
                      echo      "<input type='checkbox' name='".$newname."' value='".$physician."'>";
                      echo      "<span></span>";
                      echo      "<label><h4>".$physician."</h4>";
                      echo      "</label>";
                      echo "<table>";
                      echo "<tr>";
                      echo "<td>Name: ".$firstname." ".$lastname."</td>";
                      echo "<td>Email: ".$email."</td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td>Job: ".$job."</td>";
                      echo "<td>Organization: ".$org."</td>";
                      echo "</tr>";
                      echo "</table>";
                      echo  "</td>";
                      echo "</tr>";
                    }
                    
                    $name++;
                  }

                  echo "<tr>";
                  echo  "<td>";
                  echo    '<input type="submit" name="phyUpdate" value="Update">';
                  echo  "</td>";
                  echo "</tr>";

                  closeDatabase();  
                }
                else
                {
                  echo "<tr>";
                  echo  "<td>You are physician!</td>";
                  echo "</tr>";
                }
                           
                ?>
          </form>
        </tbody>
      </table>
    </div>
  </div>

  </body>
</html>