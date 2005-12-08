<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('modules/Reports/Reports.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

global $adb;
global $log;

$reportid = $_REQUEST["record"];

//<<<<<<<selectcolumn>>>>>>>>>
$selectedcolumnstring = $_REQUEST["selectedColumnsString"];
//<<<<<<<selectcolumn>>>>>>>>>

//<<<<<<<reportsortcol>>>>>>>>>
$sort_by1 = $_REQUEST["Group1"];
$sort_order1 = $_REQUEST["Sort1"];
$sort_by2 = $_REQUEST["Group2"];
$sort_order2 = $_REQUEST["Sort2"];
$sort_by3 = $_REQUEST["Group3"];
$sort_order3 = $_REQUEST["Sort3"];
//<<<<<<<reportsortcol>>>>>>>>>

//<<<<<<<reportmodules>>>>>>>>>
$pmodule = $_REQUEST["primarymodule"];
$smodule = $_REQUEST["secondarymodule"];
//<<<<<<<reportmodules>>>>>>>>>

//<<<<<<<report>>>>>>>>>
$reportname = $_REQUEST["reportName"];
$reportdescription = $_REQUEST["reportDesc"];
$reporttype = $_REQUEST["reportType"];
$folderid = $_REQUEST["folder"];
//<<<<<<<report>>>>>>>>>

//<<<<<<<standarfilters>>>>>>>>>
$stdDateFilterField = $_REQUEST["stdDateFilterField"];
$stdDateFilter = $_REQUEST["stdDateFilter"];
$startdate = $_REQUEST["startdate"];
$enddate = $_REQUEST["enddate"];
//<<<<<<<standardfilters>>>>>>>>>

//<<<<<<<columnstototal>>>>>>>>>>
$allKeys = array_keys($HTTP_POST_VARS);
for ($i=0;$i<count($allKeys);$i++)
{
   $string = substr($allKeys[$i], 0, 3);
   if($string == "cb:")
   {
	   $columnstototal[] = $allKeys[$i];
   }
}
//<<<<<<<columnstototal>>>>>>>>>

//<<<<<<<advancedfilter>>>>>>>>
//$adv_filter_col = "kcol";
$allKeys = array_keys($HTTP_POST_VARS);
for ($i=0;$i<count($allKeys);$i++)
{
   $string = substr($allKeys[$i], 0, 4);
   if($string == "fcol")
   {
	   $adv_filter_col[] = $_REQUEST[$allKeys[$i]];
   }
}
for ($i=0;$i<count($allKeys);$i++)
{
   $string = substr($allKeys[$i], 0, 3);
   if($string == "fop")
   {
           $adv_filter_option[] = $_REQUEST[$allKeys[$i]];
   }
}
for ($i=0;$i<count($allKeys);$i++)
{
   $string = substr($allKeys[$i], 0, 4);
   if($string == "fval")
   {
           $adv_filter_value[] = $_REQUEST[$allKeys[$i]];
   }
}
//<<<<<<<advancedfilter>>>>>>>>
if($reportid == "")
{
	$genQueryId = $adb->getUniqueID("selectquery");
	if($genQueryId != "")
	{
		$iquerysql = "insert into selectquery (QUERYID,STARTINDEX,NUMOFOBJECTS) values (".$genQueryId.",0,0)";
		$iquerysqlresult = $adb->query($iquerysql);
		 $log->info("Reports :: Save->Successfully saved selectquery");
		if($iquerysqlresult!=false)
		{
			//<<<<step2 selectcolumn>>>>>>>>
			if($selectedcolumnstring != "")
			{
				$selectedcolumns = explode(";",$selectedcolumnstring);
				for($i=0 ;$i< count($selectedcolumns) -1 ;$i++)
				{
					$icolumnsql = "insert into selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (".$genQueryId.",".$i.",'".$selectedcolumns[$i]."')";
					$icolumnsqlresult = $adb->query($icolumnsql);
				}
			}
			$log->info("Reports :: Save->Successfully saved selectcolumn");
			//<<<<step2 selectcolumn>>>>>>>>

		       //$genReportMId = $adb->getUniqueID("reportmodules");

		       if($genQueryId != "")
		       {
				$ireportsql = "insert into report (REPORTID,FOLDERID,REPORTNAME,DESCRIPTION,REPORTTYPE,QUERYID,STATE)";
				$ireportsql .= " values (".$genQueryId.",".$folderid.",'".$reportname."','".$reportdescription."','".$reporttype."',".$genQueryId.",'CUSTOM')";
				$ireportresult = $adb->query($ireportsql);
			       	$log->info("Reports :: Save->Successfully saved report");
				if($ireportresult!=false)
				{
					//<<<<reportmodules>>>>>>>
					$ireportmodulesql = "insert into reportmodules (REPORTMODULESID,PRIMARYMODULE,SECONDARYMODULES) values (".$genQueryId.",'".$pmodule."','".$smodule."')";
					$ireportmoduleresult = $adb->query($ireportmodulesql);
					$log->info("Reports :: Save->Successfully saved reportmodules");
					//<<<<reportmodules>>>>>>>

					//<<<<step3 reportsortcol>>>>>>>
					if($sort_by1 != "")
					{
						$sort_by1sql = "insert into reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (1,".$genQueryId.",'".$sort_by1."','".$sort_order1."')";
						$sort_by1result = $adb->query($sort_by1sql);
					}
					if($sort_by2 != "")
					{
						$sort_by2sql = "insert into reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (2,".$genQueryId.",'".$sort_by2."','".$sort_order2."')";
						$sort_by2result = $adb->query($sort_by2sql);
					}
					if($sort_by3 != "")
					{
						$sort_by3sql = "insert into reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (3,".$genQueryId.",'".$sort_by3."','".$sort_order3."')";
						$sort_by3result = $adb->query($sort_by3sql);
					}
					$log->info("Reports :: Save->Successfully saved reportsortcol");
					//<<<<step3 reportsortcol>>>>>>>

					//<<<<step5 standarfilder>>>>>>>
					$ireportmodulesql = "insert into reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (".$genQueryId.",'".$stdDateFilterField."','".$stdDateFilter."','".$startdate."','".$enddate."')";
					$ireportmoduleresult = $adb->query($ireportmodulesql);
					$log->info("Reports :: Save->Successfully saved reportdatefilter");
					//<<<<step5 standarfilder>>>>>>>

					//<<<<step4 columnstototal>>>>>>>
					for ($i=0;$i<count($columnstototal);$i++)
					{
						$ireportsummarysql = "insert into reportsummary (REPORTSUMMARYID,SUMMARYTYPE,COLUMNNAME) values (".$genQueryId.",".$i.",'".$columnstototal[$i]."')";
						$ireportsummaryresult = $adb->query($ireportsummarysql);
					}
					$log->info("Reports :: Save->Successfully saved reportsummary");
					//<<<<step4 columnstototal>>>>>>>

					//<<<<step5 advancedfilter>>>>>>>
                                        for ($i=0;$i<count($adv_filter_col);$i++)
                                        {
                                                $irelcriteriasql = "insert into relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) values (".$genQueryId.",".$i.",'".$adv_filter_col[$i]."','".$adv_filter_option[$i]."','".$adv_filter_value[$i]."')";
						//echo $irelcriteriasql;

                                                $irelcriteriaresult = $adb->query($irelcriteriasql);
                                        }
					$log->info("Reports :: Save->Successfully saved relcriteria");
                                        //<<<<step5 advancedfilter>>>>>>>

				}else
				{
					include('themes/'.$theme.'/header.php');
					$errormessage = "<font color='red'><B>Error Message<ul>
					<li><font color='red'>Error while inserting the record</font>
					</ul></B></font> <br>" ;
					echo $errormessage;
				}
		       }
		}else
		{
			include('themes/'.$theme.'/header.php');
			$errormessage = "<font color='red'><B>Error Message<ul>
			<li><font color='red'>Error while inserting the record</font>
			</ul></B></font> <br>" ;
			echo $errormessage;
		}
		header("Location: index.php?action=SaveAndRun&module=Reports&record=$genQueryId");
	}
}else
{
	if($reportid != "")
	{
	       if($selectedcolumnstring != "")
		{
			$idelcolumnsql = "delete from selectcolumn where queryid=".$reportid;
			$idelcolumnsqlresult = $adb->query($idelcolumnsql);
			//echo $idelcolumnsql;
			if($idelcolumnsqlresult != false)
			{
				$selectedcolumns = explode(";",$selectedcolumnstring);
				for($i=0 ;$i< count($selectedcolumns) -1 ;$i++)
				{
					$icolumnsql = "insert into selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (".$reportid.",".$i.",'".$selectedcolumns[$i]."')";
					$icolumnsqlresult = $adb->query($icolumnsql);
					//echo $icolumnsql;
				}
			}
		}
		
		$ireportsql = "update report set";
		$ireportsql .= " REPORTNAME='".$reportname."',";
		$ireportsql .= " DESCRIPTION='".$reportdescription."',";
		$ireportsql .= " REPORTTYPE='".$reporttype."'";
		$ireportsql .= " where REPORTID=".$reportid;
		$ireportresult = $adb->query($ireportsql);
		$log->info("Reports :: Save->Successfully saved report");
		//echo $ireportsql;

		$idelreportsortcolsql = "delete from reportsortcol where reportid=".$reportid;
		$idelreportsortcolsqlresult = $adb->query($idelreportsortcolsql);
		$log->info("Reports :: Save->Successfully deleted reportsortcol");
		//echo $idelreportsortcolsql;

		if($idelreportsortcolsqlresult!=false)
		{
			//<<<<step3 reportsortcol>>>>>>>
			if($sort_by1 != "")
			{
				$sort_by1sql = "insert into reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (1,".$reportid.",'".$sort_by1."','".$sort_order1."')";
				$sort_by1result = $adb->query($sort_by1sql);
				//echo $sort_by1sql;
			}
			if($sort_by2 != "")
			{
				$sort_by2sql = "insert into reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (2,".$reportid.",'".$sort_by2."','".$sort_order2."')";
				$sort_by2result = $adb->query($sort_by2sql);
			}
			if($sort_by3 != "")
			{
				$sort_by3sql = "insert into reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (3,".$reportid.",'".$sort_by3."','".$sort_order3."')";
				$sort_by3result = $adb->query($sort_by3sql);
			}
			$log->info("Reports :: Save->Successfully saved reportsortcol");
			//<<<<step3 reportsortcol>>>>>>>

			$idelreportdatefiltersql = "delete from reportdatefilter where datefilterid=".$reportid;
			$idelreportdatefiltersqlresult = $adb->query($idelreportdatefiltersql);
			//echo $idelreportsortcolsql;

			//<<<<step5 standarfilder>>>>>>>
			$ireportmodulesql = "insert into reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (".$reportid.",'".$stdDateFilterField."','".$stdDateFilter."','".$startdate."','".$enddate."')";
			$ireportmoduleresult = $adb->query($ireportmodulesql);
			$log->info("Reports :: Save->Successfully saved reportdatefilter");
			//<<<<step5 standarfilder>>>>>>>

			//<<<<step4 columnstototal>>>>>>>
			$idelreportsummarysql = "delete from reportsummary where reportsummaryid=".$reportid;
			$idelreportsummarysqlresult = $adb->query($idelreportsummarysql);

			for ($i=0;$i<count($columnstototal);$i++)
			{
				$ireportsummarysql = "insert into reportsummary (REPORTSUMMARYID,SUMMARYTYPE,COLUMNNAME) values (".$reportid.",".$i.",'".$columnstototal[$i]."')";
				$ireportsummaryresult = $adb->query($ireportsummarysql);
			}
			 $log->info("Reports :: Save->Successfully saved reportsummary");
			//<<<<step4 columnstototal>>>>>>>


			//<<<<step5 advancedfilter>>>>>>>

                        $idelrelcriteriasql = "delete from relcriteria where queryid=".$reportid;
                        $idelrelcriteriasqlresult = $adb->query($idelrelcriteriasql);

                        for ($i=0;$i<count($adv_filter_col);$i++)
                        {
                                $irelcriteriasql = "insert into relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) values (".$reportid.",".$i.",'".$adv_filter_col[$i]."','".$adv_filter_option[$i]."','".$adv_filter_value[$i]."')";
                                $irelcriteriaresult = $adb->query($irelcriteriasql);
                        }
                        $log->info("Reports :: Save->Successfully saved relcriteria");
			//<<<<step5 advancedfilter>>>>>>>

		}else
		{
			include('themes/'.$theme.'/header.php');
			$errormessage = "<font color='red'><B>Error Message<ul>
			<li><font color='red'>Error while inserting the record</font>
			</ul></B></font> <br>" ;
			echo $errormessage;
		}
	}else
	{
		include('themes/'.$theme.'/header.php');
		$errormessage = "<font color='red'><B>Error Message<ul>
		<li><font color='red'>Error while inserting the record</font>
		</ul></B></font> <br>" ;
		echo $errormessage;
	}
	header("Location: index.php?action=SaveAndRun&module=Reports&record=$reportid");
}
?>
