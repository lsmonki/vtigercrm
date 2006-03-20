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
require_once('modules/Leads/Lead.php');
require_once('include/database/PearDatabase.php');
global $adb;

$local_log =& LoggerManager::getLogger('LeadsAjax');

$ajaxaction = $_REQUEST["ajxaction"];
if($_REQUEST['file'] != '')
{
	require_once('modules/Leads/'.$_REQUEST['file'].'.php');
}
elseif($ajaxaction == "DETAILVIEW")
{
     $crmid = $_REQUEST["recordid"];
     $tablename = $_REQUEST["tableName"];
     $fieldname = $_REQUEST["fldName"];
     //$columname = $_REQUEST["clmnName"];
     $fieldvalue = $_REQUEST["fieldValue"];
     
     if($crmid != "")
	{
          $leadObj = new Lead();
	     $leadObj->retrieve_entity_info($crmid,"Leads");
	     $leadObj->column_fields[$fieldname] = $fieldvalue;
	     $leadObj->id = $crmid;
  		$leadObj->mode = "edit";
       	$leadObj->save("Leads");
          if($leadObj->id != "")
		{
			echo ":#:SUCCESS";
		}else
		{
			echo ":#:FAILURE";
		}   
	}else
	{
	    echo ":#:FAILURE";
     }
}
elseif($ajaxaction == "SAVETAG")
{
	
	require_once('include/freetag/freetag.class.php');
	global $current_user;
	$crmid = $_REQUEST["recordid"];
	$module = $_REQUEST["module"];
	$tagfields = $_REQUEST["tagfields"];
	$userid = $current_user->id;
    $freetag = new freetag();
	if (isset($_REQUEST["tagfields"]) && trim($_REQUEST["tagfields"]) != "")
	{
	      $freetag->tag_object($userid,$crmid,$tagfields,$module);
	 	  $tagcloud = $freetag->get_tag_cloud_html($module);
		  echo $tagcloud;
	}
	
}
elseif($ajaxaction == 'GETTAGCLOUD')
{
	require_once('include/freetag/freetag.class.php');
	$freetag = new freetag();
	$module = $_REQUEST["module"];
	$useid = $current_user->id;
	global $adb;
	$query='select * from freetagged_objects where module = "'.$module .'"';
	$result=$adb->query($query);
	if($adb->num_rows($result) > 0)
	{
		if(trim($module) != "")
		{
			$tagcloud = $freetag->get_tag_cloud_html($module);
			echo $tagcloud;
		}else
		{
			$tagcloud = $freetag->get_tag_cloud_html();
			echo $tagcloud;
		}
	}
	else
	{
		echo '';
	}
}

?>
