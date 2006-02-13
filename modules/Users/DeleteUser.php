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

//Updating the smcreatorid,smownerid, modifiedby in crmentity
$sql1 = "update crmentity set smcreatorid=".$tran_id." where smcreatorid=".$del_id;
$adb->query($sql1);
$sql2 = "update crmentity set smownerid=".$tran_id." where smownerid=".$del_id;
$adb->query($sql2);
$sql3 = "update crmentity set modifiedby=".$tran_id." where modifiedby=".$del_id;
$adb->query($sql3);

//deleting from tracker
$sql4 = "delete from tracker where user_id='".$del_id."'";
$adb->query($sql4);

//updating created by in lar table
$sql5 = "update lar set createdby=".$tran_id." where createdby=".$del_id;
$adb->query($sql5);

//updating the import_maps table
$sql6 ="update import_maps set assigned_user_id='".$tran_id."' where assigned_user_id='".$del_id."'";
$adb->query($sql6);

//update assigned_user_id in files
$sql7 ="update files set assigned_user_id='".$tran_id."' where assigned_user_id='".$del_id."'";
$adb->query($sql7); 


//update assigned_user_id in users_last_import
$sql8 = "update users_last_import set assigned_user_id='".$tran_id."' where assigned_user_id='".$del_id."'";
$adb->query($sql8);

//delete from user table;
$sql9 = "delete from users where id=".$del_id;
$adb->query($sql9);

header("Location: index.php?action=index&module=Administration");
?>
