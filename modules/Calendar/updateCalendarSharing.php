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
require_once('include/database/PearDatabase.php');
global $adb;
$sharedid = $_REQUEST['sharedid'];
if(isset($_REQUEST['sharedid']) && $_REQUEST['sharedid'] != '')
{
        //split the string and store in an array
        $storearray = explode (";",$sharedid);
        foreach($storearray as $sid)
        {
                if($sid != '')
                {
			$sql = "insert into sharedcalendar values (".$_REQUEST["current_userid"].",".$sid.")";
		        $adb->query($sql);
                }
        }
        header("Location: index.php?action=calendar_share&module=Calendar");
}

?>

