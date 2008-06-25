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

global $adb;
$del_id =  $_REQUEST['delete_user_id'];
$tran_id = $_REQUEST['transfer_user_id'];

//Updating the smcreatorid,smownerid, modifiedby in vtiger_crmentity
$sql1 = "update vtiger_crmentity set smcreatorid=? where smcreatorid=?";
$adb->pquery($sql1, array($tran_id, $del_id));
$sql2 = "update vtiger_crmentity set smownerid=? where smownerid=?";
$adb->pquery($sql2, array($tran_id, $del_id));
$sql3 = "update vtiger_crmentity set modifiedby=? where modifiedby=?";
$adb->pquery($sql3, array($tran_id, $del_id));

//deleting from vtiger_tracker
$sql4 = "delete from vtiger_tracker where user_id=?";
$adb->pquery($sql4, array($del_id));

//updating created by in vtiger_lar vtiger_table
$sql5 = "update vtiger_lar set createdby=? where createdby=?";
$adb->pquery($sql5, array($tran_id, $del_id));

//updating the vtiger_import_maps vtiger_table
$sql6 ="update vtiger_import_maps set assigned_user_id=? where assigned_user_id=?";
$adb->pquery($sql6, array($tran_id, $del_id));

//update assigned_user_id in vtiger_files
$sql7 ="update vtiger_files set assigned_user_id=? where assigned_user_id=?";
$adb->pquery($sql7, array($tran_id, $del_id)); 


//update assigned_user_id in vtiger_users_last_import
$sql8 = "update vtiger_users_last_import set assigned_user_id=? where assigned_user_id=?";
$adb->pquery($sql8, array($tran_id, $del_id));

//delete from vtiger_users to group vtiger_table
$sql9 = "delete from vtiger_user2role where userid=?";
$adb->pquery($sql9, array($del_id));

//delete from vtiger_users to vtiger_role vtiger_table
$sql9 = "delete from vtiger_users2group where userid=?";
$adb->pquery($sql9, array($del_id));


//delete from user vtiger_table;
$sql9 = "delete from vtiger_users where id=?";
$adb->pquery($sql9, array($del_id));

//if check to delete user from detail view
if(isset($_REQUEST["ajax_delete"]) && $_REQUEST["ajax_delete"] == 'false')
	header("Location: index.php?action=ListView&module=Users");
else
	header("Location: index.php?action=UsersAjax&module=Users&file=ListView&ajax=true");
?>
