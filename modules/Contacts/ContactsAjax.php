<?php
require_once('include/logging.php');
require_once('modules/Contacts/Contact.php');
require_once('include/database/PearDatabase.php');
global $adb;

$local_log =& LoggerManager::getLogger('ContactsAjax');

$ajaxaction = $_REQUEST["ajxaction"];

if($ajaxaction == "DETAILVIEW")
{
     $crmid = $_REQUEST["recordid"];
     $tablename = $_REQUEST["tableName"];
     $fieldname = $_REQUEST["fldName"];
     $fieldvalue = $_REQUEST["fieldValue"];
     
     if($crmid != "")
	{
		$cntObj = new Contact();
	     $cntObj->retrieve_entity_info($crmid,"Contacts");
	     $cntObj->column_fields[$fieldname] = $fieldvalue;
	     $cntObj->id = $crmid;
  		$cntObj->mode = "edit";
       	$cntObj->save("Contacts");
          if($cntObj->id != "")
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
?>
