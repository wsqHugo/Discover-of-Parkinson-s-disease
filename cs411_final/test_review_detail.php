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
require_once('CustomFunctions.php');

$id = $_GET['id'];

openDatabase();

//comments and replies
$commentPageId = encrypt(json_encode(array("targetID"=>$id,"targetDescript"=>"test_review_detail.php")));
$commentID = isset($_GET['commentID']) ? check(decrypt($_GET['commentID'])) : '0';
$query = "SELECT COUNT(*) AS count FROM usr_Comment 
    WHERE  CommentID < '".$commentID."' AND TargetID = '".$id."' AND TargetDescript='test_review_detail.php';";
$result = runQuery($query);
$commentDisplayStart = $result[0]['count'];


//process txt file
$query_wave = "SELECT createTime, Q1, Q2, Q3, Q4, Q5, Q6, videofile, wavefile, processData FROM usr_Test WHERE id = ?";

if( $result_wave = $mysqli->prepare( $query_wave ) )
{
  $result_wave->bind_param( 'i', $id );
  $result_wave->execute();
  $result_wave->store_result();
  $result_wave->bind_result( $createTime, $Q1, $Q2, $Q3, $Q4, $Q5, $Q6, $videofile, $wavefile, $processedResult );
  $result_wave->fetch();
}

$address_txt = 'uploads/'.$wavefile;
$content_txt = file( $address_txt );

for( $j=0; $j<count( $content_txt ); $j++ ) 
{
  $content_txt[$j] = preg_split( '/\s/', $content_txt[$j] );
  $z = count( $content_txt[$j] );
  
  for( $z=0; $z<count($content_txt[$j]); $z++ )
  {
    $content_txt[$j][$z] = rtrim( $content_txt[$j][$z] );
    $content_txt[$j][$z] = strtoupper($content_txt[$j][$z]);
  }
}

$content_array = array();

for( $j=0; $j<count( $content_txt ); $j++ ) 
{
    $content_array[] = "{ x: ".$content_txt[$j][0].", y: ".$content_txt[$j][1]." }";
}

