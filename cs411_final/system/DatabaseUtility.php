<?php
//require_once('config.php');
require_once('check.php');
require_once('Crypt.php');
require_once('CustomFunctions.php');
require_once('Database_UserFunction.php');
require_once('Database_Protein.php');
require_once('DatabaseDataTableFilterFunction.php');


function openDatabase() {
	//$gaSql = getGaSql();
	$CLIENT_MULTI_RESULTS=131072;
	//$host=$gaSql['server'];
	//$username=$gaSql['user'];
	//$password=$gaSql['password'];
	//$database=$gaSql['db'];

	$GLOBALS['mysqli'] = new mysqli('localhost','root','','p3db_test');
	if ($GLOBALS['mysqli']->connect_error) {
		die('Connect Error (' . $GLOBALS['mysqli']->connect_errno . ') '
				. $GLOBALS['mysqli']->connect_error);
	}
}


function closeDatabase() {
	//mysql_close();
	$GLOBALS['mysqli']->close();
}

function errorMessage($query) {
	return '<strong>Error:</strong> ('.$GLOBALS['mysqli']->errno. ') '.$GLOBALS['mysqli']->error.'<br /><br /><strong>Query:</strong> '.$query;
}

function runQuery($query) {
	$result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
	
	//define $dataSource is array  Ge,Huangyi
	$dataSource = array();
	//
	
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$dataSource[] = $row;
	}
	return $dataSource;
}

function runQueryNoReturn($query) {
	$GLOBALS['mysqli']->query($query) or die(errorMessage($query));
	$affectRows = affectRows();
	$GLOBALS['mysqli']->use_result();
	return $affectRows;
}

function runMultiQuery($query) {
	$GLOBALS['mysqli']->multi_query($query) or die(errorMessage($query));
	
	$dataSource = array();
	$i = 0;
	DO
	{
		$i++;
		$array = array();
		$result = $GLOBALS['mysqli']->store_result();
		while($result!=NULL&&$row = $result->fetch_array(MYSQLI_ASSOC)) {
			$array[] = $row;
		}
		
		if($result!=NULL)
		{
			$dataSource[] = $array;
			$result->free();
		}
		if($GLOBALS['mysqli']->more_results())
			continue;
		else
			break;
	} while ($GLOBALS['mysqli']->next_result());
	//print $i;
	//print sizeof($dataSource);
	return $dataSource;
}

function runMultiQueryNoReturn($query) {
	$GLOBALS['mysqli']->multi_query($query) or die(errorMessage($query));
	$affectRows = affectRows();
	DO
	{
		if($GLOBALS['mysqli']->more_results())
			continue;
		else
			break;
	} while ($GLOBALS['mysqli']->next_result());
	return $affectRows;
}

function runQueryWithKey($query, $key) {
	$result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
	
	//define $dataSource is array  Ge,Huangyi
	$dataSource = array();
	//
	
	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$dataSource[$row[$key]] = $row;
	}
	return $dataSource;
}

function affectRows()
{
	return $GLOBALS['mysqli']->affected_rows;
}

function getXrefLink($protId) {
    $query = 'SELECT idType.linkOut,idType.name,idMapping.otherId FROM idMapping,idType WHERE idMapping.otherType=idType.label AND idMapping.p3dbId=\''.$protId.'\';';
	$result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $type = $row['name'];
        $acc = $row['otherId'];
        $link = str_replace('$id', $acc, $row['linkOut']);
		$link = str_replace('&', '&amp;', $link);
        $xrefs[] = "<a href=\"$link\" title=\"Click to go to $type\" target=\"_blank\">$type:$acc</a>";
    }
    return $xrefs;
}

function getXref($protId) {
    $query = "SELECT idType.name,idMapping.otherId FROM idMapping,idType ".
             "WHERE idMapping.otherType=idType.label AND idMapping.p3dbId='$protId';";
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $type = $row['name'];
        $acc = $row['otherId'];
        $xrefs[] = "$type:$acc";
    }
    return $xrefs;
}

function getDataSourceProtein($protId) {
    $query = "SELECT id,reference,link,pubmed FROM dataSource,proteinDataSourceRelation WHERE protein='$protId' AND dataSource=id ORDER BY pubmed DESC";
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $dataSource[] = $row;
    }
    return $dataSource;
}

function getDataSourceSite($siteId) {
    $query = "SELECT id,reference,link,pubmed FROM dataSource,siteDataSourceRelation WHERE site='$siteId' AND dataSource=id ORDER BY pubmed DESC";
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $dataSource[] = $row;
    }
    return $dataSource;
}

