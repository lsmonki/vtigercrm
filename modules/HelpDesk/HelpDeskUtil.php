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

//function to construct the combo field from database
function getComboValues($fieldname, $tableName, $tableColumn, $tabindex, $value)
{
	global $adb;
$query = "select * from ".$tableName;
$result = $adb->query($query);
$output = "<select name='".$fieldname."' tabindex='1'>"; 
while($row = $adb->fetch_array($result))
{
	$selected = '';
	if($value != '' && $row[$tableColumn] == $value)
	{
		$selected = 'selected';
	}
	$output .= "<OPTION value='".$row[$tableColumn]."' ".$selected.">".$row[$tableColumn]."</OPTION>";
}
$output .= "</select>";
return $output;		
}

function getTicketList()
{
	global $adb;
	$query = "select troubletickets.id,groupname,contact_id,priority,troubletickets.status,parent_id,parent_type,category,troubletickets.title,troubletickets.description,update_log,version_id,troubletickets.date_created,troubletickets.date_modified,troubletickets.assigned_user_id,contacts.first_name,contacts.last_name,users.user_name from troubletickets left join contacts on troubletickets.contact_id=contacts.id  left join users on troubletickets.assigned_user_id=users.id where troubletickets.deleted=0";
	$result = $adb->query($query);
	return $result;

}

function getFaqList()
{
	global $adb;
	$query = "select faq.id,question,answer,category,author_id,users.id,users.user_name from faq left join users on faq.author_id=users.id where faq.deleted ='0'";
	$result = $adb->query($query);
	return $result;

}

?>
