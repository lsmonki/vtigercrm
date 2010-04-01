<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

//5.1.0 to 5.2.0 database changes

//we have to use the current object (stored in PatchApply.php) to execute the queries
require_once ('vtlib/Vtiger/Utils.php');

$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.1.0 to 5.2.0 -------- Starts \n\n");

ExecuteQuery("CREATE TABLE IF NOT EXISTS vtiger_tab_info (tabid INT, prefname VARCHAR(256), prefvalue VARCHAR(256), FOREIGN KEY fk_1_vtiger_tab_info(tabid) REFERENCES vtiger_tab(tabid) ON DELETE CASCADE ON UPDATE CASCADE)  ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$documents_tab_id=getTabid('Documents'); 
ExecuteQuery("update vtiger_field set quickcreate=3 where tabid = $documents_tab_id and columnname = 'filelocationtype'"); 


Vtiger_Utils::AddColumn('vtiger_inventorynotification', 'status','VARCHAR(30)');

//Fix : 6182 after migration from 510 'fields to be shown' at a profile for Email module

	$query = "SELECT * from vtiger_profile";
	$result = $adb->pquery($query,array());
	$rows = $adb->num_rows($result);

	$fields = "SELECT fieldid from vtiger_field where tablename = ?";
	$fieldResult = $adb->pquery($fields,array('vtiger_emaildetails'));
	$fieldRows = $adb->num_rows($fieldResult);
	$EmailTabid = getTabid('Emails');
	for($i=0; $i<$rows ;$i++){
		$profileid = $adb->query_result($result ,$i ,'profileid');
		for($j=0 ;$j<$fieldRows; $j++) {
			$fieldid = $adb->query_result($fieldResult, $j ,'fieldid');

			$sql_profile2field = "select * from vtiger_profile2field where fieldid=? and profileid=?";
			$result_profile2field = $adb->pquery($sql_profile2field,array($fieldid,$profileid));
			$rows_profile2field = $adb->num_rows($result_profile2field);
			if(!($rows_profile2field > 0)){
				$adb->query("INSERT INTO vtiger_profile2field(profileid ,tabid,fieldid,visible,readonly) VALUES ($profileid, $EmailTabid, $fieldid, 0 , 1)");
			}
		}
	}
	for($k=0;$k<$fieldRows;$k++){
		$fieldid = $adb->query_result($fieldResult, $k ,'fieldid');
		$sql_deforgfield = "select * from vtiger_def_org_field where tabid=? and fieldid=?";
		$result_deforgfield = $adb->pquery($sql_deforgfield,array($EmailTabid,$fieldid));
		$rows_deforgfield = $adb->num_rows($result_deforgfield);
		if(!($rows_deforgfield)){
			$adb->query("INSERT INTO vtiger_def_org_field(tabid ,fieldid,visible,readonly) VALUES ($EmailTabid, $fieldid, 0 , 1)");
		}
	}
	$sql = 'update vtiger_field set block=(select blockid from vtiger_blocks where '.
        "blocklabel=?) where tablename=?";
        $params = array('LBL_EMAIL_INFORMATION','vtiger_emaildetails');
        $adb->pquery($sql,$params);
	//END

$migrationlog->debug("\n\nDB Changes from 5.1.0 to 5.2.0 -------- Ends \n\n");


?>
