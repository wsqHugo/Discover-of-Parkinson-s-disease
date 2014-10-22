<?php

$firename = 'waveform.txt';
$address_txt = 'results/'.$firename;
$content_txt = file( $address_txt );

//process txt file
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

//print_r($results);

$content_string = implode(",", $content_array);

//echo $content_string;
?>
<html>
<head>

	<style>
	table, th, td
	{
		border:3px solid black;
		border-collapse:collapse;
	}
	</style>

	<script type="text/javascript" src="js/canvasjs.min.js"></script>
	<script type="text/javascript">
	  	window.onload = function () {
		    var chart = new CanvasJS.Chart("chartContainer",
		    {

		      	title:{
		      		text: "Earthquakes - per month"
		      	},
		       	data: [
			      {
			        type: "line",

			        dataPoints: [	<?php print $content_string; ?> ]
			      }
		      	]
		    });

		    chart.render();
		}
 	</script>
</head>
<body>
	<video width="480" height="320" controls="controls">
	<source src="uploads/parkinson2_2.mp4" type="video/mp4">
	</video>

  	<div id="chartContainer" style="height: 300px; width: 50%;"></div>
<?php
//txt
/*
echo "<table><tr>";
		for( $i=0; $i<count($content_txt[0]); $i++ )
		{
			echo "<th align='center'>" . $content_txt[0][$i] . "</th>";
		}
echo "</tr>";
		for( $i=1; $i<count($content_txt); $i++ )
		{
			echo "<tr>";
			for( $j=0; $j<count($content_txt[$i]); $j++ )
			{
				echo "<td align='center'>" . $content_txt[$i][$j] . "</td>";
			}
			echo "</tr>";
		}
echo "</table>";
*/
?>
</body>
</html>