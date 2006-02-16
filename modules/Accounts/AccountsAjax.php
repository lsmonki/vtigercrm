<?php
require_once('include/logging.php');
require_once('modules/Accounts/Account.php');
require_once('include/database/PearDatabase.php');
global $adb;

$local_log =& LoggerManager::getLogger('AccountsAjax');

$ajaxaction = $_REQUEST["ajxaction"];

if($ajaxaction == "DETAILVIEW")
{
     $crmid = $_REQUEST["recordid"];
     $tablename = $_REQUEST["tableName"];
     $fieldname = $_REQUEST["fldName"];
     $fieldvalue = $_REQUEST["fieldValue"];
     
     if($crmid != "")
	{
		$acntObj = new Account();
	     $acntObj->retrieve_entity_info($crmid,"Accounts");
	     $acntObj->column_fields[$fieldname] = $fieldvalue;
	     $acntObj->id = $crmid;
  		$acntObj->mode = "edit";
       	$acntObj->save("Accounts");
          if($acntObj->id != "")
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
