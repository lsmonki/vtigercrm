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
$del_id =  $_REQUEST['delete_role_id'];
$tran_id = $_REQUEST['transfer_role_id'];

//Updating the user2 role table
$sql1 = "update user2role set roleid=".$tran_id." where roleid=".$del_id;
$adb->query($sql1);

//Deleteing from role2profile table
$sql2 = "delete from role2profile where roleid=".$del_id;
$adb->query($sql2);

//delete from role table;
$sql9 = "delete from role where roleid=".$del_id;
$adb->query($sql9);

header("Location: index.php?action=listroles&module=Users");
?>
