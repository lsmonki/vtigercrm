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

$cvid = $_REQUEST["record"];
$cvmodule = $_REQUEST["cvmodule"];

if($cvmodule != "")
{
	$viewname = addslashes($_REQUEST["viewName"]);
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
	$std_filter_list["startdate"] = $startdate;
	$enddate = $_REQUEST["enddate"];
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
           	$adv_filter_value[] = addslashes($_REQUEST[$allKeys[$i]]);
   	   }
	}
	//<<<<<<<advancedfilter>>>>>>>>

	if($cvid == "")
	{
		$genCVid = $adb->getUniqueID("customview");
		if($genCVid != "")
		{

			if($setdefault == 1)
			{
				$updatedefaultsql = "update customview set setdefault=0 where entitytype='".$cvmodule."'";
				$updatedefaultresult = $adb->query($updatedefaultsql);
			}
			$log->info("CustomView :: Save :: setdefault upated successfully");

			$customviewsql = "insert into customview(cvid,viewname,setdefault,setmetrics,entitytype)";
			$customviewsql .= " values(".$genCVid.",'".$viewname."',".$setdefault.",".$setmetrics.",'".$cvmodule."')";
			$customviewresult = $adb->query($customviewsql);
			$log->info("CustomView :: Save :: customview created successfully");
			if($customviewresult)
			{
				if(isset($columnslist))
				{
					for($i=0;$i<count($columnslist);$i++)
					{
						$columnsql = "insert into cvcolumnlist (cvid,columnindex,columnname)";
						$columnsql .= " values (".$genCVid.",".$i.",'".$columnslist[$i]."')";
						$columnresult = $adb->query($columnsql);
					}
					$log->info("CustomView :: Save :: cvcolumnlist created successfully");

					$stdfiltersql = "insert into cvstdfilter(cvid,columnname,stdfilter,startdate,enddate)";
					$stdfiltersql .= " values (".$genCVid.",'".$std_filter_list["columnname"]."',";
					$stdfiltersql .= "'".$std_filter_list["stdfilter"]."',";
					$stdfiltersql .= "'".$std_filter_list["startdate"]."',";
					$stdfiltersql .= "'".$std_filter_list["enddate"]."')";
					$stdfilterresult = $adb->query($stdfiltersql);
					$log->info("CustomView :: Save :: cvstdfilter created successfully");
					for($i=0;$i<count($adv_filter_col);$i++)
					{
						$advfiltersql = "insert into cvadvfilter(cvid,columnindex,columnname,comparator,value)";
						$advfiltersql .= " values (".$genCVid.",".$i.",'".$adv_filter_col[$i]."',";
						$advfiltersql .= "'".$adv_filter_option[$i]."',";
						$advfiltersql .= "'".$adv_filter_value[$i]."')";
						$advfilterresult = $adb->query($advfiltersql);
					}
					$log->info("CustomView :: Save :: cvadvfilter created successfully");
				}
			}
			$cvid = $genCVid;
		}
	}else
	{

		if($setdefault == 1)
		{
			$updatedefaultsql = "update customview set setdefault=0 where entitytype='".$cvmodule."'";
			$updatedefaultresult = $adb->query($updatedefaultsql);
		}
		$log->info("CustomView :: Save :: setdefault upated successfully".$genCVid);
		$updatecvsql = "update customview set viewname='".$viewname."',setdefault=".$setdefault.",setmetrics=".$setmetrics." where cvid=".$cvid;
		$updatecvresult = $adb->query($updatecvsql);
		$log->info("CustomView :: Save :: customview upated successfully".$genCVid);
		$deletesql = "delete from cvcolumnlist where cvid=".$cvid;
		$deleteresult = $adb->query($deletesql);

		$deletesql = "delete from cvstdfilter where cvid=".$cvid;
		$deleteresult = $adb->query($deletesql);

		$deletesql = "delete from cvadvfilter where cvid=".$cvid;
		$deleteresult = $adb->query($deletesql);
		$log->info("CustomView :: Save :: cvcolumnlist,cvstdfilter,cvadvfilter deleted successfully before update".$genCVid);

		$genCVid = $cvid;
		if($updatecvresult)
		{
			if(isset($columnslist))
			{
				for($i=0;$i<count($columnslist);$i++)
				{
					$columnsql = "insert into cvcolumnlist (cvid,columnindex,columnname)";
					$columnsql .= " values (".$genCVid.",".$i.",'".$columnslist[$i]."')";
					$columnresult = $adb->query($columnsql);
				}
				$log->info("CustomView :: Save :: cvcolumnlist update successfully".$genCVid);
				$stdfiltersql = "insert into cvstdfilter(cvid,columnname,stdfilter,startdate,enddate)";
				$stdfiltersql .= " values (".$genCVid.",'".$std_filter_list["columnname"]."',";
				$stdfiltersql .= "'".$std_filter_list["stdfilter"]."',";
				$stdfiltersql .= "'".$std_filter_list["startdate"]."',";
				$stdfiltersql .= "'".$std_filter_list["enddate"]."')";
				$stdfilterresult = $adb->query($stdfiltersql);
				$log->info("CustomView :: Save :: cvstdfilter update successfully".$genCVid);
				for($i=0;$i<count($adv_filter_col);$i++)
				{
					$advfiltersql = "insert into cvadvfilter(cvid,columnindex,columnname,comparator,value)";
					$advfiltersql .= " values (".$genCVid.",".$i.",'".$adv_filter_col[$i]."',";
					$advfiltersql .= "'".$adv_filter_option[$i]."',";
					$advfiltersql .= "'".$adv_filter_value[$i]."')";
					$advfilterresult = $adb->query($advfiltersql);
				}
				$log->info("CustomView :: Save :: cvadvfilter update successfully".$genCVid);
			}
		}
	}
}

header("Location: index.php?action=index&module=$cvmodule&viewname=$cvid");
?>
