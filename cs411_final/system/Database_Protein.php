<?php
function getKinaseProtein($kinase, $protein)
{
	$query = "SELECT * FROM gnr_PPI_KinaseProtein WHERE kinase like CONCAT('$kinase', '%') AND protein like CONCAT('$protein', '%') ORDER BY kinase";
	$result = runQuery($query);
	return $result;
}

function getDomain($domainID, $proteinID, $description)
{
	$query = "SELECT * FROM gnr_DomainMap WHERE proteinID like CONCAT('$proteinID', '%') AND domainID like CONCAT('$domainID', '%') AND domainDescription like CONCAT('%', CONCAT('$description', '%')) ORDER By proteinID";
	$result = runQuery($query);
	return $result;
}
function getDomainByProtein($proteinID)
{
	$query = "SELECT * FROM gnr_DomainMap D, idMapping WHERE p3dbId=$proteinID and D.proteinID like CONCAT(otherId, '%')";
	$result = runQuery($query);
	return $result;
}

function getOntologyChild($ontologyID)
{
	$query = "SELECT description, O.ontologyID, name, def, synonym, xref FROM stock_Ontology_BiologicalProcess O, stock_Ontology_BiologicalRelationship R WHERE R.parentID='$ontologyID' AND R.childID=O.ontologyID
			  UNION
			  SELECT description, O.ontologyID, name, def, synonym, xref FROM stock_Ontology_CellularComponent O, stock_Ontology_CellularRelationship R WHERE R.parentID='$ontologyID' AND R.childID=O.ontologyID
			  UNION
			  SELECT description, O.ontologyID, name, def, synonym, xref FROM stock_Ontology_MolecularFunction O, stock_Ontology_MolecularRelationship R WHERE R.parentID='$ontologyID' AND R.childID=O.ontologyID";
	$result = runQuery($query);
	return $result;
}

function getOntologyParent($ontologyID)
{
	$query = "SELECT description, O.ontologyID, name, def, synonym, xref FROM stock_Ontology_BiologicalProcess O, stock_Ontology_BiologicalRelationship R WHERE R.childID='$ontologyID' AND R.parentID=O.ontologyID
			  UNION
			  SELECT description, O.ontologyID, name, def, synonym, xref FROM stock_Ontology_CellularComponent O, stock_Ontology_CellularRelationship R WHERE R.childID='$ontologyID' AND R.parentID=O.ontologyID
			  UNION
			  SELECT description, O.ontologyID, name, def, synonym, xref FROM stock_Ontology_MolecularFunction O, stock_Ontology_MolecularRelationship R WHERE R.childID='$ontologyID' AND R.parentID=O.ontologyID";
	$result = runQuery($query);
	return $result;
}

function getOntologyByID($term, $name, $description, $biological, $cellular, $molecular)
{	
	$query="";
	$result=array();
	if (strcmp($biological,"on")==0)
	{
		$query = "SELECT ontologyID, name, def, synonym, xref FROM stock_Ontology_BiologicalProcess WHERE ontologyID like CONCAT('$term', '%') AND (name like CONCAT('$name', '%') OR synonym like CONCAT('$name', '%') AND def like CONCAT('$description', '%'))";
		$temp = runQuery($query);
		foreach ($temp as $row)
			array_push($result, $row);
	}
	if (strcmp($cellular,"on")==0)
	{
		$query = "SELECT ontologyID, name, def, synonym, xref FROM stock_Ontology_CellularComponent WHERE ontologyID like CONCAT('$term', '%') AND (name like CONCAT('$name', '%') OR synonym like CONCAT('$name', '%') AND def like CONCAT('$description', '%'))";
		$result = runQuery($query);
		foreach ($temp as $row)
			array_push($result, $row);
	}
	if (strcmp($cellular,"on")==0)
	{
		$query = "SELECT ontologyID, name, def, synonym, xref FROM stock_Ontology_MolecularFunction WHERE ontologyID like CONCAT('$term', '%') AND (name like CONCAT('$name', '%') OR synonym like CONCAT('$name', '%') AND def like CONCAT('$description', '%'))";
		$result = runQuery($query);
		foreach ($temp as $row)
			array_push($result, $row);
	}
	return $result;
}

function getOntologyByProtein($protein)
{
	$query = "SELECT O.ontologyID, name, def, synonym, xref FROM stock_Ontology_BiologicalProcess O, gnr_goMapBiological M, idMapping where p3dbId=$protein and M.proteinID like CONCAT(otherId, '%') and M.ontologyID=O.ontologyID
			  UNION
			  SELECT O.ontologyID, name, def, synonym, xref FROM stock_Ontology_CellularComponent O, gnr_goMapCellular M, idMapping where p3dbId=$protein and M.proteinID like CONCAT(otherId, '%') and M.ontologyID=O.ontologyID
			  UNION
			  SELECT O.ontologyID, name, def, synonym, xref FROM stock_Ontology_MolecularFunction O, gnr_goMapMolecular M, idMapping where p3dbId=$protein and M.proteinID like CONCAT(otherId, '%') and M.ontologyID=O.ontologyID";
	$result = runQuery($query);
	return $result;
}

