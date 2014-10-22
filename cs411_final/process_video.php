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

	//sleep(3);
	/*
	
	echo $_SESSION['uploadfile'];
	echo "test".$currentTime.".txt";
	echo $_SESSION["name"];
	echo $_SESSION["q1"];
	echo $_SESSION["q2"];
	echo $_SESSION["q3"];
	echo $_SESSION["q4"];
	echo $_SESSION["q5"];
	echo $_SESSION["q6"];
	*/
	openDatabase();
	
	$groupName = "";

	$query_phy = "SELECT groupName FROM usr_authorization WHERE userName = ?";

	if( $result_phy = $mysqli->prepare( $query_phy ) )
	{
		$result_phy->bind_param( 's', $_SESSION["name"] );
		$result_phy->execute();
		$result_phy->store_result();
		$result_phy->bind_result( $groupName );
		$result_phy->fetch();
	}

	$currentTime = time();
	$srcName = $_SESSION['uploadfile'];
	$waveName = "test".$currentTime.".txt";
	$src = "uploads/".$srcName;
	$waveform_text = "uploads/".$waveName;
	//$groupName = "shangquan1|shangquan2";
	$processData = "0";

	$pieces = explode(".", $srcName);
	//echo $pieces[0]."/".$pieces[1];

	//$src = "uploads/parkinson2_2.mp4";
	$dst = "uploads/".$pieces[0]."_".$currentTime.".mp4";
	$srcName = $pieces[0]."_".$currentTime.".mp4";
	//echo $srcName."/".$dst;
	exec("/usr/bin/python /Applications/XAMPP/xamppfiles/htdocs/shangquan/Archive/mp4converter/client_converter.py ".$src." ".$dst);
	//$pieces = explode(".", "parkinson2.mp4");
	//$dst = "uploads/".$pieces[0]."_c.mp4";
	sleep(20);

	$processhand = exec("/usr/bin/python /Applications/XAMPP/xamppfiles/htdocs/shangquan/Archive4/handDetectionCV/client_hand.py ".$dst);
	//echo $processhand;
	
	if( $processhand > 0.25 )
	{
		$processData = exec("/usr/bin/python /Applications/XAMPP/xamppfiles/htdocs/shangquan/cs411_final/client.py ".$dst." ".$waveform_text);

		$query_usr_test = "INSERT INTO usr_Test(userName, createTime, Q1, Q2, Q3, Q4, Q5, Q6, videoFile, waveFile, groupName, processData) VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		if( $result_usr_test = $mysqli->prepare($query_usr_test) )
		{
			//echo $groupName; 
			$result_usr_test->bind_param( 'siiiiiiissss', $_SESSION["name"], $currentTime, $_SESSION["q1"], $_SESSION["q2"], $_SESSION["q3"], $_SESSION["q4"], $_SESSION["q5"], $_SESSION["q6"], $srcName, $waveName, $groupName, $processData );
			$result_usr_test->execute();
			$result_usr_test->store_result();
		}

		//echo $processhand;
		echo '<a class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" href="test_review.php">Successfully Processed Video, View Details</a>';
	}	
	else
	{
		echo "You video is invalid!";
	}
	

	closeDatabase();
?>