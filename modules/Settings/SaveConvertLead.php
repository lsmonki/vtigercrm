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

//echo"<h3> In Save</h3>";
	
	$sql="select fieldid from field, tab where field.tabid=tab.tabid and generatedtype=2 and tab.name='Leads';";	
//	echo $sql;	
	$result = $adb->query($sql);
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
			$account_id_val="";
		}
		if($contact_id_val=="None")
		{
			$contact_id_val="";
		}
		if($potential_id_val =="None")	
		{
			$potential_id_val="";
		}
		$update_sql="update convertleadmapping set accountfid='".$account_id_val."',contactfid='".$contact_id_val."',potentialfid='".$potential_id_val."' where leadfid=".$lead_id;

		$adb->query($update_sql);
	}
	 header("Location: index.php?module=Settings&action=ListLeadCustomFieldMapping");
	
?>
