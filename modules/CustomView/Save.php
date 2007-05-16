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
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
global $adb;
global $log;

$cvid = (int) $_REQUEST["record"];
$cvmodule = $_REQUEST["cvmodule"];
$parenttab = $_REQUEST["parenttab"];
$return_action = $_REQUEST["return_action"];
if($cvmodule != "")
{
	$viewname = htmlentities($_REQUEST["viewName"]);
	if(isset($_REQUEST["setDefault"]))
	{
	  $setdefault = 1;
	}else
	{
	  $setdefault = 0;
	}

	if(isset($_REQUEST["setMetrics"]))
        {
          $setmetrics = 1;
        }else
        {
          $setmetrics = 0;
        }

 	$allKeys = array_keys($HTTP_POST_VARS);

	//<<<<<<<columns>>>>>>>>>>
	for ($i=0;$i<count($allKeys);$i++)
	{
	   $string = substr($allKeys[$i], 0, 6);
	   if($string == "column")
	   {
        	   $columnslist[] = $_REQUEST[$allKeys[$i]];
   	   }
	}
	//<<<<<<<columns>>>>>>>>>

	//<<<<<<<standardfilters>>>>>>>>>
	$stdfiltercolumn = $_REQUEST["stdDateFilterField"];
	$std_filter_list["columnname"] = $stdfiltercolumn;
	$stdcriteria = $_REQUEST["stdDateFilter"];
	$std_filter_list["stdfilter"] = $stdcriteria;
	$startdate = $_REQUEST["startdate"];
	$enddate = $_REQUEST["enddate"];
	if($stdcriteria == "custom")
	{
		$startdate = getDBInsertDateValue($startdate);
		$enddate = getDBInsertDateValue($enddate);
	}
	$std_filter_list["startdate"] = $startdate;
	$std_filter_list["enddate"]=$enddate;
	//<<<<<<<standardfilters>>>>>>>>>

	//<<<<<<<advancedfilter>>>>>>>>>
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
		   $adv_filter_value[] = htmlentities(trim($_REQUEST[$allKeys[$i]]));
   	   }
	}
	//<<<<<<<advancedfilter>>>>>>>>

	if(!$cvid)
	{
		$genCVid = $adb->getUniqueID("vtiger_customview");
		if($genCVid != "")
		{

			if($setdefault == 1)
			{
				$updatedefaultsql = "UPDATE vtiger_customview SET setdefault = 0 WHERE entitytype = ".$adb->quote($cvmodule);
				$updatedefaultresult = $adb->query($updatedefaultsql);
			}
			$log->info("CustomView :: Save :: setdefault upated successfully");

			$customviewsql = "INSERT INTO vtiger_customview(cvid, viewname,
						setdefault, setmetrics,
						entitytype)
					VALUES (".$genCVid.",".$adb->quote($viewname).",
						".$setdefault.",".$setmetrics.",
						".$adb->quote($cvmodule).")";
			$customviewresult = $adb->query($customviewsql);
			$log->info("CustomView :: Save :: vtiger_customview created successfully");
			if($customviewresult)
			{
				if(isset($columnslist))
				{
					for($i=0;$i<count($columnslist);$i++)
					{
						$columnsql = "INSERT INTO vtiger_cvcolumnlist (cvid, columnindex, columnname)
							VALUES (".$genCVid.", ".$i.", ".$adb->quote($columnslist[$i]).")";
						$columnresult = $adb->query($columnsql);
					}
					$log->info("CustomView :: Save :: vtiger_cvcolumnlist created successfully");
					if($std_filter_list["columnname"] !="")
					{
						$stdfiltersql = "INSERT INTO vtiger_cvstdfilter
							(cvid,
							 columnname,
							 stdfilter,
							 startdate,
							 enddate)
							VALUES
							(".$genCVid.",
							 ".$adb->quote($std_filter_list["columnname"]).",

							 ".$adb->quote($std_filter_list["stdfilter"]).",
							 ".$adb->formatDate($std_filter_list["startdate"]).",
							 ".$adb->formatDate($std_filter_list["enddate"]).")";
						$stdfilterresult = $adb->query($stdfiltersql);
						$log->info("CustomView :: Save :: vtiger_cvstdfilter created successfully");
					}
					for($i=0;$i<count($adv_filter_col);$i++)
					{
						$col = explode(":",$adv_filter_col[$i]);
						$temp_val = explode(",",$adv_filter_value[$i]);
						if($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || $col[4] == 'DT')
						{
							$val = Array();
							for($x=0;$x<count($temp_val);$x++)
							{
								//if date and time given then we have to convert the date and leave the time as it is, if date only given then temp_time value will be empty
								list($temp_date,$temp_time) = explode(" ",$temp_val[$x]);
								$temp_date = getDBInsertDateValue(trim($temp_date));
								if(trim($temp_time) != '')
									$temp_date .= ' '.$temp_time;
								$val[$x] = $temp_date;
							}
							$adv_filter_value[$i] = implode(", ",$val);
						}
						$advfiltersql = "INSERT INTO vtiger_cvadvfilter
								(cvid,
								columnindex,
								columnname,
								comparator,
								value)
							VALUES
								(".$genCVid.",
								".$i.",
								".$adb->quote($adv_filter_col[$i]).",
								".$adb->quote($adv_filter_option[$i]).",
								".$adb->quote($adv_filter_value[$i]).")";
						$advfilterresult = $adb->query($advfiltersql);
					}
					$log->info("CustomView :: Save :: vtiger_cvadvfilter created successfully");
				}
			}
			$cvid = $genCVid;
		}
	}else
	{

		if($setdefault == 1)
		{
			$updatedefaultsql = "UPDATE vtiger_customview SET setdefault = 0 WHERE entitytype = ".$adb->quote($cvmodule);
			$updatedefaultresult = $adb->query($updatedefaultsql);
		}
		$log->info("CustomView :: Save :: setdefault upated successfully".$genCVid);
		$updatecvsql = "UPDATE vtiger_customview
				SET viewname = ".$adb->quote($viewname).",
					setdefault = ".$setdefault.",
					setmetrics = ".$setmetrics."
				WHERE cvid = ".$cvid;
		$updatecvresult = $adb->query($updatecvsql);
		$log->info("CustomView :: Save :: vtiger_customview upated successfully".$genCVid);
		$deletesql = "DELETE FROM vtiger_cvcolumnlist WHERE cvid = ".$cvid;
		$deleteresult = $adb->query($deletesql);

		$deletesql = "DELETE FROM vtiger_cvstdfilter WHERE cvid = ".$cvid;
		$deleteresult = $adb->query($deletesql);

		$deletesql = "DELETE FROM vtiger_cvadvfilter WHERE cvid = ".$cvid;
		$deleteresult = $adb->query($deletesql);
		$log->info("CustomView :: Save :: vtiger_cvcolumnlist,cvstdfilter,cvadvfilter deleted successfully before update".$genCVid);

		$genCVid = $cvid;
		if($updatecvresult)
		{
			if(isset($columnslist))
			{
				for($i=0;$i<count($columnslist);$i++)
				{
					$columnsql = "INSERT INTO vtiger_cvcolumnlist (cvid, columnindex, columnname)
						VALUES (".$genCVid.", ".$i.", ".$adb->quote($columnslist[$i]).")";
					$columnresult = $adb->query($columnsql);
				}
				$log->info("CustomView :: Save :: vtiger_cvcolumnlist update successfully".$genCVid);
				if($std_filter_list["columnname"] !="")
				{
					$stdfiltersql = "INSERT INTO vtiger_cvstdfilter
						(cvid,
						 columnname,
						 stdfilter,
						 startdate,
						 enddate)
						VALUES
						(".$genCVid.",
						 ".$adb->quote($std_filter_list["columnname"]).",
						 ".$adb->quote($std_filter_list["stdfilter"]).",
						 ".$adb->formatDate($std_filter_list["startdate"]).",
						 ".$adb->formatDate($std_filter_list["enddate"]).")";
					$stdfilterresult = $adb->query($stdfiltersql);
					$log->info("CustomView :: Save :: vtiger_cvstdfilter update successfully".$genCVid);
				}
				for($i=0;$i<count($adv_filter_col);$i++)
				{
					$col = explode(":",$adv_filter_col[$i]);
					$temp_val = explode(",",$adv_filter_value[$i]);
					if($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || $col[4] == 'DT')
					{
						$val = Array();
						for($x=0;$x<count($temp_val);$x++){

								//if date and time given then we have to convert the date and leave the time as it is, if date only given then temp_time value will be empty
								list($temp_date,$temp_time) = explode(" ",$temp_val[$x]);
								$temp_date = getDBInsertDateValue(trim($temp_date));
								if(trim($temp_time) != '')
									$temp_date .= ' '.$temp_time;
								$val[$x] = $temp_date;
			
						}
						$adv_filter_value[$i] = implode(", ",$val);	
					}
					$advfiltersql = "INSERT INTO vtiger_cvadvfilter
								(cvid,
								columnindex,
								columnname,
								comparator,
								value)
							VALUES
								(".$genCVid.",
								".$i.",
								".$adb->quote($adv_filter_col[$i]).",
								".$adb->quote($adv_filter_option[$i]).",
								".$adb->quote($adv_filter_value[$i]).")";
					$advfilterresult = $adb->query($advfiltersql);
				}
				$log->info("CustomView :: Save :: vtiger_cvadvfilter update successfully".$genCVid);
			}
		}
	}
}

header("Location: index.php?action=$return_action&parenttab=$parenttab&module=$cvmodule&viewname=$cvid");
?>
