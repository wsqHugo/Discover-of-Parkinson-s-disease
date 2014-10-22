<?php
session_start();
require('XLSXReader.php');

//echo $_SESSION['uploadfile'];

//$address_excel = '../upload/'.$_SESSION['uploadfile'];
$firename = 'Pedigree_sheet_for_RFID_researchers.xlsx';
$address_excel = '../upload/'.$firename;

$xlsx = new XLSXReader($address_excel);
$sheetNames = $xlsx->getSheetNames();

openDatabase();

//foreach($sheetNames as $sheetName) 
//{
//	$sheet = $xlsx->getSheet($sheetName);
//	$data =	$sheet->getData();
//	unset($data[0]);
	
	//echo '<table>';
//	foreach($data as $row) 
//	{
		//echo "<tr>";
		//foreach($row as $cell) {
		//	echo "<td>" . $row[1] . "</td>";
		//}
		//echo "</tr>";
		/*
		$query_animalEID = "SELECT Animal_EID FROM Cows WHERE Animal_EID = ?";
		
		if( $result_animalEID = $mysqli->prepare( $query_animalEID ) )
		{
			$result_animalEID->bind_param( 's', $row[1] );
			$result_animalEID->execute();
			$result_animalEID->store_result();
			$result_animalEID->bind_result( $animalEID );
			
			if( $result_animalEID->fetch() == NULL )
			{
				$query_insert_cows = "INSERT INTO Cows (Animal_EID, Bday, Birth_Weight, Sex, Dam, Sire, Most_Recent_weight, Weaning_Weight, 205_d_Adj_WW, Yearling_Weight, Mature_Hip_Height, Pelvic_Area, Repro_Tract_Score, Dist_Travel_24hr, Time_At_Water, Time_At_Feed_Bunk, Time_At_Mineral_Salt, Al_Date, Nat_Serv_Intro_Date, Conception_Date, Resp_Blackleg_Vacc_Date, Anthelmintic_Date, Brucellosis_Vacc_Date, Castration_Date)
									  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				
				if( $result_insert_cows = $mysqli->prepare($query_insert_cows) )
				{
					$result_insert_cows->bind_param( 'ssisisiiiiiiiiiiisssssss', $row[1], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[20], $row[21], $row[22], $row[23], $row[24], $row[25], $row[26] );
					$result_insert_cows->execute();
					$result_insert_cows->store_result();
				}
		/*	}
			else
			{
				$query_update_cows = "UPDATE Cows
									  SET Bday=?, Birth_Weight=?, Sex=?, Dam=?, Sire=?, Most_Recent_weight=?, Weaning_Weight=?, 205_d_Adj_WW=?, Yearling_Weight=?, Mature_Hip_Height=?, Pelvic_Area=?, Repro_Tract_Score=?, Dist_Travel_24hr=?, Time_At_Water=?, Time_At_Feed_Bunk=?, Time_At_Mineral_Salt=?, Al_Date=?, Nat_Serv_Intro_Date=?, Conception_Date=?, Resp_Blackleg_Vacc_Date=?, Anthelmintic_Date=?, Brucellosis_Vacc_Date=?, Castration_Date=?
									  WHERE Animal_EID = ?";
									  
				if( $result_update_cows = $mysqli->prepare($query_update_cows) )
				{
					$result_update_cows->bind_param( 'sisisiiiiiiiiiiissssssss', $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[20], $row[21], $row[22], $row[23], $row[24], $row[25], $row[26], $row[1] );
					$result_update_cows->execute();
					$result_update_cows->store_result();
				}
			}
		}*/
			
//	}
	//echo '</table>';
//}


function escape($string) {
	return htmlspecialchars($string, ENT_QUOTES);
}

