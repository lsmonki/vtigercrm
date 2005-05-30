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

$local_log =& LoggerManager::getLogger('index');
$focus = new Reports();

$rfid = $_REQUEST['record'];

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Reports";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "index";

if($rfid != "")
{
	$sql .= "delete from reportfolder where folderid=".$rfid;
	$result = $adb->query($sql);
	if($result!=false)
	{
		header("Location: index.php?action=$return_action&module=$return_module");
	}else
	{
		include('themes/'.$theme.'/header.php');
		$errormessage = "<font color='red'><B>Error Message<ul>
		<li><font color='red'>Error while deleting the record</font>
		</ul></B></font> <br>" ;
		echo $errormessage;
	}   
}


   	
?>