function getDataSourceNrPep($protein,$loc,$nrseq) {
    $query = "SELECT DISTINCT dataSource.id,reference,link,pubmed FROM peptide,mass,dataSource".
             " WHERE peptide.protein=$protein AND peptide.location=$loc AND peptide.phosphoSequence='$nrseq' AND peptide.mass=mass.id AND mass.dataSource=dataSource.id ORDER BY pubmed DESC";
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $dataSource[] = $row;
    }
    return $dataSource;
}

function getOrganismSelect() {
    $query = "SELECT label,latin,commonName FROM organism ORDER BY latin";
    $result = runQuery($query);
	$s = "";
    foreach($result as $row) {
		$s.="<option value=\"{$row['label']}\">{$row['latin']} ({$row['commonName']})</option>";
    }
	return $s;
}

function getDataSourceSelect() {
    $query = "SELECT id,reference FROM dataSource ORDER BY pubmed DESC";
    $result = runQuery($query);
	$s = "";
    foreach($result as $row) {
		$s.="<option value=\"{$row['id']}\">{$row['reference']}</option>";
    }
	return $s;
}

function getDataSourceLink($id) {
    $query = "SELECT reference,link FROM dataSource  WHERE id=$id";
    $result = runQuery($query);
    foreach($result as $row) {
        $link = $row['link'];
        if (empty($link)) {
                return $row['reference'];
        } else {
                return "<a href=\"{$link}\" target=\"_blank\">{$row['reference']}</a>";
        }
    }
}

function spectralCountProtein($id,$ref) {
    $query = "SELECT COUNT(DISTINCT mass) AS specCount ".
             "FROM peptide ".
             "WHERE peptide.protein=$id";
    if (!empty($ref)) {
        $query .= " AND '$ref'=(SELECT mass.dataSource FROM mass WHERE mass.id=peptide.mass)";
    }
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    $row = $result->fetch_array(MYSQLI_ASSOC);
    return $row['specCount'];
}

function spectralCountSite($id,$ref) {
    $query = "SELECT COUNT(DISTINCT mass) AS specCount ".
             "FROM peptide, peptideSiteRelation ".
             "WHERE peptideSiteRelation.peptide=peptide.id AND peptideSiteRelation.site=$id";
    if (!empty($ref)) {
        $query .= " AND '$ref'=(SELECT mass.dataSource FROM mass WHERE mass.id=peptide.mass)";
    }
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    $row = $result->fetch_array(MYSQLI_ASSOC);
    return $row['specCount'];
}

function spectralCountNrPep($protein,$loc,$nrseq,$ref) {
    $query = "SELECT COUNT(DISTINCT mass) AS specCount ".
             "FROM peptide ".
             "WHERE protein=$protein AND location=$loc AND phosphoSequence='$nrseq'";
    if (!empty($ref)) {
        $query .= " AND '$ref'=(SELECT mass.dataSource FROM mass WHERE mass.id=peptide.mass)";
    }
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    $row = $result->fetch_array(MYSQLI_ASSOC);
    return $row['specCount'];
}

function spectralOfProtein($id,$ref) {
    $query = "SELECT DISTINCT mass ".
             "FROM peptide ".
             "WHERE peptide.protein=$id";
    if (!empty($ref)) {
        $query .= " AND '$ref'=(SELECT mass.dataSource FROM mass WHERE mass.id=peptide.mass)";
    }
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $mass[] = $row['mass'];
    }
    return $mass;
}

function getP3DBVersion() {
    $query = "SELECT version FROM p3dbInfo";
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    $row = $result->fetch_array(MYSQLI_ASSOC);
    return $row['version'];
}

function getRecordsFromATable($table, $where) {
    $query = "SELECT * FROM $table WHERE $where";
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $records[] = $row;
    }
    return $records;
}

function getMapIdRecordFromATable($table, $where) {
    $records = getRecordsFromATable($table, $where);
    $ret = array();
    foreach ($records as $rec) {
        $ret[$rec['id']] = $rec;
    }
    return $ret;
}

function serializeToDb($label, $data) {
    $sdata = serialize($data);
    $query = "INSERT INTO tmpSerialize(label,data,date) VALUE('$label','$sdata',NOW());";
    return mysql_query($query);
}

function unserializeFromDb($label) {
    $query = "SELECT data FROM tmpSerialize WHERE label='$label'";
    $result = $GLOBALS['mysqli']->query($query) or die(errorMessage($query));
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $data[] = unserialize($row['data']);
    }
    return $data;
}


function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
}
?>
