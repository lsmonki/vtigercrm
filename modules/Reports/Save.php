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
$reportname = addslashes($_REQUEST["reportName"]);
$reportdescription = $_REQUEST["reportDesc"];
$reporttype = $_REQUEST["reportType"];
$folderid = $_REQUEST["folder"];
//<<<<<<<report>>>>>>>>>

//<<<<<<<standarfilters>>>>>>>>>
$stdDateFilterField = $_REQUEST["stdDateFilterField"];
$stdDateFilter = $_REQUEST["stdDateFilter"];
$startdate = getDBInsertDateValue($_REQUEST["startdate"]);
$enddate = getDBInsertDateValue($_REQUEST["enddate"]);
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
           $adv_filter_value[] = addslashes($_REQUEST[$allKeys[$i]]);
   }
}
//<<<<<<<advancedfilter>>>>>>>>
if($reportid == "")
{
	$genQueryId = $adb->getUniqueID("vtiger_selectquery");
	if($genQueryId != "")
	{
		$iquerysql = "insert into vtiger_selectquery (QUERYID,STARTINDEX,NUMOFOBJECTS) values (".$genQueryId.",0,0)";
		$iquerysqlresult = $adb->query($iquerysql);
		$log->info("Reports :: Save->Successfully saved vtiger_selectquery");
		if($iquerysqlresult!=false)
		{
			//<<<<step2 vtiger_selectcolumn>>>>>>>>
			if($selectedcolumnstring != "")
			{
				$selectedcolumns = explode(";",$selectedcolumnstring);
				for($i=0 ;$i< count($selectedcolumns) -1 ;$i++)
				{
					$icolumnsql = "insert into vtiger_selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (".$genQueryId.",".$i.",'".$selectedcolumns[$i]."')";
					$icolumnsqlresult = $adb->query($icolumnsql);
				}
			}
			$log->info("Reports :: Save->Successfully saved vtiger_selectcolumn");
			//<<<<step2 vtiger_selectcolumn>>>>>>>>

			if($genQueryId != "")
			{
				$ireportsql = "insert into vtiger_report (REPORTID,FOLDERID,REPORTNAME,DESCRIPTION,REPORTTYPE,QUERYID,STATE)";
				$ireportsql .= " values (".$genQueryId.",".$folderid.",'".$reportname."','".$reportdescription."','".$reporttype."',".$genQueryId.",'CUSTOM')";
				$ireportresult = $adb->query($ireportsql);
				$log->info("Reports :: Save->Successfully saved vtiger_report");
				if($ireportresult!=false)
				{
					//<<<<reportmodules>>>>>>>
					$ireportmodulesql = "insert into vtiger_reportmodules (REPORTMODULESID,PRIMARYMODULE,SECONDARYMODULES) values (".$genQueryId.",'".$pmodule."','".$smodule."')";
					$ireportmoduleresult = $adb->query($ireportmodulesql);
					$log->info("Reports :: Save->Successfully saved vtiger_reportmodules");
					//<<<<reportmodules>>>>>>>

					//<<<<step3 vtiger_reportsortcol>>>>>>>
					if($sort_by1 != "")
					{
						$sort_by1sql = "insert into vtiger_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (1,".$genQueryId.",'".$sort_by1."','".$sort_order1."')";
						$sort_by1result = $adb->query($sort_by1sql);
					}
					if($sort_by2 != "")
					{
						$sort_by2sql = "insert into vtiger_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (2,".$genQueryId.",'".$sort_by2."','".$sort_order2."')";
						$sort_by2result = $adb->query($sort_by2sql);
					}
					if($sort_by3 != "")
					{
						$sort_by3sql = "insert into vtiger_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (3,".$genQueryId.",'".$sort_by3."','".$sort_order3."')";
						$sort_by3result = $adb->query($sort_by3sql);
					}
					$log->info("Reports :: Save->Successfully saved vtiger_reportsortcol");
					//<<<<step3 vtiger_reportsortcol>>>>>>>

					//<<<<step5 standarfilder>>>>>>>
					$ireportmodulesql = "insert into vtiger_reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (".$genQueryId.",'".$stdDateFilterField."','".$stdDateFilter."','".$startdate."','".$enddate."')";
					$ireportmoduleresult = $adb->query($ireportmodulesql);
					$log->info("Reports :: Save->Successfully saved vtiger_reportdatefilter");
					//<<<<step5 standarfilder>>>>>>>

					//<<<<step4 columnstototal>>>>>>>
					for ($i=0;$i<count($columnstototal);$i++)
					{
						$ireportsummarysql = "insert into vtiger_reportsummary (REPORTSUMMARYID,SUMMARYTYPE,COLUMNNAME) values (".$genQueryId.",".$i.",'".$columnstototal[$i]."')";
						$ireportsummaryresult = $adb->query($ireportsummarysql);
					}
					$log->info("Reports :: Save->Successfully saved vtiger_reportsummary");
					//<<<<step4 columnstototal>>>>>>>

					//<<<<step5 advancedfilter>>>>>>>
					for ($i=0;$i<count($adv_filter_col);$i++)
					{
						$irelcriteriasql = "insert into vtiger_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) values (".$genQueryId.",".$i.",'".$adv_filter_col[$i]."','".$adv_filter_option[$i]."','".$adv_filter_value[$i]."')";
						$irelcriteriaresult = $adb->query($irelcriteriasql);
					}
					$log->info("Reports :: Save->Successfully saved vtiger_relcriteria");
					//<<<<step5 advancedfilter>>>>>>>

				}else
				{
					$errormessage = "<font color='red'><B>Error Message<ul>
						<li><font color='red'>Error while inserting the record</font>
						</ul></B></font> <br>" ;
					echo $errormessage;
					die;
				}
			}
		}else
		{
			$errormessage = "<font color='red'><B>Error Message<ul>
				<li><font color='red'>Error while inserting the record</font>
				</ul></B></font> <br>" ;
			echo $errormessage;
			die;
		}
		echo '<script>window.opener.location.href =window.opener.location.href;self.close();</script>';
	}
}else
{
	if($reportid != "")
	{
		if($selectedcolumnstring != "")
		{
			$idelcolumnsql = "delete from vtiger_selectcolumn where queryid=".$reportid;
			$idelcolumnsqlresult = $adb->query($idelcolumnsql);
			if($idelcolumnsqlresult != false)
			{
				$selectedcolumns = explode(";",$selectedcolumnstring);
				for($i=0 ;$i< count($selectedcolumns) -1 ;$i++)
				{
					$icolumnsql = "insert into vtiger_selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (".$reportid.",".$i.",'".$selectedcolumns[$i]."')";
					$icolumnsqlresult = $adb->query($icolumnsql);
				}
			}
		}

		$ireportsql = "update vtiger_report set";
		$ireportsql .= " REPORTNAME='".$reportname."',";
		$ireportsql .= " DESCRIPTION='".$reportdescription."',";
		$ireportsql .= " REPORTTYPE='".$reporttype."'";
		$ireportsql .= " where REPORTID=".$reportid;
		$ireportresult = $adb->query($ireportsql);
		$log->info("Reports :: Save->Successfully saved vtiger_report");

		$idelreportsortcolsql = "delete from vtiger_reportsortcol where reportid=".$reportid;
		$idelreportsortcolsqlresult = $adb->query($idelreportsortcolsql);
		$log->info("Reports :: Save->Successfully deleted vtiger_reportsortcol");

		if($idelreportsortcolsqlresult!=false)
		{
			//<<<<step3 vtiger_reportsortcol>>>>>>>
			if($sort_by1 != "")
			{
				$sort_by1sql = "insert into vtiger_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (1,".$reportid.",'".$sort_by1."','".$sort_order1."')";
				$sort_by1result = $adb->query($sort_by1sql);
			}
			if($sort_by2 != "")
			{
				$sort_by2sql = "insert into vtiger_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (2,".$reportid.",'".$sort_by2."','".$sort_order2."')";
				$sort_by2result = $adb->query($sort_by2sql);
			}
			if($sort_by3 != "")
			{
				$sort_by3sql = "insert into vtiger_reportsortcol (SORTCOLID,REPORTID,COLUMNNAME,SORTORDER) values (3,".$reportid.",'".$sort_by3."','".$sort_order3."')";
				$sort_by3result = $adb->query($sort_by3sql);
			}
			$log->info("Reports :: Save->Successfully saved vtiger_reportsortcol");
			//<<<<step3 vtiger_reportsortcol>>>>>>>

			$idelreportdatefiltersql = "delete from vtiger_reportdatefilter where datefilterid=".$reportid;
			$idelreportdatefiltersqlresult = $adb->query($idelreportdatefiltersql);

			//<<<<step5 standarfilder>>>>>>>
			$ireportmodulesql = "insert into vtiger_reportdatefilter (DATEFILTERID,DATECOLUMNNAME,DATEFILTER,STARTDATE,ENDDATE) values (".$reportid.",'".$stdDateFilterField."','".$stdDateFilter."','".$startdate."','".$enddate."')";
			$ireportmoduleresult = $adb->query($ireportmodulesql);
			$log->info("Reports :: Save->Successfully saved vtiger_reportdatefilter");
			//<<<<step5 standarfilder>>>>>>>

			//<<<<step4 columnstototal>>>>>>>
			$idelreportsummarysql = "delete from vtiger_reportsummary where reportsummaryid=".$reportid;
			$idelreportsummarysqlresult = $adb->query($idelreportsummarysql);

			for ($i=0;$i<count($columnstototal);$i++)
			{
				$ireportsummarysql = "insert into vtiger_reportsummary (REPORTSUMMARYID,SUMMARYTYPE,COLUMNNAME) values (".$reportid.",".$i.",'".$columnstototal[$i]."')";
				$ireportsummaryresult = $adb->query($ireportsummarysql);
			}
			$log->info("Reports :: Save->Successfully saved vtiger_reportsummary");
			//<<<<step4 columnstototal>>>>>>>


			//<<<<step5 advancedfilter>>>>>>>

			$idelrelcriteriasql = "delete from vtiger_relcriteria where queryid=".$reportid;
			$idelrelcriteriasqlresult = $adb->query($idelrelcriteriasql);

			for ($i=0;$i<count($adv_filter_col);$i++)
			{
				$irelcriteriasql = "insert into vtiger_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) values (".$reportid.",".$i.",'".$adv_filter_col[$i]."','".$adv_filter_option[$i]."','".$adv_filter_value[$i]."')";
				$irelcriteriaresult = $adb->query($irelcriteriasql);
			}
			$log->info("Reports :: Save->Successfully saved vtiger_relcriteria");
			//<<<<step5 advancedfilter>>>>>>>

		}else
		{
			$errormessage = "<font color='red'><B>Error Message<ul>
				<li><font color='red'>Error while inserting the record</font>
				</ul></B></font> <br>" ;
			echo $errormessage;
			die;
		}
	}else
	{
		$errormessage = "<font color='red'><B>Error Message<ul>
			<li><font color='red'>Error while inserting the record</font>
			</ul></B></font> <br>" ;
		echo $errormessage;
		die;
	}
	echo '<script>window.opener.location.href = window.opener.location.href;self.close();</script>';
}
?>