$content_string = implode(",", $content_array);

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
    <script type="text/javascript" src="js/canvasjs.min.js"></script>
    <script src="js/account.js"></script>
    <script type="text/javascript" src="js/userAccount.js"></script>

    <script type="text/javascript">
      window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer",
        {

            title:{
              text: "Wave Diagram"
            },
            data: [
            {
              type: "line",

              dataPoints: [ <?php print $content_string; ?> ]
            }
            ]
        });

        chart.render();
      }
    </script>

    <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-17389493-1']);
    _gaq.push(['_trackPageview']);
    var commentPageId = "<?php isset($commentPageId) ? print $commentPageId : print '';?>";
    $(function() 
    {
      var vTable = $('#commentTable').dataTable({
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": 'userAccountProcessing.php?stype=Comment&id='+commentPageId,
        "bAutoWidth": false,
        "bFilter": true,
        "bInfo": true,
        "bJQueryUI": true,
        "bLengthChange": true,
        "bPaginate": true,
        "bSort": false,
        "bSortClasses": true,
        "bSortCellsTop": true,
        "sPaginationType": "full_numbers",
        "aoColumnDefs": 
          [
            { "sWidth": 200, "aTargets": [ 0 ] }
          ],
        "aaSorting": [[1,'asc']],
        "iDisplayStart": <?php isset($commentDisplayStart) ? print $commentDisplayStart : print 0 ;?>,
        "fnDrawCallback": function() {
          initGUI();
          $(this).scroll();
        }
      }).fnSetFilteringDelay();
      
      
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

    <div id="advanced_container1">
    <div class="col-md-3">
      <table class="box">
        <thead>
          <tr>
            <th class='ui-state-default'><h3>Test General Information</h3></th>
          </tr>
        </thead>
        <tbody>
            <?php
            //$Q1, $Q2, $Q3, $Q4, $Q5, $Q6, $processedResult
            $point1 = ($Q1 == 1) ? 0 : 1;
            $point2 = ($Q2 == 1) ? 0 : 1;
            $point3 = ($Q3 == 1) ? 1 : 0;
            $point4 = ($Q4 == 1) ? 1 : 0;

            //echo $Q1;
            //echo $Q2;
            //echo $Q3;
            //echo $Q4;
            echo "<tr>";
            echo    "<td>";
            echo       "<h5>Tremor Frequency: </h5>";
            echo       "<p>".$processedResult." Hz</p><br/>";
            echo    "</td>";
            echo "</tr>";
            echo "<tr>";
            echo    "<td>";
            echo       "<h5>Test Result Comment: </h5>";
            if( $processedResult >= 4 && $processedResult <= 7 )
            {
              if( $Q5 == 1 )
              {
                echo "<p>Might be non-Parkinsonian.</p>";
                echo "<p>Given your age under 40, your opportunity of developing Parkinsonian symptom is very low. Your tremor is more likely resulted from essential tremor, Wilson disease, and other reasons.</p>";
              }
              elseif( $Q6 == 1 )
              {
                echo "<p>Might be non-Parkinsonian.</p>";
                 echo "<p>Since Parkinson Disease almost does not come only with action tremor, it is likely that your tremor is not Parkinsonian but other kinds.</p>";
              }
              elseif( ($point1+$point2+$point3+$point4) >= 3 )
              {
                echo "<p>Strongly suspected Parkinson’s disease. Tremor Frequency in typical Parkinsonian interval.</p>";
                
                if ( $Q1 == 1 ) 
                {
                  echo "<p>Since you recently have been having anxiety, caffeine overuse, or liver testing anxiety, your symptom may be resulted from physiologic reasons.</p>";
                }

                if ( $Q2 == 1 ) 
                {
                  echo "<p>Your tremor maybe drug-induced according to your response to the question.</p>";
                }
              }
              elseif( ($point1+$point2+$point3+$point4) <= 2 )
              {
                echo "<p>Weakly suspected Parkinson’s disease. Tremor Frequency in typical Parkinsonian interval.</p>";

                if ( $Q1 == 1 ) 
                {
                  echo "<p>Since you recently been having any anxiety, caffeine overuse, or liver testing anxiety, your symptom may be resulted from physiologic reasons.</p>";
                }

                if ( $Q2 == 1 ) 
                {
                  echo "<p>Your tremor maybe drug-induced according to your response to the question.</p>";
                }
              }
            }
            elseif( $processedResult < 4 && $processedResult >= 3 && $processedResult > 7 && $processedResult <= 10)
            {
              if( $Q5 == 1 )
              {
                echo "<p>Might be non-Parkinsonian.</p>";
                echo "<p>Given your age under 40, your opportunity of developing Parkinsonian symptom is very low. Your tremor is more likely resulted from essential tremor, Wilson disease, and other reasons.</p>";
              }
              elseif( $Q6 == 1 )
              {
                echo "<p>Might be non-Parkinsonian. </p>";
                echo "<p>Since Parkinson Disease almost does not come only with action tremor, it is likely that your tremor is not Parkinsonian but other kinds.</p>";
              }
              elseif( ($point1+$point2+$point3+$point4) >= 3 )
              {
                echo "<p>Weakly suspected Parkinson’s disease. Tremor Frequency in atypical Parkinsonian interval which may imply the other kinds of tremor cause or initial stage of Parkinson’s disease. </p>";
              
                if ( $Q1 == 0 ) 
                {
                  echo "<p>Since you recently been having any anxiety, caffeine overuse, or liver testing anxiety, your symptom may be resulted from physiologic reasons.</p>";
                }

                if ( $Q2 == 0 ) 
                {
                  echo "<p>Your tremor maybe drug-induced according to your response to the question.</p>";
                }
              }
              elseif( ($point1+$point2+$point3+$point4) <= 2 )
              {
                echo "<p>Weakly negative Parkinsonian sign. Tremor Frequency in atypical Parkinsonian interval which may imply the other kinds of tremor cause or initial stage of Parkinson’s disease.</p>";
              
                if ( $Q1 == 0 ) 
                {
                  echo "<p>Since you recently been having any anxiety, caffeine overuse, or liver testing anxiety, your symptom may be resulted from physiologic reasons.</p>";
                }

                if ( $Q2 == 0 ) 
                {
                  echo "<p>Your tremor maybe drug-induced according to your response to the question.</p>";
                }
              }
            }
            else
            {
              echo "<p>Negative sign for Parkinson’s disease since no Parkinsonian tremor is detected.</p>";
            }
            echo    "</td>";
            echo "</tr>";
            ?>
            <tr>
              <td>
                <br>
                <label class="label_name"><h3>Test Video: </h3></label>
                <video width="480" controls="controls">
                  <?php
                    echo '<source src="uploads/'.$videofile.'"'.' type="video/mp4">';
                  ?>
                </video>
              </td>
            </tr>
            <tr>
              <td>
                <br>
                <label class="label_name"><h3>Test Diagram: </h3></label>
                <div id="chartContainer" style="height: 200px; width: 100%;"></div>
              </td>
            </tr>
        </tbody>
      </table>
    </div>

    <div id="container" class="col-md-10">
      <div id='comments'>
        <span class="txt_bold txt_align_top"><h3>Comments:</h3></span>
        <table cellpadding="0" cellspacing="0" border="0" class="display data" id="commentTable">
          <thead>
            <tr>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr><td colspan="2" class="dataTables_empty">Loading data from server</td></tr>
          </tbody>
        </table>
      </div>
      <div id="workSpaceAlert"></div>
      <?php
        if($_SESSION['level']>=1)
        {
      ?>
        <div id="addComment">
          <form id="commentForm" method="POST">
            <input type='hidden' name='mod' value='CommentAdd' />
            <input type='hidden' name='id' value='<?php print $commentPageId;?>' />
            <input type='hidden' name='replyTo' value='<?php print encrypt('0');?>' />
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
                    <textarea name="commentContent" cols="68" rows="8" placeholder="(500 Characters, Shortcuts: Ctrl+Enter)" onkeydown="Comment(event,'commentForm');" maxlength="500" required/></textarea>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      <?php
        }
      ?>
    </div>

    </div> 
  </body>
</html>