function array2Table($data) 
{
	echo '<table>';
	unset($data[0]);
	unset($data[1]);
	$j=0;

	foreach($data as $row) 
	{
		//echo "<tr>";
		/*foreach($row as $cell) {
			echo "<td>" . $cell . "</td>";
		}
		echo "</tr>";
		*/
		global $mysqli;
		
		$query_insert_cows = "INSERT INTO Cows (UserID, Animal_EID, Bday, Birth_Weight, Sex, Dam, Sire, Most_Recent_weight, Weaning_Weight, 205_d_Adj_WW, Yearling_Weight, Mature_Hip_Height, Pelvic_Area, Repro_Tract_Score, Dist_Travel_24hr, Time_At_Water, Time_At_Feed_Bunk, Time_At_Mineral_Salt, Al_Date, Nat_Serv_Intro_Date, Conception_Date, Resp_Blackleg_Vacc_Date, Anthelmintic_Date, Brucellosis_Vacc_Date, Castration_Date)
							  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				
		if( $result_insert_cows = $mysqli->prepare($query_insert_cows) )
		{
			$result_insert_cows->bind_param( 'issisisiiiiiiiiiiisssssss', $j, $row[1], convertTime($row[3]), intval($row[4]), $row[5], intval($row[6]), $row[7], intval($row[8]), intval($row[9]), intval($row[10]), intval($row[11]), intval($row[12]), intval($row[13]), intval($row[14]), intval($row[15]), intval($row[16]), intval($row[17]), intval($row[18]), convertTime($row[20]), convertTime($row[21]), convertTime($row[22]), convertTime($row[23]), convertTime($row[24]), convertTime($row[25]), convertTime($row[26]) );
			$result_insert_cows->execute();
			$result_insert_cows->store_result();
			//echo "<td>" . $row[1] . "</td>"; 
		}
		
		//$excel[$j] = $row;
		$j++;
		//echo "</tr>";
	}
	echo '</table>';
}

function convertTime($date)
{
	if( $date == "" )
	{
		return $date;
	}
	else
	{
		return date("m/d/Y", mktime(0,0,0,1,$date-1,1900));
	}
}

function openDatabase() 
{
	$host		=	'dbhost-mysql.cs.missouri.edu';
	$username	=	'cs4970sp13grp3';
	$password	=	'1jlqPrve96';
	$database	=	'cs4970sp13grp3';

	$GLOBALS['mysqli'] = new mysqli($host, $username, $password, $database);
	if ($GLOBALS['mysqli']->connect_error) 
	{
		die('Connect Error (' . $GLOBALS['mysqli']->connect_errno . ') '
				. $GLOBALS['mysqli']->connect_error);
	}
	$GLOBALS['mysqli']->set_charset("utf8");
}
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
</head>
<body>

<?
foreach($sheetNames as $sheetName) 
{
	$sheet = $xlsx->getSheet($sheetName);
	array2Table($sheet->getData());
}
/*
for($i=0; $i<=count($excel); $i++)
{
	$query_insert_cows = "INSERT INTO Cows (Animal_EID, Bday, Birth_Weight, Sex, Dam, Sire, Most_Recent_weight, Weaning_Weight, 205_d_Adj_WW, Yearling_Weight, Mature_Hip_Height, Pelvic_Area, Repro_Tract_Score, Dist_Travel_24hr, Time_At_Water, Time_At_Feed_Bunk, Time_At_Mineral_Salt, Al_Date, Nat_Serv_Intro_Date, Conception_Date, Resp_Blackleg_Vacc_Date, Anthelmintic_Date, Brucellosis_Vacc_Date, Castration_Date)
						  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					
	if( $result_insert_cows = $mysqli->prepare($query_insert_cows) )
	{
		$result_insert_cows->bind_param( 'ssisisiiiiiiiiiiisssssss', $excel[$i][1], convertTime($excel[$i][3]), intval($excel[$i][4]), $excel[$i][5], intval($excel[$i][6]), $excel[$i][7], intval($excel[$i][8]), intval($excel[$i][9]), intval($excel[$i][10]), intval($excel[$i][11]), intval($excel[$i][12]), intval($excel[$i][13]), intval($excel[$i][14]), intval($excel[$i][15]), intval($excel[$i][16]), intval($excel[$i][17]), intval($excel[$i][18]), convertTime($excel[$i][20]), convertTime($excel[$i][21]), convertTime($excel[$i][22]), convertTime($excel[$i][23]), convertTime($excel[$i][24]), convertTime($excel[$i][25]), convertTime($excel[$i][26]) );
		$result_insert_cows->execute();
		$result_insert_cows->store_result();
	}
}*/
?>
</body>
</html>



