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
$del_id =  $_REQUEST['delete_prof_id'];
$tran_id = $_REQUEST['transfer_prof_id'];

//deleting from profile 2 tab; 
$sql4 = "delete from profile2tab where profileid=".$del_id;
$adb->query($sql4);

//deleting from profile2standardpermissions table
$sql5 = "delete from profile2standardpermissions where profileid=".$del_id;
$adb->query($sql5);

//deleting from profile2field
$sql6 ="delete from profile2field where profileid=".$del_id;
$adb->query($sql6);

//deleting from profile2utility
$sql7 ="delete from profile2utility where profileid=".$del_id;
$adb->query($sql7); 


//updating role2profile 
$sql8 = "update role2profile set profileid=".$tran_id." where profileid=".$del_id;
$adb->query($sql8);

//delete from profile table;
$sql9 = "delete from profile where profileid=".$del_id;
$adb->query($sql9);

header("Location: index.php?module=Users&action=ListProfiles");
?>
