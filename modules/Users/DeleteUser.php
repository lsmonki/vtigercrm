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
$sql1 = "update vtiger_crmentity set smcreatorid=".$tran_id." where smcreatorid=".$del_id;
$adb->query($sql1);
$sql2 = "update vtiger_crmentity set smownerid=".$tran_id." where smownerid=".$del_id;
$adb->query($sql2);
$sql3 = "update vtiger_crmentity set modifiedby=".$tran_id." where modifiedby=".$del_id;
$adb->query($sql3);

//deleting from vtiger_tracker
$sql4 = "delete from vtiger_tracker where user_id='".$del_id."'";
$adb->query($sql4);

//updating created by in vtiger_lar vtiger_table
$sql5 = "update vtiger_lar set createdby=".$tran_id." where createdby=".$del_id;
$adb->query($sql5);

//updating the vtiger_import_maps vtiger_table
$sql6 ="update vtiger_import_maps set assigned_user_id='".$tran_id."' where assigned_user_id='".$del_id."'";
$adb->query($sql6);

//update assigned_user_id in vtiger_files
$sql7 ="update vtiger_files set assigned_user_id='".$tran_id."' where assigned_user_id='".$del_id."'";
$adb->query($sql7); 


//update assigned_user_id in vtiger_users_last_import
$sql8 = "update vtiger_users_last_import set assigned_user_id='".$tran_id."' where assigned_user_id='".$del_id."'";
$adb->query($sql8);

//delete from vtiger_users to group vtiger_table
$sql9 = "delete from vtiger_user2role where userid=".$del_id;
$adb->query($sql9);

//delete from vtiger_users to vtiger_role vtiger_table
$sql9 = "delete from vtiger_users2group where userid=".$del_id;
$adb->query($sql9);


//delete from user vtiger_table;
$sql9 = "delete from vtiger_users where id=".$del_id;
$adb->query($sql9);

//if check to delete user from detail view
if(isset($_REQUEST["ajax_delete"]) && $_REQUEST["ajax_delete"] == 'false')
	header("Location: index.php?action=ListView&module=Users");
else
	header("Location: index.php?action=UsersAjax&module=Users&file=ListView&ajax=true");
?>
