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
require_once('include/utils/utils.php');

	
	$sql="select fieldid from vtiger_field, vtiger_tab where vtiger_field.tabid=vtiger_tab.tabid and generatedtype=2 and vtiger_tab.name='Leads';";	
	$result = $adb->pquery($sql, array());
	$noofrows = $adb->num_rows($result);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$lead_id=$adb->query_result($result,$i,"fieldid");
		$account_id_name=$lead_id."_account";			
		$contact_id_name=$lead_id."_contact";			
		$potential_id_name=$lead_id."_potential";			
		
		$account_id_val=$_REQUEST[$account_id_name];
		$contact_id_val=$_REQUEST[$contact_id_name];
		$potential_id_val=$_REQUEST[$potential_id_name];

		if($account_id_val=="None")
		{
			$account_id_val=0;
		}
		if($contact_id_val=="None")
		{
			$contact_id_val=0;
		}
		if($potential_id_val =="None")	
		{
			$potential_id_val=0;
		}
		$update_sql="update vtiger_convertleadmapping set accountfid=?, contactfid=?, potentialfid=? where leadfid=?";
		$update_params = array($account_id_val, $contact_id_val, $potential_id_val, $lead_id);
		$adb->pquery($update_sql, $update_params);
	}
	 header("Location: index.php?action=CustomFieldList&module=Settings&parenttab=Settings");
	
?>
