<?php


function insertComboValues($values, $tableName)
{
	$i=0;
        foreach ($values as $val => $cal)
        {
        	if($val != '')
	        {
			$sql = "insert into ".$tableName. " values('','".$val."',".$i.",1)";
        		mysql_query($sql);//"insert into ".$tableName. " values('','".$val."',".$i.",1)");
	        }
        	else
            	{
			$sql = "insert into ".$tableName. " values('','--None--',".$i.",1)";
              		mysql_query($sql);//"insert into ".$tableName. " values('','--None--',".$i.",1)");
            	}
            	$i++;
		echo '<br> Function - insertComboValues : '.$sql;
        }
}
function PopulateReportFolder($fldrname,$fldrdescription)
{
	$sql = "INSERT INTO reportfolder ";
	$sql .= "(FOLDERID,FOLDERNAME,DESCRIPTION,STATE) ";
	$sql .= "VALUES ('','".$fldrname."','".$fldrdescription."','SAVED')";
	$result = mysql_query($sql);
	echo '<br> Function - PopulateReportFolder : '.$sql;
}
function insertSelectQuery()
{
	$res = mysql_query("select max(queryid) count from selectquery");
	$genQueryId = mysql_result($res,0,'count');
	$genQueryId++;

        if($genQueryId != "")
        {
		$iquerysql = "insert into selectquery (QUERYID,STARTINDEX,NUMOFOBJECTS) values (".$genQueryId.",0,0)";
		$iquerysqlresult = mysql_query($iquerysql);
		echo '<br>Unique id : '.$genQueryId.'<br> Function - insertSelectQuery : '.$iquerysql;
	}

	return $genQueryId;
}
function insertReports($queryid,$folderid,$reportname,$description,$reporttype)
{
	if($queryid != "")
	{
		$ireportsql = "insert into report (REPORTID,FOLDERID,REPORTNAME,DESCRIPTION,REPORTTYPE,QUERYID,STATE)";
                $ireportsql .= " values (".$queryid.",".$folderid.",'".$reportname."','".$description."','".$reporttype."',".$queryid.",'SAVED')";
		$ireportresult = mysql_query($ireportsql);
		echo '<br> Function - insertReports : '.$ireportsql;
	}
}
function insertSelectColumns($queryid,$columnname)
{
	if($queryid != "")
	{
		for($i=0;$i < count($columnname);$i++)
		{
			$icolumnsql = "insert into selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (".$queryid.",".$i.",'".$columnname[$i]."')";
			$icolumnsqlresult = mysql_query($icolumnsql);	
			echo '<br> Function - insertSelectColumns : '.$icolumnsql;
		}
	}
}
function insertReportModules($queryid,$primarymodule,$secondarymodule)
{
	if($queryid != "")
	{
		$ireportmodulesql = "insert into reportmodules (REPORTMODULESID,PRIMARYMODULE,SECONDARYMODULES) values (".$queryid.",'".$primarymodule."','".$secondarymodule."')";
		$ireportmoduleresult = mysql_query($ireportmodulesql);
		echo '<br> Function - insertReportModules : '.$ireportmodulesql;
	}
}
function insertStdFilter($queryid,$filtercolumn,$datefilter,$startdate,$enddate)
{
	if($queryid != "")
	{
		$ireportmodulesql = "insert into reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (".$queryid.",'".$filtercolumn."','".$datefilter."','".$startdate."','".$enddate."')";
		$ireportmoduleresult = mysql_query($ireportmodulesql);
		echo '<br> Function - insertStdFilter : '.$ireportmodulesql;
	}

}
function insertAdvFilter($queryid,$filters)
{
	if($queryid != "")
	{
		foreach($filters as $i=>$filter)
		{
		      $irelcriteriasql = "insert into relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) 
		      values (".$queryid.",".$i.",'".$filter['columnname']."','".$filter['comparator']."','".$filter['value']."')";
		      $irelcriteriaresult = mysql_query($irelcriteriasql);
		      echo '<br> Function - insertAdvFilter : '.$irelcriteriasql;
		}
	}
}
function insertSortColumns($queryid,$sortlists)
{
	if($queryid != "")
	{
		foreach($sortlists as $i=>$sort)
                {
			$sort_bysql = "insert into reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) 
					values (".($i+1).",".$queryid.",'".$sort['columnname']."','".$sort['sortorder']."')";
			$sort_byresult = mysql_query($sort_bysql);
			echo '<br> Function - insertSortColumns : '.$sort_bysql;
		}
	}

}



//The following functions are used to Populate the default CustomViews.
function insertCustomView($viewname,$setdefault,$setmetrics,$cvmodule)
{
	//$genCVid = $adb->getUniqueID("customview");
	$res = mysql_query("select max(cvid) count from customview");
        $genCVid = mysql_result($res,0,'count');
        $genCVid++;

	if($genCVid != "")
	{

		$customviewsql = "insert into customview(cvid,viewname,setdefault,setmetrics,entitytype)";
		$customviewsql .= " values(".$genCVid.",'".$viewname."',".$setdefault.",".$setmetrics.",'".$cvmodule."')";
		$customviewresult = mysql_query($customviewsql);
		echo '<br> Unique id : '.$genCVid.'<br> Function - insertCustomView : '.$customviewsql;
	}
	return $genCVid;
}
function insertCvColumns($CVid,$columnslist)
{
	if($CVid != "")
	{
		for($i=0;$i<count($columnslist);$i++)
		{
			$columnsql = "insert into cvcolumnlist (cvid,columnindex,columnname)";
			$columnsql .= " values (".$CVid.",".$i.",'".$columnslist[$i]."')";
			$columnresult = mysql_query($columnsql);
			echo '<br> Function - insertCvColumns : '.$columnsql;
		}
	}
}
function insertCvStdFilter($CVid,$filtercolumn,$filtercriteria,$startdate,$enddate)
{
	if($CVid != "")
	{
		$stdfiltersql = "insert into cvstdfilter(cvid,columnname,stdfilter,startdate,enddate)";
		$stdfiltersql .= " values (".$CVid.",'".$filtercolumn."',";
		$stdfiltersql .= "'".$filtercriteria."',";
		$stdfiltersql .= "'".$startdate."',";
		$stdfiltersql .= "'".$enddate."')";
		$stdfilterresult = mysql_query($stdfiltersql);
		echo '<br> Function - insertCvStdFilter : '.$stdfiltersql;
	}
}
function insertCvAdvFilter($CVid,$filters)
{
	if($CVid != "")
	{
		foreach($filters as $i=>$filter)
		{
			$advfiltersql = "insert into cvadvfilter(cvid,columnindex,columnname,comparator,value)";
                        $advfiltersql .= " values (".$CVid.",".$i.",'".$filter['columnname']."',";
                        $advfiltersql .= "'".$filter['comparator']."',";
                        $advfiltersql .= "'".$filter['value']."')";
                        $advfilterresult = mysql_query($advfiltersql);
			echo '<br> Function - insertCvAdvFilter : '.$advfiltersql;
		}

		/*for($i=0;$i<count($filtercolumns);$i++)
		{
			$advfiltersql = "insert into cvadvfilter(cvid,columnindex,columnname,comparator,value)";
			$advfiltersql .= " values (".$CVid.",".$i.",'".$filtercolumns[$i]."',";
			$advfiltersql .= "'".$filteroption[$i]."',";
			$advfiltersql .= "'".$filtervalue[$i]."')";
			//echo $advfiltersql;
			$advfilterresult = $adb->query($advfiltersql);
		}*/

	}
}


?>
