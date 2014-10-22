<?php


/* 
 * Paging
 */
function dataTableQueryConditionLimit()
{
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".$GLOBALS['mysqli']->real_escape_string( $_GET['iDisplayStart'] ).", ".
			$GLOBALS['mysqli']->real_escape_string( $_GET['iDisplayLength'] );
	}
	return $sLimit;
}

/*
 * Ordering
 */
function dataTableQueryConditionOrdering($aColumns)
{
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
					".$GLOBALS['mysqli']->real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	else
	{
		$sOrder = "";
	}
	return $sOrder;
}

/* 
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
function dataTableQueryConditionFilteringAllColumn($sWhere,$aColumns)
{
	if ( isset($_GET['sSearch'])&&$_GET['sSearch'] != "" )
	{
		$str = pack('H*',"E38080");//全角空格
		$_GET['sSearch'] = trim(preg_replace("/[\s\p{Z}".$str."]+/",' ',$_GET['sSearch']));
		$keyArray = explode(' ',$_GET['sSearch']);
		if(empty($sWhere)) {
			$sWhere = "WHERE (";
		} else {
			$sWhere .= " AND (";
		}
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			foreach($keyArray AS $key)
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$GLOBALS['mysqli']->real_escape_string( str_replace("*","%",$key ))."%' OR ";
			}
		}
		$sWhere = substr_replace( $sWhere, "", -3 ).')';
	}
	return $sWhere;
}

/* Individual column filtering */
function dataTableQueryConditionFilteringIndividualColumn($sWhere,$aColumns)
{
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{

		$str = pack('H*',"E38080");//全角空格
		if(isset($_GET['sSearch_'.$i]))
		{
			$_GET['sSearch_'.$i] = trim(preg_replace("/[\s\p{Z}".$str."]+/",' ',$_GET['sSearch_'.$i]));
			
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				$keyArray = explode(' ',$_GET['sSearch_'.$i]);
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE (";
				}
				else
				{
					$sWhere .= " AND (";
				}
				
				$j = 0;
				foreach($keyArray AS $key)
				{
					if($j>0)
						$sWhere .= " OR ";
					//$sWhere .= $aColumns[$i]." LIKE '%".str_replace(" ","%' OR ".$aColumns[$i]." LIKE '%",mysql_real_escape_string($_GET['sSearch_'.$i]))."%') ";
					$sWhere .= $aColumns[$i]." LIKE '%".str_replace("*","%",$GLOBALS['mysqli']->real_escape_string($key))."%'";
					++$j;
				}
				$sWhere .=")";
			}
		}
	}
	return $sWhere;
}

/* filtering */
function dataTableQueryConditionFiltering($sWhere,$aColumns)
{
	$sWhere = dataTableQueryConditionFilteringAllColumn($sWhere,$aColumns);
	$sWhere = dataTableQueryConditionFilteringIndividualColumn($sWhere,$aColumns);
	return $sWhere;
}
/*
 * SQL queries
 * Get data to display
 */
function dataTableRunQuery($distinct,$sTable,$sWhere,$sOrder,$sLimit)
{
	$sQuery = "
		SELECT".(($distinct)? " DISTINCT" : "")." SQL_CALC_FOUND_ROWS *
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	//mysql_fetch_row
	$rResult = runQuery($sQuery);
	return $rResult;
}

function dataTableQueryFoundRows()
{
	$query = "SELECT FOUND_ROWS()";
	$result = runQuery($query);
	return $result[0]["FOUND_ROWS()"];
}
function dataTableQueryTotalRows($sTable,$sJoinCondition)
{
	$query = "SELECT COUNT(*) FROM $sTable ".(($sJoinCondition != "")? "WHERE $sJoinCondition" : "");
	$result = runQuery($query);
	return $result[0]["COUNT(*)"];
}

function dataTableQueryResultHandle($rResult,$output,$dataHandleFunction)
{
	foreach($rResult as $row)
	{
		$output["aaData"][]=$dataHandleFunction($row);
	}
	return $output;
}

function dataTableQueryResultOutput($iTotal,$iFilteredTotal,$rResult,$dataHandleFunction)
{
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
		/*,
		"sQuery" => $sQuery,
		"tmp"=>explode("\u3000",preg_replace("/[\s\p{Z}]+/",'',trim($_GET['sSearch_0'])))
		*/
	);
	
	$output = dataTableQueryResultHandle($rResult,$output,$dataHandleFunction);
	print json_encode( $output );
}

?>