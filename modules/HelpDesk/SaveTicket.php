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
require_once('database/DatabaseConnection.php');
$groupname = $_REQUEST['assigned_group_name'];
$assigned_user_id = $_REQUEST['assigned_user_id'];
$parent_type = $_REQUEST['parent_type'];
$parent_id = $_REQUEST['parent_id'];
$contact_id = $_REQUEST['contact_id'];
$priority = $_REQUEST['priority'];
$category = $_REQUEST['category'];
$status = $_REQUEST['status'];
$subject = $_REQUEST['subject'];
$description = $_REQUEST['description'];
$datemodified = date('YmdHis');
$updatelog = "Ticket Created. Assigned To";
$return_action = $_REQUEST['return_action'];
$return_module = $_REQUEST['return_module'];
$return_id = $_REQUEST['return_id'];
$mode = $_REQUEST['mode'];
if(isset($mode) && $mode != '' && $mode == 'Edit')
{
	$ticketid = $_REQUEST['id'];
	$query="update troubletickets set groupname='".$groupname."',contact_id='".$contact_id."',priority='".$priority."',status='".$status."',parent_id='".$parent_id."',parent_type='".$parent_type."',category='".$category."',title='".$subject."',description='".$description."',update_log='".$updatelog."',date_modified='".$datemodified."',assigned_user_id='".$assigned_user_id."' where id=".$ticketid;
	//echo $query;
	mysql_query($query); 

}
else
{
	//Inserting value into troubletickets table
	$query="insert into troubletickets values('','".$groupname."','".$contact_id."','".$priority."','".$status."','".$parent_id."','".$parent_type."','".$category."','".$subject."','".$description."','".$updatelog."','','".$datecreated."','".$datemodified."','".$assigned_user_id."','')";
	mysql_query($query);

	//Retreiving the id
	$idquery = "select max(id) as id from troubletickets";
	$idresult = mysql_query($idquery);
	$return_id = mysql_result($idresult,0,"id");
}
$loc = "Location: index.php?action=".$return_action."&module=".$return_module."&record=".$return_id;
//echo "locisss ".$loc;
header($loc);
?>