function browseFamily($tablename)
{
	$query = "SELECT * FROM $tablename ";//ORDER BY level";
	$result = runQuery($query);
	return $result;
}
/*
SELECT *,
	LPAD(SUBSTRING_INDEX(level,'.',1),3,'0') AS A,
	LPAD(REPLACE(substring(substring_index(level, '.', 2), length(substring_index(level, '.', 1)) + 1),'.', ''),3,'0') AS D,
        LPAD(REPLACE(substring(substring_index(level, '.', 3), length(substring_index(level, '.', 2)) + 1),'.', ''),3,'0') AS E,
        SUBSTRING_INDEX(SUBSTRING_INDEX(level,'.',2),'.',-1) AS B,
        SUBSTRING_INDEX(SUBSTRING_INDEX(level,'.',3),'.',-1) AS C
*/
function browseKinase($tablename, $kinaseID, $description)
{
	$query = "SELECT * FROM $tablename WHERE proteinID like '%$kinaseID%' and name like '%$description%' ORDER BY family";
	$result = runQuery($query);
	return $result;
}
function browsePhosphatase($tablename, $id, $name, $desc)
{
	$query = "SELECT * FROM $tablename WHERE NCBI_ID like '%$id%' AND (organism like '%$name%' OR description like '%$desc%') ORDER BY family";
	$result = runQuery($query);
	return $result;
}
function getKinaseByProtein($proteinID)
{
	$query = "SELECT kinase FROM gnr_PPI_KinaseProtein, idMapping WHERE p3dbId=$proteinID AND protein like CONCAT(otherId, '%')";
	$result = runQuery($query);
	return $result;
}

function loadFile($tablename, $filename, $num)
{
	$file = file($filename);
	$query="LOAD DATA LOCAL INFILE '$filename' INTO TABLE $tablename IGNORE $num LINES";
	runQueryNoReturn($query);
}
function findProtein($protein)
{
	$query = "SELECT interactor_a AS Protein FROM gnr_PPI WHERE interactor_a like CONCAT('$protein', '%')
			  UNION
			  SELECT interactor_b AS Protein FROM gnr_PPI WHERE interactor_b like CONCAT('$protein', '%')";
	$result = runQuery($query);
	$num=0;
	foreach ($result as $line)
		if ($num!=0)
			break;
		else
			$num++;
	if ($num==0)
		return false;
	else
		return $result;
}

function PPISearch_BFS($start, $goal)
{
	$result=array();
	$visited=array();
	$ancestor=array();
	$queue= array();
	$flag = false;
	array_push($queue, $start);
	array_push($visited, $start);
	while (!empty($queue))
	{
		foreach ($queue as $current)
		{
							echo $current." ".$goal."\n";
			if ($current==$goal)
			{

				$flag = true;
				while($ancestor[$goal]!=$start)
				{
					$goal=$ancestor[$goal];
					array_push($result,$goal);
				}
				break;
			}
		}
		if ($flag)
			break;
		$current=array_shift($queue);
		$query = "select interactor_a, interactor_b from gnr_PPI where interactor_a='$current' or interactor_b='$current'";
		$neighbors = runQuery($query);
		foreach ($neighbors as $row)
		{
			if ($row['interactor_a']==$current)
				$neighbor=$row['interactor_b'];
			else
				$neighbor=$row['interactor_a'];
			if (!in_array($neighbor, $visited))
			{
				array_push($queue, $neighbor);
				array_push($visited, $neighbor);
				$ancestor[$neighbor]=$current;
			}
		}
	}
	array_push($result, $start, $goal);
	return $result;
}

function PPISearch($input)
{
	$size=sizeof($input);
	if ($size==1)
		return $input;
	$result=array();

	for ($start=0; $start<$size-1; $start++)
		for ($goal=$start+1; $goal<$size; $goal++)
		{
			$path=PPISearch_BFS ($input[$start], $input[$goal]);
			foreach ($path as $node)
			{
				if (!in_array($node, $result))
					array_push($result, $node);
				echo $node;
			}
	}
	return $result;
}

function expend($input, $loop)
{
	$result = array();
	$count=0;
	$visited = $input;
	if ($loop==0)
		foreach	($input as $protein)
		{
			$query = "select * from gnr_PPI where interactor_a='$protein' or interactor_b='$protein'";
			$neighbors=runQuery($query);
			foreach ($neighbors as $row)
			{
				if ($row['interactor_a']==$protein)
					$neighbor=$row['interactor_b'];
				else
					$neighbor=$row['interactor_a'];
				if (in_array($neighbor, $input) && !in_array($row, $result))
					$result[$count++]=$row;
			}
		}
	else
		while ($loop>0)
		{
			$expendNode= array();
			foreach	($input as $protein)
			{
				$query = "select * from gnr_PPI where interactor_a='$protein' or interactor_b='$protein'";
				$neighbors=runQuery($query);
				foreach ($neighbors as $row)
				{
					if (!in_array($row, $result))
						$result[$count++]=$row;
					if ($row['interactor_a']==$protein)
						$neighbor=$row['interactor_b'];
					else
						$neighbor=$row['interactor_a'];
					if (!in_array($neighbor, $visited))
					{
						array_push($expendNode,$neighbor);
						array_push($visited, $neighbor);
					}
				}
			}
			$loop--;
			$input=$expendNode;		
		}
	return $result;
}

function getPPI($id)
{
	$query = "SELECT * FROM gnr_PPI, idMapping WHERE p3dbId=$id AND (interactor_a like CONCAT(otherId, '%') or interactor_b like CONCAT(otherId, '%'))";
	$result = runQuery($query);
	return $result;	
}
?>