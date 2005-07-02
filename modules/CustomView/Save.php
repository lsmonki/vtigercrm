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
global $vtlog;

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

	//echo $viewname.$setdefault;
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
		  $vtlog->logthis("CustomView :: Save :: setdefault upated successfully","info");

		  $customviewsql = "insert into customview(cvid,viewname,setdefault,setmetrics,entitytype)";
		  $customviewsql .= " values(".$genCVid.",'".$viewname."',".$setdefault.",".$setmetrics.",'".$cvmodule."')";
		  //echo $customviewsql;
		  $customviewresult = $adb->query($customviewsql);
		  $vtlog->logthis("CustomView :: Save :: customview created successfully","info");
		  if($customviewresult)
		  {
			if(isset($columnslist))
			{
  			    for($i=0;$i<count($columnslist);$i++)
			    {
				$columnsql = "insert into cvcolumnlist (cvid,columnindex,columnname)";
				$columnsql .= " values (".$genCVid.",".$i.",'".$columnslist[$i]."')";
				//echo $columnsql;
				$columnresult = $adb->query($columnsql);
			    }
			    $vtlog->logthis("CustomView :: Save :: cvcolumnlist created successfully","info");

			    $stdfiltersql = "insert into cvstdfilter(cvid,columnname,stdfilter,startdate,enddate)";
			    $stdfiltersql .= " values (".$genCVid.",'".$std_filter_list["columnname"]."',";
			    $stdfiltersql .= "'".$std_filter_list["stdfilter"]."',";
			    $stdfiltersql .= "'".$std_filter_list["startdate"]."',";
			    $stdfiltersql .= "'".$std_filter_list["enddate"]."')";
			    //echo $stdfiltersql;
			    $stdfilterresult = $adb->query($stdfiltersql);
			    $vtlog->logthis("CustomView :: Save :: cvstdfilter created successfully","info");

			    for($i=0;$i<count($adv_filter_col);$i++)
			    {
				$advfiltersql = "insert into cvadvfilter(cvid,columnindex,columnname,comparator,value)";
				$advfiltersql .= " values (".$genCVid.",".$i.",'".$adv_filter_col[$i]."',";
				$advfiltersql .= "'".$adv_filter_option[$i]."',";
				$advfiltersql .= "'".$adv_filter_value[$i]."')";
				//echo $advfiltersql;
				$advfilterresult = $adb->query($advfiltersql);
			    }
			    $vtlog->logthis("CustomView :: Save :: cvadvfilter created successfully","info");
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
	     $vtlog->logthis("CustomView :: Save :: setdefault upated successfully".$genCVid,"info");

	     $updatecvsql = "update customview set viewname='".$viewname."',setdefault=".$setdefault.",setmetrics=".$setmetrics." where cvid=".$cvid;
	     $updatecvresult = $adb->query($updatecvsql);
	     $vtlog->logthis("CustomView :: Save :: customview upated successfully".$genCVid,"info");

	     $deletesql = "delete from cvcolumnlist where cvid=".$cvid;
	     $deleteresult = $adb->query($deletesql);

	     $deletesql = "delete from cvstdfilter where cvid=".$cvid;
             $deleteresult = $adb->query($deletesql);

             $deletesql = "delete from cvadvfilter where cvid=".$cvid;
             $deleteresult = $adb->query($deletesql);
	     $vtlog->logthis("CustomView :: Save :: cvcolumnlist,cvstdfilter,cvadvfilter deleted successfully before update".$genCVid,"info");

	     $genCVid = $cvid;
             if($updatecvresult)
	     {
		if(isset($columnslist))
                {
                     for($i=0;$i<count($columnslist);$i++)
                     {
                         $columnsql = "insert into cvcolumnlist (cvid,columnindex,columnname)";
                         $columnsql .= " values (".$genCVid.",".$i.",'".$columnslist[$i]."')";
                         //echo $columnsql;
                         $columnresult = $adb->query($columnsql);
                     }
		     $vtlog->logthis("CustomView :: Save :: cvcolumnlist update successfully".$genCVid,"info");

                     $stdfiltersql = "insert into cvstdfilter(cvid,columnname,stdfilter,startdate,enddate)";
                     $stdfiltersql .= " values (".$genCVid.",'".$std_filter_list["columnname"]."',";
                     $stdfiltersql .= "'".$std_filter_list["stdfilter"]."',";
                     $stdfiltersql .= "'".$std_filter_list["startdate"]."',";
                     $stdfiltersql .= "'".$std_filter_list["enddate"]."')";
                     //echo $stdfiltersql;
                     $stdfilterresult = $adb->query($stdfiltersql);
		     $vtlog->logthis("CustomView :: Save :: cvstdfilter update successfully".$genCVid,"info");

                     for($i=0;$i<count($adv_filter_col);$i++)
                     {
                         $advfiltersql = "insert into cvadvfilter(cvid,columnindex,columnname,comparator,value)";
                         $advfiltersql .= " values (".$genCVid.",".$i.",'".$adv_filter_col[$i]."',";
                         $advfiltersql .= "'".$adv_filter_option[$i]."',";
                         $advfiltersql .= "'".$adv_filter_value[$i]."')";
                        // echo $advfiltersql;
                         $advfilterresult = $adb->query($advfiltersql);
                     }
		     $vtlog->logthis("CustomView :: Save :: cvadvfilter update successfully".$genCVid,"info");
                }
	     }
	}
}
//echo $cvmodule;

if($cvmodule == "Vendor")
{
	$cvmodule = "Products&smodule=VENDOR";
}elseif($cvmodule == "PriceBook")
{
	$cvmodule = "Products&smodule=PRICEBOOK";
}elseif($cvmodule == "SalesOrder")
{
	$cvmodule = "Orders&smodule=SO";
}

header("Location: index.php?action=index&module=$cvmodule&viewname=$cvid");
?>
